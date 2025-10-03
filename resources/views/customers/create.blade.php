<x-app-layout>
    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white text-center">
                        <h5 class="card-title mb-0"><i class="bi bi-person-plus"></i> Form Tambah Customer</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('customers.store') }}" method="post">
                            @csrf

                            <div class="row mb-3 align-items-center">
                                <label for="name" class="col-sm-3 col-form-label"><b>Nama</b></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="company_name" class="col-sm-3 col-form-label"><b>Perusahaan</b></label>
                                <div class="col-sm-9">
                                    <input type="text"
                                        class="form-control @error('company_name') is-invalid @enderror"
                                        id="company_name" name="company_name" value="{{ old('company_name') }}">
                                    @error('company_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="contact_person" class="col-sm-3 col-form-label"><b>Contact
                                        Person</b></label>
                                <div class="col-sm-9">
                                    <input type="text"
                                        class="form-control @error('contact_person') is-invalid @enderror"
                                        id="contact_person" name="contact_person" value="{{ old('contact_person') }}">
                                    @error('contact_person')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="address" class="col-sm-3 col-form-label"><b>Alamat</b></label>
                                <div class="col-sm-9">
                                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="phone_number" class="col-sm-3 col-form-label"><b>Nomor Telepon</b></label>
                                <div class="col-sm-9">
                                    <input type="tel"
                                        class="form-control @error('phone_number') is-invalid @enderror"
                                        id="phone_number" name="phone_number" value="{{ old('phone_number') }}">
                                    @error('phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="contact_email" class="col-sm-3 col-form-label"><b>Email</b></label>
                                <div class="col-sm-9">
                                    <input type="email"
                                        class="form-control @error('contact_email') is-invalid @enderror"
                                        id="contact_email" name="contact_email" value="{{ old('contact_email') }}">
                                    @error('contact_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="status" class="col-sm-3 col-form-label"><b>Status</b></label>
                                <div class="col-sm-9">
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="status" value="inactive">
                                        <input class="form-check-input @error('status') is-invalid @enderror"
                                            type="checkbox" id="status" name="status" value="active"
                                            {{ old('status', 'active') === 'active' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status">
                                            Aktif
                                        </label>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <input type="hidden" name="status_hidden" value="inactive">
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="description" class="col-sm-3 col-form-label"><b>Keterangan</b></label>
                                <div class="col-sm-9">
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                        rows="3">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mb-3">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-save"></i> Simpan
                                </button>
                                <a href="{{ route('customers.index') }}" class="btn btn-secondary ms-2"
                                    id="btn-back-customer">
                                    <i class="bi bi-x-lg"></i> Batal
                                </a>
                            </div>
                        </form>
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

            @if (session('error'))
                Toast.fire({
                    icon: 'error',
                    title: '{{ session('error') }}'
                });
            @endif

            @if ($errors->any())
                Toast.fire({
                    icon: 'error',
                    title: 'Terjadi kesalahan pada input',
                    html: '{!! implode('<br>', $errors->all()) !!}'
                });
            @endif
        });
    </script>
</x-app-layout>
