<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class XtreamController extends Controller
{
    private $baseUrl;
    private $username;
    private $password;
    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 30,
            'verify' => false
        ]);
    }

    private function getAuthParams()
    {
        return [
            'username' => session('xtream_username'),
            'password' => session('xtream_password')
        ];
    }

    private function makeRequest($endpoint, $params = [], $type = 'player_api')
    {
        $baseUrl = session('xtream_dns');
        $authParams = $this->getAuthParams();
        $allParams = array_merge($authParams, $params);

        $apiEndpoint = $type === 'player_api' ? '/player_api.php' : '/get.php';
        
        try {
            $response = $this->client->get($baseUrl . $apiEndpoint, [
                'query' => $allParams
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            \Log::error('Xtream API Error: ' . $e->getMessage());
            return null;
        }
    }

    private function getStreamUrl($type, $id, $extension = 'm3u8')
    {
        $baseUrl = session('xtream_dns');
        $username = session('xtream_username');
        $password = session('xtream_password');

        return "{$baseUrl}/{$type}/{$username}/{$password}/{$id}.{$extension}";
    }

    public function showLoginForm()
    {
        return view('auth.xtream-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'dns' => 'required|url',
            'username' => 'required',
            'password' => 'required'
        ]);

        $this->baseUrl = rtrim($request->dns, '/');
        $this->username = $request->username;
        $this->password = $request->password;

        try {
            $response = $this->client->get("{$this->baseUrl}/player_api.php", [
                'query' => [
                    'username' => $this->username,
                    'password' => $this->password
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            if (isset($data['user_info'])) {
                session([
                    'xtream_dns' => $this->baseUrl,
                    'xtream_username' => $this->username,
                    'xtream_password' => $this->password,
                    'xtream_user_info' => $data['user_info']
                ]);

                return redirect()->route('dashboard');
            }

            return back()->withErrors(['message' => 'Invalid credentials']);
        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Connection failed: ' . $e->getMessage()]);
        }
    }

    // Live TV Methods
    public function getLiveCategories()
    {
        $cacheKey = 'live_categories_' . session('xtream_username');
        return Cache::remember($cacheKey, 300, function () {
            return $this->makeRequest('get_live_categories');
        });
    }

    public function getLiveTV($category_id = null)
    {
        $params = ['action' => 'get_live_streams'];
        if ($category_id) {
            $params['category_id'] = $category_id;
        }

        $channels = $this->makeRequest('get_live_streams', $params);
        return view('livetv.index', compact('channels'));
    }

    public function getLiveEPG($stream_id, $limit = 5)
    {
        return $this->makeRequest('get_short_epg', [
            'stream_id' => $stream_id,
            'limit' => $limit
        ]);
    }

    // VOD Methods
    public function getVODCategories()
    {
        $cacheKey = 'vod_categories_' . session('xtream_username');
        return Cache::remember($cacheKey, 300, function () {
            return $this->makeRequest('get_vod_categories');
        });
    }

    public function getMovies($category_id = null)
    {
        $params = ['action' => 'get_vod_streams'];
        if ($category_id) {
            $params['category_id'] = $category_id;
        }

        $movies = $this->makeRequest('get_vod_streams', $params);
        return view('movies.index', compact('movies'));
    }

    public function getMovieInfo($movie_id)
    {
        return $this->makeRequest('get_vod_info', [
            'vod_id' => $movie_id
        ]);
    }

    // Series Methods
    public function getSeriesCategories()
    {
        $cacheKey = 'series_categories_' . session('xtream_username');
        return Cache::remember($cacheKey, 300, function () {
            return $this->makeRequest('get_series_categories');
        });
    }

    public function getSeries($category_id = null)
    {
        $params = ['action' => 'get_series'];
        if ($category_id) {
            $params['category_id'] = $category_id;
        }

        $series = $this->makeRequest('get_series', $params);
        return view('series.index', compact('series'));
    }

    public function getSeriesInfo($series_id)
    {
        $seriesInfo = $this->makeRequest('get_series_info', [
            'series_id' => $series_id
        ]);
        return view('series.detail', compact('seriesInfo'));
    }

    // Catchup Methods
    public function getCatchupChannels()
    {
        return $this->makeRequest('get_live_streams', [
            'action' => 'get_live_streams',
            'type' => 'catchup'
        ]);
    }

    public function getCatchupEPG($stream_id, $start = null, $end = null)
    {
        $params = [
            'stream_id' => $stream_id,
            'action' => 'get_epg'
        ];

        if ($start) $params['start'] = $start;
        if ($end) $params['end'] = $end;

        return $this->makeRequest('get_epg', $params);
    }

    public function playCatchup($stream_id, $start, $duration)
    {
        $baseUrl = session('xtream_dns');
        $username = session('xtream_username');
        $password = session('xtream_password');

        return "{$baseUrl}/streaming/timeshift.php?username={$username}&password={$password}&stream={$stream_id}&start={$start}&duration={$duration}";
    }

    // XUI Panel Methods
    public function xuiLogin()
    {
        $baseUrl = session('xtream_dns');
        try {
            $response = $this->client->post($baseUrl . '/player_api.php', [
                'form_params' => [
                    'username' => session('xtream_username'),
                    'password' => session('xtream_password')
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            if (isset($data['user_info'])) {
                session(['xui_token' => $data['token'] ?? null]);
                return true;
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getXUIPanel()
    {
        if (!session('xui_token')) {
            if (!$this->xuiLogin()) {
                return response()->json(['error' => 'XUI login failed'], 401);
            }
        }

        return $this->makeRequest('panel_api', [], 'get');
    }

    // M3U Methods
    public function getM3U()
    {
        $baseUrl = session('xtream_dns');
        $username = session('xtream_username');
        $password = session('xtream_password');

        return redirect("{$baseUrl}/get.php?username={$username}&password={$password}&type=m3u_plus&output=ts");
    }

    public function getM3UWithCategories()
    {
        $baseUrl = session('xtream_dns');
        $username = session('xtream_username');
        $password = session('xtream_password');

        return redirect("{$baseUrl}/get.php?username={$username}&password={$password}&type=m3u_plus&output=ts&category_type=live,movie,series");
    }

    // Additional Helper Methods
    public function getAccountInfo()
    {
        return $this->makeRequest('', [], 'player_api');
    }

    public function search($keyword, $type = 'all')
    {
        $results = [];
        
        if (in_array($type, ['all', 'live'])) {
            $live = $this->makeRequest('get_live_streams');
            $results['live'] = array_filter($live ?? [], function($item) use ($keyword) {
                return stripos($item['name'], $keyword) !== false;
            });
        }
        
        if (in_array($type, ['all', 'vod'])) {
            $vod = $this->makeRequest('get_vod_streams');
            $results['vod'] = array_filter($vod ?? [], function($item) use ($keyword) {
                return stripos($item['name'], $keyword) !== false;
            });
        }
        
        if (in_array($type, ['all', 'series'])) {
            $series = $this->makeRequest('get_series');
            $results['series'] = array_filter($series ?? [], function($item) use ($keyword) {
                return stripos($item['name'], $keyword) !== false;
            });
        }
        
        return $results;
    }
}