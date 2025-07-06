<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\YouTubeChannel;
use Google\Client;
use Google\Service\YouTube;

class YouTubeController extends Controller
{
    public function index()
    {
        $channels = auth()->user()->youtubeChannels()->paginate(10);
        return view('channels.index', compact('channels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'channel_url' => 'required|url',
        ]);

        try {
            $channelData = $this->getChannelDataFromUrl($request->channel_url);
            
            if (!$channelData) {
                return back()->with('error', 'YouTube channel not found. Please check the URL.');
            }

            $existingChannel = YouTubeChannel::where('channel_id', $channelData['id'])
                ->where('user_id', auth()->id())
                ->first();
            
            if ($existingChannel) {
                return back()->with('error', 'This channel has already been added.');
            }

            $channel = YouTubeChannel::create([
                'channel_id' => $channelData['id'],
                'channel_name' => $channelData['title'],
                'channel_url' => $request->channel_url,
                'channel_thumbnail' => $channelData['thumbnail'],
                'user_id' => auth()->id(),
            ]);

            return back()
                ->with('success', 'YouTube channel added successfully!')
                ->with('channel_info', [
                    'name' => $channelData['title'],
                    'thumbnail' => $channelData['thumbnail']
                ]);

        } catch (\Exception $e) {
            \Log::error('YouTube API Error: ' . $e->getMessage());
            return back()->with('error', 'YouTube API error: ' . $e->getMessage());
        }
    }

    public function destroy(YouTubeChannel $channel)
    {
        if ($channel->user_id !== auth()->id()) {
            abort(403);
        }

        $channel->protectedLinks()->delete();
        $channel->delete();

        return back()->with('success', 'Channel and related links have been deleted.');
    }

    private function getChannelDataFromUrl($url)
    {
        $apiKey = config('services.youtube.api_key');
        
        if (!$apiKey) {
            throw new \Exception('YouTube API key is not configured.');
        }

        $client = new Client();
        $client->setDeveloperKey($apiKey);
        $youtube = new YouTube($client);

        $channelIdentifier = $this->extractChannelIdentifier($url);
        
        if (!$channelIdentifier) {
            return null;
        }

        if ($channelIdentifier['type'] === 'id') {
            $response = $youtube->channels->listChannels('snippet', [
                'id' => $channelIdentifier['value'],
            ]);

            if (!empty($response->getItems())) {
                return $this->formatChannelData($response->getItems()[0]);
            }
        }

        if ($channelIdentifier['type'] === 'username') {
            $response = $youtube->channels->listChannels('snippet', [
                'forUsername' => $channelIdentifier['value'],
            ]);

            if (!empty($response->getItems())) {
                return $this->formatChannelData($response->getItems()[0]);
            }
        }

        if ($channelIdentifier['type'] === 'handle') {
            $searchResponse = $youtube->search->listSearch('snippet', [
                'q' => '@' . $channelIdentifier['value'],
                'type' => 'channel',
                'maxResults' => 1,
            ]);

            if (!empty($searchResponse->getItems())) {
                $channelId = $searchResponse->getItems()[0]->getSnippet()->getChannelId();
                
                $response = $youtube->channels->listChannels('snippet', [
                    'id' => $channelId,
                ]);

                if (!empty($response->getItems())) {
                    return $this->formatChannelData($response->getItems()[0]);
                }
            }
        }

        return null;
    }

    private function extractChannelIdentifier($url)
    {
        $patterns = [
            '/youtube\.com\/channel\/([a-zA-Z0-9_-]+)/' => 'id',
            '/youtube\.com\/c\/([a-zA-Z0-9_-]+)/' => 'username',
            '/youtube\.com\/user\/([a-zA-Z0-9_-]+)/' => 'username',
            '/youtube\.com\/@([a-zA-Z0-9_-]+)/' => 'handle',
        ];

        foreach ($patterns as $pattern => $type) {
            if (preg_match($pattern, $url, $matches)) {
                return [
                    'type' => $type,
                    'value' => $matches[1]
                ];
            }
        }

        return null;
    }

    private function formatChannelData($channel)
    {
        $snippet = $channel->getSnippet();
        $thumbnails = $snippet->getThumbnails();
        
        return [
            'id' => $channel->getId(),
            'title' => $snippet->getTitle(),
            'thumbnail' => $thumbnails ? ($thumbnails->getMedium() ? $thumbnails->getMedium()->getUrl() : $thumbnails->getDefault()->getUrl()) : null,
        ];
    }
}
