<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithTitle;

class WeeklyStockStatusExport implements WithMultipleSheets
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

    public function sheets(): array
    {
        return [
            new HeadOfficeSheet($this->transfers, $this->dateFrom, $this->dateTo),
            new MainStoreSheet($this->transfers, $this->dateFrom, $this->dateTo),
            new ProjectToProjectSheet($this->transfers, $this->dateFrom, $this->dateTo),
        ];
    }
}

// Sheet 1: Head Office to Projects
class HeadOfficeSheet implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithTitle, WithDrawings
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
        return 'HO to Projects';
    }

    public function headings(): array
    {
        return [
            ['TNT CONSTRUCTION & TRADING'],
            ['ቲ. ኤን. ቲ. ኮንስትራክሽንና ንግድ ሥራዎች'],
            ['WEEKLY STOCK STATUS REPORT - HEAD OFFICE TO PROJECTS'],
            ['Document No: OF/TNT/SUP/034'],
            ['Period: ' . $this->dateFrom . ' to ' . $this->dateTo],
            [''],
            ['No.', 'Item Description', 'UOM', 'TR-Out Qty', 'Unit Price', 'Total Price', 'TR-Out No.', 'TR-Out Date', 'TR-IN No.', 'TR-IN Qty', 'Project', 'TR-IN Date', 'Remark'],
        ];
    }

    public function collection()
    {
        $hoTransfers = $this->transfers->filter(function($t) {
            return $t->fromLocation && $t->fromLocation->type === 'head_office';
        });
        
        $data = collect();
        $counter = 1;
        $grandTotal = 0;
        
        foreach ($hoTransfers as $t) {
            $totalPrice = $t->quantity * ($t->item->unit_price ?? 0);
            $grandTotal += $totalPrice;
            
            $data->push([
                $counter++,
                $t->item->name ?? '',
                $t->item->unit ?? '',
                $t->quantity,
                $t->item->unit_price ? number_format($t->item->unit_price, 2) : '',
                $totalPrice ? number_format($totalPrice, 2) : '',
                $t->reference_number ?? '',
                $t->transaction_date->format('d-m-Y'),
                $t->document_number ?? '',
                $t->quantity,
                $t->toLocation->name ?? '',
                $t->transaction_date->format('d-m-Y'),
                $t->remarks ?? ''
            ]);
        }
        
        // Grand total row
        $data->push(['', '', '', '', 'GRAND TOTAL:', number_format($grandTotal, 2), '', '', '', '', '', '', '']);
        
        return $data;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6, 'B' => 30, 'C' => 8, 'D' => 10, 'E' => 12, 'F' => 14,
            'G' => 12, 'H' => 12, 'I' => 12, 'J' => 10, 'K' => 20, 'L' => 12, 'M' => 10,
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Company Logo');
        $drawing->setPath(public_path('images/company-logo.png'));
        $drawing->setHeight(60);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(10);
        $drawing->setOffsetY(5);
        return [$drawing];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:M1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E3A8A']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(35);
        
        $sheet->mergeCells('A2:M2');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 13],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        
        $sheet->mergeCells('A3:M3');
        $sheet->getStyle('A3')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FBBF24']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        
        $sheet->mergeCells('A4:M4');
        $sheet->mergeCells('A5:M5');
        
        $sheet->getStyle('A7:M7')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4B5563']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);
        
        $lastRow = $sheet->getHighestRow();
        if ($lastRow > 7) {
            $sheet->getStyle('A8:M' . $lastRow)->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            ]);
        }
        
        return [];
    }
}

// Sheet 2: Main Store to Projects
class MainStoreSheet implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithTitle, WithDrawings
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
        return 'Main Store to Projects';
    }

    public function headings(): array
    {
        return [
            ['TNT CONSTRUCTION & TRADING'],
            ['ቲ. ኤን. ቲ. ኮንስትራክሽንና ንግድ ሥራዎች'],
            ['WEEKLY STOCK STATUS REPORT - MAIN STORE TO PROJECTS'],
            ['Document No: OF/TNT/SUP/034'],
            ['Period: ' . $this->dateFrom . ' to ' . $this->dateTo],
            [''],
            ['No.', 'Item Description', 'UOM', 'TR-Out Qty', 'Unit Price', 'Total Price', 'TR-Out No.', 'TR-Out Date', 'TR-IN No.', 'TR-IN Qty', 'Project', 'TR-IN Date', 'Remark'],
        ];
    }

    public function collection()
    {
        $mainStoreTransfers = $this->transfers->filter(function($t) {
            return $t->fromLocation && $t->fromLocation->code === 'MAIN';
        });
        
        $data = collect();
        $counter = 1;
        $grandTotal = 0;
        
        foreach ($mainStoreTransfers as $t) {
            $totalPrice = $t->quantity * ($t->item->unit_price ?? 0);
            $grandTotal += $totalPrice;
            
            $data->push([
                $counter++,
                $t->item->name ?? '',
                $t->item->unit ?? '',
                $t->quantity,
                $t->item->unit_price ? number_format($t->item->unit_price, 2) : '',
                $totalPrice ? number_format($totalPrice, 2) : '',
                $t->reference_number ?? '',
                $t->transaction_date->format('d-m-Y'),
                $t->document_number ?? '',
                $t->quantity,
                $t->toLocation->name ?? '',
                $t->transaction_date->format('d-m-Y'),
                $t->remarks ?? ''
            ]);
        }
        
        $data->push(['', '', '', '', 'GRAND TOTAL:', number_format($grandTotal, 2), '', '', '', '', '', '', '']);
        
        return $data;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6, 'B' => 30, 'C' => 8, 'D' => 10, 'E' => 12, 'F' => 14,
            'G' => 12, 'H' => 12, 'I' => 12, 'J' => 10, 'K' => 20, 'L' => 12, 'M' => 10,
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Company Logo');
        $drawing->setPath(public_path('images/company-logo.png'));
        $drawing->setHeight(60);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(10);
        $drawing->setOffsetY(5);
        return [$drawing];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:M1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E3A8A']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(35);
        
        $sheet->mergeCells('A2:M2');
        $sheet->mergeCells('A3:M3');
        $sheet->getStyle('A3')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FBBF24']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        
        $sheet->mergeCells('A4:M4');
        $sheet->mergeCells('A5:M5');
        
        $sheet->getStyle('A7:M7')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4B5563']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);
        
        return [];
    }
}

// Sheet 3: Project to Project
class ProjectToProjectSheet implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithTitle, WithDrawings
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
        return 'Project to Project';
    }

    public function headings(): array
    {
        return [
            ['TNT CONSTRUCTION & TRADING'],
            ['ቲ. ኤን. ቲ. ኮንስትራክሽንና ንግድ ሥራዎች'],
            ['WEEKLY STOCK STATUS REPORT - PROJECT TO PROJECT TRANSFERS'],
            ['Document No: OF/TNT/SUP/034'],
            ['Period: ' . $this->dateFrom . ' to ' . $this->dateTo],
            [''],
            ['No.', 'Item Description', 'UOM', 'Requested Qty', 'SR.No', 'Date', 'From Project', 'Out/SIV NO', 'To Project', 'In NO', 'Received QTY', 'Delivered Date', 'Remark'],
        ];
    }

    public function collection()
    {
        $projectToProject = $this->transfers->filter(function($t) {
            return $t->fromLocation && in_array($t->fromLocation->type, ['project', 'site']) 
                && $t->toLocation && in_array($t->toLocation->type, ['project', 'site']);
        });
        
        $data = collect();
        $counter = 1;
        
        foreach ($projectToProject as $t) {
            $data->push([
                $counter++,
                $t->item->name ?? '',
                $t->item->unit ?? '',
                $t->quantity,
                $t->reference_number ?? '',
                $t->transaction_date->format('d/m/Y'),
                $t->fromLocation->name ?? '',
                $t->reference_number ?? '',
                $t->toLocation->name ?? '',
                $t->document_number ?? '',
                $t->quantity,
                $t->transaction_date->format('d/m/Y'),
                '',
                $t->remarks ?? ''
            ]);
        }
        
        return $data;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6, 'B' => 30, 'C' => 8, 'D' => 10, 'E' => 12, 'F' => 12,
            'G' => 18, 'H' => 12, 'I' => 18, 'J' => 12, 'K' => 12, 'L' => 12, 'M' => 10,
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Company Logo');
        $drawing->setPath(public_path('images/company-logo.png'));
        $drawing->setHeight(60);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(10);
        $drawing->setOffsetY(5);
        return [$drawing];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:M1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E3A8A']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(35);
        
        $sheet->mergeCells('A2:M2');
        $sheet->mergeCells('A3:M3');
        $sheet->getStyle('A3')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FBBF24']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        
        $sheet->mergeCells('A4:M4');
        $sheet->mergeCells('A5:M5');
        
        $sheet->getStyle('A7:M7')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4B5563']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);
        
        return [];
    }
}
