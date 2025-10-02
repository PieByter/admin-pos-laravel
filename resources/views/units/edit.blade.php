<x-app-layout>
    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white text-center">
                        <h5 class="card-title mb-0"><i class="bi bi-rulers"></i> Edit Satuan</h5>
                    </div>
                    <div class="card-body">
                        <form action="<?= site_url('satuan/update/' . $satuan['id']) ?>" method="post"
                            autocomplete="off">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label for="nama" class="form-label"><b>Nama Satuan</b></label>
                                <input type="text" name="nama" id="nama" class="form-control"
                                    value="<?= old('nama', $satuan['nama']) ?>" required autofocus>
                            </div>
                            <div class="mb-3">
                                <label for="keterangan" class="form-label"><b>Keterangan</b></label>
                                <input type="text" name="keterangan" id="keterangan" class="form-control"
                                    value="<?= old('keterangan', $satuan['keterangan']) ?>">
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary me-2"><i class="bi bi-save"></i>
                                    Update</button>
                                <a href="<?= site_url('satuan') ?>" class="btn btn-secondary">
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
