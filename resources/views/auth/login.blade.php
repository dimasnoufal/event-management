<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #2f6bff;
            --primary-700: #1f4bcf;
            --bg: #f6f7fb;
            --card: #ffffff;
            --muted: #6c757d;
            --radius: 18px;
        }

        html,
        body {
            height: 100%;
        }

        body {
            background: var(--bg);
        }

        .auth-wrapper {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
        }

        .auth-card {
            width: 100%;
            max-width: 1000px;
            background: var(--card);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(20, 33, 61, .12);
        }

        .auth-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        @media (max-width: 992px) {
            .auth-grid {
                grid-template-columns: 1fr;
            }

            .visual {
                display: none;
            }
        }

        /* ====== Left visual (interactive) ====== */
        .visual {
            position: relative;
            background: radial-gradient(1200px 600px at -10% 110%, #5ea1ff 0%, #2f6bff 40%, #1f4bcf 100%);
            color: #fff;
            padding: 48px 32px;
            overflow: hidden;
        }

        .visual h2 {
            font-weight: 800;
            letter-spacing: .3px;
            line-height: 1.15;
        }

        .visual p {
            opacity: .9;
            max-width: 420px;
        }

        .blob,
        .ring,
        .dot {
            position: absolute;
            filter: saturate(120%);
            opacity: .25;
            animation: float 10s ease-in-out infinite;
        }

        .blob {
            width: 240px;
            height: 240px;
            border-radius: 50%;
            background: radial-gradient(60% 60% at 30% 30%, rgba(255, 255, 255, .35), rgba(255, 255, 255, 0));
        }

        .blob.b1 {
            top: 10%;
            left: 10%;
            animation-duration: 12s;
        }

        .blob.b2 {
            bottom: -30px;
            right: -40px;
            width: 320px;
            height: 320px;
            animation-duration: 16s;
        }

        .ring {
            width: 220px;
            height: 220px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, .35);
        }

        .ring.r1 {
            top: 55%;
            left: 15%;
            animation-duration: 18s;
        }

        .ring.r2 {
            top: 15%;
            right: 8%;
            animation-duration: 20s;
        }

        .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #fff;
            opacity: .6;
            animation: drift 14s linear infinite;
        }

        .dot.d1 {
            top: 20%;
            left: 60%;
        }

        .dot.d2 {
            top: 70%;
            left: 35%;
            animation-duration: 11s;
        }

        .dot.d3 {
            top: 35%;
            left: 20%;
            animation-duration: 9s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) translateX(0) scale(1);
            }

            50% {
                transform: translateY(-12px) translateX(6px) scale(1.03);
            }
        }

        @keyframes drift {
            0% {
                transform: translate(0, 0);
                opacity: .35;
            }

            50% {
                transform: translate(20px, -10px);
                opacity: .8;
            }

            100% {
                transform: translate(0, 0);
                opacity: .35;
            }
        }

        /* ====== Right form ====== */
        .form-side {
            padding: 48px 40px;
        }

        .brand {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #e8f0ff, #ffffff);
            border: 1px solid #e7ecff;
            color: var(--primary);
            font-weight: 800;
            margin-bottom: 18px;
        }

        .title {
            font-weight: 800;
            margin-bottom: 6px;
        }

        .subtitle {
            color: var(--muted);
            margin-bottom: 28px;
        }

        .form-control {
            border-radius: var(--radius);
            padding: 12px 14px;
            border: 1px solid #e5e7ef;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 .2rem rgba(47, 107, 255, .15);
        }

        .input-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9aa4b2;
            cursor: pointer;
            user-select: none;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 18px;
            margin-top: 12px; 
        }

        .btn-primary {
            background: var(--primary);
            border: none;
            border-radius: 14px;
            padding: 12px 16px;
            font-weight: 600;
        }

        .btn-primary:hover {
            background: var(--primary-700);
        }

        .small-link {
            font-size: .9rem;
            text-decoration: none;
        }

        .small-link:hover {
            text-decoration: underline;
        }

        .footer {
            color: #9aa4b2;
            font-size: .86rem;
            text-align: center;
            margin-top: 18px;
        }
    </style>
</head>

<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="auth-grid">

                {{-- LEFT: interactive panel (tanpa ilustrasi gambar) --}}
                <aside class="visual">
                    <div class="blob b1"></div>
                    <div class="blob b2"></div>
                    <div class="ring r1"></div>
                    <div class="ring r2"></div>
                    <div class="dot d1"></div>
                    <div class="dot d2"></div>
                    <div class="dot d3"></div>

                    <h2 class="mb-2">Welcome Back!</h2>
                    <p class="mb-0">Kelola event, registrasi, dan pembayaran di satu tempat. Panel admin yang ringkas
                        dan cepat.</p>
                </aside>

                {{-- RIGHT: form login --}}
                <section class="form-side">
                    {{-- flash / error --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Terjadi kesalahan:</strong>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="brand">EM</div>
                    <h3 class="title">Hello Again!</h3>
                    <div class="subtitle">Masuk untuk mengelola event dan peserta.</div>

                    <form method="POST" action="{{ route('login') }}" novalidate>
                        @csrf

                        <div class="mb-3 position-relative">
                            <label class="form-label">Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror" placeholder="you@example.com"
                                required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 position-relative">
                            <label class="form-label">Password</label>
                            <input id="password" type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror" placeholder="••••••••"
                                required autocomplete="current-password">
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Login</button>

                        <div class="footer">&copy; {{ date('Y') }} Event Management. All rights reserved.</div>
                    </form>
                </section>

            </div>
        </div>
    </div>

    <script>
        // Toggle show/hide password
        const btn = document.getElementById('togglePwd');
        const pwd = document.getElementById('password');
        if (btn && pwd) {
            btn.addEventListener('click', () => {
                const isPwd = pwd.type === 'password';
                pwd.type = isPwd ? 'text' : 'password';
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
