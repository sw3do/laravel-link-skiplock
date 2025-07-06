<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProtectedLink;
use App\Models\YouTubeChannel;

class DashboardController extends Controller
{
    public function index()
    {
        $totalLinks = auth()->user()->protectedLinks()->count();
        $activeLinks = auth()->user()->protectedLinks()->where('is_active', true)->count();
        $totalClicks = auth()->user()->protectedLinks()->sum('click_count');
        $totalChannels = auth()->user()->youtubeChannels()->count();

        $recentLinks = auth()->user()->protectedLinks()
            ->with('youtubeChannel')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact('totalLinks', 'activeLinks', 'totalClicks', 'totalChannels', 'recentLinks'));
    }
}
