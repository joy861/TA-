<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lupa Password - Sistem Restoran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">

<div class="container">
    <div class="row justify-content-center align-items-center" style="height: 100vh;">
        <div class="col-md-4">

            <div class="card shadow">
                <div class="card-body">

                    <h4 class="text-center mb-4">Reset Password</h4>

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    {{-- STEP 1: Cek username --}}
                    @if(!session('reset_user_id'))
                    <form action="{{ route('forgot.password.cek') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" required
                                placeholder="Masukkan username Anda">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Cek Username</button>
                    </form>

                    {{-- STEP 2: Jawab pertanyaan keamanan --}}
                    @else
                    <p class="text-muted mb-3" style="font-size:13px;">
                        Jawab pertanyaan keamanan untuk melanjutkan.
                    </p>

                    <form action="{{ route('forgot.password.proses') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="fw-bold">{{ session('pertanyaan_keamanan') }}</label>
                        </div>

                        <div class="mb-3">
                            <label>Jawaban</label>
                            <input type="text" name="jawaban" class="form-control" required
                                placeholder="Jawaban keamanan Anda">
                        </div>

                        <div class="mb-3">
                            <label>Password Baru</label>
                            <input type="password" name="password_baru" class="form-control"
                                required minlength="6">
                        </div>

                        <div class="mb-3">
                            <label>Konfirmasi Password Baru</label>
                            <input type="password" name="password_konfirmasi" class="form-control"
                                required minlength="6">
                        </div>

                        <button type="submit" class="btn btn-success w-100">Reset Password</button>
                    </form>
                    @endif

                    <div class="text-center mt-3">
                        <a href="{{ url('/login') }}" class="text-muted" style="font-size:13px;">
                            ← Kembali ke Login
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>