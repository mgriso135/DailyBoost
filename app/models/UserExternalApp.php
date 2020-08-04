<?php
require_once('../bin/utilities.php');


/**
 * Description of UserExternalApps
 *
 * @author mgris
 */
class UserExternalApp {
    public $user_id;
    public $ExternalAppId;
    public $ExternalAppType;
    public $ExternalAppName;
    public $token_type;
    public $scope;
    public $id_token;
    public $access_token;
    public $refresh_token;
    public $created;
    public $expires_in;
    
    public function __construct($id=-1)
    {
        $this->id=-1;
        $this->user_id=-1;
        $this->ExternalAppType = "";
        $this->ExternalAppName = "";
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

            $sql = "SELECT id, userid, ExternalAppType, ExternalAppName, token_type, scope, id_token, "
                    . " access_token, refresh_token, created, expires_in "
                    . "FROM usersexternalapps WHERE id = ? ";
            if($stmt = $link->prepare($sql))
            {
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                while($row = $result->fetch_assoc()) {
                    $this->id= $row["id"];
                    $this->user_id= $row["userid"];
                    $this->ExternalAppType = $row["ExternalAppType"];
                    $this->ExternalAppName = $row["ExternalAppName"];
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
            
}
