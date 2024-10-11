<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesertaAcara extends Model
{
    use HasFactory;

    protected $table = 'tbl_peserta_acara';
    protected $primaryKey = 'id_peserta_acara';

    protected $fillable = [
        'id_acara',
        'id_user',
        'status_kehadiran',
    ];

    public function acara()
    {
        return $this->belongsTo(Acara::class, 'id_acara');
    }
}