<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Project Material Ledger</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 8px; padding: 15px; }
        
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
        
        .logo-container { 
            position: absolute;
            left: 0;
            top: 0;
            width: 80px; 
            text-align: center; 
        }
        
        .logo-container img { 
            width: 80px; 
            height: 40px; 
            object-fit: contain;
        }
        
        .company-info { 
            text-align: center; 
            padding: 0 100px; 
        }
        
        .company-amharic { font-size: 12px; font-weight: bold; }
        .company-english { font-size: 9px; font-style: italic; }
        .project-name { font-size: 11px; font-weight: bold; text-decoration: underline; margin-top: 2px; }
        
        .doc-info { 
            position: absolute;
            right: 0;
            top: 0;
            text-align: right; 
            font-size: 8px; 
        }
        
        table { width: 100%; border-collapse: collapse; }
        th { border: 1px solid #000; padding: 4px 2px; background: #4b5563; color: #fff; font-size: 7px; text-align: center; }
        td { border: 1px solid #000; padding: 3px 2px; font-size: 7px; }
        .category-header { background: #e5e7eb; font-weight: bold; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-container">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/company-logo.png'))) }}" alt="TNT Logo" style="width: 80px; height: 40px;">
        </div>
        <div class="company-info">
            <div class="company-amharic">{{ $companyName }}</div>
            <div class="company-english">{{ $companyNameEn }}</div>
            <div class="project-name">{{ $locationName }}</div>
        </div>
        <div class="doc-info">
            <div><strong>Document No:</strong> OF/TNT/SUP/033</div>
            <div><strong>Period:</strong> {{ $dateFrom }} to {{ $dateTo }}</div>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th rowspan="2">Item No.</th>
                <th rowspan="2">Description</th>
                <th rowspan="2">Unit</th>
                <th rowspan="2">Date</th>
                <th rowspan="2">Beg. Balance</th>
                <th colspan="2">GRV</th>
                <th colspan="2">ISTRV</th>
                <th colspan="2">SIV</th>
                <th colspan="2">Transferred Out</th>
                <th colspan="2">Store Return</th>
                <th rowspan="2">Ending Balance</th>
                <th rowspan="2">Remark</th>
            </tr>
            <tr>
                <th>Ref</th><th>Qty</th>
                <th>Ref</th><th>Qty</th>
                <th>Ref</th><th>Qty</th>
                <th>Ref</th><th>Qty</th>
                <th>Ref</th><th>Qty</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData as $category => $items)
            <tr class="category-header"><td colspan="16">{{ $category }}</td></tr>
            @php $counter = 1; @endphp
            @foreach($items as $data)
            <tr>
                <td class="text-center">{{ $counter++ }}</td>
                <td>{{ $data['item']->name }}</td>
                <td class="text-center">{{ $data['item']->unit }}</td>
                <td></td>
                <td class="text-right">{{ $data['opening_balance'] ?: '' }}</td>
                <td>{{ $data['grv_ref'] ?? '' }}</td>
                <td class="text-right">{{ $data['grv_qty'] ?: '' }}</td>
                <td>{{ $data['istrv_ref'] ?? '' }}</td>
                <td class="text-right">{{ $data['istrv_qty'] ?: '' }}</td>
                <td>{{ $data['siv_ref'] ?? '' }}</td>
                <td class="text-right">{{ $data['siv_qty'] ?: '' }}</td>
                <td>{{ $data['transfer_ref'] ?? '' }}</td>
                <td class="text-right">{{ $data['transfer_out_qty'] ?: '' }}</td>
                <td>{{ $data['return_ref'] ?? '' }}</td>
                <td class="text-right">{{ $data['store_return_qty'] ?: '' }}</td>
                <td class="text-right"><strong>{{ $data['ending_balance'] ?: '' }}</strong></td>
                <td></td>
            </tr>
            @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>
