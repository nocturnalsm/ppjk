<?php

namespace App\Controllers;

use Now\System\Core\Controller as Controller;

class Login extends Controller
{
    public function index()
    {                           
        $user = $this->post("username");
        $password = $this->post("password");        
        if ($user && $password)
        {
            $this->loadModel("users");
            /*
            $pass = $this->users->validate($user, $password);
            if ($pass){
                $this->authenticate($user);
                $this->redirect($this->app->getBaseUrl("login"));
            }
            else {
                $this->render("login.php", ["message" => "User tidak dikenali atau password salah", "code" => 1]);
            }*/
        }    
        else {
            $this->render("login.php", ["message" => "Silahkan memasukkan username dan password", "code" => 0]);
        }
    }    
    public function logout()
    {
        $user = $this->getSessionValue("logged_in");
        $this->destroySession();
        $this->users->updateSession($user, Array("session_started" => NULL,
                                          "session_active" => "T",
                                          "session_ip" => NULL));
    }
    private function authenticate($user)
    {
        $this->setSessionValue("logged_in", $user);
        $this->users->updateSession($user, Array("session_started" => Date("Y-m-d H:i:s"),
                                          "session_active" => "Y",
                                          "session_ip" => "127.0.0.1"));
        $this->redirect($this->app->getBaseUrl("login"));
    }
}