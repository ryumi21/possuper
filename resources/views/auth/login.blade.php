<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TriFusion POS</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bs-body-font-family: 'Inter', sans-serif;
            --bs-body-bg: #f8f9fc;
            --theme-orange: #ff9f43;
            --theme-dark: #1b2850;
        }

        body {
            font-family: var(--bs-body-font-family);
            background-color: var(--bs-body-bg);
            color: #4b5563;
            font-size: 0.875rem;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-login {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05), 0 4px 6px -2px rgba(0,0,0,0.025);
            width: 100%;
            max-width: 420px;
            padding: 2.5rem;
            background: #ffffff;
        }

        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            border-color: #e2e8f0;
        }

        .form-control:focus {
            border-color: var(--theme-orange);
            box-shadow: 0 0 0 0.25rem rgba(255, 159, 67, 0.25);
        }

        .btn-theme-orange {
            background-color: var(--theme-orange);
            color: #fff;
            border: none;
            font-weight: 600;
            padding: 0.75rem;
            border-radius: 0.5rem;
        }

        .btn-theme-orange:hover {
            background-color: #e88c38;
            color: #fff;
        }

        .logo-text {
            color: var(--theme-dark);
            letter-spacing: -1px;
        }
        
        .logo-accent {
            color: var(--theme-orange);
            font-size: 0.5em;
            vertical-align: super;
        }
    </style>
</head>
<body>

    <div class="card-login">
        <div class="text-center mb-4">
            <span class="fs-1 fw-bold logo-text">
                Tri<span class="logo-accent">Fusion</span>
            </span>
            <p class="text-muted mt-2">Sign in to your account</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger px-3 py-2" style="border-radius: 0.5rem;">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <small>{{ $errors->first() }}</small>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label fw-medium">Username</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0" style="color: #cbd5e1;"><i class="bi bi-person"></i></span>
                    <input type="text" class="form-control border-start-0 ps-0" id="name" name="name" value="{{ old('name') }}" placeholder="Enter your username" required autofocus>
                </div>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label fw-medium">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0" style="color: #cbd5e1;"><i class="bi bi-lock"></i></span>
                    <input type="password" class="form-control border-start-0 ps-0" id="password" name="password" placeholder="Enter your password" required>
                </div>
            </div>

            <div class="mb-4 d-flex justify-content-between align-items-center">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
            </div>

            <button type="submit" class="btn btn-theme-orange w-100 fs-6">Sign In</button>
        </form>
        
        <div class="text-center mt-4">
            <small class="text-muted">&copy; {{ date('Y') }} TriFusion POS. All rights reserved.</small>
        </div>
    </div>

</body>
</html>
