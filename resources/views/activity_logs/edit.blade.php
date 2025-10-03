{{-- filepath: c:\laragon\www\admin-pos\resources\views\activity_logs\edit.blade.php --}}
<x-app-layout>
    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0"><i class="bi bi-journal-text fs-5"></i> Form Edit Log Aktivitas</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('activity-logs.update', $activityLog->id) }}" method="post">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3 align-items-center">
                                <label for="user_id" class="col-md-3 col-form-label"><b>User</b></label>
                                <div class="col-md-9">
                                    <select class="form-select @error('user_id') is-invalid @enderror" id="user_id"
                                        name="user_id" required>
                                        <option value="">- Pilih User -</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                {{ old('user_id', $activityLog->user_id) == $user->id ? 'selected' : '' }}>
                                                {{ $user->username }} ({{ $user->name }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="activity" class="col-md-3 col-form-label"><b>Aktivitas</b></label>
                                <div class="col-md-9">
                                    <textarea class="form-control @error('activity') is-invalid @enderror" id="activity" name="activity" rows="3"
                                        required>{{ old('activity', $activityLog->activity) }}</textarea>
                                    @error('activity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label for="description" class="col-md-3 col-form-label"><b>Keterangan</b></label>
                                <div class="col-md-9">
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                        rows="2">{{ old('description', $activityLog->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Update
                                </button>
                                <a href="{{ route('activity-logs.index') }}" class="btn btn-secondary">
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
                timerProgressBar: true
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
