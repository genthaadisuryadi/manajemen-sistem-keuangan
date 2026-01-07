<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';
    protected $primaryKey = 'mahasiswa_id';
    public $timestamps = false;

    protected $fillable = [
        'nomor_induk',
        'mahasiswa',
        'kategori' // ⬅️ SESUAI DATABASE
    ];
}
