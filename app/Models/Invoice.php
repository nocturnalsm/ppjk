<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table  = 'tbl_header_invoice';
    protected $primaryKey = 'ID';
    protected $guarded = ['ID'];
    public $timestamps = false;

}
