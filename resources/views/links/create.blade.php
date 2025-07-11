@extends('layouts.app')

@section('title', 'Create New Link - Link Skiplock')

@section('content')
<div class="page-header" data-aos="fade-down">
    <h1 class="page-title">Create New Link</h1>
    <p class="page-subtitle">Create a new YouTube subscriber protected link.</p>
</div>

<div class="w-full max-w-2xl mx-auto" data-aos="fade-up">
    <div class="card">
        <form method="POST" action="{{ route('links.store') }}" class="space-y-6">
            @csrf
            
            <div class="form-group">
                <label for="title" class="form-label">Link Title</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                       class="form-input" placeholder="Enter your link title">
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" rows="3" required
                          class="form-textarea" placeholder="A brief description about the link">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label for="target_url" class="form-label">Target URL</label>
                <input type="url" name="target_url" id="target_url" value="{{ old('target_url') }}" required
                       class="form-input" placeholder="https://example.com">
                <p class="text-xs text-gray-500 mt-1">
                    The URL where users will be redirected
                </p>
            </div>

            <div class="form-group">
                <label for="youtube_channel_id" class="form-label">YouTube Channel</label>
                <select name="youtube_channel_id" id="youtube_channel_id" required class="form-select">
                    <option value="">Select channel...</option>
                    @foreach($channels as $channel)
                        <option value="{{ $channel->id }}" {{ old('youtube_channel_id') == $channel->id || request('channel') == $channel->id ? 'selected' : '' }}>
                            {{ $channel->channel_name ?? 'Unknown Channel' }}
                        </option>
                    @endforeach
                </select>
                @if($channels->count() == 0)
                    <p class="text-xs text-yellow-600 mt-1">
                        You need to add a YouTube channel first. 
                        <a href="{{ route('channels.index') }}" class="text-blue-600 underline">
                            Add channel
                        </a>
                    </p>
                @endif
            </div>

            <div class="form-group">
                <label for="short_code" class="form-label">Short Code (Optional)</label>
                <input type="text" name="short_code" id="short_code" value="{{ old('short_code') }}"
                       class="form-input" placeholder="example-code" maxlength="50">
                <p class="text-xs text-gray-500 mt-1">
                    If left empty, it will be generated automatically. Use only letters, numbers and hyphens.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" id="is_active" value="1" 
                       {{ old('is_active', true) ? 'checked' : '' }}
                       class="w-4 h-4">
                <label for="is_active" class="text-sm text-gray-700">
                    Keep link active
                </label>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200">
                <button type="submit" class="btn btn-primary flex-1 sm:flex-none">
                    <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Create Link
                </button>
                
                <a href="{{ route('links.index') }}" class="btn btn-secondary flex-1 sm:flex-none">
                    <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Go Back
                </a>
            </div>
        </form>
    </div>
</div>
@endsection 