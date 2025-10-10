<x-app-layout>
    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white text-center">
                        <h5 class="card-title mb-0"><i class="fas fa-money-check-alt"></i> Form Tambah Satuan Konversi
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('unit-conversions.store') }}" method="POST">
                            @csrf

                            <div class="row mb-3 align-items-center">
                                <label for="item_id" class="col-md-3 col-form-label"><b>Barang</b></label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select name="item_id" id="item_id"
                                            class="form-select @error('item_id') is-invalid @enderror" required>
                                            <option value="">- Pilih Barang -</option>
                                            @foreach ($items as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                                    {{ $item->item_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                            onclick="openItemModal()">
                                            <i class="fas fa-search"></i> Cari
                                        </button>
                                    </div>
                                    @error('item_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="unit_id" class="col-md-3 col-form-label"><b>Satuan</b></label>
                                <div class="col-md-9">
                                    <select name="unit_id" id="unit_id"
                                        class="form-select @error('unit_id') is-invalid @enderror" required>
                                        <option value="">- Pilih Satuan -</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}"
                                                {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                                {{ $unit->unit_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('unit_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="conversion_value" class="col-md-3 col-form-label"><b>Nilai
                                        Konversi</b></label>
                                <div class="col-md-9">
                                    <input type="number" name="conversion_value" id="conversion_value"
                                        class="form-control @error('conversion_value') is-invalid @enderror"
                                        value="{{ old('conversion_value') }}" required min="0.01" step="0.01">
                                    @error('conversion_value')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="description" class="col-md-3 col-form-label"><b>Keterangan</b></label>
                                <div class="col-md-9">
                                    <input type="text" name="description" id="description"
                                        class="form-control @error('description') is-invalid @enderror"
                                        value="{{ old('description') }}">
                                    @error('description')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-success me-2">
                                    <i class="fas fa-save"></i> Simpan
                                </button>
                                <a href="{{ route('unit-conversions.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Cari Barang -->
        <div class="modal fade" id="modalItem" tabindex="-1" aria-labelledby="modalItemLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalItemLabel">Cari Barang</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" id="modal-item-search" class="form-control mb-2"
                            placeholder="Ketik nama barang...">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Kode Barang</th>
                                        <th>Nama Barang</th>
                                        <th class="text-center">Pilih</th>
                                    </tr>
                                </thead>
                                <tbody id="modal-item-list">
                                    @foreach ($items as $item)
                                        <tr data-id="{{ $item->id }}" data-name="{{ $item->item_name }}"
                                            data-code="{{ $item->item_code ?? '' }}">
                                            <td>{{ $item->item_code ?? '-' }}</td>
                                            <td>{{ $item->item_name }}</td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-success btn-sm pilih-item-btn">
                                                    Pilih
                                                </button>
                                            </td>
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

            // Success message
            @if (session('success'))
                Toast.fire({
                    icon: 'success',
                    title: '{{ session('success') }}'
                });
            @endif

            // Error message
            @if (session('error'))
                Toast.fire({
                    icon: 'error',
                    title: '{{ session('error') }}'
                });
            @endif

            // Validation errors
            @if ($errors->any())
                Toast.fire({
                    icon: 'error',
                    title: 'Terjadi kesalahan pada input',
                    html: '{!! implode('<br>', $errors->all()) !!}'
                });
            @endif
        });

        function openItemModal() {
            document.getElementById('modal-item-search').value = '';
            filterModalItem('');
            var modal = new bootstrap.Modal(document.getElementById('modalItem'));
            modal.show();
        }

        function filterModalItem(keyword) {
            keyword = keyword.toLowerCase();
            document.querySelectorAll('#modal-item-list tr').forEach(function(row) {
                const name = row.getAttribute('data-name').toLowerCase();
                const code = row.getAttribute('data-code').toLowerCase();
                if (name.includes(keyword) || code.includes(keyword) || keyword === '') {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        document.getElementById('modal-item-search').addEventListener('input', function() {
            filterModalItem(this.value);
        });

        document.getElementById('modal-item-list').addEventListener('click', function(e) {
            if (e.target.classList.contains('pilih-item-btn')) {
                const row = e.target.closest('tr');
                const itemId = row.getAttribute('data-id');
                const itemName = row.getAttribute('data-name');

                // Set selected option
                const select = document.getElementById('item_id');
                select.value = itemId;

                // Trigger change event
                select.dispatchEvent(new Event('change'));

                // Close modal
                var modal = bootstrap.Modal.getInstance(document.getElementById('modalItem'));
                modal.hide();

                // Show success message
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000
                });
                Toast.fire({
                    icon: 'success',
                    title: `Barang "${itemName}" dipilih`
                });
            }
        });
    </script>

</x-app-layout>
