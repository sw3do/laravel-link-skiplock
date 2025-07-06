@extends('layouts.app')

@section('title', 'Dashboard - Link Skiplock')

@section('content')
<div class="page-header" data-aos="fade-down">
    <h1 class="page-title">Dashboard</h1>
    <p class="page-subtitle">Welcome {{ auth()->user()->name }}! View your statistics and links.</p>
</div>

<div class="grid grid-2 md:grid-4 gap-4 mb-8" data-aos="fade-up">
    <div class="stat-card">
        <div class="stat-icon primary">
            <svg class="icon-lg" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="stat-content">
            <h3>{{ $totalLinks }}</h3>
            <p>Total Links</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon success">
            <svg class="icon-lg" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="stat-content">
            <h3>{{ $activeLinks }}</h3>
            <p>Active Links</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon warning">
            <svg class="icon-lg" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="stat-content">
            <h3>{{ $totalClicks }}</h3>
            <p>Total Clicks</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon danger">
            <svg class="icon-lg" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="stat-content">
            <h3>{{ $totalChannels }}</h3>
            <p>YouTube Channels</p>
        </div>
    </div>
</div>

<div class="grid grid-1 lg:grid-2 gap-6" data-aos="fade-up" data-aos-delay="200">
    <div class="card">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <h2>Recent Links</h2>
            <a href="{{ route('links.index') }}" class="btn btn-sm btn-primary">View All</a>
        </div>

        @if($recentLinks->count() > 0)
            <div class="space-y-4">
                @foreach($recentLinks as $link)
                    <div class="border-b border-gray-200 pb-4 last:border-b-0 last:pb-0">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="flex-1">
                                <h4 style="font-weight: 500; margin-bottom: 0.25rem;">{{ $link->title }}</h4>
                                <p style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 0.5rem;">
                                    {{ Str::limit($link->description, 60) }}
                                </p>
                                <div class="flex items-center gap-4 text-xs text-gray-500">
                                    <span>{{ $link->click_count }} clicks</span>
                                    <span>{{ $link->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                @if($link->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-danger">Inactive</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd" />
                    </svg>
                </div>
                <h3 class="empty-title">No Links Yet</h3>
                <p class="empty-subtitle">Create your first protected link!</p>
                <a href="{{ route('links.create') }}" class="btn btn-primary">Create Link</a>
            </div>
        @endif
    </div>

    <div class="card">
        <h2 class="mb-6">Quick Actions</h2>

        <div class="space-y-4">
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-3">
                    <h4 class="font-medium flex items-center gap-2">
                        <svg class="icon" fill="currentColor" viewBox="0 0 20 20" style="color: #ff0000;">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                        </svg>
                        YouTube Subscriptions
                    </h4>
                    @php $status = auth()->user()->getYouTubeStatus() @endphp
                    @if($status === 'active')
                        <span class="badge badge-success">Connected</span>
                    @elseif($status === 'no_subscriptions')
                        <span class="badge badge-warning">Not Subscribed</span>
                    @else
                        <span class="badge badge-danger">Permission Required</span>
                    @endif
                </div>
                
                <div class="text-sm text-gray-600 mb-3">
                    @if($status === 'no_token')
                        Google account not connected
                    @elseif($status === 'no_permission')
                        YouTube permission not granted
                    @elseif($status === 'no_subscriptions')
                        You are not subscribed to any channels
                    @elseif($status === 'active')
                        Following {{ count(auth()->user()->youtube_subscriptions) }} channels
                    @endif
                    
                    @if(auth()->user()->subscriptions_updated_at)
                        <br>Last updated: {{ auth()->user()->subscriptions_updated_at->diffForHumans() }}
                    @endif
                </div>

                <div class="flex flex-col sm:flex-row gap-2">
                    @if($status === 'active')
                        <form action="{{ route('auth.refresh-subscriptions') }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-primary w-full sm:w-auto">
                                <svg class="icon mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                                </svg>
                                Refresh
                            </button>
                        </form>
                    @elseif($status === 'no_subscriptions')
                        <form action="{{ route('auth.refresh-subscriptions') }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-primary w-full sm:w-auto">
                                <svg class="icon mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                                </svg>
                                Check Subscriptions
                            </button>
                        </form>
                    @else
                        <a href="{{ route('google.reauth') }}" class="btn btn-sm btn-danger w-full sm:w-auto">
                            <svg class="icon mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                            </svg>
                            Grant YouTube Permission
                        </a>
                    @endif
                </div>
            </div>

            <a href="{{ route('links.create') }}" class="quick-action primary">
                <div class="action-icon primary">
                    <svg class="icon" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    <h4 style="font-weight: 500; margin-bottom: 0.25rem;">Create New Link</h4>
                    <p style="font-size: 0.875rem; margin: 0; opacity: 0.8;">Add a protected link</p>
                </div>
            </a>

            <a href="{{ route('channels.index') }}" class="quick-action danger">
                <div class="action-icon danger">
                    <svg class="icon" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    <h4 style="font-weight: 500; margin-bottom: 0.25rem;">Add YouTube Channel</h4>
                    <p style="font-size: 0.875rem; margin: 0; opacity: 0.8;">Connect a new channel</p>
                </div>
            </a>

            <a href="{{ route('links.index') }}" class="quick-action success">
                <div class="action-icon success">
                    <svg class="icon" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    <h4 style="font-weight: 500; margin-bottom: 0.25rem;">Manage My Links</h4>
                    <p style="font-size: 0.875rem; margin: 0; opacity: 0.8;">Edit and track</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection 