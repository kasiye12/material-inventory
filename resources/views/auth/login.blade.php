<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TNT Material & Inventory System</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1e293b 0%, #334155 50%, #1e3a8a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        body::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: radial-gradient(circle at 20% 80%, rgba(251,191,36,0.1) 0%, transparent 50%),
                        radial-gradient(circle at 80% 20%, rgba(59,130,246,0.1) 0%, transparent 50%);
        }
        .login-container { width: 100%; max-width: 450px; padding: 20px; position: relative; z-index: 1; }
        .login-card { background: rgba(255,255,255,0.98); border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); overflow: hidden; }
        .login-header { background: linear-gradient(135deg, #1e3a8a 0%, #1e293b 100%); padding: 30px; text-align: center; position: relative; }
        .login-header::after { content: ''; position: absolute; bottom: -2px; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #fbbf24, #f59e0b, #fbbf24); }
        .logo-container { width: 80px; height: 80px; margin: 0 auto 15px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid #fbbf24; }
        .logo-container img { max-width: 60px; max-height: 60px; border-radius: 50%; }
        .logo-text { font-size: 28px; font-weight: 800; color: #fff; letter-spacing: 2px; }
        .logo-subtext { font-size: 11px; color: #fbbf24; letter-spacing: 1px; text-transform: uppercase; }
        .company-amharic { font-size: 12px; color: #e2e8f0; margin-top: 8px; }
        .login-body { padding: 30px; }
        .login-title { font-size: 20px; font-weight: 700; color: #1e293b; text-align: center; margin-bottom: 3px; }
        .login-subtitle { font-size: 12px; color: #64748b; text-align: center; margin-bottom: 25px; }
        .form-group { margin-bottom: 18px; }
        .form-label { font-weight: 600; font-size: 13px; color: #334155; margin-bottom: 6px; }
        .input-group { position: relative; }
        .input-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #64748b; z-index: 5; }
        .form-control { width: 100%; padding: 12px 15px 12px 40px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 14px; background: #f8fafc; transition: all 0.3s; }
        .form-control:focus { border-color: #1e3a8a; box-shadow: 0 0 0 4px rgba(30,58,138,0.1); background: #fff; outline: none; }
        .btn-login { width: 100%; padding: 13px; background: linear-gradient(135deg, #1e3a8a, #2563eb); color: #fff; border: none; border-radius: 10px; font-size: 15px; font-weight: 600; cursor: pointer; transition: all 0.3s; }
        .btn-login:hover { background: linear-gradient(135deg, #1e40af, #1d4ed8); box-shadow: 0 10px 30px rgba(30,58,138,0.4); transform: translateY(-2px); }
        .alert { border-radius: 10px; padding: 12px 15px; font-size: 13px; margin-bottom: 20px; }
        .alert-danger { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; }
        .demo-box { margin-top: 20px; padding: 15px; background: #f8fafc; border-radius: 10px; border: 1px dashed #e2e8f0; }
        .demo-box h6 { font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 8px; text-align: center; text-transform: uppercase; }
        .demo-item { display: flex; justify-content: space-between; padding: 4px 0; font-size: 11px; color: #64748b; }
        .demo-item .role { font-weight: 600; color: #334155; }
        .footer-text { text-align: center; margin-top: 20px; font-size: 10px; color: #94a3b8; }
        @media (max-width: 480px) { .login-container { padding: 10px; } .login-header { padding: 20px; } .login-body { padding: 20px; } }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="logo-container">
                    <img src="{{ asset('images/company-logo.png') }}" alt="TNT Logo">
                </div>
                <div class="logo-text">TNT</div>
                <div class="logo-subtext">Construction & Trading</div>
                <div class="company-amharic">ቲ. ኤን. ቲ. ኮንስትራክሽንና ንግድ ሥራዎች</div>
            </div>
            <div class="login-body">
                <h4 class="login-title">Welcome Back</h4>
                <p class="login-subtitle">Material & Inventory Management System</p>
                
                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ $errors->first() }}
                    </div>
                @endif
                
                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-icon"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control" name="email" placeholder="Enter your email" value="{{ old('email') }}" required autofocus>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-icon"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" name="password" placeholder="Enter your password" required>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label" for="remember" style="font-size: 13px; color: #64748b;">Remember me</label>
                        </div>
                    </div>
                    <button type="submit" class="btn-login" id="loginBtn">
                        <i class="fas fa-sign-in-alt me-2"></i> Sign In
                    </button>
                </form>
                
                <div class="demo-box">
                    <h6>Demo Accounts</h6>
                    <div class="demo-item"><span class="role">👑 Admin</span><span>admin@mims.com</span></div>
                    <div class="demo-item"><span class="role">🏢 Head Office</span><span>headoffice@mims.com</span></div>
                    <div class="demo-item"><span class="role">📦 Storekeeper</span><span>storekeeper@mims.com</span></div>
                </div>
                
                <p class="footer-text">&copy; {{ date('Y') }} TNT Construction & Trading. All rights reserved.</p>
            </div>
        </div>
    </div>
    
    <script>
        document.getElementById('loginForm').addEventListener('submit', function() {
            var btn = document.getElementById('loginBtn');
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Signing In...';
            btn.disabled = true;
        });
    </script>
</body>
</html>
