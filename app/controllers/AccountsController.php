<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of accounts
 *
 * @author mgris
 * 
 * Test github function
 */
/* Returns:
 * 0 if generic error
 * 1 if all is ok
 * 2 if email is not valid
 */   
class AccountsController extends Controller {
    public function addAccount()
    {
        $email = $_POST['email'];
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->model('AccountsModel');
            $AccountsModel = new AccountsModel();
            
            $ret = $AccountsModel->add($email, $email, "", "", "", "", "","", $email, "");
            echo $ret;
        } else {
            echo 2;
        }
    }
    
    /* Returns:
     * 0 if generic error
     * 2 if account/user/checksum/mail not found
     * 3 if fistname is empty
     * 4 if lastname is empty
     * 5 if password < 7 characters OR does not match password2
     * 6 if account not found
     * 7 if user already enabled
     */
    public function validateuser(/*$account, $user, $checksum, $firstname, $lastname, $email, $password, $password2*/)
    {
        $ret = 0;
        
        $account = $_POST['account'];
        $user = $_POST['user'];
        $checksum = $_POST['checksum'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $password2 = $_POST['password2'];
        
        $log = "";
        $this->model('UserAccountModel');
        $usr = new UserAccountModel($account);
        if($usr->account_id !=-1)
        {
            if(strlen($password) > 6 && $password == $password2)
            {
                if(strlen($firstname) > 0)
                {
                    if(strlen($lastname) > 0)
                    {
                        $ret = $usr->validateuser($user, $checksum, $firstname, $lastname, $email, $password);
                    }
                    else
                    {
                        $ret=4;
                    }
                }
                else
                {
                    $ret = 3;
                }
            }
            else
            {
                $ret = 5;
            }
        }
        else
        {
            $ret = 6;
        }

        echo $ret;
    }
}
