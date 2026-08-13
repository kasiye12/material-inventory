<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Material Delivery Report</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', Arial, sans-serif; font-size: 12px; padding: 20px; background: #fff; }
        .report-container { max-width: 1000px; margin: 0 auto; border: 1px solid #000; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #000; padding-bottom: 15px; margin-bottom: 20px; }
        .company-info { flex: 1; }
        .company-name { font-weight: bold; font-size: 16px; margin-bottom: 5px; }
        .company-name-en { font-style: italic; color: #333; margin-bottom: 5px; }
        .document-info { text-align: right; min-width: 200px; }
        .title-section { display: flex; justify-content: space-between; margin-bottom: 20px; padding: 10px; background: #f9f9f9; }
        .report-subtitle { text-align: center; font-size: 12px; margin-bottom: 15px; text-decoration: underline; font-weight: bold; }
        table.data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data-table th { border: 1px solid #000; padding: 8px 5px; background: #f0f0f0; font-weight: bold; text-align: center; font-size: 11px; text-transform: uppercase; }
        table.data-table td { border: 1px solid #000; padding: 6px 5px; font-size: 11px; vertical-align: middle; }
        .category-header td { background: #e8e8e8; font-weight: bold; text-align: center; font-size: 12px; text-transform: uppercase; }
        .footer { margin-top: 30px; display: flex; justify-content: space-between; padding-top: 20px; border-top: 1px solid #ccc; }
        .signature { text-align: center; }
        .signature-line { margin-top: 40px; border-top: 1px solid #000; padding-top: 5px; font-size: 11px; }
        .export-buttons { position: fixed; top: 10px; right: 10px; display: flex; gap: 10px; z-index: 1000; }
        .export-buttons button { padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; color: white; }
        .btn-print { background: #007bff; }
        .btn-pdf { background: #dc3545; }
        .btn-excel { background: #28a745; }
        @media print {
            body { padding: 0; }
            .report-container { border: none; padding: 0; }
            .export-buttons { display: none; }
        }
    </style>
</head>
<body>
    <div class="export-buttons">
        <button class="btn-print" onclick="window.print()">🖨️ Print</button>
        <button class="btn-pdf" onclick="exportPDF()">📄 Download PDF</button>
        <button class="btn-excel" onclick="exportExcel()">📊 Download Excel</button>
        <button class="btn-print" onclick="goBack()" style="background:#6c757d;">⬅️ Back</button>
    </div>

    <div class="report-container">
        <div class="header">
            <div class="company-info">
                <div class="company-name">{{ $companyName }}</div>
                <div class="company-name-en">{{ $companyNameEn }}</div>
            </div>
            <div class="document-info">
                <strong>Document No:</strong> {{ $documentNo }}
            </div>
        </div>
        
        <div class="title-section">
            <div><strong>Title:</strong></div>
            <div style="text-align: center;"><strong>Daily Material Delivery Report Format</strong></div>
            <div style="text-align: right;">
                <div><strong>Issue No:</strong> 1</div>
                <div><strong>Page No:</strong> Page 1 of 1</div>
            </div>
        </div>
        
        <div class="report-subtitle">{{ $reportTitle }}</div>
        
        @if($groupedDeliveries && $groupedDeliveries->count() > 0)
            @foreach($groupedDeliveries as $category => $items)
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:40px;">NO</th>
                        <th>Item Description</th>
                        <th style="width:60px;">Unit</th>
                        <th style="width:60px;">Qty</th>
                        <th style="width:80px;">ISTV NO</th>
                        <th style="width:80px;">ISTRV NO</th>
                        <th style="width:80px;">Delivery Date</th>
                        <th style="width:80px;">FROM</th>
                        <th style="width:70px;">Remark</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="category-header">
                        <td colspan="9">{{ $category }}</td>
                    </tr>
                    @php $counter = 1; @endphp
                    @foreach($items as $delivery)
                    <tr>
                        <td style="text-align:center;">{{ $counter++ }}</td>
                        <td>{{ $delivery->item->name ?? 'N/A' }}</td>
                        <td style="text-align:center;">{{ $delivery->item->unit ?? '' }}</td>
                        <td style="text-align:center;">{{ $delivery->quantity }}</td>
                        <td style="text-align:center;">{{ $delivery->transaction_type === 'GRV' ? ($delivery->reference_number ?? '') : '' }}</td>
                        <td style="text-align:center;">{{ $delivery->transaction_type === 'ISTRV' ? ($delivery->reference_number ?? '') : ($delivery->document_number ?? '') }}</td>
                        <td style="text-align:center;">{{ $delivery->transaction_date->format('d/m/Y') }}</td>
                        <td style="text-align:center;">{{ $delivery->fromLocation->name ?? 'Head Office' }}</td>
                        <td style="text-align:center;">{{ $delivery->remarks ?? '' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <br>
            @endforeach
        @else
            <div style="text-align:center; padding:50px; border:1px solid #ccc; margin:20px 0;">
                <h3>No delivery records found</h3>
                <p>No deliveries found for the selected period and filters.</p>
            </div>
        @endif
        
        <div class="footer">
            <div class="signature">
                <div>Prepared By</div>
                <div class="signature-line">Store Keeper</div>
            </div>
            <div class="signature">
                <div>Checked By</div>
                <div class="signature-line">Project Manager</div>
            </div>
            <div class="signature">
                <div>Approved By</div>
                <div class="signature-line">General Manager</div>
            </div>
        </div>
    </div>
    
    <script>
        function exportPDF() {
            var currentUrl = window.location.href;
            var separator = currentUrl.includes('?') ? '&' : '?';
            window.location.href = currentUrl + separator + 'format=pdf';
        }
        
        function exportExcel() {
            var currentUrl = window.location.href;
            var separator = currentUrl.includes('?') ? '&' : '?';
            window.location.href = currentUrl + separator + 'format=excel';
        }
        
        function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>
