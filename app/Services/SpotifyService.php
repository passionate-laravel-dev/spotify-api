<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

class SpotifyService
{
    private $clientId;
    private $clientSecret;
    private $apiUrl;
    private $accessToken;

    public function __construct()
    {
        $this->clientId = config('services.spotify.client_id');
        $this->clientSecret = config('services.spotify.client_secret');
        $this->apiUrl = config('services.spotify.api_url');
        $this->accessToken = $this->getAccessToken();
    }

    /**
     * Get Access Token from cache or request a new one
     */
    private function getAccessToken()
    {
        if (Cache::has('spotify_access_token')) {
            return Cache::get('spotify_access_token');
        }

        return $this->refreshAccessToken();
    }

    /**
     * Refresh the access token and store in cache
     * 
     * @return string|null
     */
    private function refreshAccessToken()
    {
        try {
            $response = Http::asForm()->post('https://accounts.spotify.com/api/token', [
                'grant_type' => 'client_credentials',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Cache::put('spotify_access_token', $data['access_token'], now()->addSeconds($data['expires_in'] - 60));
                return $data['access_token'];
            }

            return null;
        } catch (Throwable $th) {
            Log::error('Spotify refresh token happen issue: ' . $th->getMessage());
            return null;
        }
    }

    /**
     * Ensure access token is valid
     */
    private function ensureAccessToken()
    {
        if (!$this->accessToken) {
            $this->accessToken = $this->refreshAccessToken();
        }
    }

    /**
     * Search items from Spotify
     * 
     * @param array $data
     * @return array
     */
    public function searchItems(array $data): array
    {
        try {
            $this->ensureAccessToken();

            return Http::withToken($this->accessToken)
                ->get($this->apiUrl . 'search', [
                    'q' => $data['query'],
                    'type' => $data['type'],
                    'market' => $data['market'] ?? '',
                    'limit' => $data['limit'] ?? '',
                    'offset' => $data['offset'] ?? '',
                    'include_external' => $data['include_external'] ?? '',
                ])->json();
        } catch (Throwable $th) {
            Log::error('Search Items from Spotify happen issue: ' . $th->getMessage());
            throw $th;
        }
    }

    /**
     * Get Artist
     * 
     * @param string $id
     * @return array
     */
    public function getArtist(string $id): array
    {
        try {
            $this->ensureAccessToken();

            return Http::withToken($this->accessToken)
                ->get("{$this->apiUrl}artists/{$id}")
                ->json();
        } catch (Throwable $th) {
            Log::error('Get Artist From Spitofy happen issue: ' . $th->getMessage());
            throw $th;
        }
    }

    /**
     * Get Artists
     * 
     * @param string $id
     * @return array
     */
    public function getArtists(string $ids): array
    {
        try {
            $this->ensureAccessToken();

            return Http::withToken($this->accessToken)
                ->get($this->apiUrl . 'artists', [
                    'ids' => $ids
                ])
                ->json();
        } catch (Throwable $th) {
            Log::error('Get Artists From Spitofy happen issue: ' . $th->getMessage());
            throw $th;
        }
    }

    /**
     * Get Artist's Albums
     * 
     * @param string $id
     * @param array $data
     * @return array
     */
    public function getArtistAlbums(string $id, array $data): array
    {
        try {
            $this->ensureAccessToken();

            return Http::withToken($this->accessToken)
                ->get("{$this->apiUrl}artists/{$id}/albums", [
                    'id' => $id,
                    'include_groups' => $data['include_groups'] ?? '',
                    'market' => $data['market'] ?? '',
                    'limit' => $data['limit'] ?? '',
                    'offset' => $data['offset'] ?? '',
                ])
                ->json();
        } catch (Throwable $th) {
            Log::error("Get Artist's Albums From Spitofy happen issue: " . $th->getMessage());
            throw $th;
        }
    }
}
