<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Link Skiplock')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-brand">
                <a href="{{ route('dashboard') }}">
                    <h1>Link Skiplock</h1>
                </a>
            </div>

            <div class="nav-toggle" id="navToggle">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <div class="nav-menu" id="navMenu">
                <div class="nav-links">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg class="icon" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                        </svg>
                        Dashboard
                    </a>
                    
                    <a href="{{ route('links.index') }}" class="nav-link {{ request()->routeIs('links.*') ? 'active' : '' }}">
                        <svg class="icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd" />
                        </svg>
                        Links
                    </a>
                    
                    <a href="{{ route('channels.index') }}" class="nav-link {{ request()->routeIs('channels.*') ? 'active' : '' }}">
                        <svg class="icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                        </svg>
                        Channels
                    </a>
                </div>

                <div class="nav-user">
                    <div class="dropdown">
                        <button class="dropdown-btn">
                            <svg class="icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                            <span>{{ auth()->user()->name }}</span>
                            <svg class="icon-sm" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div class="dropdown-menu">
                            <a href="{{ route('dashboard') }}" class="dropdown-item">
                                <svg class="icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                                </svg>
                                Dashboard
                            </a>
                            <form action="{{ route('logout') }}" method="POST" class="dropdown-item-form">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <svg class="icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="main-content">
        @if (session('success'))
            <div class="alert alert-success" data-aos="fade-down">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error" data-aos="fade-down">
                {{ session('error') }}
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning" data-aos="fade-down">
                {{ session('warning') }}
            </div>
        @endif

        @yield('content')
    </main>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 600,
            easing: 'ease-in-out'
        });

        const navToggle = document.getElementById('navToggle');
        const navMenu = document.getElementById('navMenu');

        navToggle.addEventListener('click', () => {
            navMenu.classList.toggle('active');
        });

        document.addEventListener('click', (e) => {
            if (!navToggle.contains(e.target) && !navMenu.contains(e.target)) {
                navMenu.classList.remove('active');
            }
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) {
                navMenu.classList.remove('active');
            }
        });

        document.querySelectorAll('.dropdown-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const dropdown = btn.parentElement;
                dropdown.classList.toggle('active');
            });
        });

        document.addEventListener('click', (e) => {
            document.querySelectorAll('.dropdown').forEach(dropdown => {
                if (!dropdown.contains(e.target)) {
                    dropdown.classList.remove('active');
                }
            });
        });
    </script>
</body>
</html> 