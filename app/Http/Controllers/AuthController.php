<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Google\Client;
use Google\Service\YouTube;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect()->intended(route('dashboard'));
    }

    public function doLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->scopes(['https://www.googleapis.com/auth/youtube.readonly'])
            ->with([
                'access_type' => 'offline',
                'approval_prompt' => 'force'
            ])
            ->redirect();
    }

    public function redirectToGoogleWithReturn()
    {
        session()->put('url.intended', url()->previous());
        
        return Socialite::driver('google')
            ->scopes(['https://www.googleapis.com/auth/youtube.readonly'])
            ->with([
                'access_type' => 'offline',
                'approval_prompt' => 'force'
            ])
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            $user = User::where('email', $googleUser->getEmail())->first();
            
            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => Hash::make(uniqid()),
                ]);
            }

            $user->update([
                'google_access_token' => $googleUser->token,
                'google_refresh_token' => $googleUser->refreshToken,
            ]);

            $this->updateUserSubscriptions($user, $googleUser->token);
            
            Auth::login($user);
            
            return redirect()->intended(route('dashboard'))->with('success', 'Successfully logged in with Google!');
            
        } catch (\Exception $e) {
            \Log::error('Google login error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Google login failed: ' . $e->getMessage());
        }
    }

    private function updateUserSubscriptions(User $user, $accessToken)
    {
        try {
            $client = new Client();
            $client->setApplicationName('Laravel Link Skiplock');
            $client->setDeveloperKey(config('services.youtube.api_key'));
            $client->setAccessToken($accessToken);
            
            $youtube = new YouTube($client);
            
            $subscriptions = [];
            $nextPageToken = '';
            
            do {
                $response = $youtube->subscriptions->listSubscriptions('snippet', [
                    'mine' => true,
                    'maxResults' => 200,
                    'pageToken' => $nextPageToken,
                ]);

                $items = $response->getItems();

                foreach ($items as $subscription) {
                    $channelId = $subscription->getSnippet()->getResourceId()->getChannelId();
                    $subscriptions[] = $channelId;
                }

                $nextPageToken = $response->getNextPageToken();
                
            } while ($nextPageToken);

            $user->update([
                'youtube_subscriptions' => $subscriptions,
                'subscriptions_updated_at' => now(),
            ]);

            \Log::info("User {$user->id} subscriptions updated successfully: " . count($subscriptions) . " channels");
            
        } catch (\Google\Service\Exception $e) {
            \Log::error('YouTube API Error: ' . $e->getMessage());
            
            $user->update([
                'youtube_subscriptions' => [],
                'subscriptions_updated_at' => now(),
            ]);
            
        } catch (\Exception $e) {
            \Log::error('YouTube subscriptions update error: ' . $e->getMessage());
            
            $user->update([
                'youtube_subscriptions' => [],
                'subscriptions_updated_at' => now(),
            ]);
        }
    }

    public function refreshSubscriptions()
    {
        $user = auth()->user();
        
        if (!$user->google_access_token) {
            return back()->with('error', 'Your Google account is not connected. Please log in with Google again.');
        }

        try {
            $this->updateUserSubscriptions($user, $user->google_access_token);
            
            $status = $user->fresh()->getYouTubeStatus();
            
            if ($status === 'no_subscriptions') {
                return back()->with('warning', 'Your YouTube subscriptions have been checked but you are not subscribed to any channels.');
            } else {
                $subscriptionCount = count($user->fresh()->youtube_subscriptions);
                return back()->with('success', "Your YouTube subscriptions have been updated! Following {$subscriptionCount} channels.");
            }
            
        } catch (\Exception $e) {
            \Log::error('Refresh subscriptions error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while updating subscriptions. Please try again.');
        }
    }
}
