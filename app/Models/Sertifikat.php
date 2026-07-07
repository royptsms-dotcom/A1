<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sertifikat extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_sertifikat',
        'nama_pt',
        'nama_rs',
        'nama_alat',
        'nomor_sn',
        'tanggal_kalibrasi',
        'valid_until',
        'penandatangan1_nama',
        'penandatangan1_jabatan',
        'penandatangan2_nama',
        'penandatangan2_jabatan',
    ];

    protected $casts = [
        'tanggal_kalibrasi' => 'date',
        'valid_until' => 'date',
    ];
}
