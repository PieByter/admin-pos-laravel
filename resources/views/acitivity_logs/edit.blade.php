<x-app-layout>
    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0"><i class="bi bi-journal-text fs-5"></i> Form Edit Log</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" action="<?= site_url('superadmin/logs/update/' . $log['id']) ?>">
                            <?= csrf_field() ?>
                            <div class="row mb-3 align-items-center">
                                <label for="user_id" class="col-md-3 col-form-label"><b>User ID</b></label>
                                <div class="col-md-9">
                                    <input type="number" class="form-control" id="user_id" name="user_id"
                                        value="<?= $log['user_id'] ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label for="aktivitas" class="col-md-3 col-form-label"><b>Aktivitas</b></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="aktivitas" name="aktivitas"
                                        value="<?= $log['aktivitas'] ?>" required>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-2">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i>
                                    Update</button>
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
