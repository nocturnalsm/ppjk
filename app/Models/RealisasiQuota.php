<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RealisasiQuota extends Model
{
    protected $table  = 'tbl_realisasi_quota';
    protected $primaryKey = 'ID';
    protected $guarded = ['ID'];
    public $timestamps = false;
}
