<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Delivery Report</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; padding: 20px; }
        
        .header { 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            margin-bottom: 15px; 
            border-bottom: 3px double #1e293b; 
            padding-bottom: 12px; 
            position: relative;
            min-height: 60px;
        }
        
        .logo-container { 
            position: absolute;
            left: 0;
            top: 5px;
            width: 100px; 
            text-align: center; 
        }
        
        .logo-container img { 
            width: 100px; 
            height: 50px; 
            object-fit: contain;
        }
        
        .company-info { 
            text-align: center; 
            padding: 0 120px; 
        }
        
        .company-amharic { 
            font-size: 13px; 
            font-weight: bold; 
            color: #1e293b; 
            margin-bottom: 3px;
        }
        
        .company-english { 
            font-size: 11px; 
            font-style: italic; 
            color: #475569; 
        }
        
        .doc-info { 
            position: absolute;
            right: 0;
            top: 5px;
            text-align: right; 
            font-size: 9px; 
        }
        
        .doc-info div { margin-bottom: 3px; }
        
        .title { 
            text-align: center; 
            font-size: 13px; 
            font-weight: bold; 
            margin: 15px 0; 
            text-transform: uppercase; 
            background: #fbbf24; 
            padding: 8px; 
        }
        
        .info-line { text-align: center; margin-bottom: 5px; font-size: 10px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { border: 1px solid #000; padding: 6px 4px; background: #4b5563; color: #fff; font-size: 9px; text-align: center; }
        td { border: 1px solid #000; padding: 5px 4px; font-size: 9px; }
        .category-header { background: #e5e7eb; font-weight: bold; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-container">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/company-logo.png'))) }}" alt="TNT Logo" style="width: 100px; height: 50px;">
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
    <p class="info-line"><strong>Location:</strong> {{ $locationName }}</p>
    <p class="info-line"><strong>Period:</strong> {{ $dateFrom }} to {{ $dateTo }}</p>
    
    <table>
        <thead>
            <tr>
                <th width="5%">NO</th>
                <th width="30%">Item Description</th>
                <th width="8%">Unit</th>
                <th width="8%">Qty</th>
                <th width="10%">ISTV NO</th>
                <th width="10%">ISTRV NO</th>
                <th width="10%">Delivery Date</th>
                <th width="10%">FROM</th>
                <th width="9%">Remark</th>
            </tr>
        </thead>
        <tbody>
            @foreach($groupedDeliveries as $category => $items)
            <tr class="category-header">
                <td colspan="9">{{ $category }}</td>
            </tr>
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
