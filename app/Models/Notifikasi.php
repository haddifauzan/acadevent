<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'tbl_notifikasi';
    protected $primaryKey = 'id_notifikasi';

    protected $fillable = [
        'nama_acara',
        'pesan',
        'link_acara',
    ];
}
