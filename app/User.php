<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use DB;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function hasCompany()
    {
        $company = DB::table("user_has_companies")
                     ->select("company_id", "company_type")
                     ->where("user_id", $this->id);
        if ($company->count() > 0){
          $data = $company->first();
          $company_type = Array("C" => ['table_name' => 'tb_customer',
                                        'table_field' => 'id_customer'],
                                'I' => ['table_name' => 'importir',
                                        'table_field' => 'IMPORTIR_ID']
                            );
          $comp = DB::table($company_type[$data->company_type]['table_name'])
                     ->selectRaw($company_type[$data->company_type]['table_field'] ." AS id ")
                     ->where($company_type[$data->company_type]['table_field'], $data->company_id);
          if ($comp->count() > 0){
              return $comp->first();
          }
        }
        return false;
    }
}
