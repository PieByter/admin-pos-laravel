<x-app-layout>
    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white  text-center">
                        <h5 class="card-title mb-0"> <i class="bi bi-truck fs-5"></i> Form Edit Supplier</h5>
                    </div>
                    <div class="card-body">
                        <form action="<?= site_url('supplier/update/' . $supplier['id']) ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="row mb-3 align-items-center">
                                <label for="nama" class="col-sm-3 col-form-label"><b>Nama</b></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="nama" name="nama"
                                        value="<?= old('nama', $supplier['nama']) ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label for="alamat" class="col-sm-3 col-form-label"><b>Alamat</b></label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" id="alamat" name="alamat" required><?= old('alamat', $supplier['alamat']) ?></textarea>
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label for="no_telp" class="col-sm-3 col-form-label"><b>Nomor Telepon</b></label>
                                <div class="col-sm-9">
                                    <input type="tel" class="form-control" id="no_telp" name="no_telp"
                                        value="<?= old('no_telp', $supplier['no_telp']) ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label for="email" class="col-sm-3 col-form-label"><b>Email</b></label>
                                <div class="col-sm-9">
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="<?= old('email', $supplier['email']) ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label for="status" class="col-sm-3 col-form-label"><b>Status</b></label>
                                <div class="col-sm-9">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="status" name="status"
                                            value="aktif"
                                            <?= old('status', $supplier['status']) === 'aktif' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="status">
                                            Aktif
                                        </label>
                                    </div>
                                    <input type="hidden" name="status_hidden" value="tidak_aktif">
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label for="keterangan" class="col-sm-3 col-form-label"><b>Keterangan</b></label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" id="keterangan" name="keterangan"><?= old('keterangan', $supplier['keterangan']) ?></textarea>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mb-3">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i>
                                    Update</button>
                                <a href="<?= site_url('supplier') ?>" class="btn btn-secondary ms-2"
                                    id="btn-back-supplier">
                                    <i class="bi bi-x-lg"></i> Batal
                                </a>
                            </div>
                        </form>
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

</x-app-layout>
