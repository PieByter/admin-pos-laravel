<x-app-layout>
    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-person"></i> Detail Supplier</h5>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-4 text-justify">
                            <dt class="col-sm-4">Nama</dt>
                            <dd class="col-sm-8">{{ $supplier->name }}</dd>

                            <dt class="col-sm-4">Perusahaan</dt>
                            <dd class="col-sm-8">{{ $supplier->company_name ?? '-' }}</dd>

                            <dt class="col-sm-4">Contact Person</dt>
                            <dd class="col-sm-8">{{ $supplier->contact_person ?? '-' }}</dd>

                            <dt class="col-sm-4">Alamat</dt>
                            <dd class="col-sm-8">{{ $supplier->address ?? '-' }}</dd>

                            <dt class="col-sm-4">No. Telp</dt>
                            <dd class="col-sm-8">{{ $supplier->phone_number ?? '-' }}</dd>

                            <dt class="col-sm-4">Email</dt>
                            <dd class="col-sm-8">{{ $supplier->contact_email ?? '-' }}</dd>

                            <dt class="col-sm-4">Status</dt>
                            <dd class="col-sm-8">
                                @if ($supplier->status === 'active')
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Tidak Aktif</span>
                                @endif
                            </dd>

                            <dt class="col-sm-4">Keterangan</dt>
                            <dd class="col-sm-8">{{ $supplier->description ?? '-' }}</dd>
                        </dl>

                        <div class="mt-4 d-flex justify-content-end gap-2">

                            <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Edit
                            </a>

                            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary ms-2"
                                id="btn-back-supplier">
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
                timer: 3000,
                timerProgressBar: true
            });

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
        });
    </script>
</x-app-layout>
