<x-app-layout>
    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-currency-exchange"></i> Detail Satuan Konversi</h5>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-4 text-justify">
                            <dt class="col-sm-4">Barang</dt>
                            <dd class="col-sm-8">{{ $conversion->item_name ?? '-' }}</dd>

                            <dt class="col-sm-4">Satuan</dt>
                            <dd class="col-sm-8">{{ $conversion->unit_name ?? '-' }}</dd>

                            <dt class="col-sm-4">Nilai Konversi</dt>
                            <dd class="col-sm-8">{{ $conversion->conversion_value }}</dd>

                            <dt class="col-sm-4">Keterangan</dt>
                            <dd class="col-sm-8">{{ $conversion->description ?? '-' }}</dd>

                        </dl>

                        <div class="mt-4 d-flex justify-content-end gap-2">
                            <a href="{{ route('unit-conversions.edit', $conversion->id) }}" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="{{ route('unit-conversions.index') }}" class="btn btn-secondary ms-2"
                                id="btn-back-konversi">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        </div>
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
                timerProgressBar: true
            });

            @if (session('error'))
                Toast.fire({
                    icon: 'error',
                    title: '{{ session('error') }}'
                });
            @endif
        });
    </script>
</x-app-layout>
