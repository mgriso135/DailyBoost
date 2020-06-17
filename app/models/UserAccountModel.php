<?php
require_once('../bin/utilities.php');
require_once('User.php');
require_once('Category.php');
$lang = $_SESSION['language'];
require_once "assets/UserAccountModel_{$lang}.php"; 
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserAccountModel
 *
 * @author mgris
 */
class UserAccountModel {
    
    public $account_id;
    
    public function __construct($account =-1)
    {
        $this->account_id = $account;
    }
    
      /* Returns:
     * 0 if generic error
     * 1 if all is ok
     * 2 if email is not valid
     * 3 if e-mail is already linked to this account
     */   
    public function add($email, $role, $language, $region)
    {
        // Attempt insert query execution
        $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
        // Check connection
        if($link === false){
            die("ERROR: Could not connect. " . mysqli_connect_error());
        }
        
        $sql ="SELECT id, email, useraccounts.enabled FROM users INNER JOIN useraccounts ON(users.id = useraccounts.userid) "
                . " WHERE email LIKE '" . $email . "' AND accountid = " . $this->account_id;
        $result = mysqli_query($link, $sql) or die(mysqli_error($link));
        $alreadyexists_account = false;
        $isenabled = false;
         while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
            $alreadyexists_account = true;
            $isenabled = $row['enabled'];
        }
        
        if(!$alreadyexists_account)
        {
            // Checks if the user exists
            $sql ="SELECT id, email, enabled FROM users "
                . " WHERE email LIKE '" . $email . "'";
        $result = mysqli_query($link, $sql) or die(mysqli_error($link));
        $alreadyexists = false;
        $isenabled = false;
        $user_id=-1;
         while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
            $alreadyexists = true;
            $isenabled = $row['enabled'];
            $user_id = $row['id'];
        }
        
            if(!$alreadyexists)
            {
                // Finds the max id
                 $sql = "SELECT MAX(id) FROM users";
                $result = mysqli_query($link, $sql) or die(mysqli_error($link));
                $user_id = 0;
                while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
                    $user_id = $row['MAX(id)'] + 1;
                }
               // Creates a new user
                $sql ="INSERT INTO users(id, GoogleId, username, email, firstname, lastname, password, language, "
                        ."region, creationdate, enabled) VALUES("
                        .$user_id.", "
                        . "''".", "
                        . "'".$email."', "
                        . "'".$email."', "
                        . "'', "
                        . "'', "
                        . "'', "
                        . "'" . $language . "', "
                        . "'" . $region . "', "
                        . "'" . date("Y-m-d h:i:s") . "', "
                        . "false "
                        .")";
                $result = mysqli_query($link, $sql) or die(mysqli_error($link));                
            }
            
            // Links the user to the current account
            $util = new utilities();
            $checksum = $util->generate_string($email . $user_id . $this->account_id, 45);
               $sql ="INSERT INTO useraccounts(accountid, userid, creationdate, role, checksum, enabled) VALUES("
                        . $this->account_id . ", "
                        . $user_id . ", "
                        . "'".date("Y-m-d h:i:s")."', "
                        . "'" . $role . "',"
                       . "'" . $checksum . "', "
                       . "false)";

                $result = mysqli_query($link, $sql) or die(mysqli_error($link));
                
                $mailbody = "";
                $mailsubject = "";
                if($alreadyexists)
                {
                    $mailsubject = _INVITESUBJECT;
                    $mailbody = _INVITEBODY1 . "<a href='www.virtualchief.net/dailyboost/public/home/activateaccount?account_id="
                            .$this->account_id
                            ."&id=" . $user_id 
                            ."&mail=" .$email
                            . "&checksum=" . $checksum . "'>" 
                            . _INVITEBODY2."</a>" ._INVITEBODY3;                            
                }
                else
                {
                    $mailsubject = _NEWACCOUNTSUBJECT;
                    $mailbody = _NEWACCOUNTBODY1 . "<a href='www.virtualchief.net/dailyboost/public/home/activateaccount/"
                            .$this->account_id
                            ."/" . $user_id 
                            ."/" .$email
                            . "/" . $checksum . "'>" . _NEWACCOUNTBODY2."</a>" ._NEWACCOUNTBODY3; 
                }
                
                $util->sendmail($email, $mailsubject, $mailbody);
                
                return 1;
            }     
        
        else
        {
            return 3;
        }
        
        mysql_close($link);
    }
    
    /* Returns:
     * 0 if generic error
     * 1 if user successfully activated
     * 2 if account/user/checksum/mail not found
     * 3 if fistname is empty
     * 4 if lastname is empty
     * 5 if password < 7 characters OR does not match password2
     * 6 if account not found
     * 7 if user was already enabled
     */
    public function validateuser($user, $checksum, $firstname, $lastname, $email, $password)
    {
        $ret = 0;
        $log = "";

        if($this->account_id !=-1)
        {
            if(strlen($password) > 6)
            {
                if(strlen($firstname) > 0)
                {
                    if(strlen($lastname) > 0)
                    {
                        // Attempt insert query execution
                        $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
                        // Check connection
                        if($link === false){
                            die("ERROR: Could not connect. " . mysqli_connect_error());
                        }
        
                        $sql ="SELECT id, email, useraccounts.enabled, users.enabled FROM users INNER JOIN useraccounts ON(users.id = useraccounts.userid) "
                        . " WHERE email LIKE '" . $email 
                                . "' AND accountid = " . $this->account_id
                                . " AND userid = " . $user
                                . " AND checksum = '" . $checksum ."'"
                                . " AND users.email LIKE '" . $email . "'";
                        $result = mysqli_query($link, $sql) or die(mysqli_error($link));
                        $userexists=false;
                        $userenabled = false;
                        while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
                             $userexists=true;
                             $userenabled = $row[3];
                        }
                        
                        if($userexists && !$userenabled)
                        {
                            $sql = "UPDATE users SET firstname = ?, lastname = ?, password = ?, enabled = true WHERE id = ?";
                            $stmt = $link->prepare($sql);
                            $stmt->bind_param("sssi", $firstname, $lastname, md5($password), $user);
                            $stmt->execute();
                            $stmt->close();
                            
                            $sql = "UPDATE useraccounts SET enabled = true WHERE accountid = ? AND userid = ?";
                            $stmt = $link->prepare($sql);
                            $acc = $this->account_id;
                            $stmt->bind_param("ii", $acc, $user);
                            $stmt->execute();
                            $stmt->close();
                            
                            $_SESSION['userId'] = $user;
                            $_SESSION['accountId'] = $acc;
                            $_SESSION['username'] = $email;
                            
                            $ret = 1;
                        }
                        else if($userexists && $userenabled)
                        {
                            $ret = 7;
                        }
                        mysqli_close($link);
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
            $ret = 1;
        }
        
        // If user exists and has been activated, creates a new category
        $currusr = new User($user);
        if($currusr->id!=-1)
        {
            $currusr->loadCategories();
            if(sizeof($currusr->categories) == 0)
            {
                $currusr->addCategory("Personal", "Personal category");
            }
            
            // Adds a default "spare-time" task
            $currusr->loadCategories();
            if(sizeof($currusr->categories) == 1)
            {
               // $this->model('Category');
                $cat = $currusr->categories[0];
                $cat->addTask(_SPARETASK_NAME, "", true);
            }
        }    
        return $ret;
    }
}
