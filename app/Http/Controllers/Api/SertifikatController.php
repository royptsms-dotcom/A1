<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sertifikat;
use Illuminate\Http\Request;

class SertifikatController extends Controller
{
    public function index(Request $request)
    {
        $query = Sertifikat::query();
        if ($request->nama_rs) {
            $query->where('nama_rs', $request->nama_rs);
        }
        return response()->json($query->orderByDesc('created_at')->get());
    }

    public function getHistoryCount(Request $request)
    {
        $request->validate([
            'nama_rs'   => 'required|string',
            'nama_alat' => 'required|string',
        ]);

        $count = Sertifikat::where('nama_rs', $request->nama_rs)
            ->where('nama_alat', $request->nama_alat)
            ->count();

        return response()->json(['count' => $count]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_sertifikat'     => 'required|string',
            'nama_pt'              => 'required|string',
            'nama_rs'              => 'required|string',
            'nama_alat'            => 'required|string',
            'nomor_sn'             => 'required|string',
            'tanggal_kalibrasi'    => 'required|date',
            'valid_until'          => 'required|date',
            'penandatangan1_nama'  => 'nullable|string',
            'penandatangan1_jabatan' => 'nullable|string',
            'penandatangan2_nama'  => 'nullable|string',
            'penandatangan2_jabatan' => 'nullable|string',
        ]);

        $sertifikat = Sertifikat::create($validated);
        return response()->json($sertifikat, 201);
    }

    public function show(Sertifikat $sertifikat)
    {
        return response()->json($sertifikat);
    }

    public function destroy(Sertifikat $sertifikat)
    {
        $sertifikat->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
