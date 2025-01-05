<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class XtreamController extends Controller
{
    private $baseUrl;
    private $username;
    private $password;

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
            $client = new Client();
            $response = $client->get("{$this->baseUrl}/player_api.php", [
                'query' => [
                    'username' => $this->username,
                    'password' => $this->password
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            if (isset($data['user_info'])) {
                // Store credentials in session
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

    public function getLiveTV()
    {
        try {
            $client = new Client();
            $response = $client->get(session('xtream_dns') . "/player_api.php", [
                'query' => [
                    'username' => session('xtream_username'),
                    'password' => session('xtream_password'),
                    'action' => 'get_live_streams'
                ]
            ]);

            $channels = json_decode($response->getBody(), true);
            return view('livetv.index', compact('channels'));
        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Failed to fetch live TV: ' . $e->getMessage()]);
        }
    }

    public function getMovies()
    {
        try {
            $client = new Client();
            $response = $client->get(session('xtream_dns') . "/player_api.php", [
                'query' => [
                    'username' => session('xtream_username'),
                    'password' => session('xtream_password'),
                    'action' => 'get_vod_streams'
                ]
            ]);

            $movies = json_decode($response->getBody(), true);
            return view('movies.index', compact('movies'));
        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Failed to fetch movies: ' . $e->getMessage()]);
        }
    }

    public function getSeries()
    {
        try {
            $client = new Client();
            $response = $client->get(session('xtream_dns') . "/player_api.php", [
                'query' => [
                    'username' => session('xtream_username'),
                    'password' => session('xtream_password'),
                    'action' => 'get_series'
                ]
            ]);

            $series = json_decode($response->getBody(), true);
            return view('series.index', compact('series'));
        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Failed to fetch series: ' . $e->getMessage()]);
        }
    }

    public function getSeriesInfo($series_id)
    {
        try {
            $client = new Client();
            $response = $client->get(session('xtream_dns') . "/player_api.php", [
                'query' => [
                    'username' => session('xtream_username'),
                    'password' => session('xtream_password'),
                    'action' => 'get_series_info',
                    'series_id' => $series_id
                ]
            ]);

            $seriesInfo = json_decode($response->getBody(), true);
            return view('series.detail', compact('seriesInfo'));
        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Failed to fetch series info: ' . $e->getMessage()]);
        }
    }
}