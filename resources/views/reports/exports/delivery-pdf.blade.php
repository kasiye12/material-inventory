<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Daily Material Delivery Report</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 8px; padding: 15px; }
        
        .header { display: flex; align-items: center; justify-content: center; margin-bottom: 10px; border-bottom: 3px double #1e293b; padding-bottom: 8px; position: relative; min-height: 50px; }
        .logo-container { position: absolute; left: 0; top: 0; width: 80px; text-align: center; }
        .logo-container img { width: 80px; height: 40px; object-fit: contain; }
        .company-info { text-align: center; padding: 0 100px; }
        .company-amharic { font-size: 12px; font-weight: bold; }
        .company-english { font-size: 9px; font-style: italic; }
        .doc-info { position: absolute; right: 0; top: 0; text-align: right; font-size: 8px; }
        
        .title { text-align: center; font-size: 11px; font-weight: bold; margin: 8px 0; text-transform: uppercase; background: #fbbf24; padding: 5px; }
        .subtitle { text-align: center; font-size: 9px; margin-bottom: 8px; text-decoration: underline; font-weight: bold; }
        
        .section-heading { background: #1e3a8a; color: #fff; padding: 5px 8px; font-size: 9px; font-weight: bold; margin: 10px 0 5px; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th { border: 1px solid #000; padding: 4px 2px; background: #4b5563; color: #fff; font-size: 6px; text-align: center; }
        td { border: 1px solid #000; padding: 3px 2px; font-size: 6px; }
        .text-center { text-align: center; }
        .category-row td { background: #e5e7eb; font-weight: bold; }
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
    
    <div class="title">Daily Material Delivery Report</div>
    <div class="subtitle">List of Items to {{ $locationName }}</div>
    
    @php
        $regularItems = $deliveries->whereIn('transaction_type', ['GRV', 'ISTRV']);
        $fixedItems = $deliveries->where('transaction_type', 'FARV');
        $usedItems = $deliveries->where('transaction_type', 'UMTRV');
    @endphp
    
    @if($regularItems->count() > 0)
    <div class="section-heading">SECTION A: REGULAR MATERIALS (ISTV/ISTRV)</div>
    <table>
        <thead>
            <tr>
                <th>No</th><th>Item Description</th><th>Unit</th><th>Qty</th>
                <th>ISTV NO</th><th>ISTRV NO</th><th>Delivery Date</th><th>FROM</th><th>Remark</th>
            </tr>
        </thead>
        <tbody>
            @php $counter = 1; @endphp
            @foreach($regularItems as $d)
            <tr>
                <td class="text-center">{{ $counter++ }}</td>
                <td>{{ $d->item->name ?? '' }}</td>
                <td class="text-center">{{ $d->item->unit ?? '' }}</td>
                <td class="text-center">{{ $d->quantity }}</td>
                <td class="text-center">{{ $d->reference_number ?? '' }}</td>
                <td class="text-center">{{ $d->document_number ?? '' }}</td>
                <td class="text-center">{{ $d->transaction_date->format('d/m/Y') }}</td>
                <td class="text-center">{{ $d->fromLocation->name ?? 'Head Office' }}</td>
                <td>{{ $d->remarks ?? '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    
    @if($fixedItems->count() > 0)
    <div class="section-heading">SECTION B: FIXED ASSETS (ISFATV/ISFATRV)</div>
    <table>
        <thead>
            <tr>
                <th>No</th><th>Item Description</th><th>Unit</th><th>Qty</th>
                <th>ISFATV NO</th><th>ISFATRV NO</th><th>Delivery Date</th><th>From</th><th>Remark</th>
            </tr>
        </thead>
        <tbody>
            @php $counter = 1; @endphp
            @foreach($fixedItems as $d)
            <tr>
                <td class="text-center">{{ $counter++ }}</td>
                <td>{{ $d->item->name ?? '' }}</td>
                <td class="text-center">{{ $d->item->unit ?? '' }}</td>
                <td class="text-center">{{ $d->quantity }}</td>
                <td class="text-center">{{ $d->reference_number ?? '' }}</td>
                <td class="text-center">{{ $d->document_number ?? '' }}</td>
                <td class="text-center">{{ $d->transaction_date->format('d/m/Y') }}</td>
                <td class="text-center">{{ $d->fromLocation->name ?? 'Head Office' }}</td>
                <td>{{ $d->remarks ?? '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    
    @if($usedItems->count() > 0)
    <div class="section-heading">SECTION C: USED MATERIALS (UMTR/UMTRV)</div>
    <table>
        <thead>
            <tr>
                <th>No</th><th>Item Description</th><th>Unit</th><th>Qty</th>
                <th>UMTR NO</th><th>UMTRV No</th><th>Delivery Date</th><th>FROM</th><th>Remark</th>
            </tr>
        </thead>
        <tbody>
            @php $counter = 1; @endphp
            @foreach($usedItems as $d)
            <tr>
                <td class="text-center">{{ $counter++ }}</td>
                <td>{{ $d->item->name ?? '' }}</td>
                <td class="text-center">{{ $d->item->unit ?? '' }}</td>
                <td class="text-center">{{ $d->quantity }}</td>
                <td class="text-center">{{ $d->reference_number ?? '' }}</td>
                <td class="text-center">{{ $d->document_number ?? '' }}</td>
                <td class="text-center">{{ $d->transaction_date->format('d/m/Y') }}</td>
                <td class="text-center">{{ $d->fromLocation->name ?? 'Head Office' }}</td>
                <td>{{ $d->remarks ?? '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</body>
</html>
