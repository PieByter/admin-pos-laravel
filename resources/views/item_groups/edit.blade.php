<x-app-layout>
    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white text-center">
                        <h5 class="card-title mb-0"><i class="bi bi-tags"></i> Edit Group Barang</h5>
                    </div>
                    <div class="card-body">
                        <form action="<?= site_url('group-barang/update/' . $group['id']) ?>" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="nama" class="form-label"><b>Nama Group Barang</b></label>
                                <input type="text" name="nama" id="nama"
                                    class="form-control <?= session('validation') && session('validation')->hasError('nama') ? 'is-invalid' : '' ?>"
                                    value="<?= old('nama', $group['nama']) ?>" required autofocus>
                                <?php if (session('validation') && session('validation')->hasError('nama')): ?>
                                <div class="invalid-feedback"><?= session('validation')->getError('nama') ?></div>
                                <?php endif ?>
                            </div>
                            <div class="mb-3">
                                <label for="keterangan" class="form-label"><b>Keterangan</b></label>
                                <input type="text" name="keterangan" id="keterangan"
                                    class="form-control <?= session('validation') && session('validation')->hasError('keterangan') ? 'is-invalid' : '' ?>"
                                    value="<?= old('keterangan', $group['keterangan']) ?>">
                                <?php if (session('validation') && session('validation')->hasError('keterangan')): ?>
                                <div class="invalid-feedback"><?= session('validation')->getError('keterangan') ?></div>
                                <?php endif ?>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary me-2"><i class="bi bi-save"></i>
                                    Update</button>
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
