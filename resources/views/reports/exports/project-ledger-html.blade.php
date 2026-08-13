<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Material Ledger</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', Arial, sans-serif; font-size: 11px; padding: 20px; background: #fff; }
        
        .report-container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px; }
        .company-info { flex: 1; }
        .company-name { font-weight: bold; font-size: 14px; }
        .company-name-en { font-size: 12px; }
        .project-name { font-weight: bold; font-size: 13px; margin-top: 5px; text-decoration: underline; }
        .document-info { text-align: right; }
        .document-info div { margin-bottom: 3px; }
        
        table.ledger-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table.ledger-table th { border: 1px solid #000; padding: 6px 4px; font-size: 9px; text-align: center; background: #f0f0f0; font-weight: bold; }
        table.ledger-table td { border: 1px solid #000; padding: 5px 4px; font-size: 10px; }
        
        .category-row td { background: #e0e0e0; font-weight: bold; text-align: left; font-size: 11px; text-transform: uppercase; }
        
        .no-col { width: 35px; text-align: center; }
        .desc-col { width: 200px; text-align: left; }
        .unit-col { width: 40px; text-align: center; }
        .date-col { width: 70px; text-align: center; }
        .balance-col { width: 60px; text-align: right; }
        .ref-col { width: 55px; text-align: center; }
        .qty-col { width: 50px; text-align: right; }
        .remark-col { width: 60px; text-align: center; }
        
        .export-buttons { position: fixed; top: 10px; right: 10px; display: flex; gap: 10px; z-index: 1000; }
        .export-buttons button { padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; color: white; }
        .btn-print { background: #007bff; }
        .btn-pdf { background: #dc3545; }
        .btn-excel { background: #28a745; }
        .btn-back { background: #6c757d; }
        
        .footer { margin-top: 30px; display: flex; justify-content: space-between; }
        .signature { text-align: center; }
        .signature-line { margin-top: 40px; border-top: 1px solid #000; padding-top: 5px; font-size: 10px; }
        
        @media print {
            body { padding: 0; }
            .export-buttons { display: none; }
            .report-container { padding: 0; }
        }
    </style>
</head>
<body>
    <div class="export-buttons">
        <button class="btn-print" onclick="window.print()">🖨️ Print</button>
        <button class="btn-pdf" onclick="exportPDF()">📄 PDF</button>
        <button class="btn-back" onclick="goBack()">⬅️ Back</button>
    </div>

    <div class="report-container">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <div>
                    <strong>Company Name:</strong>
                    <span style="margin-left: 20px;"><strong>Document No:</strong></span>
                </div>
                <div class="company-name">{{ $companyName }}</div>
                <div class="company-name-en">{{ $companyNameEn }}</div>
                <div class="project-name">{{ $locationName }}</div>
            </div>
            <div class="document-info">
                <div><strong>Document No:</strong> OF/TNT/SUP/033</div>
                <div><strong>Issue No:</strong> 1</div>
                <div><strong>Page No:</strong> Page 1 of 1</div>
            </div>
        </div>
        
        <!-- Main Table -->
        <table class="ledger-table">
            <thead>
                <tr>
                    <th rowspan="2" class="no-col">Item No.</th>
                    <th rowspan="2" class="desc-col">Description</th>
                    <th rowspan="2" class="unit-col">Unit</th>
                    <th rowspan="2" class="date-col">Delivery Issued Date</th>
                    <th rowspan="2" class="balance-col">Beg. Balance</th>
                    <th colspan="2">GRV</th>
                    <th colspan="2">ISTRV</th>
                    <th colspan="2">SIV</th>
                    <th colspan="2">Transferred Out</th>
                    <th colspan="2">Store return</th>
                    <th rowspan="2" class="balance-col">Ending Balance</th>
                    <th rowspan="2" class="remark-col">Remark</th>
                </tr>
                <tr>
                    <th class="ref-col">Pad Ref.No.</th>
                    <th class="qty-col">Qty</th>
                    <th class="ref-col">Pad Ref.No.</th>
                    <th class="qty-col">Qty</th>
                    <th class="ref-col">Pad Ref.No.</th>
                    <th class="qty-col">Qty</th>
                    <th class="ref-col">Pad Ref.No.</th>
                    <th class="qty-col">Qty</th>
                    <th class="ref-col">Pad Ref.No.</th>
                    <th class="qty-col">Qty</th>
                </tr>
            </thead>
            <tbody>
                @if(count($reportData) > 0)
                    @foreach($reportData as $category => $items)
                    <tr class="category-row">
                        <td colspan="16">{{ $category }}</td>
                    </tr>
                    @php $itemCounter = 1; @endphp
                    @foreach($items as $data)
                    <tr>
                        <td class="no-col">{{ $itemCounter++ }}</td>
                        <td class="desc-col">{{ $data['item']->name }}</td>
                        <td class="unit-col">{{ $data['item']->unit }}</td>
                        <td class="date-col"></td>
                        <td class="balance-col">{{ $data['opening_balance'] != 0 ? number_format($data['opening_balance'], 2) : '' }}</td>
                        <td class="ref-col"></td>
                        <td class="qty-col">{{ $data['grv_qty'] != 0 ? number_format($data['grv_qty'], 2) : '' }}</td>
                        <td class="ref-col"></td>
                        <td class="qty-col">{{ $data['istrv_qty'] != 0 ? number_format($data['istrv_qty'], 2) : '' }}</td>
                        <td class="ref-col"></td>
                        <td class="qty-col">{{ $data['siv_qty'] != 0 ? number_format($data['siv_qty'], 2) : '' }}</td>
                        <td class="ref-col"></td>
                        <td class="qty-col">{{ $data['transfer_out_qty'] != 0 ? number_format($data['transfer_out_qty'], 2) : '' }}</td>
                        <td class="ref-col"></td>
                        <td class="qty-col">{{ $data['store_return_qty'] != 0 ? number_format($data['store_return_qty'], 2) : '' }}</td>
                        <td class="balance-col"><strong>{{ $data['ending_balance'] != 0 ? number_format($data['ending_balance'], 2) : '' }}</strong></td>
                        <td class="remark-col"></td>
                    </tr>
                    @endforeach
                    @endforeach
                @else
                    <tr>
                        <td colspan="16" style="text-align:center; padding:30px;">
                            <strong>No records found for the selected period</strong>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
        
        <!-- Footer -->
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
        
        function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>
