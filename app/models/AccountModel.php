<?php
require_once('../bin/initialize.php');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AccountModel
 *
 * @author mgris
 */
class AccountModel {
    public $log;
    
    public $id;
    public $name;
    public $businessname;
    public $vatnumber;
    public $doctype;
    public $address;
    public $city;
    public $zipcode;
    public $country;
    public $email;
    public $telephone;
    public $enabled;
    public $expirydate;
    
    public $users;
    public $categories;
    
    
    public function __construct($accountid=-1)
    {
        $this->id = -1;
           $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
        // Check connection
        if($link === false){
            die("ERROR: Could not connect. " . mysqli_connect_error());
        }
        
         // Attempt insert query execution
        $sql = "SELECT id, name, businessname, vatnumber, doctype, address, city, zipcode, "
                ." country, email, telephone, enabled, expirydate "
                . " FROM accounts WHERE id = " . $accountid;
        $result = mysqli_query($link, $sql) or die(mysqli_error($link));
        while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->businessname = $row['businessname'];
            $this->vatnumber = $row['vatnumber'];
            $this->doctype = $row['doctype'];
            $this->city = $row['city'];
            $this->address = $row['address'];
            $this->zipcode = $row['zipcode'];
            $this->country = $row['country'];
            $this->email = $row['email'];
            $this->telephone = $row['telephone'];
            $this->enabled = $row['enabled'];
            $this->expirydate = $row['expirydate'];
        }
    }
    
    public function __get($name) {
    $getter = 'get'.$name;
    if (method_exists($this, $getter)) {
        return $this->$getter();
    }

    $message = sprintf('Class "%1$s" does not have a property named "%2$s" or a method named "%3$s".', get_class($this), $name, $getter);
    throw new \OutOfRangeException($message);
    }   

    public function __set($name, $value) {
        $setter = 'set'.$name;
        if (method_exists($this, $setter)) {
            return $this->$setter($value);
    }

    $getter = 'get'.$name;
    if (method_exists($this, $getter)) {
        $message = sprintf('Implicit property "%2$s" of class "%1$s" cannot be set because it is read-only.', get_class($this), $name);
    }
    else {
        $message = sprintf('Class "%1$s" does not have a property named "%2$s" or a method named "%3$s".', get_class($this), $name, $setter);
    }
    throw new \OutOfRangeException($message);
}

    public function getid()
    {
        return $this->id;
    }
    
    public function setid($value)
    {
        $this->id = $value;
    }
    
    /* Returns
     * 0 if generic error
     * 1 if accounts activated
     * 2 if no user/account/checksum found
     * 3 if no account found
     */
    public function activateaccount($userid, $email, $checksum)
    {
        $ret = 3;
        if($this->id >=0)
        {
            $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
            // Check connection
            if($link === false){
                die("ERROR: Could not connect. " . mysqli_connect_error());
            }
        
            // Attempt insert query execution
            $sql = "SELECT accounts.id, useraccounts.userid, useraccounts.accountid, useraccounts.checksum FROM "
                    ."useraccounts INNER JOIN users ON (users.id = useraccounts.userid) "
                    . " INNER JOIN accounts ON (useraccounts.accountid = accounts.id) "
                    . " WHERE useraccounts.accountid=".$this->id
                    . " AND users.email LIKE '".$email."' "
                    . " AND users.id = " .$userid
                    . " AND useraccounts.checksum = '" . $checksum . "'"
                    . " AND accounts.email LIKE '" . $email . "'";

            $result = mysqli_query($link, $sql) or die(mysqli_error($link));
            $found = false;
            while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
                $found = true;
            }
            if($found)
            {
                $sql = "UPDATE accounts SET enabled = true WHERE id = " .$this->id;
                $result = mysqli_query($link, $sql) or die(mysqli_error($link));
                $sql = "UPDATE useraccounts SET enabled = true WHERE accountid = " .$this->id . " AND userid = ".$userid;
                $result = mysqli_query($link, $sql) or die(mysqli_error($link));
            
                mysqli_close($link);
                $ret = 1;
            }
            else
            {
                $ret = 2;
            }
        }
        return $ret;
    }
    
 public function loadCategories()
 {
        $ret = 0;
        $this->categories=array();
        if($this->id!=-1)
        {
            $accid=$this->id;
             // Attempt insert query execution
        $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
        // Check connection
        if($link === false){
                            die("ERROR: Could not connect. " . mysqli_connect_error());
        }
       
        $sql = "SELECT id FROM categories INNER JOIN categoriesaccounts ON "
                . " (categories.id = categoriesaccounts.idcategory) WHERE categoriesusers.idaccount = ? ORDER BY name";
       if($stmt = $link->prepare($sql))
       {
            $stmt->bind_param("i", $accid);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows == 0) { 
                $ret=1;                    
            }
            else
            {
                while($row = $result->fetch_assoc()) {
                    $curr = new Category($row['id']);
                    array_push($this->categories, $curr);
                }
                $stmt->close();
                $ret = 1;               
            }
        }
        mysqli_close($link);
        }
        return $ret;
 }
}