{{-- filepath: c:\laragon\www\admin-pos\resources\views\items\create.blade.php --}}
<x-app-layout>
    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white text-center">
                        <h5 class="card-title mb-0"><i class="bi bi-box-seam"></i> Form Tambah Barang</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('items.store') }}" method="post">
                            @csrf

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="item_name" class="form-label"><b>Nama Barang</b></label>
                                    <input type="text" class="form-control @error('item_name') is-invalid @enderror"
                                        id="item_name" name="item_name" value="{{ old('item_name') }}" required>
                                    @error('item_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="item_code" class="form-label"><b>Kode Barang</b></label>
                                    <input type="text" class="form-control @error('item_code') is-invalid @enderror"
                                        id="item_code" name="item_code" value="{{ old('item_code') }}" required>
                                    @error('item_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="item_category_id" class="form-label"><b>Jenis Barang</b></label>
                                    <select class="form-select @error('item_category_id') is-invalid @enderror"
                                        id="item_category_id" name="item_category_id" required>
                                        <option value="">- Pilih Jenis -</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('item_category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                        <option value="tambah-baru">+ Tambah Jenis Barang Baru</option>
                                    </select>
                                    @error('item_type_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="item_group_id" class="form-label"><b>Group Barang</b></label>
                                    <select class="form-select @error('item_group_id') is-invalid @enderror"
                                        id="item_group_id" name="item_group_id" required>
                                        <option value="">- Pilih Group -</option>
                                        @foreach ($groups as $group)
                                            <option value="{{ $group->id }}"
                                                {{ old('item_group_id') == $group->id ? 'selected' : '' }}>
                                                {{ $group->name }}
                                            </option>
                                        @endforeach
                                        <option value="tambah-baru">+ Tambah Group Barang Baru</option>
                                    </select>
                                    @error('item_group_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="unit_id" class="form-label"><b>Satuan Utama</b></label>
                                    <select class="form-select @error('unit_id') is-invalid @enderror" id="unit_id"
                                        name="unit_id" required>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}"
                                                {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                                {{ $unit->name }}
                                            </option>
                                        @endforeach
                                        <option value="tambah-baru">+ Tambah Satuan Baru</option>
                                    </select>
                                    @error('unit_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="buy_price" class="form-label"><b>Harga Beli</b></label>
                                    <input type="number" class="form-control @error('buy_price') is-invalid @enderror"
                                        id="buy_price" name="buy_price" value="{{ old('buy_price') }}" min="0"
                                        step="0.01">
                                    @error('buy_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="sell_price" class="form-label"><b>Harga Jual</b></label>
                                    <input type="number" class="form-control @error('sell_price') is-invalid @enderror"
                                        id="sell_price" name="sell_price" value="{{ old('sell_price') }}"
                                        min="0" step="0.01">
                                    @error('sell_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="stock" class="form-label"><b>Stok</b></label>
                                    <input type="number" class="form-control @error('stock') is-invalid @enderror"
                                        id="stock" name="stock" value="{{ old('stock', '') }}" min="0">
                                    @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="description" class="form-label"><b>Keterangan</b></label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                        rows="2">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-success me-2">
                                    <i class="bi bi-save"></i> Simpan
                                </button>
                                <a href="{{ route('items.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-lg"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Satuan Barang -->
        <div class="modal fade" id="modalTambahSatuan" tabindex="-1">
            <div class="modal-dialog">
                <form id="formTambahSatuan">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Satuan Baru</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="text" name="name" class="form-control mb-2" placeholder="Nama Satuan"
                                required>
                            <input type="text" name="description" class="form-control" placeholder="Keterangan">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Jenis Barang -->
        <div class="modal fade" id="modalTambahJenis" tabindex="-1">
            <div class="modal-dialog">
                <form id="formTambahJenis">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Jenis Barang Baru</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="text" name="name" class="form-control mb-2"
                                placeholder="Nama Jenis Barang" required>
                            <input type="text" name="description" class="form-control" placeholder="Keterangan">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Group Barang -->
        <div class="modal fade" id="modalTambahGroup" tabindex="-1">
            <div class="modal-dialog">
                <form id="formTambahGroup">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Group Barang Baru</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="text" name="name" class="form-control mb-2"
                                placeholder="Nama Group Barang" required>
                            <input type="text" name="description" class="form-control" placeholder="Keterangan">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>
                    </div>
                </form>
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

            @if ($errors->any())
                Toast.fire({
                    icon: 'error',
                    title: 'Terjadi kesalahan pada input',
                    html: '{!! implode('<br>', $errors->all()) !!}'
                });
            @endif

            // Handle dropdown selections
            document.getElementById('unit_id').addEventListener('change', function() {
                if (this.value === 'tambah-baru') {
                    showModalTambahSatuan();
                    this.value = '';
                }
            });

            document.getElementById('item_category_id').addEventListener('change', function() {
                if (this.value === 'tambah-baru') {
                    showModalTambahJenis();
                    this.value = '';
                }
            });

            document.getElementById('item_group_id').addEventListener('change', function() {
                if (this.value === 'tambah-baru') {
                    showModalTambahGroup();
                    this.value = '';
                }
            });

            // Handle form submissions
            document.getElementById('formTambahSatuan').addEventListener('submit', function(e) {
                e.preventDefault();
                const form = e.target;
                fetch('{{ route('units.ajax-save') }}', {
                        method: 'POST',
                        body: new FormData(form)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.id && data.name) {
                            const dropdown = document.getElementById('unit_id');
                            const option = document.createElement('option');
                            option.value = data.id;
                            option.textContent = data.name;
                            const tambahBaruOption = dropdown.querySelector(
                                'option[value="tambah-baru"]');
                            dropdown.insertBefore(option, tambahBaruOption);
                            dropdown.value = data.id;
                            var modal = bootstrap.Modal.getInstance(document.getElementById(
                                'modalTambahSatuan'));
                            modal.hide();
                            form.reset();
                        } else if (data.error) {
                            Toast.fire({
                                icon: 'error',
                                title: data.error
                            });
                        }
                    })
                    .catch(error => {
                        Toast.fire({
                            icon: 'error',
                            title: 'Terjadi kesalahan saat menyimpan satuan'
                        });
                    });
            });

            document.getElementById('formTambahJenis').addEventListener('submit', function(e) {
                e.preventDefault();
                const form = e.target;
                fetch('{{ route('item-categories.ajax-save') }}', {
                        method: 'POST',
                        body: new FormData(form)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.id && data.name) {
                            const dropdown = document.getElementById('item_category_id');
                            const option = document.createElement('option');
                            option.value = data.id;
                            option.textContent = data.name;
                            const tambahBaruOption = dropdown.querySelector(
                                'option[value="tambah-baru"]');
                            dropdown.insertBefore(option, tambahBaruOption);
                            dropdown.value = data.id;
                            var modal = bootstrap.Modal.getInstance(document.getElementById(
                                'modalTambahJenis'));
                            modal.hide();
                            form.reset();
                        } else if (data.error) {
                            Toast.fire({
                                icon: 'error',
                                title: data.error
                            });
                        }
                    })
                    .catch(error => {
                        Toast.fire({
                            icon: 'error',
                            title: 'Terjadi kesalahan saat menyimpan jenis barang'
                        });
                    });
            });

            document.getElementById('formTambahGroup').addEventListener('submit', function(e) {
                e.preventDefault();
                const form = e.target;
                fetch('{{ route('item-groups.ajax-save') }}', {
                        method: 'POST',
                        body: new FormData(form)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.id && data.name) {
                            const dropdown = document.getElementById('item_group_id');
                            const option = document.createElement('option');
                            option.value = data.id;
                            option.textContent = data.name;
                            const tambahBaruOption = dropdown.querySelector(
                                'option[value="tambah-baru"]');
                            dropdown.insertBefore(option, tambahBaruOption);
                            dropdown.value = data.id;
                            var modal = bootstrap.Modal.getInstance(document.getElementById(
                                'modalTambahGroup'));
                            modal.hide();
                            form.reset();
                        } else if (data.error) {
                            Toast.fire({
                                icon: 'error',
                                title: data.error
                            });
                        }
                    })
                    .catch(error => {
                        Toast.fire({
                            icon: 'error',
                            title: 'Terjadi kesalahan saat menyimpan group barang'
                        });
                    });
            });
        });

        function showModalTambahSatuan() {
            var modal = new bootstrap.Modal(document.getElementById('modalTambahSatuan'));
            modal.show();
        }

        function showModalTambahJenis() {
            var modal = new bootstrap.Modal(document.getElementById('modalTambahJenis'));
            modal.show();
        }

        function showModalTambahGroup() {
            var modal = new bootstrap.Modal(document.getElementById('modalTambahGroup'));
            modal.show();
        }
    </script>
</x-app-layout>
