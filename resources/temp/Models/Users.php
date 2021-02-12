<?php

namespace App\Models;

use Now\System\Core\Model as Model;

class Users extends Model
{
    protected $_tableName = "users";
    
    public function validate($user, $password)
    {
        $check = $this->query("SELECT username FROM users WHERE username = '" 
                              .$this->escapeString($user) ."' AND password = MD5('" .$this->escapeString($password) ."')");
        return $check->num_rows() > 0;
    }
    public function updateSession($user, $data)
    {
        $this->updateBy("username", $this->escapeString($user), $data);
    }
    public function getData($user)
    {   
        $data = $this->query("SELECT * FROM users WHERE username = '" .$this->escapeString($user) ."'");
        $user = $data->current();
        $user->company = $this->hasCompany($user);
        return $user;
    }
    public function hasCompany($user)
    {
        $company = $this->query("SELECT company_id, company_type from user_has_companies WHERE user_id = " .$user->id);
        if ($company->num_rows() > 0){
          $data = $company->current();
          $company_type = Array("C" => ['table_name' => 'tb_customer',
                                        'table_field' => 'id_customer'],
                                'I' => ['table_name' => 'importir',
                                        'table_field' => 'IMPORTIR_ID']
                            );
          $comp = $this->query("SELECT " .$company_type[$data->company_type]['table_field'] ." AS id "
                              ." from " .$company_type[$data->company_type]['table_name'] 
                              ." WHERE " .$company_type[$data->company_type]['table_field'] ." = " .$data->company_id);
          if ($comp->num_rows() > 0){
              return $comp->current();
          }
        }
        return false;
    }
}