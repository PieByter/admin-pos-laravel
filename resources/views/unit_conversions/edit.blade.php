<x-app-layout>
    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white text-center">
                        <h5 class="card-title mb-0"><i class="bi bi-pencil"></i> Edit Satuan Konversi</h5>
                    </div>
                    <div class="card-body">
                        <form action="<?= site_url('satuan-konversi/update/' . $konversi['id']) ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="row mb-3 align-items-center">
                                <label for="id_barang" class="col-md-3 col-form-label"><b>Barang</b></label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select name="id_barang" id="id_barang" class="form-select" required>
                                            <option value="">- Pilih Barang -</option>
                                            <?php foreach ($barangList as $barang): ?>
                                            <option value="<?= $barang['id'] ?>"
                                                <?= old('id_barang', $konversi['id_barang']) == $barang['id'] ? 'selected' : '' ?>>
                                                <?= esc($barang['nama_barang']) ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                            onclick="openBarangKonversiModal()">
                                            <i class="bi bi-search"></i> Cari
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label for="id_satuan" class="col-md-3 col-form-label"><b>Satuan</b></label>
                                <div class="col-md-9">
                                    <select name="id_satuan" id="id_satuan" class="form-select" required>
                                        <option value="">- Pilih Satuan -</option>
                                        <?php foreach ($satuanList as $satuan): ?>
                                        <option value="<?= $satuan['id'] ?>"
                                            <?= old('id_satuan', $konversi['id_satuan']) == $satuan['id'] ? 'selected' : '' ?>>
                                            <?= esc($satuan['nama']) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label for="konversi" class="col-md-3 col-form-label"><b>Konversi</b></label>
                                <div class="col-md-9">
                                    <input type="number" name="konversi" id="konversi" class="form-control"
                                        value="<?= old('konversi', $konversi['konversi']) ?>" required min="1"
                                        step="any">
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label for="keterangan" class="col-md-3 col-form-label"><b>Keterangan</b></label>
                                <div class="col-md-9">
                                    <input type="text" name="keterangan" id="keterangan" class="form-control"
                                        value="<?= old('keterangan', $konversi['keterangan']) ?>">
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary me-2"><i class="bi bi-save"></i>
                                    Update</button>
                                <a href="<?= site_url('satuan-konversi') ?>" class="btn btn-secondary">
                                    <i class="bi bi-x-lg"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalBarangKonversi" tabindex="-1" aria-labelledby="modalBarangKonversiLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalBarangKonversiLabel">Cari Barang</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" id="modal-barang-konversi-search" class="form-control mb-2"
                            placeholder="Ketik nama barang...">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th class="text-center">Pilih</th>
                                </tr>
                            </thead>
                            <tbody id="modal-barang-konversi-list">
                                <?php foreach ($barangList as $barang): ?>
                                <tr data-id="<?= esc($barang['id']) ?>" data-nama="<?= esc($barang['nama_barang']) ?>">
                                    <td><?= esc($barang['nama_barang']) ?></td>
                                    <td class="text-center">
                                        <button type="button"
                                            class="btn btn-success btn-sm pilih-barang-konversi-btn">Pilih</button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (session()->has('validation')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                icon: 'error',
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
            Toast.fire({
                title: '<?= implode('<br>', array_map('esc', session('validation')->getErrors())) ?>'
            });
        });
    </script>
    <?php endif ?>

    <script>
        function openBarangKonversiModal() {
            document.getElementById('modal-barang-konversi-search').value = '';
            filterModalBarangKonversi('');
            var modal = new bootstrap.Modal(document.getElementById('modalBarangKonversi'));
            modal.show();
        }

        function filterModalBarangKonversi(keyword) {
            keyword = keyword.toLowerCase();
            document.querySelectorAll('#modal-barang-konversi-list tr').forEach(function(row) {
                const nama = row.getAttribute('data-nama').toLowerCase();
                if (nama.includes(keyword) || keyword === '') {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        document.getElementById('modal-barang-konversi-search').addEventListener('input', function() {
            filterModalBarangKonversi(this.value);
        });

        document.getElementById('modal-barang-konversi-list').addEventListener('click', function(e) {
            if (e.target.classList.contains('pilih-barang-konversi-btn')) {
                const row = e.target.closest('tr');
                document.getElementById('id_barang').value = row.getAttribute('data-id');
                var modal = bootstrap.Modal.getInstance(document.getElementById('modalBarangKonversi'));
                modal.hide();
            }
        });
    </script>


</x-app-layout>
