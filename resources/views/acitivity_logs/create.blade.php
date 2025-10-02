<x-app-layout>
    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0"><i class="bi bi-journal-plus fs-5"></i> Form Tambah Log</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" action="<?= site_url('superadmin/logs/save') ?>">
                            @csrf
                            <div class="row mb-3 align-items-center">
                                <label for="user_id" class="col-md-3 col-form-label"><b>User ID</b></label>
                                <div class="col-md-9">
                                    <input type="number" class="form-control" id="user_id" name="user_id" required>
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label for="aktivitas" class="col-md-3 col-form-label"><b>Aktivitas</b></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="aktivitas" name="aktivitas" required>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-2">
                                <button type="submit" class="btn btn-success"><i class="bi bi-save"></i>
                                    Simpan</button>
                                <a href="<?= site_url('superadmin/logs') ?>" class="btn btn-secondary ms-2">
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
