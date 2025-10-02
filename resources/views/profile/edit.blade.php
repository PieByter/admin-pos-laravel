<x-app-layout>
    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white text-center">
                        <h5 class="card-title mb-0"><i class="bi bi-person-lines-fill"></i> Form Edit Profil</h5>
                    </div>
                    <div class="card-body">
                        <form action="<?= site_url('profile/update') ?>" method="post" enctype="multipart/form-data">
                            <?= csrf_field() ?>

                            <div class="mb-3">
                                <div for="username" class="form-label"><b>Username</b></div>
                                <input type="text" class="form-control" id="username" name="username"
                                    value="<?= old('username', $user['username']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label"><b>Email</b></label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="<?= old('email', $user['email'] ?? '') ?>">
                            </div>

                            <div class="mb-3">
                                <label for="password"><b>Password Baru</b></label>
                                <div class="password-container position-relative">
                                    <input type="password" class="form-control" id="password" name="password"
                                        oninput="checkPasswordStrength()" placeholder="Masukkan password baru">
                                    <i class="bi bi-eye-slash password-toggle" id="togglePassword"
                                        onclick="togglePasswordVisibility('password', 'togglePassword')"
                                        title="Show/Hide Password"
                                        style="position: absolute; top: 50%; right: 15px; transform: translateY(-50%); cursor: pointer;"></i>
                                </div>
                                <ul id="password-checklist" class="text-start small mt-2 mb-0 list-unstyled"
                                    style="display:none;">
                                    <li id="check-length"><span class="me-1" id="icon-length">❌</span>Minimal 8
                                        karakter
                                    </li>
                                    <li id="check-case"><span class="me-1" id="icon-case">❌</span>Huruf besar & kecil
                                    </li>
                                    <li id="check-symbol"><span class="me-1" id="icon-symbol">❌</span>Mengandung
                                        simbol</li>
                                </ul>
                            </div>

                            <div class="mb-3">
                                <label for="confirm_password"><b>Konfirmasi Password Baru</b></label>
                                <div class="password-container position-relative">
                                    <input type="password" class="form-control" id="confirm_password"
                                        name="confirm_password" placeholder="Konfirmasi password baru">
                                    <i class="bi bi-eye-slash password-toggle" id="toggleConfirmPassword"
                                        onclick="togglePasswordVisibility('confirm_password', 'toggleConfirmPassword')"
                                        title="Show/Hide Confirm Password"
                                        style="position: absolute; top: 50%; right: 15px; transform: translateY(-50%); cursor: pointer;"></i>
                                </div>
                                <ul id="confirm-password-checklist" class="text-start small mt-2 mb-0 list-unstyled"
                                    style="display:none;">
                                    <li id="check-confirm"><span class="me-1" id="icon-confirm">❌</span>Password dan
                                        konfirmasi harus sama</li>
                                </ul>
                            </div>

                            <div class="mb-3 text-center">
                                <label class="form-label"><b>Foto Profil</b></label>
                                <div class="mb-3">
                                    <?php if ($user['foto'] && file_exists(FCPATH . 'uploads/profile/' . $user['foto'])): ?>
                                    <img src="<?= base_url('uploads/profile/' . $user['foto']) ?>"
                                        alt="Current Profile Photo" class="rounded-circle mb-2"
                                        style="width:100px; height:100px; object-fit:cover;" id="preview-image">
                                    <?php else: ?>
                                    <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center mb-2"
                                        style="width:100px; height:100px;" id="preview-placeholder">
                                        <i class="bi bi-person-circle text-white" style="font-size:3rem;"></i>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <input type="file" class="form-control" id="foto" name="foto"
                                    accept="image/*" onchange="previewImage(this)">
                                <small class="text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB</small>
                            </div>

                            <div class="mt-4 float-end">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan
                                    Perubahan</button>
                                <a href="<?= site_url('profile') ?>" class="btn btn-secondary">
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
        function togglePasswordVisibility(passwordFieldId, toggleIconId) {
            const passwordField = document.getElementById(passwordFieldId);
            const toggleIcon = document.getElementById(toggleIconId);

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.replace('bi-eye-slash', 'bi-eye');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.replace('bi-eye', 'bi-eye-slash');
            }
        }

        const passwordInput = document.getElementById('password');
        const passwordChecklist = document.getElementById('password-checklist');

        // function isPasswordValid() {
        //     const password = passwordInput.value;
        //     return (
        //         password.length >= 8 &&
        //         /[a-z]/.test(password) &&
        //         /[A-Z]/.test(password) &&
        //         /[^A-Za-z0-9]/.test(password)
        //     );
        // }

        function checkPasswordStrength() {
            const password = passwordInput.value;
            const lengthCheck = password.length >= 8;
            const caseCheck = /[a-z]/.test(password) && /[A-Z]/.test(password);
            const symbolCheck = /[^A-Za-z0-9]/.test(password);

            document.getElementById('check-length').className = lengthCheck ? 'text-success' : 'text-danger';
            document.getElementById('icon-length').textContent = lengthCheck ? '✔️' : '❌';

            document.getElementById('check-case').className = caseCheck ? 'text-success' : 'text-danger';
            document.getElementById('icon-case').textContent = caseCheck ? '✔️' : '❌';

            document.getElementById('check-symbol').className = symbolCheck ? 'text-success' : 'text-danger';
            document.getElementById('icon-symbol').textContent = symbolCheck ? '✔️' : '❌';

            if (password.length === 0) {
                passwordChecklist.style.display = passwordInput === document.activeElement ? 'block' : 'none';
            } else if (lengthCheck && caseCheck && symbolCheck) {
                passwordChecklist.style.display = passwordInput === document.activeElement ? 'block' : 'none';
            } else {
                passwordChecklist.style.display = 'block';
            }
        }

        passwordInput.addEventListener('focus', checkPasswordStrength);
        passwordInput.addEventListener('input', checkPasswordStrength);
        passwordInput.addEventListener('blur', checkPasswordStrength);

        const confirmInput = document.getElementById('confirm_password');
        const confirmChecklist = document.getElementById('confirm-password-checklist');

        // function isConfirmValid() {
        //     return (
        //         confirmInput.value.length > 0 &&
        //         passwordInput.value === confirmInput.value
        //     );
        // }

        function checkConfirmPassword() {
            const password = passwordInput.value;
            const confirm = confirmInput.value;
            const isMatch = password === confirm && confirm.length > 0;

            document.getElementById('check-confirm').className = isMatch ? 'text-success' : 'text-danger';
            document.getElementById('icon-confirm').textContent = isMatch ? '✔️' : '❌';

            if (confirm.length === 0) {
                confirmChecklist.style.display = confirmInput === document.activeElement ? 'block' : 'none';
            } else if (isMatch) {
                confirmChecklist.style.display = confirmInput === document.activeElement ? 'block' : 'none';
            } else {
                confirmChecklist.style.display = 'block';
            }
        }

        confirmInput.addEventListener('focus', checkConfirmPassword);
        confirmInput.addEventListener('input', checkConfirmPassword);
        confirmInput.addEventListener('blur', checkConfirmPassword);

        passwordInput.addEventListener('input', checkConfirmPassword);

        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const preview = document.getElementById('preview-image');
                    const placeholder = document.getElementById('preview-placeholder');

                    if (preview) {
                        preview.src = e.target.result;
                    } else if (placeholder) {
                        placeholder.innerHTML =
                            `<img src="${e.target.result}" class="rounded-circle" style="width:100px; height:100px; object-fit:cover;">`;
                    }
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

    <?php if (session()->getFlashdata('error')): ?>
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
                title: 'Form tidak valid!',
                html: `<?= session()->getFlashdata('error') ?>`
            });
        });
    </script>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                icon: 'success',
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
            Toast.fire({
                title: 'Berhasil!',
                html: `<?= session()->getFlashdata('success') ?>`
            });
        });
    </script>
    <?php endif; ?>
</x-app-layout>
