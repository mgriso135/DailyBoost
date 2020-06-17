<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of login
 *
 * @author mgris
 */
class login extends Controller {
    
    public function loginform($email="")
    {
        $this->view('/login/loginform', ['email' => $email]);
    }

public function dologin($username, $password)
{
     $ret = false;
     $this->model('User');
     $usr = new User();
     $ret = $usr->login($username, $password);
     if($ret == true)
     {
         echo "true";
     }
     else
     {
         echo "false";
     }

}

        public function dologout()
        {
            $ret = true;
            $_SESSION["username"] = "";
            $_SESSION['email'] = "";
            $_SESSION['language'] = "";
            $_SESSION['isLoggedIn'] = false;
            session_destroy();
            echo "true";
            return true;
        }

}
