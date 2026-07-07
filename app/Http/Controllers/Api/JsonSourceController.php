<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JsonSource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class JsonSourceController extends Controller
{
    public function index()
    {
        return response()->json(JsonSource::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'url'  => 'required|url',
            'type' => 'required|in:rumah_sakit,alat,penandatangan',
        ]);

        $source = JsonSource::create($validated);
        return response()->json($source, 201);
    }

    public function destroy(JsonSource $jsonSource)
    {
        $jsonSource->delete();
        return response()->json(['message' => 'Deleted']);
    }

    /**
     * Fetch data dari URL JSON eksternal yang sudah tersimpan di database.
     */
    public function fetchData($id)
    {
        $source = JsonSource::findOrFail($id);

        try {
            $response = Http::timeout(10)->get($source->url);
            return response()->json([
                'source' => $source,
                'data'   => $response->json(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mengambil data dari ' . $source->url], 500);
        }
    }

    /**
     * Fetch semua data dari semua sumber aktif sekaligus.
     */
    public function fetchAll()
    {
        $sources = JsonSource::where('is_active', true)->get();
        $result = [];

        foreach ($sources as $source) {
            try {
                $response = Http::timeout(10)->get($source->url);
                $result[$source->type][] = [
                    'source' => $source->name,
                    'data'   => $response->json(),
                ];
            } catch (\Exception $e) {
                $result[$source->type][] = [
                    'source' => $source->name,
                    'error'  => 'Gagal mengambil data',
                ];
            }
        }

        return response()->json($result);
    }
}
