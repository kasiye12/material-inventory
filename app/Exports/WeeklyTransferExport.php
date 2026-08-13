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

class WeeklyTransferExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithTitle, WithDrawings
{
    protected $transfers;
    protected $dateFrom;
    protected $dateTo;

    public function __construct($transfers, $dateFrom, $dateTo)
    {
        $this->transfers = $transfers;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function title(): string
    {
        return 'Weekly Transfer Report';
    }

    public function headings(): array
    {
        return [
            ['TNT CONSTRUCTION & TRADING'],
            ['ቲ ኤን ቲ ኮንስትራክሽንና ንግድ ሥራዎች'],
            ['WEEKLY MATERIAL TRANSFER REPORT'],
            ['Document No: OF/TNT/SUP/034'],
            ['Period: ' . $this->dateFrom . ' to ' . $this->dateTo],
            [''],
            ['No', 'Item Description', 'Unit', 'Requested Qty', 'SR.No', 'Date', 'From Project', 'Out/SIV NO', 'To Project', 'In NO', 'Received QTY', 'Delivered Date', 'Remaining QTY', 'Remark']
        ];
    }

    public function collection()
    {
        $data = collect();
        $counter = 1;
        
        foreach ($this->transfers as $transfer) {
            $data->push([
                $counter++,
                $transfer->item->name ?? '',
                $transfer->item->unit ?? '',
                $transfer->quantity,
                $transfer->reference_number ?? '',
                $transfer->transaction_date->format('d/m/Y'),
                $transfer->fromLocation->name ?? '',
                $transfer->reference_number ?? '',
                $transfer->toLocation->name ?? '',
                $transfer->document_number ?? '',
                $transfer->quantity,
                $transfer->transaction_date->format('d/m/Y'),
                '',
                $transfer->remarks ?? ''
            ]);
        }
        
        return $data;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8, 'B' => 30, 'C' => 8, 'D' => 12, 'E' => 12,
            'F' => 12, 'G' => 15, 'H' => 12, 'I' => 15, 'J' => 12,
            'K' => 12, 'L' => 12, 'M' => 12, 'N' => 12,
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
        $sheet->mergeCells('A1:N1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E3A8A']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(30);
        
        $sheet->mergeCells('A2:N2');
        $sheet->mergeCells('A3:N3');
        $sheet->getStyle('A3')->applyFromArray([
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FBBF24']],
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        
        $sheet->mergeCells('A4:N4');
        $sheet->mergeCells('A5:N5');
        
        $headerRow = 7;
        $sheet->getStyle('A' . $headerRow . ':N' . $headerRow)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4B5563']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);
        
        return [];
    }
}
