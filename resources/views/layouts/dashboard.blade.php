<x-app-layout title="Dashboard">
    {{-- <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div> --}}

    <x-content-header title="Dashboard" breadcrumb-parent="Home" breadcrumb-url="{{ route('dashboard') }}" />

    <div class="content mb-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $purchaseOrdersCount ?? 0 }}</h3>
                            <p>Purchase Orders</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="{{ route('purchase-orders.index') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $purchasesCount ?? 0 }}</h3>
                            <p>Daftar Pembelian</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pricetags"></i>
                        </div>
                        <a href="{{ route('purchases.index') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $salesCount ?? 0 }}</h3>
                            <p>Daftar Penjualan</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-cash"></i>
                        </div>
                        <a href="{{ route('sales.index') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $itemsCount ?? 0 }}</h3>
                            <p>Daftar Barang</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('items.index') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Informasi Tambahan -->
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card h-100">
                        <div class="card-header">
                            <i class="bi bi-info-circle me-1"></i>
                            Informasi Sistem
                        </div>
                        <div class="card-body">
                            <p>Selamat datang di dashboard administrator. Anda dapat mengelola berbagai fitur sistem
                                melalui
                                menu yang tersedia.</p>
                            <p>
                                Role:
                                <span
                                    class="badge
                                @switch($user->role ?? 'user')
                                    @case('superadmin')
                                        bg-primary
                                        @break
                                    @case('useradmin')
                                        bg-info
                                        @break
                                    @case('kasir')
                                        bg-warning
                                        @break
                                    @case('gudang')
                                        bg-secondary
                                        @break
                                    @case('viewer')
                                        bg-success
                                        @break
                                    @default
                                        bg-secondary
                                @endswitch">
                                    {{ ucfirst($user->role ?? 'User') }}
                                </span>
                            </p>
                            <p>Login terakhir: <strong>{{ now()->format('d-m-Y H:i:s') }}</strong></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <!-- Chart 1: Volume Penjualan & Pembelian (Qty) per tanggal -->
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Bar Chart Volume Penjualan & Pembelian (Qty) per Tanggal</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="barChartVolume"
                                    style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <!-- Chart 2: Penjualan & Pembelian (Rupiah) per tanggal -->
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Bar Chart Total Penjualan & Pembelian (Rupiah) per Tanggal</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="barChartRupiah"
                                    style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Pastikan SweetAlert2 sudah tersedia
            if (typeof Swal !== 'undefined') {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });

                @if (session('login_success'))
                    Toast.fire({
                        icon: 'success',
                        title: 'Anda telah berhasil login ke dashboard.'
                    });
                @endif

                @if (session('success'))
                    Toast.fire({
                        icon: 'success',
                        title: '{{ session('success') }}'
                    });
                @endif

                @if (session('error'))
                    Toast.fire({
                        icon: 'error',
                        title: '{{ session('error') }}'
                    });
                @endif
            } else {
                console.error('SweetAlert2 tidak tersedia dari template!');
            }
        });

        const labelsTanggal = @json($chartData['labels'] ?? []);
        const qtyPenjualanHarian = @json($chartData['salesQty'] ?? []);
        const qtyPembelianHarian = @json($chartData['purchasesQty'] ?? []);
        const totalPenjualanHarian = @json($chartData['salesTotal'] ?? []);
        const totalPembelianHarian = @json($chartData['purchasesTotal'] ?? []);

        // Chart 1: Volume Penjualan & Pembelian (Qty) per tanggal
        new Chart(document.getElementById('barChartVolume'), {
            type: 'bar',
            data: {
                labels: labelsTanggal,
                datasets: [{
                        label: 'Penjualan (Qty)',
                        backgroundColor: '#17a2b8',
                        data: qtyPenjualanHarian
                    },
                    {
                        label: 'Pembelian (Qty)',
                        backgroundColor: '#28a745',
                        data: qtyPembelianHarian
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    }
                },
                scales: {
                    x: {
                        stacked: true
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Chart 2: Penjualan & Pembelian (Rupiah) per tanggal
        new Chart(document.getElementById('barChartRupiah'), {
            type: 'bar',
            data: {
                labels: labelsTanggal,
                datasets: [{
                        label: 'Penjualan (Rp)',
                        backgroundColor: '#ffc107',
                        data: totalPenjualanHarian
                    },
                    {
                        label: 'Pembelian (Rp)',
                        backgroundColor: '#007bff',
                        data: totalPembelianHarian
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    }
                },
                scales: {
                    x: {
                        stacked: true
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</x-app-layout>
