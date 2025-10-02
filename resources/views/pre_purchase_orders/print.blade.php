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

        /* .po-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 30px;
    }

    .company-info {
        text-align: left;
        width: 50%;
    }

    .po-info {
        text-align: right;
        width: 40%;
    }

    .po-title {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 10px;
    }
    */
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
                <!-- <img src="<?= FCPATH . 'img/image.png' ?>" alt="Logo Perusahaan"
                    style="height:40px; margin-bottom:10px;"> -->
                <?php if (!empty($logoBase64)): ?>
                <img src="<?= $logoBase64 ?>" alt="Logo Perusahaan" style="height:100px; margin-bottom:10px;">
                <?php endif; ?>
                <br><br>
                <b>PT. Sumatra Tobacco Trading Company (Depot Medan)</b><br>
                Jalan HOS Cokroaminoto No. 11<br>
                Medan 20211<br>
                Sumatera Utara, Indonesia<br>
                Telp: (061) 4567890<br>
                Email: info@sttc.co.id<br>
                <?php if (!empty($po['alamat_pengiriman'])): ?>
                <div style="margin-top: 16px;">
                    <div style="font-weight: bold;">ALAMAT PENGIRIMAN:</div>
                    <div><?= esc($po['alamat_pengiriman']) ?></div>
                </div>
                <?php endif; ?>
            </td>
            <td style="width:45%; vertical-align:top; text-align:right; border:none;">
                <div class="po-title" style="font-size:24px; font-weight:bold;">PURCHASE ORDER</div>
                <div style="font-weight: bold;">
                    Tanggal Pemesanan: <?= esc(date('d/m/Y', strtotime($po['tanggal_terbit']))) ?>
                </div>
                <div style="font-weight: bold;">No. PO: <?= esc($po['no_po']) ?></div>
                <?php if (!empty($po['tanggal_pengiriman'])): ?>
                <div style="font-weight: bold;">Tanggal Pengiriman:
                    <?= esc(formatTanggalIndo($po['tanggal_pengiriman'])) ?></div>
                <?php endif; ?>
            </td>
        </tr>
    </table>

    <!-- ALAMAT PENGIRIMAN -->
    <?php if (!empty($po['alamat_pengiriman'])): ?>
    <div style="margin-bottom: 20px;">
        <div style="font-weight: bold;">ALAMAT PENGIRIMAN:</div>
        <div><?= esc($po['alamat_pengiriman']) ?></div>
    </div>
    <?php endif; ?>

    <!-- DETAIL PO -->
    <div class="detail-section">
        <div class="detail-title">DETAIL Purchase Order</div>
        <div class="info-row"><span class="info-label">Jatuh Tempo</span>:
            <?= esc(formatTanggalIndo($po['jatuh_tempo'])) ?>
        </div>
        <div class="info-row"><span class="info-label">Supplier</span>: <?= esc($supplier['nama']) ?></div>
        <div class="info-row"><span class="info-label">PPN (12%)</span>: Rp.
            <?= number_format($po['ppn'], 0, ',', '.') ?>,-
        </div>
        <div class="info-row"><span class="info-label">Otorisasi</span>: <?= esc($pembuatNama) ?></div>
        <div class="info-row"><span class="info-label">Status</span>: <?= ucfirst($po['status']) ?></div>
        <div class="info-row"><span class="info-label">Metode Pembayaran</span>:
            <?= ucfirst($po['metode_pembayaran'] ?? '-') ?></div>
        <div class="info-row"><span class="info-label">Keterangan</span>: <?= esc($po['keterangan']) ?></div>
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
                <?php foreach ($details as $i => $d): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= esc($barangMap[$d['id_barang']] ?? '-') ?></td>
                    <td><?= esc($d['nama_satuan'] ?? ($d['satuan_nama'] ?? ($d['satuan'] ?? '-'))) ?></td>
                    <td><?= esc($d['qty']) ?></td>
                    <td>Rp. <?= number_format($d['harga'], 0, ',', '.') ?>,-</td>
                    <td>Rp. <?= number_format($d['subtotal'], 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div style="font-size: 16px; font-weight: bold; margin: 20px 0; text-align: right;">
        Total Harga: Rp. <?= number_format($po['total_harga'], 0, ',', '.') ?>,-
    </div>

    <!-- TANDA TANGAN -->
    <table id="ttd-table" style="width:100%; margin-top:40px;">
        <tr>
            <td style="width:40%; text-align:center;">
                Penerima,<br><br><br><br><br>
                (<span><?= esc($supplier['nama']) ?></span>)<br>
                ___________<br>
            </td>
            <td style="width:20%;"></td>
            <td style="width:40%; text-align:center;">
                Disetujui oleh,<br><br><br><br><br>
                <?php if ($pembuatNama !== ''): ?>
                (<span><?= esc($pembuatNama) ?></span>)<br>
                <?php else: ?>
                <br>
                <?php endif; ?>
                ___________<br>
            </td>
        </tr>
    </table>

    <?php
    function formatTanggalIndo($tanggal)
    {
        $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $bulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];
        $dateObj = date_create($tanggal);
        $namaHari = $hari[(int) date_format($dateObj, 'w')];
        $tgl = date_format($dateObj, 'd');
        $bln = $bulan[(int) date_format($dateObj, 'm')];
        $thn = date_format($dateObj, 'Y');
        return $namaHari . ', ' . $tgl . ' ' . $bln . ' ' . $thn;
    }
    ?>
</body>

</html>
