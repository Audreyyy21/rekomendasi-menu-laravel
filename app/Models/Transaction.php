<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['tanggal', 'menu_daging_id', 'menu_jeroan_id', 'jumlah_box'];
}
