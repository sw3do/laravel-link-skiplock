<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Link Skiplock</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body class="auth-container">
    <div class="auth-wrapper">
        <div class="auth-card" data-aos="zoom-in">
            <div class="text-center mb-8">
                <h1 class="auth-title">Link Skiplock</h1>
                <p class="auth-subtitle">Sign in to your account</p>
            </div>

            @if (session('error'))
                <div class="alert alert-error" data-aos="fade-down">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success" data-aos="fade-down">
                    {{ session('success') }}
                </div>
            @endif

            <div class="space-y-6">
                <div class="google-auth">
                    <a href="{{ route('google.login') }}" class="btn btn-google btn-lg w-full">
                        <svg class="icon" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                        </svg>
                        Continue with Google
                    </a>
                </div>

                <div class="divider">
                    <span>or sign in with email</span>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input 
                            id="email" 
                            name="email" 
                            type="email" 
                            autocomplete="email" 
                            required 
                            class="form-input @error('email') error @enderror"
                            value="{{ old('email') }}"
                        >
                        @error('email')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            autocomplete="current-password" 
                            required 
                            class="form-input @error('password') error @enderror"
                        >
                        @error('password')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-lg w-full">
                            Sign In
                        </button>
                    </div>
                </form>

                <div class="auth-footer">
                    <p>Don't have an account? <a href="#" onclick="showRegisterForm()">Sign up</a></p>
                </div>
            </div>
        </div>

        <div class="auth-card register-card" id="registerCard" data-aos="zoom-in" style="display: none;">
            <div class="text-center mb-8">
                <h1 class="auth-title">Create Account</h1>
                <p class="auth-subtitle">Sign up for a new account</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <div class="form-group">
                    <label for="register_name" class="form-label">Full Name</label>
                    <input 
                        id="register_name" 
                        name="name" 
                        type="text" 
                        autocomplete="name" 
                        required 
                        class="form-input @error('name') error @enderror"
                        value="{{ old('name') }}"
                    >
                    @error('name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="register_email" class="form-label">Email Address</label>
                    <input 
                        id="register_email" 
                        name="email" 
                        type="email" 
                        autocomplete="email" 
                        required 
                        class="form-input @error('email') error @enderror"
                        value="{{ old('email') }}"
                    >
                    @error('email')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="register_password" class="form-label">Password</label>
                    <input 
                        id="register_password" 
                        name="password" 
                        type="password" 
                        autocomplete="new-password" 
                        required 
                        class="form-input @error('password') error @enderror"
                    >
                    @error('password')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        type="password" 
                        autocomplete="new-password" 
                        required 
                        class="form-input"
                    >
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg w-full">
                        Create Account
                    </button>
                </div>
            </form>

            <div class="auth-footer">
                <p>Already have an account? <a href="#" onclick="showLoginForm()">Sign in</a></p>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 600,
            easing: 'ease-in-out'
        });

        function showRegisterForm() {
            document.querySelector('.auth-card:first-child').style.display = 'none';
            document.getElementById('registerCard').style.display = 'block';
        }

        function showLoginForm() {
            document.querySelector('.auth-card:first-child').style.display = 'block';
            document.getElementById('registerCard').style.display = 'none';
        }
    </script>
</body>
</html> 