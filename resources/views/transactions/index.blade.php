<x-app-layout>
    <div class="container-fluid pt-4">
        <h4 class="mb-4">Laporan Pendapatan & Pengeluaran</h4>

        <div class="row mb-2">
            <div class="col-md-4">
                <div class="card text-center border-success">
                    <div class="card-body">
                        <h6 class="text-success">Total Pendapatan</h6>
                        <h3 class="text-success">Rp. {{ number_format($total_pendapatan, 0, ',', '.') }}</h3>
                        <small class="text-muted">Total Penjualan Selesai</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center border-danger">
                    <div class="card-body">
                        <h6 class="text-danger">Total Pengeluaran</h6>
                        <h3 class="text-danger">Rp. {{ number_format($total_pengeluaran, 0, ',', '.') }}</h3>
                        <small class="text-muted">Total Pembelian Selesai</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center border-{{ $pendapatan_bersih >= 0 ? 'success' : 'danger' }}">
                    <div class="card-body">
                        <h6 class="text-{{ $pendapatan_bersih >= 0 ? 'success' : 'danger' }}">Pendapatan Bersih</h6>
                        <h3 class="text-{{ $pendapatan_bersih >= 0 ? 'success' : 'danger' }}">
                            Rp. {{ number_format($pendapatan_bersih, 0, ',', '.') }}
                        </h3>
                        <small class="text-muted">Total Pendapatan - Total Pengeluaran</small>
                    </div>
                </div>
            </div>
        </div>

        <h4 class="mb-4">Daftar Hutang & Piutang</h4>
        <!-- Summary Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="card text-center border-danger">
                    <div class="card-body">
                        <h6 class="text-danger">Total Hutang Belum Dibayar</h6>
                        <h3 class="text-danger">Rp. {{ number_format($total_hutang_belum_lunas, 0, ',', '.') }}</h3>
                        <small class="text-muted">Total Hutang Purchase Order</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center border-success">
                    <div class="card-body">
                        <h6 class="text-success">Total Hutang Lunas</h6>
                        <h3 class="text-success">Rp. {{ number_format($total_hutang_lunas, 0, ',', '.') }}</h3>
                        <small class="text-muted">Total Hutang Purchase Order Lunas</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center border-warning">
                    <div class="card-body">
                        <h6 class="text-warning">Total Piutang Belum Dibayar</h6>
                        <h3 class="text-warning">Rp. {{ number_format($total_piutang_belum_lunas, 0, ',', '.') }}</h3>
                        <small class="text-muted">Total Piutang Penjualan</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center border-info">
                    <div class="card-body">
                        <h6 class="text-info">Total Piutang Lunas</h6>
                        <h3 class="text-info">Rp. {{ number_format($total_piutang_lunas, 0, ',', '.') }}</h3>
                        <small class="text-muted">Total Piutang Penjualan Selesai</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Detail Hutang -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-danger pb-0">
                        <h5><i class="bi bi-cash-stack"></i> Detail Hutang Belum Lunas</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm text-center mb-0">
                                <thead>
                                    <tr>
                                        <th>No. PO</th>
                                        <th>Supplier</th>
                                        <th>Jatuh Tempo</th>
                                        <th>Jumlah</th>
                                        <!-- <th>Status</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($list_hutang as $h)
                                        <tr>
                                            <td>{{ $h['no_po'] }}</td>
                                            <td>{{ $h['supplier_nama'] }}</td>
                                            <td>{{ date('d/m/Y', strtotime($h['jatuh_tempo'])) }}</td>
                                            <td>Rp. {{ number_format($h['total_harga'], 0, ',', '.') }}</td>
                                            <!-- <td>
                                        <span class="badge bg-{{ $h['status'] == 'utang' ? 'danger' : 'warning' }}">
                                            {{ ucfirst($h['status']) }}
                                        </span>
                                    </td> -->
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Piutang -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-warning pb-0">
                        <h5><i class="bi bi-receipt"></i> Detail Piutang Belum Lunas</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm text-center mb-0">
                                <thead>
                                    <tr>
                                        <th>No. Nota</th>
                                        <th>Customer</th>
                                        <th>Tanggal Terbit</th>
                                        <th>Jumlah</th>
                                        <!-- <th>Status</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($list_piutang as $p)
                                        <tr class="text-center">
                                            <td>{{ $p['no_nota'] }}</td>
                                            <td>{{ $p['customer_nama'] }}</td>
                                            <td>{{ date('d/m/Y', strtotime($p['tanggal_terbit'])) }}</td>
                                            <td>Rp. {{ number_format($p['total_harga'], 0, ',', '.') }}</td>
                                            <!-- <td>
                                        <span class="badge bg-{{ $h['status'] == 'utang' ? 'danger' : 'warning' }}">
                                            {{ ucfirst($h['status']) }}
                                        </span>
                                    </td> -->
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
