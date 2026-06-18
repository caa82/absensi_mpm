<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Sistem Absensi Anggota MPM Politeknik Astra</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.1/dist/sweetalert2.min.css">
    
    <style>
        :root {
            --primary-bg: #0f172a;
            --card-bg: rgba(30, 41, 59, 0.65);
            --border-color: rgba(255, 255, 255, 0.08);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --accent-blue: #3b82f6;
            --accent-gradient: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--primary-bg);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Abstract Background blobs */
        .blob-1 {
            position: absolute;
            width: 350px;
            height: 350px;
            border-radius: 50%;
            background: rgba(59, 130, 246, 0.2);
            filter: blur(80px);
            top: -100px;
            left: -100px;
            z-index: 1;
        }

        .blob-2 {
            position: absolute;
            width: 350px;
            height: 350px;
            border-radius: 50%;
            background: rgba(139, 92, 246, 0.15);
            filter: blur(80px);
            bottom: -100px;
            right: -100px;
            z-index: 1;
        }

        .login-container {
            width: 100%;
            max-width: 440px;
            padding: 20px;
            position: relative;
            z-index: 10;
        }

        .login-card {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        }

        .logo-box {
            width: 70px;
            height: 70px;
            background: var(--accent-gradient);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
        }

        .logo-box i {
            font-size: 2.2rem;
            color: white;
        }

        .brand-title {
            font-weight: 800;
            font-size: 1.5rem;
            background: var(--accent-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-align: center;
            margin-bottom: 0.25rem;
        }

        .brand-subtitle {
            color: var(--text-secondary);
            font-size: 0.85rem;
            text-align: center;
            margin-bottom: 2rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            font-weight: 600;
        }

        .form-label {
            color: var(--text-secondary);
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .form-control {
            background-color: rgba(15, 23, 42, 0.6);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            border-radius: 14px;
            padding: 0.75rem 1rem;
            transition: all 0.3s;
        }

        .form-control:focus {
            background-color: rgba(15, 23, 42, 0.8);
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
            color: var(--text-primary);
        }

        .btn-login {
            background: var(--accent-gradient);
            border: none;
            color: white;
            padding: 0.8rem;
            border-radius: 14px;
            font-weight: 700;
            width: 100%;
            transition: all 0.3s;
            margin-top: 1rem;
            box-shadow: 0 5px 15px rgba(59, 130, 246, 0.3);
        }

        .btn-login:hover {
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.5);
            transform: translateY(-1px);
        }

        .alert-custom {
            background-color: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #ef4444;
            border-radius: 14px;
            font-size: 0.85rem;
            padding: 0.75rem 1rem;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>

    <div class="blob-1"></div>
    <div class="blob-2"></div>

    <div class="login-container">
        <div class="login-card">
            
            <div class="logo-box">
                <i class="bi bi-person-check-fill"></i>
            </div>
            
            <h4 class="brand-title">SISTEM ABSENSI</h4>
            <div class="brand-subtitle">MPM Politeknik Astra</div>

            @if($errors->any())
                <div class="alert alert-custom">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" id="username" class="form-control" placeholder="Masukkan username Anda" value="{{ old('username') }}" required autofocus autocomplete="off">
                </div>
                
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <label for="password" class="form-label">Password</label>
                    </div>
                    <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
                </div>
                
                <button type="submit" class="btn btn-login">
                    Masuk Sekarang
                </button>
            </form>
            
        </div>
    </div>

    <!-- JS dependencies -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.1/dist/sweetalert2.all.min.js"></script>

    <script>
        $(document).ready(function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    background: '#1e293b',
                    color: '#f8fafc',
                    confirmButtonColor: '#3b82f6'
                });
            @endif
        });
    </script>
</body>
</html>
