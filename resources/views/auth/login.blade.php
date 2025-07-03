<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WikiDefa - Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .title {
            text-align: center;
            color: #E11D48;
            font-size: 2rem;
            margin-bottom: 1.5rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #444;
        }
        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #333;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .login-button {
            width: 100%;
            padding: 0.75rem;
            background-color: #E11D48;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            margin-top: 1rem;
        }
        .login-button:hover {
            background-color: #E11D48;
        }
        .links {
            text-align: center;
            margin-top: 1rem;
        }
        .links a {
            color: #1877f2;
            text-decoration: none;
            margin: 0 0.5rem;
            font-size: 0.9rem;
        }
        .register {
            text-align: center;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #ddd;
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
    </style>
</head>
<body>
    <div class="login-container">
        <h1 class="title">WikiDefa</h1>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="login">Email atau Username</label>
                <input type="text" id="login" name="login" placeholder="Masukkan email atau username" required>
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
            <button type="submit" class="login-button">Login</button>
            <div class="links">
                <p>Lupa Password? Hubungi admin</p>
            </div>
            <div class="register">
                <p>Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a></p>
            </div>
        </form>
    </div> 
    @if(session('success'))
<script>
    alert("{{ session('success') }}");
</script>
@endif 
</body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
</script>

@if(session('register_success'))
<script>
    Swal.fire({
        title: 'Registrasi Berhasil!',
        text: 'Silakan tunggu verifikasi dari admin sebelum login.',
        icon: 'success',
        confirmButtonText: 'OK',
        background: '#1f2937',
        color: '#fff',
        confirmButtonColor: '#3b82f6'
    });
</script>
@endif

@if(session('not_approved'))
<script>
    Swal.fire({
        title: 'Login Gagal!',
        text: '{{ session('not_approved') }}',
        icon: 'warning',
        confirmButtonText: 'OK',
        background: '#1f2937',
        color: '#fff',
        confirmButtonColor: '#ef4444' // merah
    });
</script>
@endif  

@if(session('login_failed'))
<script>
    Swal.fire({
        title: 'Login Gagal!',
        text: '{{ session('login_failed') }}',
        icon: 'error',
        confirmButtonText: 'Tutup',
        background: '#1f2937',
        color: '#fff',
        confirmButtonColor: '#ef4444'
    });
</script>
@endif

</html>
@if(session('success'))
<script>
    alert("{{ session('success') }}");
</script>
@endif
