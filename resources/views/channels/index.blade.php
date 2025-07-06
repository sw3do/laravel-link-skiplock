@extends('layouts.app')

@section('title', 'YouTube Kanalları - Link Skiplock')

@section('content')
<div class="page-header" data-aos="fade-down">
    <h1 class="page-title">YouTube Kanalları</h1>
    <p class="page-subtitle">YouTube kanallarınızı ekleyin ve korumalı linkler oluşturun.</p>
</div>

<div class="grid grid-1 gap-6 max-w-4xl mx-auto" data-aos="fade-up">
    <div class="card">
        <h2 class="mb-6">Yeni Kanal Ekle</h2>
        
        <form method="POST" action="{{ route('channels.store') }}" class="space-y-6">
            @csrf
            <div class="form-group">
                <label for="channel_url" class="form-label">YouTube Kanal URL'si</label>
                <input type="url" name="channel_url" id="channel_url" value="{{ old('channel_url') }}" required
                       class="form-input" placeholder="https://www.youtube.com/@kanaladi">
                <p class="text-xs text-gray-500 mt-1">
                    Örnek: https://www.youtube.com/@kanaladi veya https://www.youtube.com/channel/UC...
                </p>
            </div>

            <div>
                <button type="submit" class="btn btn-primary w-full sm:w-auto">
                    <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Kanal Ekle
                </button>
            </div>
        </form>
    </div>

    <div class="card">
        <h2 class="mb-6">Eklenen Kanallar</h2>

        @if($channels->count() > 0)
            <div class="space-y-4">
                @foreach($channels as $channel)
                    <div class="channel-card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        @if($channel->channel_thumbnail)
                            <img src="{{ $channel->channel_thumbnail }}" alt="{{ $channel->channel_name }}" class="channel-avatar">
                        @else
                            <div class="channel-placeholder">
                                <svg class="icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        @endif

                        <div class="flex-1">
                            <h4 style="font-weight: 500; margin-bottom: 0.25rem;">{{ $channel->channel_name ?? 'Bilinmeyen Kanal' }}</h4>
                            <a href="{{ $channel->channel_url }}" target="_blank" 
                               class="text-sm text-gray-600 underline hover:text-gray-800 transition-colors">
                                Kanalı Görüntüle
                            </a>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $channel->protectedLinks->count() }} korumalı link
                            </p>
                        </div>

                        <div class="channel-actions">
                            <a href="{{ route('links.create', ['channel' => $channel->id]) }}" 
                               class="action-btn primary" title="Link Oluştur">
                                <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd" />
                                </svg>
                            </a>

                            <form method="POST" action="{{ route('channels.destroy', $channel) }}" style="display: inline;"
                                  onsubmit="return confirm('Bu kanalı silmek istediğinizden emin misiniz? Bu kanala ait tüm linkler de silinecek.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn danger" title="Kanalı Sil">
                                    <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" clip-rule="evenodd" />
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($channels->hasPages())
                <div class="mt-8">
                    {{ $channels->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                    </svg>
                </div>
                <h3 class="empty-title">Henüz Kanal Yok</h3>
                <p class="empty-subtitle">İlk YouTube kanalınızı ekleyin ve korumalı linkler oluşturmaya başlayın!</p>
            </div>
        @endif
    </div>
</div>

@if(session('channel_info'))
<div class="card max-w-2xl mx-auto mt-4" data-aos="fade-up">
    <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg">
        @if(session('channel_info')['thumbnail'])
            <img src="{{ session('channel_info')['thumbnail'] }}" alt="{{ session('channel_info')['name'] }}" 
                 class="w-12 h-12 rounded-full object-cover">
        @endif
        <div>
            <h4 class="font-medium mb-1">{{ session('channel_info')['name'] }}</h4>
            <p class="text-sm text-gray-600">Kanal başarıyla eklendi!</p>
        </div>
    </div>
</div>
@endif
@endsection 