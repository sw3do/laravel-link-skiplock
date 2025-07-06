<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProtectedLink;
use App\Models\YouTubeChannel;
use Google\Client;
use Google\Service\YouTube;

class LinkController extends Controller
{
    public function index()
    {
        $links = auth()->user()->protectedLinks()
            ->with('youtubeChannel')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('links.index', compact('links'));
    }

    public function create()
    {
        $channels = auth()->user()->youtubeChannels()->get();
        return view('links.create', compact('channels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'target_url' => 'required|url',
            'youtube_channel_id' => 'required|exists:youtube_channels,id',
            'short_code' => 'nullable|string|max:50|unique:protected_links,short_code',
            'is_active' => 'boolean',
        ]);

        $channel = YouTubeChannel::findOrFail($request->youtube_channel_id);
        
        if ($channel->user_id !== auth()->id()) {
            abort(403, 'This channel does not belong to you.');
        }

        $linkData = [
            'title' => $request->title,
            'description' => $request->description,
            'target_url' => $request->target_url,
            'youtube_channel_id' => $request->youtube_channel_id,
            'user_id' => auth()->id(),
            'is_active' => $request->has('is_active'),
        ];

        if ($request->short_code) {
            $linkData['short_code'] = $request->short_code;
        }

        ProtectedLink::create($linkData);

        return redirect()->route('links.index')->with('success', 'Protected link created successfully!');
    }

    public function edit(ProtectedLink $link)
    {
        if ($link->user_id !== auth()->id()) {
            abort(403);
        }

        $channels = auth()->user()->youtubeChannels()->get();
        return view('links.edit', compact('link', 'channels'));
    }

    public function update(Request $request, ProtectedLink $link)
    {
        if ($link->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'target_url' => 'required|url',
            'youtube_channel_id' => 'required|exists:youtube_channels,id',
            'short_code' => 'required|string|max:50|unique:protected_links,short_code,' . $link->id,
            'is_active' => 'boolean',
        ]);

        $channel = YouTubeChannel::findOrFail($request->youtube_channel_id);
        
        if ($channel->user_id !== auth()->id()) {
            abort(403, 'This channel does not belong to you.');
        }

        $link->update([
            'title' => $request->title,
            'description' => $request->description,
            'target_url' => $request->target_url,
            'youtube_channel_id' => $request->youtube_channel_id,
            'short_code' => $request->short_code,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('links.index')->with('success', 'Link updated successfully!');
    }

    public function destroy(ProtectedLink $link)
    {
        if ($link->user_id !== auth()->id()) {
            abort(403);
        }

        $link->delete();

        return back()->with('success', 'Link deleted successfully!');
    }

    public function toggle(ProtectedLink $link)
    {
        if ($link->user_id !== auth()->id()) {
            abort(403);
        }

        $link->update(['is_active' => !$link->is_active]);

        $status = $link->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Link has been {$status}!");
    }

    public function redirect($code)
    {
        $link = ProtectedLink::where('short_code', $code)
            ->where('is_active', true)
            ->with('youtubeChannel')
            ->firstOrFail();
        
        if (!auth()->check()) {
            session()->put('url.intended', request()->fullUrl());
            return view('access-denied', [
                'channel' => $link->youtubeChannel,
                'link' => $link
            ]);
        }
        
        if (!$this->checkYouTubeSubscription($link)) {
            return view('access-denied', [
                'channel' => $link->youtubeChannel,
                'link' => $link
            ]);
        }

        $link->increment('click_count');
        return redirect($link->target_url);
    }

    private function checkYouTubeSubscription(ProtectedLink $link)
    {
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }

        if ($user->areSubscriptionsStale() && $user->google_access_token) {
            $this->updateUserSubscriptions($user);
        }

        return $user->isSubscribedToChannel($link->youtubeChannel->channel_id);
    }

    private function updateUserSubscriptions($user)
    {
        try {
            $client = new Client();
            $client->setAccessToken($user->google_access_token);
            
            if ($client->isAccessTokenExpired()) {
                if ($user->google_refresh_token) {
                    $client->refreshToken($user->google_refresh_token);
                    $newToken = $client->getAccessToken();
                    $user->update(['google_access_token' => $newToken['access_token']]);
                } else {
                    return false;
                }
            }
            
            $youtube = new YouTube($client);
            
            $subscriptions = [];
            $nextPageToken = '';
            
            do {
                $response = $youtube->subscriptions->listSubscriptions('snippet', [
                    'mine' => true,
                    'maxResults' => 200,
                    'pageToken' => $nextPageToken,
                ]);

                foreach ($response->getItems() as $subscription) {
                    $channelId = $subscription->getSnippet()->getResourceId()->getChannelId();
                    $subscriptions[] = $channelId;
                }

                $nextPageToken = $response->getNextPageToken();
                
            } while ($nextPageToken);

            $user->update([
                'youtube_subscriptions' => $subscriptions,
                'subscriptions_updated_at' => now(),
            ]);

            \Log::info("User {$user->id} subscriptions updated: " . count($subscriptions) . " channels");
            
            return true;
            
        } catch (\Exception $e) {
            \Log::error('YouTube subscriptions update error: ' . $e->getMessage());
            return false;
        }
    }
}
