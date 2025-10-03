<x-app-layout>
    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white text-center">
                        <h5 class="card-title mb-0"> <i class="bi bi-truck fs-5"></i> Form Edit Supplier</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3 align-items-center">
                                <label for="name" class="col-sm-3 col-form-label"><b>Nama</b></label>
                                <div class="col-sm-9">
                                    <input type="text" name="name" id="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $supplier->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="company_name" class="col-sm-3 col-form-label"><b>Perusahaan</b></label>
                                <div class="col-sm-9">
                                    <input type="text" name="company_name" id="company_name"
                                        class="form-control @error('company_name') is-invalid @enderror"
                                        value="{{ old('company_name', $supplier->company_name) }}">
                                    @error('company_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="contact_person" class="col-sm-3 col-form-label"><b>Contact
                                        Person</b></label>
                                <div class="col-sm-9">
                                    <input type="text" name="contact_person" id="contact_person"
                                        class="form-control @error('contact_person') is-invalid @enderror"
                                        value="{{ old('contact_person', $supplier->contact_person) }}">
                                    @error('contact_person')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="address" class="col-sm-3 col-form-label"><b>Alamat</b></label>
                                <div class="col-sm-9">
                                    <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" rows="3">{{ old('address', $supplier->address) }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="phone_number" class="col-sm-3 col-form-label"><b>Nomor Telepon</b></label>
                                <div class="col-sm-9">
                                    <input type="tel" name="phone_number" id="phone_number"
                                        class="form-control @error('phone_number') is-invalid @enderror"
                                        value="{{ old('phone_number', $supplier->phone_number) }}">
                                    @error('phone_number')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="contact_email" class="col-sm-3 col-form-label"><b>Email</b></label>
                                <div class="col-sm-9">
                                    <input type="email" name="contact_email" id="contact_email"
                                        class="form-control @error('contact_email') is-invalid @enderror"
                                        value="{{ old('contact_email', $supplier->contact_email) }}">
                                    @error('contact_email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
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
                                            {{ old('status', $supplier->status) === 'active' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status">
                                            Aktif
                                        </label>
                                    </div>
                                    @error('status')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="description" class="col-sm-3 col-form-label"><b>Keterangan</b></label>
                                <div class="col-sm-9">
                                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                                        rows="3">{{ old('description', $supplier->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mb-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Update
                                </button>
                                <a href="{{ route('suppliers.index') }}" class="btn btn-secondary ms-2">
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

            // Success message
            @if (session('success'))
                Toast.fire({
                    icon: 'success',
                    title: '{{ session('success') }}'
                });
            @endif

            // Error message
            @if (session('error'))
                Toast.fire({
                    icon: 'error',
                    title: '{{ session('error') }}'
                });
            @endif

            // Validation errors
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
