<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WikiDefa - Register</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f3f4f6;
        }

        .register-container {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .logo {
            text-align: center;
            color: #E11D48;
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #4b5563;
            font-size: 0.9rem;
        }

        input, select {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #333;
            border-radius: 4px;
            font-size: 0.9rem;
            outline: none;
            transition: border-color 0.2s;
        }

        input:focus, select:focus {
            border-color: #E11D48;
        }

        .register-btn {
            width: 100%;
            padding: 0.75rem;
            background-color: #E11D48;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 1rem;
            transition: background-color 0.2s;
        }

        .register-btn:hover {
            background-color: #E11D48;
        }

        .login-link {
            text-align: center;
            margin-top: 1rem;
            font-size: 0.9rem;
            color: #4b5563;
        }

        .login-link a {
            color: #2563eb;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .form-group {
        margin-bottom: 1rem;
        }

        .password-wrapper {
            position: relative;
        }
        .password-wrapper input {
            width: 100%;
            padding: 10px 40px 10px 10px;
            font-size: 16px;
            border: 2px solid #333;
            border-radius: 6px;
        }
        .password-wrapper button {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
        }
        .password-wrapper svg {
            width: 20px;
            height: 20px;
        }

        .error-message {
            color: red;
            font-size: 0.85rem;
            margin-top: 0.3rem;
            display: block;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="logo">WikiDefa</div>

        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" placeholder="Name" value="{{ old('name') }}" required>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Username" value="{{ old('username') }}" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
            </div>
            <div class="form-group">
                <label for="phone">Number Phone</label>
                <input type="tel" id="phone" name="phone" placeholder="Nomor handphone harus dapat dihubungi" value="{{ old('phone') }}" required>
            </div>
            <div class="form-group">
                <label for="role">Choose Role</label>
                <select id="role" name="role" required>
                    <option value="" disabled {{ old('role') ? '' : 'selected' }}>Select Role</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                </select>
            </div>
            <div class="form-group">
                <label for="posisi">Posisi</label>
                <input type="text" id="posisi" name="posisi" placeholder="Staff/etc" value="{{ old('posisi') }}" required>
            </div>
            <div class="form-group">
            <label for="password">Password</label>
            <div class="password-wrapper">
                <input type="password" id="password" name="password" placeholder="Password minimal 8 karakter." required>
                <button type="button" onclick="togglePassword()" aria-label="Toggle Password">
                    <!-- Mata terbuka -->
                    <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z" />
                        <circle stroke-width="2" cx="12" cy="12" r="3" />
                    </svg>
                    <!-- Mata tertutup -->
                    <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                        <path stroke-width="2" d="M17.94 17.94A10.94 10.94 0 0 1 12 19c-7 0-11-7-11-7a20.56 20.56 0 0 1 5.06-5.94M3 3l18 18" />
                        <path stroke-width="2" d="M9.88 9.88a3 3 0 0 0 4.24 4.24" />
                    </svg>
                </button>
            </div>
        </div>
            <div class="form-group">
    <label for="confirm-password">Confirm Password</label>
    <div class="password-wrapper">
        <input type="password" id="confirm-password" name="password_confirmation" placeholder="Password harus sama." required>
        <button type="button" onclick="toggleConfirmPassword()" aria-label="Toggle Confirm Password">
            <svg id="eyeOpenConfirm" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2" d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z" />
                <circle stroke-width="2" cx="12" cy="12" r="3" />
            </svg>
            <svg id="eyeClosedConfirm" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                <path stroke-width="2" d="M17.94 17.94A10.94 10.94 0 0 1 12 19c-7 0-11-7-11-7a20.56 20.56 0 0 1 5.06-5.94M3 3l18 18" />
                <path stroke-width="2" d="M9.88 9.88a3 3 0 0 0 4.24 4.24" />
            </svg>
        </button>
    </div>
    <span id="confirm-passwordError" class="error-message" style="display: none;">The password field confirmation does not match.</span>
            {{-- Error dari Laravel --}}
            @error('password')
                <span class="error-message">{{ $message }}</span>
            @enderror
</div>
            <button type="submit" class="register-btn">Register</button>
        </form>

        <div class="login-link">
            <p>Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Error popup --}}
    @if ($errors->any())
        <script>
            Swal.fire({
                title: 'Terjadi Kesalahan!',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                icon: 'error',
                confirmButtonText: 'Tutup',
                background: '#1f2937',
                color: '#fff',
                confirmButtonColor: '#ef4444'
            });
        </script>
    @endif

    {{-- Success popup --}}
    @if(session('register_success'))
        <script>
            Swal.fire({
                title: 'Akun berhasil dibuat!',
                text: 'Silakan menunggu verifikasi dari Admin.',
                icon: 'success',
                confirmButtonText: 'Ok',
                background: '#1f2937',
                color: '#fff',
                confirmButtonColor: '#3b82f6'
            });
        </script>
    @endif

    <script>
        function togglePassword() {
        const input = document.getElementById("password");
        const eyeOpen = document.getElementById("eyeOpen");
        const eyeClosed = document.getElementById("eyeClosed");

        if (input.type === "password") {
            input.type = "text";
            eyeOpen.style.display = "none";
            eyeClosed.style.display = "block";
        } else {
            input.type = "password";
            eyeOpen.style.display = "block";
            eyeClosed.style.display = "none";
        }
    }

    function toggleConfirmPassword() {
        const input = document.getElementById("confirm-password");
        const eyeOpen = document.getElementById("eyeOpenConfirm");
        const eyeClosed = document.getElementById("eyeClosedConfirm");

        if (input.type === "password") {
            input.type = "text";
            eyeOpen.style.display = "none";
            eyeClosed.style.display = "block";
        } else {
            input.type = "password";
            eyeOpen.style.display = "block";
            eyeClosed.style.display = "none";
        }
    }
    </script>
</body>
</html>
