<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Project Material Ledger</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', sans-serif; font-size: 9px; padding: 20px; }
        .header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px; border-bottom: 3px double #1e293b; padding-bottom: 10px; }
        .logo-container { width: 80px; text-align: center; }
        .logo-container img { max-width: 80px; max-height: 60px; }
        .logo-placeholder { width: 70px; height: 50px; background: #1e293b; color: #fbbf24; display: flex; align-items: center; justify-content: center; border-radius: 6px; font-weight: bold; font-size: 16px; }
        .company-info { text-align: center; flex: 1; }
        .company-amharic { font-size: 14px; font-weight: bold; color: #1e293b; }
        .company-english { font-size: 12px; font-style: italic; }
        .project-name { font-size: 13px; font-weight: bold; text-decoration: underline; margin-top: 3px; }
        .doc-info { text-align: right; font-size: 9px; }
        table { width: 100%; border-collapse: collapse; }
        th { border: 1px solid #000; padding: 4px 2px; background: #4b5563; color: #fff; font-size: 7px; text-align: center; }
        td { border: 1px solid #000; padding: 3px 2px; font-size: 8px; }
        .category-header { background: #e5e7eb; font-weight: bold; text-transform: uppercase; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        @media print { body { padding: 5px; } }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-container">
            <img src="{{ asset('images/company-logo.png') }}" alt="Logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <div class="logo-placeholder" style="display:none;">TNT</div>
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
                <th>Ref.No.</th><th>Qty</th>
                <th>Ref.No.</th><th>Qty</th>
                <th>Ref.No.</th><th>Qty</th>
                <th>Ref.No.</th><th>Qty</th>
                <th>Ref.No.</th><th>Qty</th>
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
