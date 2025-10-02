<x-app-layout>
    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white text-center">
                        <h5 class="card-title mb-0"><i class="bi bi-box-seam"></i> Form Tambah Barang</h5>
                    </div>
                    <div class="card-body">
                        <form action="<?= site_url('barang/save') ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="nama_barang" class="form-label"><b>Nama Barang</b></label>
                                    <input type="text" class="form-control" id="nama_barang" name="nama_barang"
                                        value="<?= old('nama_barang') ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="kode_barang" class="form-label"><b>Kode Barang</b></label>
                                    <input type="text" class="form-control" id="kode_barang" name="kode_barang"
                                        value="<?= old('kode_barang') ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="id_jenis" class="form-label"><b>Jenis Barang</b></label>
                                    <select class="form-select" id="id_jenis" name="id_jenis" required>
                                        <option value="">- Pilih Jenis -</option>
                                        <?php foreach ($jenis_list as $jenis): ?>
                                        <option value="<?= $jenis['id'] ?>"
                                            <?= old('id_jenis') == $jenis['id'] ? 'selected' : '' ?>>
                                            <?= esc($jenis['nama']) ?>
                                        </option>
                                        <?php endforeach ?>
                                        <option value="tambah-baru">+ Tambah Jenis Barang Baru</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="id_group" class="form-label"><b>Group Barang</b></label>
                                    <select class="form-select" id="id_group" name="id_group" required>
                                        <option value="">- Pilih Group -</option>
                                        <?php foreach ($group_list as $group): ?>
                                        <option value="<?= $group['id'] ?>"
                                            <?= old('id_group') == $group['id'] ? 'selected' : '' ?>>
                                            <?= esc($group['nama']) ?>
                                        </option>
                                        <?php endforeach ?>
                                        <option value="tambah-baru">+ Tambah Group Barang Baru</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="id_satuan" class="form-label"><b>Satuan Utama</b> </label>
                                    <select class="form-select" id="id_satuan" name="id_satuan" required
                                        onchange="checkTambahSatuan(this)">
                                        <?php foreach ($satuan_list as $satuan): ?>
                                        <option value="<?= $satuan['id'] ?>"
                                            <?= old('id_satuan') == $satuan['id'] ? 'selected' : '' ?>>
                                            <?= esc($satuan['nama']) ?>
                                        </option>
                                        <?php endforeach ?>
                                        <option value="tambah-baru">+ Tambah Satuan Baru</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="harga_beli" class="form-label"><b>Harga Beli</b></label>
                                    <input type="number" class="form-control" id="harga_beli" name="harga_beli"
                                        value="<?= old('harga_beli') ?>" min="0" step="0.01">
                                </div>
                                <div class="col-md-4">
                                    <label for="harga_jual" class="form-label"><b>Harga Jual</b></label>
                                    <input type="number" class="form-control" id="harga_jual" name="harga_jual"
                                        value="<?= old('harga_jual') ?>" min="0" step="0.01">
                                </div>
                                <div class="col-md-4">
                                    <label for="stok" class="form-label"><b>Stok</b></label>
                                    <input type="number" class="form-control" id="stok" name="stok"
                                        value="<?= old('stok', '') ?>" min="0">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="keterangan" class="form-label"><b>Keterangan</b></label>
                                    <textarea class="form-control" id="keterangan" name="keterangan" rows="2"><?= old('keterangan') ?></textarea>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-success me-2"><i class="bi bi-save"></i>
                                    Simpan</button>
                                <a href="<?= site_url('barang') ?>" class="btn btn-secondary">
                                    <i class="bi bi-x-lg"></i>
                                    Batal
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
                    <?= csrf_field() ?>
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Satuan Baru</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="text" name="nama" class="form-control mb-2" placeholder="Nama Satuan"
                                required>
                            <input type="text" name="keterangan" class="form-control" placeholder="Keterangan">
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
                    <?= csrf_field() ?>
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Jenis Barang Baru</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="text" name="nama" class="form-control mb-2"
                                placeholder="Nama Jenis Barang" required>
                            <input type="text" name="keterangan" class="form-control" placeholder="Keterangan">
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
                    <?= csrf_field() ?>
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Group Barang Baru</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="text" name="nama" class="form-control mb-2"
                                placeholder="Nama Group Barang" required>
                            <input type="text" name="keterangan" class="form-control" placeholder="Keterangan">
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
            document.getElementById('id_satuan').addEventListener('change', function() {
                if (this.value === 'tambah-baru') {
                    showModalTambahSatuan();
                    this.value = '';
                }
            });

            document.getElementById('id_jenis').addEventListener('change', function() {
                if (this.value === 'tambah-baru') {
                    showModalTambahJenis();
                    this.value = '';
                }
            });

            document.getElementById('id_group').addEventListener('change', function() {
                if (this.value === 'tambah-baru') {
                    showModalTambahGroup();
                    this.value = '';
                }
            });

            document.getElementById('formTambahSatuan').addEventListener('submit', function(e) {
                e.preventDefault();
                const form = e.target;
                fetch('<?= site_url('satuan/ajax-save') ?>', {
                        method: 'POST',
                        body: new FormData(form)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.id && data.nama) {
                            const dropdown = document.getElementById('id_satuan');
                            const option = document.createElement('option');
                            option.value = data.id;
                            option.textContent = data.nama;
                            const tambahBaruOption = dropdown.querySelector(
                                'option[value="tambah-baru"]');
                            dropdown.insertBefore(option, tambahBaruOption);
                            dropdown.value = data.id;
                            var modal = bootstrap.Modal.getInstance(document.getElementById(
                                'modalTambahSatuan'));
                            modal.hide();
                            form.reset();
                        } else if (data.error) {
                            alert(data.error);
                        }
                    });
            });

            document.getElementById('formTambahJenis').addEventListener('submit', function(e) {
                e.preventDefault();
                const form = e.target;
                fetch('<?= site_url('jenis-barang/ajax-save') ?>', {
                        method: 'POST',
                        body: new FormData(form)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.id && data.nama) {
                            const dropdown = document.getElementById('id_jenis');
                            const option = document.createElement('option');
                            option.value = data.id;
                            option.textContent = data.nama;
                            const tambahBaruOption = dropdown.querySelector(
                                'option[value="tambah-baru"]');
                            dropdown.insertBefore(option, tambahBaruOption);
                            dropdown.value = data.id;
                            var modal = bootstrap.Modal.getInstance(document.getElementById(
                                'modalTambahJenis'));
                            modal.hide();
                            form.reset();
                        } else if (data.error) {
                            alert(data.error);
                        }
                    });
            });

            document.getElementById('formTambahGroup').addEventListener('submit', function(e) {
                e.preventDefault();
                const form = e.target;
                fetch('<?= site_url('group-barang/ajax-save') ?>', {
                        method: 'POST',
                        body: new FormData(form)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.id && data.nama) {
                            const dropdown = document.getElementById('id_group');
                            const option = document.createElement('option');
                            option.value = data.id;
                            option.textContent = data.nama;
                            const tambahBaruOption = dropdown.querySelector(
                                'option[value="tambah-baru"]');
                            dropdown.insertBefore(option, tambahBaruOption);
                            dropdown.value = data.id;
                            var modal = bootstrap.Modal.getInstance(document.getElementById(
                                'modalTambahGroup'));
                            modal.hide();
                            form.reset();
                        } else if (data.error) {
                            alert(data.error);
                        }
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
</x-app-layout>
