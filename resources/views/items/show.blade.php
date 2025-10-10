<x-app-layout>
    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-eye"></i> Detail Barang</h5>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-4">
                            <dt class="col-sm-5">Kode Barang</dt>
                            <dd class="col-sm-7">{{ $item->item_code }}</dd>

                            <dt class="col-sm-5">Nama Barang</dt>
                            <dd class="col-sm-7">{{ $item->item_name }}</dd>

                            <dt class="col-sm-5">Jenis Barang</dt>
                            <dd class="col-sm-7">{{ $item->itemCategory->category_name ?? '-' }}</dd>

                            <dt class="col-sm-5">Group Barang</dt>
                            <dd class="col-sm-7">{{ $item->itemGroup->group_name ?? '-' }}</dd>

                            <dt class="col-sm-5">Satuan Utama</dt>
                            <dd class="col-sm-7">{{ $item->unit->unit_name ?? '-' }}</dd>
                            <dt class="col-sm-5">Harga Beli</dt>
                            <dd class="col-sm-7">
                                Rp.
                                {{ $item->buy_price == intval($item->buy_price)
                                    ? number_format($item->buy_price, 0, ',', '.')
                                    : number_format($item->buy_price, 2, ',', '.') }}
                            </dd>

                            <dt class="col-sm-5">Harga Jual</dt>
                            <dd class="col-sm-7">
                                Rp.
                                {{ $item->sell_price == intval($item->sell_price)
                                    ? number_format($item->sell_price, 0, ',', '.')
                                    : number_format($item->sell_price, 2, ',', '.') }}
                            </dd>

                            <dt class="col-sm-5">Stok</dt>
                            <dd class="col-sm-7">{{ number_format($item->stock, 0, ',', '.') }}</dd>

                            <dt class="col-sm-5">Keterangan</dt>
                            <dd class="col-sm-7">{{ $item->item_description ?? '-' }}</dd>
                        </dl>

                        <div class="mt-4 d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-info text-white" data-bs-toggle="modal"
                                data-bs-target="#modalKonversi">
                                <i class="fas fa-expand-alt"></i> Konversi Satuan
                            </button>

                            <a href="{{ route('items.edit', $item->id) }}" class="btn btn-warning">
                                <i class="fas fa-pen"></i> Edit
                            </a>

                            <a href="{{ route('items.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Konversi Satuan -->
        <div class="modal fade" id="modalKonversi" tabindex="-1" aria-labelledby="modalKonversiLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalKonversiLabel">Konversi Satuan Barang</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Satuan</th>
                                    <th>Konversi</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($item->unitConversions as $conversion)
                                    <tr>
                                        <td>{{ $conversion->unit->unit_name ?? '-' }} → pcs</td>
                                        <td>{{ number_format($conversion->conversion_value, 0, ',', '.') }}</td>
                                        <td>{{ $conversion->description ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-3">
                                            <div class="text-muted">
                                                <i class="fas fa-inbox"></i>
                                                <p class="mb-0 mt-2">Tidak ada konversi satuan</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            @if (session('error'))
                Toast.fire({
                    icon: 'error',
                    title: '{{ session('error') }}'
                });
            @endif

            @if (session('success'))
                Toast.fire({
                    icon: 'success',
                    title: '{{ session('success') }}'
                });
            @endif
        });
    </script>
</x-app-layout>
