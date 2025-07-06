<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied - Link Skiplock</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body class="access-denied-container">
    <div class="access-denied-card" data-aos="zoom-in">
        <div class="access-denied-icon">
            <svg fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
            </svg>
        </div>

        <h1 style="font-size: 2rem; font-weight: 700; color: var(--gray-900); margin-bottom: 1rem;">
            Access Denied
        </h1>

        @guest
            <p style="font-size: 1.125rem; color: var(--gray-600); margin-bottom: 2rem; line-height: 1.6;">
                You need to log in first to access this protected content.
            </p>

            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('google.login') }}" class="btn btn-danger">
                    <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                    </svg>
                    Sign in with Google
                </a>

                <a href="{{ url('/') }}" class="btn btn-secondary">
                    <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                    Home
                </a>
            </div>

        @else
            @php $status = auth()->user()->getYouTubeStatus() @endphp
            @if($status === 'no_token' || $status === 'no_permission')
                <p style="font-size: 1.125rem; color: var(--gray-600); margin-bottom: 2rem; line-height: 1.6;">
                    To access this protected content, we need to check your YouTube subscriptions. 
                    Please grant YouTube permission.
                </p>

                <div style="background: var(--info-color); background: linear-gradient(135deg, #e0f2fe 0%, #b3e5fc 100%); border-radius: var(--border-radius); padding: 1rem; margin-bottom: 2rem;">
                    <h4 style="font-weight: 600; color: var(--gray-900); margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                        <svg style="width: 1.25rem; height: 1.25rem;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        Why YouTube Permission Required?
                    </h4>
                    <p style="font-size: 0.875rem; color: var(--gray-700); line-height: 1.6;">
                        This protected link is designed for people subscribed to specific YouTube channels. 
                        We need YouTube access permission to check your subscription.
                    </p>
                </div>

                <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                    <a href="{{ route('google.reauth') }}" class="btn btn-danger">
                        <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                        </svg>
                        Grant YouTube Permission
                    </a>

                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                        <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                        </svg>
                        Dashboard
                    </a>
                </div>
            @else
                <p style="font-size: 1.125rem; color: var(--gray-600); margin-bottom: 2rem; line-height: 1.6;">
                    @if($status === 'no_subscriptions')
                        Sorry! To access this protected content, you need to subscribe to <strong>{{ $channel->channel_name }}</strong> YouTube channel.
                        <br><br>
                        <span style="color: var(--warning-color); font-weight: 500;">
                            Note: You have YouTube permission but you don't appear to be subscribed to any channels.
                        </span>
                    @else
                        Sorry! To access this protected content, you need to subscribe to <strong>{{ $channel->channel_name }}</strong> YouTube channel.
                    @endif
                </p>

                <div style="background: var(--warning-color); background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%); border-radius: var(--border-radius); padding: 1rem; margin-bottom: 2rem;">
                    <h4 style="font-weight: 600; color: var(--gray-900); margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                        <svg style="width: 1.25rem; height: 1.25rem;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        Access Restriction
                    </h4>
                    <p style="font-size: 0.875rem; color: var(--gray-700); line-height: 1.6;">
                        This content is only accessible to users subscribed to <strong>{{ $channel->channel_name }}</strong> YouTube channel.
                        @if($status === 'no_subscriptions')
                            <br><br><strong>Status:</strong> You have YouTube permission but are not subscribed to any channels.
                        @endif
                    </p>
                </div>

                <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                    <a href="https://www.youtube.com/channel/{{ $channel->channel_id }}?sub_confirmation=1" 
                       target="_blank" class="btn btn-danger">
                        <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                        </svg>
                        Subscribe to Channel
                    </a>

                    <form action="{{ route('auth.refresh-subscriptions') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                            </svg>
                            @if($status === 'no_subscriptions')
                                Check Subscriptions
                            @else
                                Refresh Subscriptions
                            @endif
                        </button>
                    </form>

                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                        <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                        </svg>
                        Dashboard
                    </a>
                </div>
            @endif
        @endguest
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 600,
            easing: 'ease-in-out'
        });
    </script>
</body>
</html> 