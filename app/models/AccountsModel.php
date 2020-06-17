<?php
require_once('../bin/initialize.php');
require_once('UserModel.php');
require_once('UserAccountModel.php');
require_once('../bin/utilities.php');
$lang = $_SESSION['language'];
require_once "assets/UserModel_{$lang}.php"; 

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of accounts
 *
 * @author mgris
 */
class AccountsModel {
    public $accounts;

    public function __construct() {
        
    }

    
    public function loadAccounts()
    {
        $this->accounts = [];
        $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
        // Check connection
        if($link === false){
            die("ERROR: Could not connect. " . mysqli_connect_error());
        }
 // Attempt insert query execution
        $sql = "SELECT id, name, businessname, vatnumber, doctype, address, city, zipcode, "
                ." country, email, telephone, enabled, ezpirydate "
                . " FROM accounts ORDER BY businessname";
        $result = mysqli_query($link, $sql) or die(mysqli_error($link));
        while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
            $curr = new AccountModel();
            $curr->id = row['id'];
            $curr->name = row['name'];
            $curr->businessname = row['businessname'];
            $curr->vatnumber = row['vatnumber'];
            $curr->doctype = row['doctype'];
            $curr->city = row['city'];
            $curr->address = row['address'];
            $curr->zipcode = row['zipcode'];
            $curr->country = row['country'];
            $curr->email = row['email'];
            $curr->telephone = row['telephone'];
            $curr->enabled = row['enabled'];
            $curr->expirydate = row['expirydate'];
            
            array_push($this->accounts, $curr);
        }
    }
    
    /* Returns:
     * 0 if generic error
     * 1 if all is ok
     * 2 if email is not valid
     * 3 if account already exists and has not been activated yet
     * 4 if account already exists and is activated
     * 5 if data inconsistency and solved
     */   
    public function add($name, $businessname, $vatnumber, $doctype, $address, $city, $zipcode, $country, $email, $telephone)
    {
        $retval =0;
        //$nextMonth = date('d/m/Y', strtotime('+1 months'));
        $nextMonth=strtotime("+1 Month");
         // Attempt insert query execution
        $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
        // Check connection
        if($link === false){
            die("ERROR: Could not connect. " . mysqli_connect_error());
        }
        
        $sql ="SELECT id, email, enabled FROM accounts WHERE email like '".$email."'";
        $result = mysqli_query($link, $sql) or die(mysqli_error($link));
        $alreadyexists = false;
        $isenabled = false;
        $account_id = -1;
         while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
            $alreadyexists=true;
            $account_id = $row['id'];
            $isenabled = $row['enabled'];
        }        
        if(!$alreadyexists)
        {
            $sql = "SELECT MAX(id) FROM accounts";
            $result = mysqli_query($link, $sql) or die(mysqli_error($link));
            $min = 0;
            while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
                $min = $row['MAX(id)'] + 1;
        }

        $sql = "INSERT INTO accounts(id, name, businessname, vatnumber, doctype, address, city, zipcode, "
                ." country, email, telephone, enabled, expirydate)"
                . " VALUES(".$min. ", '".$name."', '"
                . $businessname ."', '"
                . $vatnumber ."', '"
                . $doctype ."', '"
                . $address ."', '"
                . $city ."', '"
                . $zipcode ."', '"
                . $country ."', '"
                . $email ."', '"
                . $telephone ."', "
                . "false, '"
                . date("Y-m-d h:i:s", $nextMonth) ."' "
                .")";

        $result = mysqli_query($link, $sql) or die(mysqli_error($link));
        
        
        $usr = new UserAccountModel($min);
        
        $adduserret = $usr->add($email, "Account manager", "en", "AR");
        $retval =1;
        }
        else if(!$isenabled)
        {
            // Gets user related to this account with the specified e-mail and re-sends the e-mail
            $sql = "SELECT useraccounts.userid, useraccounts.accountid, useraccounts.checksum FROM useraccounts INNER JOIN users ON (users.id = useraccounts.userid)"
                    . " WHERE useraccounts.accountid=".$account_id." AND users.email LIKE '".$email."'";
            $result = mysqli_query($link, $sql) or die(mysqli_error($link));
            $userid=-1;
            $checksum = "";
            while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
                $userid = $row[0];
                $checksum = $row[2];
            }       
            // Send activation e-mail
            
            if($userid !=-1 )
            {
            $mailbody = "";
            $mailsubject = "";

            $mailsubject = _NEWACCOUNTSUBJECT;
            $mailbody = _NEWACCOUNTBODY1 . "<a href='www.virtualchief.net/dailyboost/public/home/activateaccount/"
                            . $account_id
                            ."/" . $userid 
                    ."/" .$email
                            . "/" . $checksum . "'>" . _NEWACCOUNTBODY2."</a>" ._NEWACCOUNTBODY3; 
              $util = new utilities();   
             $util->sendmail($email, $mailsubject, $mailbody);
                
            $retval =3;
            }
            else
            {
                $usr = new UserAccountModel($account_id);
                $adduserret = $usr->add($email, "Account manager", "en", "AR");
                $retval = 5;
            }
        }
        else
        {
            $usr = new UserAccountModel($account_id);
            $adduserret = $usr->add($email, "Account manager", "en", "AR");
            $retval =4;
        }
        
        mysqli_close($link);
        return $retval;
    }
}