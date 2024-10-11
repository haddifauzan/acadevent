<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Acara;

class PesertaAcaraController extends Controller
{
    public function getPeserta($id_acara)
    {
        // Ambil acara beserta peserta yang terdaftar
        $acara = Acara::with('users')->findOrFail($id_acara);

        // Kembalikan data peserta dalam format JSON
        return response()->json([
            'acara' => $acara->nama_acara,
            'peserta' => $acara->users->map(function($user) {
                return [
                    'id_user' => $user->id_user,
                    'nama_user' => $user->nama_user,
                    'kelas' => $user->siswa->kelas,
                    'jurusan' => $user->siswa->jurusan,
                    'status_kehadiran' => ucfirst($user->pivot->status_kehadiran),
                ];
            })
        ]);
    }

}
