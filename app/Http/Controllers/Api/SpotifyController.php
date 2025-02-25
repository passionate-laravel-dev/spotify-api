<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SpotifyService;
use Illuminate\Http\Request;
use Throwable;

class SpotifyController extends Controller
{
    protected $spotify;

    public function __construct(SpotifyService $spotify)
    {
        $this->spotify = $spotify;
    }

    public function searchItems(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'query' => 'required|string',
            'market' => 'nullable|regex:/^[A-Z]{2}$/',
            'limit' => 'nullable|integer',
            'offset' => 'nullable|integer',
            'include_external' => 'nullable|string',
        ]);

        try {
            $result = $this->spotify->searchItems($validated['type'], $validated['query']);
        } catch (Throwable $e) {
            return response()->json([
                'error' => 'Failed to fetch data from Spotify',
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json($result);
    }

    public function getArtist(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|string',
        ]);

        try {
            $result = $this->spotify->getArtist($validated['id']);
        } catch (Throwable $e) {
            return response()->json([
                'error' => 'Failed to fetch data from Spotify',
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json($result);
    }

    public function getSeveralArtists(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|string',
        ]);

        try {
            $result = $this->spotify->getArtists($validated['ids']);
        } catch (Throwable $e) {
            return response()->json([
                'error' => 'Failed to fetch data from Spotify',
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json($result);
    }

    public function getArtistAlbums(Request $request, string $id)
    {
        $request->merge(['id' => $id]);
        $validated  = $request->validate([
            'id' => 'required|string',
            'include_groups' => 'nullable|string',
            'market' => 'nullable|regex:/^[A-Z]{2}$/',
            'limit' => 'nullable|integer',
            'offset' => 'nullable|integer',
        ]);

        try {
            $result = $this->spotify->getArtistAlbums($id, $validated);
        } catch (Throwable $e) {
            return response()->json([
                'error' => 'Failed to fetch data from Spotify',
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json($result);
    }
}
