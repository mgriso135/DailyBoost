<?php
require_once('../bin/utilities.php');
$lang = $_SESSION['language'];
require_once "assets/UserModel_{$lang}.php"; 


    
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of user
 *
 * @author mgris
 */
class UserModel {
    public $name;
    public $id;
    public $GoogleID;
    public $username;
    public $email;
    public $firstname;
    public $lastname;
    public $password;
    public $language;
    public $region;
    public $verified;
    public $checksum;
    public $creationdate;
    public $enabled;
    public $accounts;
}

class UserAccounts
{
    public function __construct($userid)
    {
        
    }
}

class Users
{
    
     public function __construct($accountid) {
        $this->account_id = $accountid;
    }
    
    public $users;
    
    public function loadUsers()
    {
        $this->accounts = [];
        $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
        // Check connection
        if($link === false){
            die("ERROR: Could not connect. " . mysqli_connect_error());
        }
 // Attempt insert query execution
        $sql = "SELECT id, GoogleID, username, email, firstname, lastname, password, language, region, verified, checksum, creationdate, "
                . "enabled, accountid "
                . " FROM users INNER JOIN useraccounts ON(users.id = useraccounts.userid)"
                . " WHERE useraccounts.accountid=". $this->accountid
                . "  ORDER BY fullname";
        $result = mysqli_query($link, $sql) or die(mysqli_error($link));
        while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
            $curr = new User($row['id']);
           /* $curr->id = $row['id'];
            $curr->GoogleID = $row['GoogleID'];
            $curr->username = $row['username'];
            $curr->email = $row['email'];
            $curr->firstname = $row['firstname'];
            $curr->lastname = $row['lastname'];
            $curr->password = $row['password'];
            $curr->language = $row['language'];
            $curr->region = $row['region'];
            $curr->enabled = $row['enabled'];*/
           
            array_push($this->users, $curr);
        }
    }
    
  
}
