@extends('layouts.app')

@section('title', 'Link Düzenle - Link Skiplock')

@section('content')
<div class="page-header" data-aos="fade-down">
    <h1 class="page-title">Link Düzenle</h1>
    <p class="page-subtitle">Korumalı linkinizi düzenleyin.</p>
</div>

<div style="max-width: 600px; margin: 0 auto;" data-aos="fade-up">
    <div class="card">
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('links.update', $link) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="title" class="form-label">Link Başlığı</label>
                <input type="text" name="title" id="title" value="{{ old('title', $link->title) }}" required
                       class="form-input" placeholder="Link başlığınızı girin">
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Açıklama</label>
                <textarea name="description" id="description" rows="3" required
                          class="form-textarea" placeholder="Link hakkında kısa bir açıklama">{{ old('description', $link->description) }}</textarea>
            </div>

            <div class="form-group">
                <label for="target_url" class="form-label">Hedef URL</label>
                <input type="url" name="target_url" id="target_url" value="{{ old('target_url', $link->target_url) }}" required
                       class="form-input" placeholder="https://example.com">
                <p style="font-size: 0.75rem; color: var(--gray-500); margin-top: 0.25rem;">
                    Kullanıcıların yönlendirilmesi istediğiniz URL
                </p>
            </div>

            <div class="form-group">
                <label for="youtube_channel_id" class="form-label">YouTube Kanalı</label>
                <select name="youtube_channel_id" id="youtube_channel_id" required class="form-select">
                    @foreach($channels as $channel)
                        <option value="{{ $channel->id }}" {{ old('youtube_channel_id', $link->youtube_channel_id) == $channel->id ? 'selected' : '' }}>
                            {{ $channel->channel_name ?? 'Bilinmeyen Kanal' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="short_code" class="form-label">Kısa Kod</label>
                <input type="text" name="short_code" id="short_code" value="{{ old('short_code', $link->short_code) }}"
                       class="form-input" placeholder="ornek-kod" maxlength="50" required>
                <p style="font-size: 0.75rem; color: var(--gray-500); margin-top: 0.25rem;">
                    Sadece harf, rakam ve tire kullanın. Bu kod link URL'inde görünecek.
                </p>
            </div>

            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <input type="checkbox" name="is_active" id="is_active" value="1" 
                       {{ old('is_active', $link->is_active) ? 'checked' : '' }}
                       style="width: 1rem; height: 1rem;">
                <label for="is_active" style="font-size: 0.875rem; color: var(--gray-700);">
                    Link aktif olsun
                </label>
            </div>

            <div style="padding: 1rem; background: var(--gray-50); border-radius: var(--border-radius); border-left: 4px solid var(--info-color);">
                <h4 style="font-weight: 500; color: var(--gray-900); margin-bottom: 0.5rem;">Link İstatistikleri</h4>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; font-size: 0.875rem;">
                    <div>
                        <span style="color: var(--gray-600);">Toplam Tıklama:</span>
                        <span style="font-weight: 500; margin-left: 0.5rem;">{{ $link->click_count }}</span>
                    </div>
                    <div>
                        <span style="color: var(--gray-600);">Oluşturma:</span>
                        <span style="font-weight: 500; margin-left: 0.5rem;">{{ $link->created_at->format('d.m.Y') }}</span>
                    </div>
                    <div>
                        <span style="color: var(--gray-600);">Güncelleme:</span>
                        <span style="font-weight: 500; margin-left: 0.5rem;">{{ $link->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 1rem; padding-top: 1rem; border-top: 1px solid var(--gray-200);">
                <button type="submit" class="btn btn-primary">
                    <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 12.586V3a1 1 0 10-2 0v9.586L7.293 10.293z"/>
                    </svg>
                    Güncelle
                </button>
                
                <a href="{{ route('links.index') }}" class="btn btn-secondary">
                    <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Geri Dön
                </a>

                <a href="{{ $link->short_url }}" target="_blank" class="btn btn-success">
                    <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"/>
                        <path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z"/>
                    </svg>
                    Test Et
                </a>
            </div>
        </form>
    </div>
</div>
@endsection 