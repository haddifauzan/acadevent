<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hari extends Model
{
    use HasFactory;

    protected $table = 'tbl_hari';
    protected $primaryKey = 'id_hari';

    protected $fillable = ['nama_hari']; 

    public function acaraSekolah()
    {
        return $this->hasMany(AcaraSekolah::class, 'id_hari', 'id_hari');
    }
}