<?php
require_once('../bin/utilities.php');
require_once('Category.php');
require_once('UserExternalAccount.php');


/**
 * Description of UserExternalApps
 *
 * @author mgris
 */
class UserExternalAccount {
    public $user_id;
    public $ExternalAccountId;
    public $ExternalAccountType;
    public $ExternalAccountName;
    public $AccountName;
    public $token_type;
    public $scope;
    public $id_token;
    public $access_token;
    public $refresh_token;
    public $created;
    public $expires_in;
    public $isTokenValid;
    
    public function __construct($id=-1)
    {
        $this->ExternalAccountId=-1;
        $this->user_id=-1;
        $this->ExternalAccountType = "";
        $this->ExternalAccountName = "";
        $this->AccountName = "";
        $this->token_type = "";
        $this->scope = "";
        $this->id_token = "";
        $this->access_token = "";
        $this->refresh_token = "";
        $this->created = "";
        $this->expires_in = "";
        if($id !=-1)
        {
            $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
            // Check connection
            if($link === false){
                            die("ERROR: Could not connect. " . mysqli_connect_error());
            }

            $sql = "SELECT id, userid, ExternalAccountType, ExternalAccountName, AccountName, token_type, scope, id_token, "
                    . " access_token, refresh_token, created, expires_in "
                    . "FROM usersexternalaccounts WHERE id = ? ";
            if($stmt = $link->prepare($sql))
            {
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                while($row = $result->fetch_assoc()) {
                    $this->ExternalAccountId= $row["id"];
                    $this->user_id= $row["userid"];
                    $this->ExternalAccountType = $row["ExternalAccountType"];
                    $this->ExternalAccountName = $row["ExternalAccountName"];
                    $this->AccountName = $row["AccountName"];
                    $this->token_type = $row["token_type"];
                    $this->scope = $row["scope"];
                    $this->id_token = $row["id_token"];
                    $this->access_token = $row["access_token"];
                    $this->refresh_token = $row["refresh_token"];
                    $this->created = $row["created"];
                    $this->expires_in = $row["expires_in"];
                }
                $stmt->close();
                $ret = 1;
            }
            else
            {
                echo $link->error;
            }
            mysqli_close($link);
        }
    }
            
    public function checkTokenValidity()
    {
        $this->isTokenValid = false;
        if($this->ExternalAccountId != -1)
        {
            $refresh_token = $this->refresh_token;
            //$ret .= "Refresh token: " . $refresh_token . "<br />";
            $client = new Google_Client();
            $client->setClientId(AppConfig::$GOOGLE_CLIENT_ID);
            $client->setClientSecret(AppConfig::$GOOGLE_CLIENT_SECRET);
            $client->setRedirectUri("https://www.virtualchief.net");
            //$client->setRedirectUri("http://localhost:88");
            $client->setAccessType("offline");
            //$client->setScopes("profile email https://www.googleapis.com/auth/calendar");
            $client->setScopes($this->scope);
            try
            {
                $client->fetchAccessTokenWithRefreshToken($refresh_token);
                if(!$client->isAccessTokenExpired())
                {
                    $this->isTokenValid = true;
                }
            }
            catch(Exception $ex)
            {
            }
        }
        return $this->isTokenValid;
    }
}

class UserExternalCalendar
{
    public $id;
    public $external_account_id;
    public $user_id;
    public $calendar_type;
    public $calendar_id;
    public $calendar_name;
    public $categories;
    public $external_account;
    
    public function __construct($id=-1/*$extaccountid=-1, $calendartype="", $calendarid=-1*/)
    {
        $this->calendar_id = -1;
        $this->external_account_id = -1;
        $this->categories = array();
            $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
            // Check connection
            if($link === false){
                            die("ERROR: Could not connect. " . mysqli_connect_error());
            }

            $sql = "SELECT id, categoryid, externalaccountid, calendarid, calendarname, calendartype FROM externalcalendarscategories "
                    . " WHERE id =?";
                    //. " WHERE externalaccountid=? AND calendarid=? AND calendartype=?";
            if($stmt = $link->prepare($sql))
            {
                $stmt->bind_param("i", $id);// $extaccountid, $calendarid, $calendartype);
                $stmt->execute();
                $result = $stmt->get_result();
                while($row = $result->fetch_assoc()) {
                    $this->id = $row['id'];
                    $this->external_account_id = $row['externalaccountid'];
                    $this->calendar_id = $row['calendarid'];
                    $this->calendar_name = $row['calendarname'];
                    $this->external_account = new UserExternalAccount($row['externalaccountid']);
                    $this->calendar_type = $row['calendartype'];
                }
                $stmt->close();
                $ret = 1;
            }
    }
    
    public function loadCategories()
    {
        $this->categories = array();
        if($this->user_id != -1 && $this->calendar_id != -1 && $this->external_account_id !=-1)
        {
            $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
            // Check connection
            if($link === false){
                            die("ERROR: Could not connect. " . mysqli_connect_error());
            }

            $sql = "SELECT id, categoryid, externalappid, calendarid, categoryid, calendarname, calendartype FROM externalcalendarscategories";
            if($stmt = $link->prepare($sql))
            {
                $stmt->bind_param("iiiii", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                while($row = $result->fetch_assoc()) {
                    $this->id = $row['id'];
                    $this->external_account_id = $row['externalappid'];
                    $this->user_id = $row['userid'];
                    $this->calendar_id = $row['calendarid'];
                    $this->calendar_name = $row['calendarname'];
                    $this->calendar_type = $row['calendartype'];
                    array_push($this->categories, new Category($row['categoryid']));
                }
                $stmt->close();
                $ret = 1;
            }
        }
    }
    
}

