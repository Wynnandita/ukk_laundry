<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    protected $table = 'paket';
    protected $primaryKey = 'id_paket';
    public $timestamps = false;
    protected $fillable = ['jenis', 'harga'];
}
