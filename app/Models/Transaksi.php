<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $primaryKey = 'transaksi_id';

    protected $fillable = [
        'transaksi_jenis',
        'transaksi_nominal',
        'transaksi_tanggal'
    ];

    public $timestamps = false;
}
