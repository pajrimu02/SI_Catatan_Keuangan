<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Catatan Keuangan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            min-height: 100vh;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            background-image: url('https://images.unsplash.com/photo-1554224155-6726b3ff858f?q=80&w=1920&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            border-radius: 16px;
            border: none;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }

        .login-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: #111827;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: -10px auto 16px;
            font-size: 26px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.25);
        }

        .btn-dark-custom {
            background: #111827;
            border: none;
        }

        .btn-dark-custom:hover {
            background: #1f2937;
        }
    </style>
</head>
<body>

    <div class="card login-card">
        <div class="card-body p-4 p-sm-5 text-center">

            <div class="login-icon">
                <i class="fa-solid fa-wallet"></i>
            </div>

            <h4 class="fw-bold mb-1">Selamat Datang</h4>
            <p class="text-muted mb-4">Masuk ke akun Catatan Keuangan kamu</p>

            {{-- Session Status --}}
            @if (session('status'))
                <div class="alert alert-success small text-start">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="text-start">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fa-solid fa-envelope text-muted"></i></span>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="nama@email.com" required autofocus autocomplete="username">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fa-solid fa-lock text-muted"></i></span>
                        <input type="password" name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="••••••••" required autocomplete="current-password">
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label small" for="remember">
                            Ingat saya
                        </label>
                    </div>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="small text-decoration-none">
                            Lupa password?
                        </a>
                    @endif
                </div>

                <button type="submit" class="btn btn-dark-custom text-white w-100 py-2 mb-3">
                    <i class="fa-solid fa-right-to-bracket"></i> Masuk
                </button>

                @if (Route::has('register'))
                    <p class="text-center small text-muted mb-0">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="fw-semibold text-decoration-none">Daftar sekarang</a>
                    </p>
                @endif
            </form>

        </div>
    </div>

</body>
</html>