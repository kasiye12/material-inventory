<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Weekly Stock Status Report</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', sans-serif; font-size: 9px; padding: 15px; }
        .header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; border-bottom: 3px double #1e293b; padding-bottom: 8px; }
        .logo-container { width: 70px; text-align: center; }
        .logo-container img { max-width: 70px; max-height: 50px; }
        .company-info { text-align: center; flex: 1; }
        .company-amharic { font-size: 12px; font-weight: bold; }
        .company-english { font-size: 10px; font-style: italic; }
        .doc-info { text-align: right; font-size: 8px; }
        .title { text-align: center; font-size: 11px; font-weight: bold; margin: 8px 0; background: #fbbf24; padding: 5px; }
        .subtitle { text-align: center; font-size: 9px; margin-bottom: 10px; text-decoration: underline; }
        table { width: 100%; border-collapse: collapse; }
        th { border: 1px solid #000; padding: 4px 2px; background: #4b5563; color: #fff; font-size: 7px; text-align: center; }
        td { border: 1px solid #000; padding: 3px 2px; font-size: 7px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .total-row { font-weight: bold; background: #e5e7eb; }
        @media print { body { padding: 5px; } }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-container">
            <img src="{{ public_path('images/company-logo.png') }}" alt="Logo" style="width: 70px; height: 50px;">
        </div>
        <div class="company-info">
            <div class="company-amharic">{{ $companyName }}</div>
            <div class="company-english">{{ $companyNameEn }}</div>
        </div>
        <div class="doc-info">
            <div><strong>Document No:</strong> {{ $documentNo }}</div>
            <div><strong>Issue No:</strong> 1</div>
            <div><strong>Page:</strong> 1 of 1</div>
        </div>
    </div>
    
    <div class="title">Weekly Stock Status Report</div>
    <div class="subtitle">List of Items Purchased Through Head Office To Projects From {{ date('d/m/Y', strtotime($dateFrom)) }} - {{ date('d/m/Y', strtotime($dateTo)) }}</div>
    
    <table>
        <thead>
            <tr>
                <th rowspan="2">No.</th>
                <th rowspan="2">Item Description</th>
                <th rowspan="2">UOM</th>
                <th colspan="3">HO Transfer-Out</th>
                <th colspan="2">Project Transfer-IN</th>
                <th rowspan="2">Remark</th>
            </tr>
            <tr>
                <th>TR-Out Qty</th>
                <th>Unit Price</th>
                <th>Total Price</th>
                <th>TR-Out No.</th>
                <th>TR-Out Date</th>
                <th>TR-IN No.</th>
                <th>TR-IN Qty</th>
                <th>Project</th>
                <th>TR-IN Date</th>
            </tr>
        </thead>
        <tbody>
            @php
                $counter = 1;
                $grandTotal = 0;
            @endphp
            @forelse($transfers as $t)
            @php
                $totalPrice = $t->quantity * ($t->item->unit_price ?? 0);
                $grandTotal += $totalPrice;
            @endphp
            <tr>
                <td class="text-center">{{ $counter++ }}</td>
                <td>{{ $t->item->name ?? '' }}</td>
                <td class="text-center">{{ $t->item->unit ?? '' }}</td>
                <td class="text-right">{{ $t->quantity }}</td>
                <td class="text-right">{{ $t->item->unit_price ? number_format($t->item->unit_price, 2) : '' }}</td>
                <td class="text-right">{{ $totalPrice ? number_format($totalPrice, 2) : '' }}</td>
                <td class="text-center">{{ $t->reference_number ?? '' }}</td>
                <td class="text-center">{{ $t->transaction_date->format('d-m-Y') }}</td>
                <td class="text-center">{{ $t->document_number ?? '' }}</td>
                <td class="text-right">{{ $t->quantity }}</td>
                <td>{{ $t->toLocation->name ?? '' }}</td>
                <td class="text-center">{{ $t->transaction_date->format('d-m-Y') }}</td>
                <td>{{ $t->remarks ?? '' }}</td>
            </tr>
            @empty
            <tr><td colspan="13" class="text-center">No transfer records found</td></tr>
            @endforelse
            @if($transfers->count() > 0)
            <tr class="total-row">
                <td colspan="5" class="text-right">Grand Total:</td>
                <td class="text-right">{{ number_format($grandTotal, 2) }}</td>
                <td colspan="7"></td>
            </tr>
            @endif
        </tbody>
    </table>
</body>
</html>
