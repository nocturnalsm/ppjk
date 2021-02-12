<?php

namespace App\Controllers;

use Now\System\Packages\PageController as PageController;

class Home extends PageController {
	
	public function index()
	{				
        $username = $this->getSessionValue("logged_in");
        $userlevel = $this->getSessionValue("userlevel");
		$this->render("home.php",["username" => ucfirst($username), "userlevel" => $userlevel]);
	}
	public function login()
    {                           
        $user = $this->post("username");
        $password = $this->post("password");
        if (!isset($user) || !isset($password))
        {
            $this->render("login.php", ["message" => "Silahkan memasukkan username dan password", "code" => 0]);
        }    
        else {            
            $this->loadModel("Users");            
            $pass = $this->users->validate($user, $password);            
            if ($pass){
                $this->authenticate($user);
                $this->redirect($this->app->getBaseUrl("login"));
            }
            else {
                $this->render("login.php", ["message" => "User tidak dikenali atau password salah", "code" => 1]);
            }
        }
    }    
    public function logout()
    {
		$this->loadModel("Users");
        $user = $this->getSessionValue("logged_in");
        $this->destroySession();
        $this->users->updateSession($user, Array("session_active" => "T",
												 "session_ip" => NULL));
		$this->redirect($this->app->getBaseUrl("login"));
    }
    private function authenticate($user)
    {
        $this->setSessionValue("logged_in", $user);
        $this->loadModel("Users");
        $data = $this->users->getData($user);
        $this->setSessionValue("userlevel", $data->user_level);
        $this->users->updateSession($user, Array("session_active" => "Y",
                                                 "session_ip" => $this->app->getRequest()->getClientIp()));
        $this->redirect($this->app->getBaseUrl("login"));
    }
}

?>