<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login Admin - MySIFA</title>
    <link rel="shortcut icon" href="{{ asset('mysifa-ecommerce-site/assets/mysifa-logo.jpg') }}">
    <link href="{{ asset('backend-assets/css/app.min.css') }}?v={{ @filemtime(public_path('backend-assets/css/app.min.css')) }}" rel="stylesheet">
</head>
<body>
    <div class="auth-full-height">
        <div class="row m-0">
            <div class="col p-0 auth-full-height d-none d-md-flex" style="background: linear-gradient(120deg,#064da9,#05a99b);">
                <div class="d-flex justify-content-between flex-column h-100 px-5 py-3">
                    <div></div>
                    <div class="w-100">
                        <h1 class="display-4 text-white mb-4">MySIFA Admin</h1>
                        <p class="text-white lead" style="max-width: 630px;">Kelola admin, voucher, dan pendaftaran promo MySIFA dari satu dashboard.</p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-white">© {{ date('Y') }} MySIFA</span>
                    </div>
                </div>
            </div>
            <div class="col-12 p-0 auth-full-height bg-white" style="max-width: 450px;">
                <div class="d-flex h-100 align-items-center p-5">
                    <div class="w-100">
                        <div class="d-flex justify-content-center mt-3">
                            <img alt="logo" src="{{ asset('mysifa-ecommerce-site/assets/mysifa-logo-full.png') }}" style="width:220px;max-width:100%;height:auto;">
                        </div>
                        <h4 class="text-center mt-4 mb-0">Masuk sebagai Admin</h4>
                        <div class="mt-4">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    {{ $errors->first() }}
                                </div>
                            @endif
                            <form method="POST" action="{{ route('admin.login') }}">
                                @csrf
                                <div class="form-group mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input name="password" class="form-control" type="password" required>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                    <label class="form-check-label" for="remember">Ingat saya</label>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Log In</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('backend-assets/js/vendors.min.js') }}"></script>
    <script src="{{ asset('backend-assets/js/app.min.js') }}"></script>
</body>
</html>
