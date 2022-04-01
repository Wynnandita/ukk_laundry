<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $primarykey = 'id_transaksi';
    public $timestamps = false;
    protected $fillable = ['id_member', 'tanggal', 'batas_waktu', 'tanggal_bayar', 'status', 'dibayar', 'id', 'total_bayar'];

}
