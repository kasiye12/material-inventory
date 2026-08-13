<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Weekly Material Transfer Report</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', sans-serif; font-size: 10px; padding: 20px; }
        .header { text-align: center; margin-bottom: 10px; }
        .company-amharic { font-size: 15px; font-weight: bold; }
        .company-english { font-size: 13px; font-style: italic; }
        .doc-info { display: flex; justify-content: space-between; margin: 8px 0; font-size: 10px; }
        .title { text-align: center; font-size: 13px; font-weight: bold; margin: 10px 0; }
        .subtitle { text-align: center; font-size: 11px; font-weight: bold; margin: 5px 0; text-decoration: underline; }
        table { width: 100%; border-collapse: collapse; }
        th { border: 1px solid #000; padding: 5px 3px; background: #f0f0f0; font-size: 8px; text-align: center; }
        td { border: 1px solid #000; padding: 4px 3px; font-size: 9px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        @media print { body { padding: 10px; } }
    </style>
</head>
<body>
    <div class="header">
        <div style="display:flex; justify-content:space-between;">
            <div style="text-align:left;"><strong>Company Name:</strong></div>
            <div style="text-align:right;"><strong>Document No:</strong></div>
        </div>
        <div class="company-amharic">{{ $companyName }}</div>
        <div class="company-english">{{ $companyNameEn }}</div>
    </div>
    
    <div class="doc-info">
        <span><strong>Title:</strong> Weekly Report {{ date('d/m/Y', strtotime($dateFrom)) }} - {{ date('d/m/Y', strtotime($dateTo)) }}</span>
        <span><strong>Document No:</strong> {{ $documentNo }}</span>
        <span><strong>Issue No:</strong> 1</span>
        <span><strong>Page No:</strong> Page 1 of 1</span>
    </div>
    
    <div class="subtitle">Material Transfer From Project To Project</div>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Item Description</th>
                <th>Unit</th>
                <th>Requested Qty</th>
                <th>SR.No</th>
                <th>Date</th>
                <th>From Project</th>
                <th>Out/SIV NO</th>
                <th>To Project</th>
                <th>In NO</th>
                <th>Received QTY</th>
                <th>Delivered Date</th>
                <th>Remaining QTY</th>
                <th>Remark</th>
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
