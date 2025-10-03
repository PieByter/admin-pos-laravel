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
                            <dd class="col-sm-8">{{ url($supplier['nama']) }}</dd>
                            <dt class="col-sm-4">Alamat</dt>
                            <dd class="col-sm-8">{{ url($supplier['alamat']) }}</dd>
                            <dt class="col-sm-4">No. Telp</dt>
                            <dd class="col-sm-8">{{ url($supplier['no_telp']) }}</dd>
                            <dt class="col-sm-4">Email</dt>
                            <dd class="col-sm-8">{{ url($supplier['email']) }}</dd>
                            <dt class="col-sm-4">Status</dt>
                            <dd class="col-sm-8">
                                @if ($supplier['status'] == 'aktif')
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Tidak Aktif</span>
                                @endif
                            <dt class="col-sm-4">Keterangan</dt>
                            <dd class="col-sm-8">{{ url($supplier['keterangan']) }}</dd>
                        </dl>
                        <div class="mt-4 d-flex justify-content-end gap-2">
                            @if ($can_write ?? false)
                                <a href="{{ route('supplier.edit', $supplier['id']) }}" class="btn btn-warning">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                            @endif
                            <a href="{{ route('supplier.index') }}" class="btn btn-secondary ms-2"
                                id="btn-back-supplier">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
