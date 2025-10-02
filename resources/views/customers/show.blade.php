<x-app-layout>
    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-person"></i> Detail Customer</h5>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-4 text-justify">
                            <dt class="col-sm-4">Nama</dt>
                            <dd class="col-sm-8"><?= esc($customer['nama']) ?></dd>
                            <dt class="col-sm-4">Alamat</dt>
                            <dd class="col-sm-8"><?= esc($customer['alamat']) ?></dd>
                            <dt class="col-sm-4">No. Telp</dt>
                            <dd class="col-sm-8"><?= esc($customer['no_telp']) ?></dd>
                            <dt class="col-sm-4">Email</dt>
                            <dd class="col-sm-8"><?= esc($customer['email']) ?></dd>
                            <dt class="col-sm-4">Status</dt>
                            <dd class="col-sm-8">
                                <?php if ($customer['status'] == 'aktif'): ?>
                                <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                <span class="badge bg-secondary">Tidak Aktif</span>
                                <?php endif; ?>
                            <dt class="col-sm-4">Keterangan</dt>
                            <dd class="col-sm-8"><?= esc($customer['keterangan']) ?></dd>
                        </dl>
                        <div class="mt-4 d-flex justify-content-end gap-2">
                            <?php if ($can_write ?? false): ?>
                            <a href="<?= site_url('customer/edit/' . $customer['id']) ?>" class="btn btn-warning ">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <?php endif; ?>
                            <a href="<?= site_url('customer') ?>" class="btn btn-secondary ms-2" id="btn-back-customer">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
