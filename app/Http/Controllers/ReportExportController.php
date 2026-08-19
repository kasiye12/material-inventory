<?php

namespace App\Http\Controllers;

use App\Models\StockTransaction;
use App\Models\Location;
use App\Models\Item;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DeliveryReportExport;
use App\Exports\ProjectLedgerExport;
use App\Exports\WeeklyTransferExport;
use App\Services\ActivityLogger;

class ReportExportController extends Controller
{
    private $companyName = "ቲ. ኤን. ቲ. ኮንስትራክሽንና ንግድ ሥራዎች";
    private $companyNameEn = "TNT Construction & Trading";

    public function exportDeliveryReport(Request $request)
    {
        $format = $request->format ?? 'pdf';
        $dateFrom = $request->date_from ?? date('Y-m-01');
        $dateTo = $request->date_to ?? date('Y-m-d');
        $locationId = $request->location_id;
        $fromLocationId = $request->from_location_id;
        $reportType = $request->report_type;
        
        // Build query with all filters
        $query = StockTransaction::with(['item.category', 'fromLocation', 'toLocation'])
            ->whereBetween('transaction_date', [$dateFrom, $dateTo]);
        
        // Filter by transaction types based on report_type
        if ($reportType === 'regular') {
            $query->whereIn('transaction_type', ['GRV', 'ISTRV']);
        } elseif ($reportType === 'fixed') {
            $query->where('transaction_type', 'FARV');
        } elseif ($reportType === 'used') {
            $query->where('transaction_type', 'UMTRV');
        } else {
            // All types
            $query->whereIn('transaction_type', ['GRV', 'ISTRV', 'FARV', 'UMTRV']);
        }
        
        if ($fromLocationId) {
            $query->where('from_location_id', $fromLocationId);
        }
        if ($locationId) {
            $query->where('to_location_id', $locationId);
        }
        
        $deliveries = $query->orderBy('transaction_date', 'asc')->get();
        
        ActivityLogger::log('EXPORT', 'Delivery Report exported (Type: ' . ($reportType ?? 'All') . ')', 'REPORT', null, 'Delivery Report', 'Reports');
        
        if ($format === 'excel') {
            return Excel::download(new DeliveryReportExport($deliveries, $locationId ? Location::find($locationId)?->name : 'All', $dateFrom, $dateTo, $reportType), 'Delivery_Report_' . date('Y-m-d') . '.xlsx');
        }
        
        $groupedDeliveries = $deliveries->groupBy(fn($d) => $d->item->category->name ?? 'Uncategorized');
        $locationName = $locationId ? Location::find($locationId)?->name : 'All Locations';
        $documentNo = "OF/TNT/SUP/033";
        $companyName = $this->companyName;
        $companyNameEn = $this->companyNameEn;
        
        $data = compact('groupedDeliveries', 'companyName', 'companyNameEn', 'documentNo', 'locationName', 'dateFrom', 'dateTo', 'deliveries', 'reportType');
        
        $pdf = Pdf::loadView('reports.exports.delivery-pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions(['defaultFont' => 'DejaVu Sans', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        return $pdf->download('Delivery_Report_' . date('Y-m-d') . '.pdf');
    }
    
    public function exportQuarryDelivery(Request $request)
    {
        $format = $request->format ?? 'pdf';
        $dateFrom = $request->date_from ?? date('Y-m-01');
        $dateTo = $request->date_to ?? date('Y-m-d');
        $locationId = $request->location_id;
        $reportType = $request->report_type;
        
        $query = StockTransaction::with(['item', 'fromLocation', 'toLocation'])
            ->whereBetween('transaction_date', [$dateFrom, $dateTo]);
        
        // Filter by report type
        if ($reportType === 'purchase') {
            $query->where('transaction_type', 'GRV');
        } elseif ($reportType === 'transfer_in') {
            $query->where('transaction_type', 'ISTRV');
        } elseif ($reportType === 'transfer_out') {
            $query->where('transaction_type', 'SIV');
        } elseif ($reportType === 'quarry_transfer') {
            $query->where('transaction_type', 'TRANSFER_OUT');
        } else {
            $query->whereIn('transaction_type', ['GRV', 'ISTRV', 'SIV', 'TRANSFER_OUT']);
        }
        
        if ($locationId) {
            $query->where(function($q) use ($locationId) {
                $q->where('from_location_id', $locationId)
                  ->orWhere('to_location_id', $locationId);
            });
        }
        
        $transactions = $query->orderBy('transaction_date', 'asc')->get();
        
        $locationName = $locationId ? Location::find($locationId)?->name : 'All Locations';
        $companyName = $this->companyName;
        $companyNameEn = $this->companyNameEn;
        $documentNo = "OF/TNT/SUP/037";
        
        $data = compact('transactions', 'companyName', 'companyNameEn', 'documentNo', 'dateFrom', 'dateTo', 'locationName', 'reportType');
        
        if ($format === 'excel') {
            return $this->exportQuarryDeliveryCsv($transactions, $companyName, $companyNameEn, $documentNo, $locationName);
        }
        
        $pdf = Pdf::loadView('reports.exports.quarry-delivery-pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions(['defaultFont' => 'DejaVu Sans', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        return $pdf->download('Quarry_Delivery_Report_' . date('Y-m-d') . '.pdf');
    }
    
    public function exportProjectLedger(Request $request)
    {
        $format = $request->format ?? 'pdf';
        $dateFrom = $request->date_from ?? date('Y-m-01');
        $dateTo = $request->date_to ?? date('Y-m-d');
        $locationId = $request->location_id;
        $categoryId = $request->category_id;
        
        $locationName = $locationId ? Location::find($locationId)?->name : 'All Locations';
        
        $transactions = StockTransaction::with(['item.category', 'fromLocation', 'toLocation'])
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->when($locationId, fn($q) => $q->where(fn($sub) => $sub->where('from_location_id', $locationId)->orWhere('to_location_id', $locationId)))
            ->when($categoryId, fn($q) => $q->whereHas('item', fn($sub) => $sub->where('category_id', $categoryId)))
            ->orderBy('transaction_date', 'asc')
            ->get();
        
        $items = Item::with('category')
            ->where('is_active', true)
            ->when($categoryId, fn($q) => $q->where('category_id', $categoryId))
            ->orderBy('category_id')->orderBy('name')->get();
        
        $reportData = [];
        foreach ($items->groupBy(fn($item) => $item->category->name ?? 'Uncategorized') as $category => $categoryItems) {
            foreach ($categoryItems as $item) {
                $openingBalance = $this->calculateOpeningBalance($item->id, $dateFrom, $locationId);
                $itemTransactions = $transactions->where('item_id', $item->id);
                
                $grv = $itemTransactions->where('transaction_type', 'GRV');
                $istrv = $itemTransactions->where('transaction_type', 'ISTRV');
                $siv = $itemTransactions->where('transaction_type', 'SIV');
                $transfer = $itemTransactions->whereIn('transaction_type', ['TRANSFER_OUT', 'UMTV']);
                $return = $itemTransactions->whereIn('transaction_type', ['STORE_RETURN', 'SRV']);
                
                $reportData[$category][] = [
                    'item' => $item,
                    'opening_balance' => max(0, $openingBalance),
                    'grv_qty' => $grv->sum('quantity'),
                    'grv_ref' => $grv->pluck('reference_number')->filter()->implode(', '),
                    'istrv_qty' => $istrv->sum('quantity'),
                    'istrv_ref' => $istrv->pluck('reference_number')->filter()->implode(', '),
                    'siv_qty' => $siv->sum('quantity'),
                    'siv_ref' => $siv->pluck('reference_number')->filter()->implode(', '),
                    'transfer_out_qty' => $transfer->sum('quantity'),
                    'transfer_ref' => $transfer->pluck('reference_number')->filter()->implode(', '),
                    'store_return_qty' => $return->sum('quantity'),
                    'return_ref' => $return->pluck('reference_number')->filter()->implode(', '),
                    'ending_balance' => max(0, $openingBalance + $grv->sum('quantity') + $istrv->sum('quantity') + $return->sum('quantity') - $siv->sum('quantity') - $transfer->sum('quantity')),
                ];
            }
        }
        
        ActivityLogger::log('EXPORT', 'Project Ledger exported', 'REPORT', null, 'Project Material Ledger', 'Reports');
        
        if ($format === 'excel') {
            return Excel::download(new ProjectLedgerExport($reportData, $locationName, $dateFrom, $dateTo), 'Project_Ledger_' . date('Y-m-d') . '.xlsx');
        }
        
        $companyName = $this->companyName;
        $companyNameEn = $this->companyNameEn;
        
        $data = compact('reportData', 'companyName', 'companyNameEn', 'locationName', 'dateFrom', 'dateTo');
        
        $pdf = Pdf::loadView('reports.exports.project-ledger-pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions(['defaultFont' => 'DejaVu Sans', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        return $pdf->download('Project_Ledger_' . date('Y-m-d') . '.pdf');
    }
    
    public function exportWeeklyTransfer(Request $request)
    {
        $format = $request->format ?? 'pdf';
        $dateFrom = $request->date_from ?? Carbon::now('Africa/Addis_Ababa')->startOfWeek()->format('Y-m-d');
        $dateTo = $request->date_to ?? Carbon::now('Africa/Addis_Ababa')->endOfWeek()->format('Y-m-d');
        $locationId = $request->location_id;
        
        $transfers = StockTransaction::with(['item', 'fromLocation', 'toLocation'])
            ->where('transaction_type', 'TRANSFER_OUT')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->when($locationId, fn($q) => $q->where(fn($sub) => $sub->where('from_location_id', $locationId)->orWhere('to_location_id', $locationId)))
            ->orderBy('transaction_date', 'asc')
            ->get();
        
        ActivityLogger::log('EXPORT', 'Weekly Transfer Report exported', 'REPORT', null, 'Weekly Transfer Report', 'Reports');
        
        if ($format === 'excel') {
            return Excel::download(new WeeklyTransferExport($transfers, $dateFrom, $dateTo), 'Weekly_Transfer_Report_' . date('Y-m-d') . '.xlsx');
        }
        
        $companyName = $this->companyName;
        $companyNameEn = $this->companyNameEn;
        $documentNo = "OF/TNT/SUP/034";
        
        $data = compact('transfers', 'companyName', 'companyNameEn', 'documentNo', 'dateFrom', 'dateTo');
        
        $pdf = Pdf::loadView('reports.exports.weekly-transfer-pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions(['defaultFont' => 'DejaVu Sans', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        return $pdf->download('Weekly_Transfer_Report_' . date('Y-m-d') . '.pdf');
    }
    
    public function exportWeeklyStockStatus(Request $request)
    {
        $format = $request->format ?? 'pdf';
        $dateFrom = $request->date_from ?? Carbon::now('Africa/Addis_Ababa')->startOfMonth()->format('Y-m-d');
        $dateTo = $request->date_to ?? Carbon::now('Africa/Addis_Ababa')->endOfMonth()->format('Y-m-d');
        $locationId = $request->location_id;
        
        $transfers = StockTransaction::with(['item', 'fromLocation', 'toLocation'])
            ->where('transaction_type', 'TRANSFER_OUT')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->when($locationId, fn($q) => $q->where('to_location_id', $locationId))
            ->orderBy('transaction_date', 'asc')
            ->get();
        
        $companyName = $this->companyName;
        $companyNameEn = $this->companyNameEn;
        $documentNo = "OF/TNT/SUP/034";
        
        $data = compact('transfers', 'companyName', 'companyNameEn', 'documentNo', 'dateFrom', 'dateTo');
        
        if ($format === 'excel') {
            return $this->exportWeeklyStockStatusCsv($transfers, $companyName, $companyNameEn, $documentNo);
        }
        
        $pdf = Pdf::loadView('reports.exports.weekly-stock-status-pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions(['defaultFont' => 'DejaVu Sans', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        return $pdf->download('Weekly_Stock_Status_' . date('Y-m-d') . '.pdf');
    }
    
    private function exportQuarryDeliveryCsv($transactions, $companyName, $companyNameEn, $documentNo, $locationName)
    {
        $filename = 'Quarry_Delivery_Report_' . date('Y-m-d') . '.csv';
        $headers = ['Content-Type' => 'text/csv; charset=UTF-8', 'Content-Disposition' => 'attachment; filename="' . $filename . '"'];
        
        return response()->stream(function() use ($transactions, $companyName, $companyNameEn, $documentNo, $locationName) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['Company Name:', '', '', '', 'Document No:']);
            fputcsv($out, [$companyName, '', '', '', $documentNo]);
            fputcsv($out, [$companyNameEn]);
            fputcsv($out, []);
            fputcsv($out, ['Daily Quarry Materials Delivery Report']);
            fputcsv($out, []);
            fputcsv($out, ['No', 'Item Description', 'Unit', 'Qty', 'Ref No', 'Delivery Date', 'From', 'To', 'Plate No', 'Remark']);
            
            $counter = 1;
            foreach ($transactions as $t) {
                fputcsv($out, [
                    $counter++,
                    $t->item->name ?? '',
                    $t->item->unit ?? '',
                    $t->quantity,
                    $t->reference_number ?? '',
                    $t->transaction_date->format('d/m/Y'),
                    $t->fromLocation->name ?? '',
                    $t->toLocation->name ?? '',
                    $t->remarks ?? '',
                    $t->document_number ?? ''
                ]);
            }
            fclose($out);
        }, 200, $headers);
    }
    
    private function exportWeeklyStockStatusCsv($transfers, $companyName, $companyNameEn, $documentNo)
    {
        $filename = 'Weekly_Stock_Status_' . date('Y-m-d') . '.csv';
        $headers = ['Content-Type' => 'text/csv; charset=UTF-8', 'Content-Disposition' => 'attachment; filename="' . $filename . '"'];
        
        return response()->stream(function() use ($transfers, $companyName, $companyNameEn, $documentNo) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['Company Name:', '', '', '', 'Document No:']);
            fputcsv($out, [$companyName, '', '', '', $documentNo]);
            fputcsv($out, [$companyNameEn]);
            fputcsv($out, []);
            fputcsv($out, ['Weekly Stock Status Report']);
            fputcsv($out, []);
            fputcsv($out, ['No', 'Item Description', 'UOM', 'TR-Out Qty', 'Unit Price', 'Total Price', 'TR-Out No.', 'TR-Out Date', 'TR-IN No.', 'TR-IN Qty', 'Project', 'TR-IN Date', 'Remark']);
            
            $counter = 1;
            foreach ($transfers as $t) {
                $totalPrice = $t->quantity * ($t->item->unit_price ?? 0);
                fputcsv($out, [
                    $counter++,
                    $t->item->name ?? '',
                    $t->item->unit ?? '',
                    $t->quantity,
                    $t->item->unit_price ?? '',
                    $totalPrice,
                    $t->reference_number ?? '',
                    $t->transaction_date->format('d-m-Y'),
                    $t->document_number ?? '',
                    $t->quantity,
                    $t->toLocation->name ?? '',
                    $t->transaction_date->format('d-m-Y'),
                    $t->remarks ?? ''
                ]);
            }
            fclose($out);
        }, 200, $headers);
    }
    
    private function calculateOpeningBalance($itemId, $dateFrom, $locationId)
    {
        $inTypes = ['GRV', 'ISTRV', 'STORE_RETURN', 'BEGINNING_BALANCE', 'SRV', 'TTRV', 'FARV', 'UMTRV', 'FGRV', 'FRV'];
        $outTypes = ['SIV', 'TRANSFER_OUT', 'FIV', 'UMIV', 'UMTV'];
        
        $received = StockTransaction::where('item_id', $itemId)
            ->where('transaction_date', '<', $dateFrom)
            ->when($locationId, fn($q) => $q->where('to_location_id', $locationId))
            ->whereIn('transaction_type', $inTypes)
            ->sum('quantity');
            
        $issued = StockTransaction::where('item_id', $itemId)
            ->where('transaction_date', '<', $dateFrom)
            ->when($locationId, fn($q) => $q->where('from_location_id', $locationId))
            ->whereIn('transaction_type', $outTypes)
            ->sum('quantity');
            
        return max(0, $received - $issued);
    }
}
