<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Delivery Report</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', sans-serif; font-size: 11px; padding: 30px; }
        .header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; border-bottom: 3px double #1e293b; padding-bottom: 15px; }
        .logo-container { width: 100px; text-align: center; }
        .logo-container img { max-width: 100px; max-height: 80px; }
        .logo-placeholder { width: 80px; height: 60px; background: #1e293b; color: #fbbf24; display: flex; align-items: center; justify-content: center; border-radius: 8px; font-weight: bold; font-size: 18px; }
        .company-info { text-align: center; flex: 1; }
        .company-amharic { font-size: 16px; font-weight: bold; color: #1e293b; }
        .company-english { font-size: 14px; font-style: italic; color: #334155; }
        .doc-info { text-align: right; font-size: 10px; }
        .doc-info div { margin-bottom: 3px; }
        .title { text-align: center; font-size: 14px; font-weight: bold; margin: 15px 0; text-decoration: underline; text-transform: uppercase; background: #fbbf24; padding: 8px; }
        table { width: 100%; border-collapse: collapse; }
        th { border: 1px solid #000; padding: 6px; background: #4b5563; color: #fff; font-size: 10px; }
        td { border: 1px solid #000; padding: 5px; font-size: 10px; }
        .category-header { background: #e5e7eb; font-weight: bold; }
        .text-center { text-align: center; }
        @media print { body { padding: 10px; } }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-container">
            <img src="{{ asset('images/company-logo.png') }}" alt="TNT Logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <div class="logo-placeholder" style="display:none;">TNT</div>
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
    <p style="text-align:center; margin-bottom:5px;"><strong>Location:</strong> {{ $locationName }}</p>
    <p style="text-align:center; margin-bottom:15px;"><strong>Period:</strong> {{ $dateFrom }} to {{ $dateTo }}</p>
    
    <table>
        <thead>
            <tr>
                <th>NO</th><th>Item Description</th><th>Unit</th><th>Qty</th>
                <th>ISTV NO</th><th>ISTRV NO</th><th>Delivery Date</th><th>FROM</th><th>Remark</th>
            </tr>
        </thead>
        <tbody>
            @foreach($groupedDeliveries as $category => $items)
            <tr class="category-header"><td colspan="9">{{ $category }}</td></tr>
            @php $counter = 1; @endphp
            @foreach($items as $d)
            <tr>
                <td class="text-center">{{ $counter++ }}</td>
                <td>{{ $d->item->name }}</td>
                <td class="text-center">{{ $d->item->unit }}</td>
                <td class="text-center">{{ $d->quantity }}</td>
                <td class="text-center">{{ $d->transaction_type === 'GRV' ? $d->reference_number : '' }}</td>
                <td class="text-center">{{ $d->transaction_type === 'ISTRV' ? ($d->reference_number ?? $d->document_number) : '' }}</td>
                <td class="text-center">{{ $d->transaction_date->format('d/m/Y') }}</td>
                <td class="text-center">{{ $d->fromLocation->name ?? 'Head Office' }}</td>
                <td>{{ $d->remarks ?? '' }}</td>
            </tr>
            @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>
