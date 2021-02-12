<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryOrder extends Model
{
    protected $table  = 'deliveryorder';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $guarded = ['ID'];
}
