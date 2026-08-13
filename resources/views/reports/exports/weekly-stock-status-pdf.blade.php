<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Weekly Stock Status Report</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 7px; padding: 15px; }
        
        .header { 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            margin-bottom: 10px; 
            border-bottom: 3px double #1e293b; 
            padding-bottom: 8px; 
            position: relative;
            min-height: 50px;
        }
        
        .logo-container { position: absolute; left: 0; top: 0; width: 80px; text-align: center; }
        .logo-container img { width: 80px; height: 40px; object-fit: contain; }
        .company-info { text-align: center; padding: 0 100px; }
        .company-amharic { font-size: 12px; font-weight: bold; }
        .company-english { font-size: 9px; font-style: italic; }
        .doc-info { position: absolute; right: 0; top: 0; text-align: right; font-size: 8px; }
        
        .title { 
            text-align: center; 
            font-size: 11px; 
            font-weight: bold; 
            margin: 8px 0; 
            text-transform: uppercase; 
            background: #fbbf24; 
            padding: 5px; 
        }
        
        .subtitle { text-align: center; font-size: 9px; margin-bottom: 8px; text-decoration: underline; }
        
        .section-heading { 
            background: #1e3a8a; 
            color: #fff; 
            padding: 5px 8px; 
            font-size: 9px; 
            font-weight: bold; 
            margin: 10px 0 5px; 
        }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th { border: 1px solid #000; padding: 4px 2px; background: #4b5563; color: #fff; font-size: 6px; text-align: center; }
        td { border: 1px solid #000; padding: 3px 2px; font-size: 6px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .total-box { border: 2px solid #1e3a8a; padding: 8px; margin-top: 10px; }
        .total-box table { margin: 0; }
        .total-box td { border: none; font-size: 9px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-container">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/company-logo.png'))) }}" alt="Logo">
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
    <div class="subtitle">From {{ date('d/m/Y', strtotime($dateFrom)) }} - {{ date('d/m/Y', strtotime($dateTo)) }}</div>
    
    @php
        $hoTransfers = $transfers->filter(function($t) {
            return $t->fromLocation && $t->fromLocation->type === 'head_office';
        });
        
        $mainStoreTransfers = $transfers->filter(function($t) {
            return $t->fromLocation && $t->fromLocation->code === 'MAIN';
        });
        
        $projectToProjectTransfers = $transfers->filter(function($t) {
            return $t->fromLocation && in_array($t->fromLocation->type, ['project', 'site']) 
                && $t->toLocation && in_array($t->toLocation->type, ['project', 'site']);
        });
        
        $grandTotal = 0;
        foreach ($transfers as $t) {
            $grandTotal += $t->quantity * ($t->item->unit_price ?? 0);
        }
    @endphp
    
    <!-- Section A: Head Office to Projects -->
    @if($hoTransfers->count() > 0)
    <div class="section-heading">SECTION A: HEAD OFFICE TO PROJECTS</div>
    <table>
        <thead>
            <tr>
                <th>No.</th><th>Item Description</th><th>UOM</th>
                <th>TR-Out Qty</th><th>Unit Price</th><th>Total Price</th>
                <th>TR-Out No.</th><th>TR-Out Date</th>
                <th>TR-IN No.</th><th>TR-IN Qty</th><th>Project</th><th>TR-IN Date</th><th>Remark</th>
            </tr>
        </thead>
        <tbody>
            @php $counter = 1; @endphp
            @foreach($hoTransfers as $t)
            @php $totalPrice = $t->quantity * ($t->item->unit_price ?? 0); @endphp
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
            @endforeach
        </tbody>
    </table>
    @endif
    
    <!-- Section B: Main Store to Projects -->
    @if($mainStoreTransfers->count() > 0)
    <div class="section-heading">SECTION B: MAIN STORE TO PROJECTS</div>
    <table>
        <thead>
            <tr>
                <th>No.</th><th>Item Description</th><th>UOM</th>
                <th>TR-Out Qty</th><th>Unit Price</th><th>Total Price</th>
                <th>TR-Out No.</th><th>TR-Out Date</th>
                <th>TR-IN No.</th><th>TR-IN Qty</th><th>Project</th><th>TR-IN Date</th><th>Remark</th>
            </tr>
        </thead>
        <tbody>
            @php $counter = 1; @endphp
            @foreach($mainStoreTransfers as $t)
            @php $totalPrice = $t->quantity * ($t->item->unit_price ?? 0); @endphp
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
            @endforeach
        </tbody>
    </table>
    @endif
    
    <!-- Section C: Project to Project -->
    @if($projectToProjectTransfers->count() > 0)
    <div class="section-heading">SECTION C: PROJECT TO PROJECT TRANSFERS</div>
    <table>
        <thead>
            <tr>
                <th>No.</th><th>Item Description</th><th>UOM</th><th>Requested Qty</th><th>SR.No</th>
                <th>Date</th><th>From Project</th><th>Out/SIV NO</th><th>To Project</th><th>In NO</th>
                <th>Received QTY</th><th>Delivered Date</th><th>Remaining QTY</th><th>Remark</th>
            </tr>
        </thead>
        <tbody>
            @php $counter = 1; @endphp
            @foreach($projectToProjectTransfers as $t)
            <tr>
                <td class="text-center">{{ $counter++ }}</td>
                <td>{{ $t->item->name ?? '' }}</td>
                <td class="text-center">{{ $t->item->unit ?? '' }}</td>
                <td class="text-center">{{ $t->quantity }}</td>
                <td class="text-center">{{ $t->reference_number ?? '' }}</td>
                <td class="text-center">{{ $t->transaction_date->format('d/m/Y') }}</td>
                <td>{{ $t->fromLocation->name ?? '' }}</td>
                <td class="text-center">{{ $t->reference_number ?? '' }}</td>
                <td>{{ $t->toLocation->name ?? '' }}</td>
                <td class="text-center">{{ $t->document_number ?? '' }}</td>
                <td class="text-center">{{ $t->quantity }}</td>
                <td class="text-center">{{ $t->transaction_date->format('d/m/Y') }}</td>
                <td class="text-center"></td>
                <td>{{ $t->remarks ?? '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    
    <!-- Grand Total -->
    @if($transfers->count() > 0)
    <div class="total-box">
        <table>
            <tr>
                <td style="text-align:right; font-weight:bold; width: 70%;">GRAND TOTAL AMOUNT:</td>
                <td style="text-align:right; font-weight:bold; width: 30%;">{{ number_format($grandTotal, 2) }}</td>
            </tr>
        </table>
    </div>
    @endif
</body>
</html>
