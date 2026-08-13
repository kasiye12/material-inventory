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
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class DeliveryReportExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithTitle, WithDrawings
{
    protected $deliveries;
    protected $locationName;
    protected $dateFrom;
    protected $dateTo;

    public function __construct($deliveries, $locationName, $dateFrom, $dateTo)
    {
        $this->deliveries = $deliveries;
        $this->locationName = $locationName;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function title(): string
    {
        return 'Delivery Report';
    }

    public function headings(): array
    {
        return [
            ['TNT CONSTRUCTION & TRADING'],
            ['ቲ ኤን ቲ ኮንስትራክሽንና ንግድ ሥራዎች'],
            ['DAILY MATERIAL DELIVERY REPORT'],
            ['Document No: OF/TNT/SUP/033'],
            ['Location: ' . $this->locationName],
            ['Period: ' . $this->dateFrom . ' to ' . $this->dateTo],
            [''],
            ['NO', 'Item Description', 'Unit', 'Qty', 'ISTV NO', 'ISTRV NO', 'Delivery Date', 'FROM', 'Remark']
        ];
    }

    public function collection()
    {
        $data = collect();
        $counter = 1;
        $currentCategory = '';
        
        foreach ($this->deliveries as $delivery) {
            $category = $delivery->item->category->name ?? 'Uncategorized';
            
            // Add category header row
            if ($category !== $currentCategory) {
                $data->push([strtoupper($category), '', '', '', '', '', '', '', '']);
                $currentCategory = $category;
            }
            
            $data->push([
                $counter++,
                $delivery->item->name ?? '',
                $delivery->item->unit ?? '',
                $delivery->quantity,
                $delivery->transaction_type === 'GRV' ? ($delivery->reference_number ?? '') : '',
                $delivery->transaction_type === 'ISTRV' ? ($delivery->reference_number ?? $delivery->document_number ?? '') : '',
                $delivery->transaction_date->format('d/m/Y'),
                $delivery->fromLocation->name ?? 'Head Office',
                $delivery->remarks ?? ''
            ]);
        }
        
        return $data;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 35,
            'C' => 10,
            'D' => 12,
            'E' => 15,
            'F' => 15,
            'G' => 15,
            'H' => 15,
            'I' => 15,
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
        // Company name - dark blue background with white text
        $sheet->mergeCells('A1:I1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E3A8A']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(30);
        
        // Amharic name - white background
        $sheet->mergeCells('A2:I2');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 13],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        
        // Report title - yellow background
        $sheet->mergeCells('A3:I3');
        $sheet->getStyle('A3')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '000000']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FBBF24']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        
        // Document number
        $sheet->mergeCells('A4:I4');
        $sheet->getStyle('A4')->getFont()->setBold(true)->setSize(10);
        $sheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        
        // Location and period
        $sheet->mergeCells('A5:I5');
        $sheet->mergeCells('A6:I6');
        $sheet->getStyle('A5:A6')->getFont()->setBold(true)->setSize(10);
        
        // Header row - dark background with white text
        $headerRow = 8;
        $sheet->getStyle('A' . $headerRow . ':I' . $headerRow)->applyFromArray([
            'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4B5563']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension($headerRow)->setRowHeight(25);
        
        // Data rows
        $lastRow = $sheet->getHighestRow();
        if ($lastRow > $headerRow) {
            $sheet->getStyle('A' . ($headerRow + 1) . ':I' . $lastRow)->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            ]);
            
            // Alternate row colors
            for ($row = $headerRow + 1; $row <= $lastRow; $row++) {
                $value = $sheet->getCell('A' . $row)->getValue();
                
                // Check if it's a category header row
                if (is_string($value) && strlen($value) > 0 && !is_numeric($value)) {
                    $sheet->getStyle('A' . $row . ':I' . $row)->applyFromArray([
                        'font' => ['bold' => true, 'size' => 11],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E5E7EB']],
                    ]);
                    $sheet->mergeCells('A' . $row . ':I' . $row);
                } elseif ($row % 2 == 0) {
                    $sheet->getStyle('A' . $row . ':I' . $row)->applyFromArray([
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F9FAFB']],
                    ]);
                }
            }
        }
        
        return [];
    }
}
