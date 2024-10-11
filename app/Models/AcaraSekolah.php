<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcaraSekolah extends Model
{
    use HasFactory;

    protected $table = 'tbl_acara_sekolah';
    protected $primaryKey = 'id_acara_sekolah';

    protected $fillable = [
        'jenis_acara',
        'nama_acara',
        'tingkat',
        'deskripsi',
        'waktu_mulai',
        'waktu_selesai',
        'status_acara',
        'id_hari'
    ];

    public function hari()
    {
        return $this->belongsTo(Hari::class, 'id_hari', 'id_hari');
    }
}
