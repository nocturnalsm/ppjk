<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailBarang extends Model
{
    protected $table  = 'tbl_detail_barang';
    protected $primaryKey = 'ID';
    protected $guarded = ['ID'];
    public $timestamps = false;
}
