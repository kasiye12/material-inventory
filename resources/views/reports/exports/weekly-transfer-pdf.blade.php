<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Weekly Transfer Report</title>
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
        
        .logo-container { position: absolute; left: 0; top: 0; width: 80px; text-align: center; }
        .logo-container img { width: 80px; height: 40px; object-fit: contain; }
        .company-info { text-align: center; padding: 0 100px; }
        .company-amharic { font-size: 12px; font-weight: bold; }
        .company-english { font-size: 9px; font-style: italic; }
        .doc-info { position: absolute; right: 0; top: 0; text-align: right; font-size: 8px; }
        
        .title { text-align: center; font-size: 11px; font-weight: bold; margin: 8px 0; text-transform: uppercase; background: #fbbf24; padding: 5px; }
        .subtitle { text-align: center; font-size: 9px; margin-bottom: 8px; text-decoration: underline; font-weight: bold; }
        
        table { width: 100%; border-collapse: collapse; }
        th { border: 1px solid #000; padding: 4px 2px; background: #4b5563; color: #fff; font-size: 7px; text-align: center; }
        td { border: 1px solid #000; padding: 3px 2px; font-size: 7px; }
        .text-center { text-align: center; }
        .total-row { background: #e5e7eb; font-weight: bold; }
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
    
    <div class="title">Weekly Report {{ date('d/m/Y', strtotime($dateFrom)) }} - {{ date('d/m/Y', strtotime($dateTo)) }}</div>
    <div class="subtitle">Material Transfer From Project To Project</div>
    
    <table>
        <thead>
            <tr>
                <th>No</th><th>Item Description</th><th>Unit</th><th>Requested Qty</th><th>SR.No</th>
                <th>Date</th><th>From Project</th><th>Out/SIV NO</th><th>To Project</th><th>In NO</th>
                <th>Received QTY</th><th>Delivered Date</th><th>Remaining QTY</th><th>Remark</th>
            </tr>
        </thead>
        <tbody>
            @php $counter = 1; @endphp
            @forelse($transfers as $t)
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
            @empty
            <tr><td colspan="14" class="text-center">No transfer records found</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
