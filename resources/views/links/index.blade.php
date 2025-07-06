@extends('layouts.app')

@section('title', 'My Links - Link Skiplock')

@section('content')
<div class="page-header" data-aos="fade-down">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="page-title">My Links</h1>
            <p class="page-subtitle">Manage your protected links</p>
        </div>
        <a href="{{ route('links.create') }}" class="btn btn-primary">
            <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Create New Link
        </a>
    </div>
</div>

@if($links->count() > 0)
    <div class="card" data-aos="fade-up">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th class="hidden md:table-cell">Description</th>
                        <th class="hidden lg:table-cell">Short URL</th>
                        <th class="hidden sm:table-cell">Channel</th>
                        <th class="hidden md:table-cell">Clicks</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($links as $link)
                        <tr>
                            <td>
                                <div class="font-medium text-gray-900">{{ $link->title }}</div>
                                <div class="sm:hidden text-sm text-gray-500 mt-1">
                                    {{ Str::limit($link->description, 30) }}
                                </div>
                            </td>
                            <td class="hidden md:table-cell">
                                <span class="text-sm text-gray-600">{{ Str::limit($link->description, 50) }}</span>
                            </td>
                            <td class="hidden lg:table-cell">
                                <div class="flex items-center gap-2">
                                    <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $link->short_url }}</code>
                                    <button 
                                        onclick="copyToClipboard('{{ $link->short_url }}')" 
                                        class="btn-icon text-gray-400 hover:text-blue-600"
                                        title="Copy link"
                                    >
                                        <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"/>
                                            <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td class="hidden sm:table-cell">
                                <div class="flex items-center gap-2">
                                    @if($link->youtubeChannel && $link->youtubeChannel->channel_thumbnail)
                                        <img src="{{ $link->youtubeChannel->channel_thumbnail }}" 
                                             class="w-6 h-6 rounded-full" 
                                             alt="{{ $link->youtubeChannel->channel_name }}">
                                    @endif
                                    <span class="text-sm">{{ $link->youtubeChannel->channel_name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="hidden md:table-cell">
                                <span class="text-sm font-medium">{{ $link->click_count }}</span>
                            </td>
                            <td>
                                @if($link->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center gap-1">
                                    <form action="{{ route('links.toggle', $link) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button 
                                            type="submit" 
                                            class="btn-icon {{ $link->is_active ? 'text-orange-600 hover:text-orange-700' : 'text-green-600 hover:text-green-700' }}"
                                            title="{{ $link->is_active ? 'Deactivate' : 'Activate' }}"
                                        >
                                            @if($link->is_active)
                                                <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                                                </svg>
                                            @else
                                                <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                                                </svg>
                                            @endif
                                        </button>
                                    </form>

                                    <a href="{{ route('links.edit', $link) }}" 
                                       class="btn-icon text-blue-600 hover:text-blue-700"
                                       title="Edit">
                                        <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                        </svg>
                                    </a>

                                    <form action="{{ route('links.destroy', $link) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="submit" 
                                            class="btn-icon text-red-600 hover:text-red-700"
                                            title="Delete"
                                            onclick="return confirm('Are you sure you want to delete this link?')"
                                        >
                                            <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" clip-rule="evenodd"/>
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414L7.586 12l-1.293 1.293a1 1 0 101.414 1.414L9 13.414l2.293 2.293a1 1 0 001.414-1.414L11.414 12l1.293-1.293z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($links->hasPages())
            <div class="mt-6">
                {{ $links->links() }}
            </div>
        @endif
    </div>
@else
    <div class="empty-state" data-aos="zoom-in">
        <div class="empty-icon">
            <svg fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd" />
            </svg>
        </div>
        <h3 class="empty-title">No Protected Links</h3>
        <p class="empty-subtitle">You haven't created any protected links yet. Create your first link to get started!</p>
        <a href="{{ route('links.create') }}" class="btn btn-primary">Create Your First Link</a>
    </div>
@endif

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        showToast('Link copied to clipboard!', 'success');
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
        showToast('Failed to copy link', 'error');
    });
}

function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 px-4 py-2 rounded-lg text-white z-50 ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 'bg-blue-500'
    }`;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 2000);
}
</script>
@endsection 