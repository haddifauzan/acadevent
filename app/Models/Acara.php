<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acara extends Model
{
    use HasFactory;

    const STATUS_BERLANGSUNG = 'berlangsung';
    const STATUS_SELESAI = 'selesai';

    protected $table = 'tbl_acara';
    protected $primaryKey = 'id_acara';

    protected $fillable = [
        'nama_acara',
        'penyelenggara',
        'tingkat',
        'deskripsi',
        'tanggal_acara',
        'waktu_mulai',
        'waktu_selesai',
        'tempat',
        'jenis_acara',
        'kuota_peserta',
        'status_acara',
        'kode_kehadiran'
    ];

    /**
     * Relasi many-to-many ke tabel users melalui tabel peserta_acara
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'tbl_peserta_acara', 'id_acara', 'id_user')
                    ->withPivot('status_kehadiran');
    }

    // Method untuk menghitung jumlah peserta yang mendaftar pada acara ini
    public function jumlahPeserta()
    {
        return $this->users()->count();
    }
}
