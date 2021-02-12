<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quota extends Model
{
    protected $table  = 'tbl_header_quota';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $guarded = ['ID'];
}
