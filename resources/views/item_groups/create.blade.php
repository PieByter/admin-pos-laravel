<x-app-layout>
    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white text-center">
                        <h5 class="card-title mb-0"><i class="bi bi-tags"></i> Tambah Group Barang</h5>
                    </div>
                    <div class="card-body">
                        <form action="<?= site_url('group-barang/save') ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label for="nama" class="form-label"><b>Group Barang</b></label>
                                <input type="text" name="nama" id="nama"
                                    class="form-control <?= session('validation') && session('validation')->hasError('nama') ? 'is-invalid' : '' ?>"
                                    value="<?= old('nama') ?>" required autofocus>
                                <?php if (session('validation') && session('validation')->hasError('nama')): ?>
                                <div class="invalid-feedback"><?= session('validation')->getError('nama') ?></div>
                                <?php endif ?>
                            </div>
                            <div class="mb-3">
                                <label for="keterangan" class="form-label"><b>Keterangan</b></label>
                                <input type="text" name="keterangan" id="keterangan"
                                    class="form-control <?= session('validation') && session('validation')->hasError('keterangan') ? 'is-invalid' : '' ?>"
                                    value="<?= old('keterangan') ?>">
                                <?php if (session('validation') && session('validation')->hasError('keterangan')): ?>
                                <div class="invalid-feedback"><?= session('validation')->getError('keterangan') ?></div>
                                <?php endif ?>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-success me-2"><i class="bi bi-save"></i>
                                    Simpan</button>
                                <a href="<?= site_url('group-barang') ?>" class="btn btn-secondary">
                                    <i class="bi bi-x-lg"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
