<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ProjectLedgerExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithTitle, WithDrawings
{
    protected $reportData;
    protected $locationName;
    protected $dateFrom;
    protected $dateTo;

    public function __construct($reportData, $locationName, $dateFrom, $dateTo)
    {
        $this->reportData = $reportData;
        $this->locationName = $locationName;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function title(): string
    {
        return 'Project Material Ledger';
    }

    public function headings(): array
    {
        return [
            ['TNT CONSTRUCTION & TRADING'],
            ['ቲ ኤን ቲ ኮንስትራክሽንና ንግድ ሥራዎች'],
            ['PROJECT MATERIAL LEDGER'],
            ['Document No: OF/TNT/SUP/033'],
            ['Location: ' . $this->locationName],
            ['Period: ' . $this->dateFrom . ' to ' . $this->dateTo],
            [''],
            ['Item No.', 'Description', 'Unit', 'Beg. Balance', 'GRV Ref', 'GRV Qty', 'ISTRV Ref', 'ISTRV Qty', 'SIV Ref', 'SIV Qty', 'Transfer Ref', 'Transfer Qty', 'Return Ref', 'Return Qty', 'Ending Balance']
        ];
    }

    public function collection()
    {
        $data = collect();
        
        foreach ($this->reportData as $category => $items) {
            $data->push([strtoupper($category), '', '', '', '', '', '', '', '', '', '', '', '', '', '', '']);
            
            $counter = 1;
            foreach ($items as $itemData) {
                $data->push([
                    $counter++,
                    $itemData['item']->name,
                    $itemData['item']->unit,
                    $itemData['opening_balance'] ?: '',
                    $itemData['grv_ref'] ?? '',
                    $itemData['grv_qty'] ?: '',
                    $itemData['istrv_ref'] ?? '',
                    $itemData['istrv_qty'] ?: '',
                    $itemData['siv_ref'] ?? '',
                    $itemData['siv_qty'] ?: '',
                    $itemData['transfer_ref'] ?? '',
                    $itemData['transfer_out_qty'] ?: '',
                    $itemData['return_ref'] ?? '',
                    $itemData['store_return_qty'] ?: '',
                    $itemData['ending_balance'] ?: ''
                ]);
            }
            
            $data->push(['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '']);
        }
        
        return $data;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 30,
            'C' => 8,
            'D' => 12,
            'E' => 12,
            'F' => 10,
            'G' => 12,
            'H' => 10,
            'I' => 12,
            'J' => 10,
            'K' => 12,
            'L' => 10,
            'M' => 12,
            'N' => 10,
            'O' => 12,
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Company Logo');
        $drawing->setDescription('TNT Construction Logo');
        $drawing->setPath(public_path('images/company-logo.png'));
        $drawing->setHeight(60);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(10);
        $drawing->setOffsetY(5);
        
        return [$drawing];
    }

    public function styles(Worksheet $sheet)
    {
        // Header styling
        $sheet->mergeCells('A1:O1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E3A8A']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(30);
        
        $sheet->mergeCells('A2:O2');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 13],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        
        $sheet->mergeCells('A3:O3');
        $sheet->getStyle('A3')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FBBF24']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        
        $sheet->mergeCells('A4:O4');
        $sheet->mergeCells('A5:O5');
        $sheet->mergeCells('A6:O6');
        
        // Table header
        $headerRow = 8;
        $sheet->getStyle('A' . $headerRow . ':O' . $headerRow)->applyFromArray([
            'font' => ['bold' => true, 'size' => 9, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4B5563']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        
        // Data rows with borders
        $lastRow = $sheet->getHighestRow();
        if ($lastRow > $headerRow) {
            $sheet->getStyle('A' . ($headerRow + 1) . ':O' . $lastRow)->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            ]);
        }
        
        return [];
    }
}
