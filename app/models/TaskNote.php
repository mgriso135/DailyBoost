<?php
require_once('User.php');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TaskNote
 *
 * @author mgris
 */
class TaskNote {
    public $id;
    public $taskid;
    public $userid;
    public $date;
    public $note;
    public $private;
    public $username;
    public $fullname;
    
    public function __construct($note_id=-1)
    {
        $this->id=-1;
        $this->taskid=-1;
        $this->userid=-1;
        $this->username="";
        $this->fullname;
        $this->date = new DateTime("1970-1-1");
        $this->note = "";
        $this->private =true;
        
        if($note_id!=-1)
        {
            $user_id = -1;
            $timezone = 'UTC';
            if($_SESSION['isLoggedIn'] && $_SESSION['userid'] != "")
            {
                $user_id = $_SESSION['userid'];
                $usr = new User($user_id);
                $usr->loadConfiguration();
                $timezone = $usr->timezone;
            }
            $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
            // Check connection
            if($link === false){
                die("ERROR: Could not connect. " . mysqli_connect_error());
            }
            $sql = "SELECT tasksnotes.id AS noteid, tasksnotes.taskid AS taskid, tasksnotes.userid AS userid, "
                    ."date, note, private, users.username AS username, firstname, lastname FROM tasksnotes "
                    ." INNER JOIN users ON (tasksnotes.userid = users.id)"
                    ."WHERE tasksnotes.id=?";
            if($stmt = $link->prepare($sql))
            {
                $stmt->bind_param("i", $note_id);
                $stmt->execute();
                $result = $stmt->get_result();
                while($row = $result->fetch_assoc()) {
                     $this->id= $row['noteid'];
                     $this->taskid= $row['taskid'];
                     $this->userid=$row['userid'];
                     $this->date = new DateTime($row['date'], new DateTimeZone($timezone));
                     $this->note = $row['note'];
                     $this->private = $row['private'];
                     $this->username = $row['username'];
                     $this->fullname = $row['firstname'] . " " . $row['lastname'];
                }
                $stmt->close();
            }
            else
            {
                echo $link->error;
            }
            mysqli_close($link);
        }
    }
}
