<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KonversiBarang extends Model
{
    protected $table  = 'tbl_konversi';
    protected $primaryKey = 'ID';
    protected $guarded = ['ID'];
    public $timestamps = false;
}
