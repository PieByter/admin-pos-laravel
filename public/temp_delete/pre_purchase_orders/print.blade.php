{{-- filepath: c:\laragon\www\admin-pos\resources\views\pre_purchase_orders\print.blade.php --}}
<!DOCTYPE html>
<html>

<head>
    <title>Print PO</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        .info-row {
            margin-bottom: 4px;
            align-items: center;
        }

        .info-label {
            display: inline-block;
            width: 140px;
            font-weight: bold;
        }

        .detail-section {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .detail-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            text-decoration: underline;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px;
            text-align: center;
            vertical-align: middle;
        }

        th {
            background: #eee;
        }

        #ttd-table,
        #ttd-table td {
            border: none !important;
        }
    </style>
</head>

<body>
    <!-- HEADER PO dengan tabel tanpa border -->
    <table style="width:100%; border:none; margin-bottom:30px;">
        <tr>
            <td style="width:55%; vertical-align:top; border:none; text-align:left;">
                @if (!empty($logoBase64))
                    <img src="{{ $logoBase64 }}" alt="Logo Perusahaan" style="height:100px; margin-bottom:10px;">
                @endif
                <br><br>
                <b>PT. Sumatra Tobacco Trading Company (Depot Medan)</b><br>
                Jalan HOS Cokroaminoto No. 11<br>
                Medan 20211<br>
                Sumatera Utara, Indonesia<br>
                Telp: (061) 4567890<br>
                Email: info@sttc.co.id<br>
                @if (!empty($prePurchaseOrder->shipping_address))
                    <div style="margin-top: 16px;">
                        <div style="font-weight: bold;">ALAMAT PENGIRIMAN:</div>
                        <div>{{ $prePurchaseOrder->shipping_address }}</div>
                    </div>
                @endif
            </td>
            <td style="width:45%; vertical-align:top; text-align:right; border:none;">
                <div class="po-title" style="font-size:24px; font-weight:bold;">PURCHASE ORDER</div>
                <div style="font-weight: bold;">
                    Tanggal Pemesanan: {{ \Carbon\Carbon::parse($prePurchaseOrder->issue_date)->format('d/m/Y') }}
                </div>
                <div style="font-weight: bold;">No. PO: {{ $prePurchaseOrder->po_number }}</div>
                @if (!empty($prePurchaseOrder->shipping_date))
                    <div style="font-weight: bold;">
                        Tanggal Pengiriman:
                        @php
                            \Carbon\Carbon::setLocale('id');
                            $shippingDate = \Carbon\Carbon::parse($prePurchaseOrder->shipping_date);
                        @endphp
                        {{ $shippingDate->translatedFormat('l, d F Y') }}
                    </div>
                @endif
            </td>
        </tr>
    </table>

    <!-- ALAMAT PENGIRIMAN -->
    @if (!empty($prePurchaseOrder->shipping_address))
        <div style="margin-bottom: 20px;">
            <div style="font-weight: bold;">ALAMAT PENGIRIMAN:</div>
            <div>{{ $prePurchaseOrder->shipping_address }}</div>
        </div>
    @endif

    <!-- DETAIL PO -->
    <div class="detail-section">
        <div class="detail-title">DETAIL Purchase Order</div>
        <div class="info-row">
            <span class="info-label">Jatuh Tempo</span>:
            @php
                \Carbon\Carbon::setLocale('id');
                $dueDate = \Carbon\Carbon::parse($prePurchaseOrder->due_date);
            @endphp
            {{ $dueDate->translatedFormat('l, d F Y') }}
        </div>
        <div class="info-row">
            <span class="info-label">Supplier</span>: {{ $prePurchaseOrder->supplier->name ?? '-' }}
        </div>
        <div class="info-row">
            <span class="info-label">PPN (12%)</span>: Rp.
            {{ number_format($prePurchaseOrder->tax_amount, 0, ',', '.') }},-
        </div>
        <div class="info-row">
            <span class="info-label">Otorisasi</span>: {{ $authorizedUsernames ?? '-' }}
        </div>
        <div class="info-row">
            <span class="info-label">Status</span>:
            @php
                $statusLabels = [
                    'draft' => 'Draft',
                    'process' => 'Proses',
                    'completed' => 'Selesai (Lunas)',
                    'debt' => 'Utang',
                    'return' => 'Retur',
                    'cancelled' => 'Batal',
                ];
            @endphp
            {{ $statusLabels[$prePurchaseOrder->status] ?? ucfirst($prePurchaseOrder->status) }}
        </div>
        <div class="info-row">
            <span class="info-label">Metode Pembayaran</span>:
            @php
                $paymentLabels = [
                    'cash' => 'Cash',
                    'credit' => 'Kredit',
                    'transfer' => 'Transfer',
                    'debit' => 'Debit',
                    'e-wallet' => 'E-Wallet',
                ];
            @endphp
            {{ $paymentLabels[$prePurchaseOrder->payment_method] ?? ucfirst($prePurchaseOrder->payment_method ?? '-') }}
        </div>
        <div class="info-row">
            <span class="info-label">Keterangan</span>: {{ $prePurchaseOrder->description ?? '-' }}
        </div>
    </div>

    <!-- DETAIL BARANG -->
    <div class="detail-section">
        <div class="detail-title">DETAIL BARANG</div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Barang</th>
                    <th>Satuan</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($prePurchaseOrder->details as $index => $detail)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $detail->item->name ?? '-' }}</td>
                        <td>{{ $detail->unit->name ?? '-' }}</td>
                        <td>{{ number_format($detail->quantity, 0, ',', '.') }}</td>
                        <td>Rp. {{ number_format($detail->unit_price, 0, ',', '.') }},-</td>
                        <td>Rp. {{ number_format($detail->subtotal, 0, ',', '.') }},-</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="font-size: 16px; font-weight: bold; margin: 20px 0; text-align: right;">
        Total Harga: Rp. {{ number_format($prePurchaseOrder->total_amount, 0, ',', '.') }},-
    </div>

    <!-- TANDA TANGAN -->
    <table id="ttd-table" style="width:100%; margin-top:40px;">
        <tr>
            <td style="width:40%; text-align:center;">
                Penerima,<br><br><br><br><br>
                (<span>{{ $prePurchaseOrder->supplier->name ?? '-' }}</span>)<br>
                ___________<br>
            </td>
            <td style="width:20%;"></td>
            <td style="width:40%; text-align:center;">
                Disetujui oleh,<br><br><br><br><br>
                @if (!empty($authorizedUsernames))
                    (<span>{{ $authorizedUsernames }}</span>)<br>
                @else
                    <br>
                @endif
                ___________<br>
            </td>
        </tr>
    </table>
</body>

</html>
