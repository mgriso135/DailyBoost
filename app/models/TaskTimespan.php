<?php
require_once "TaskTimespan.php";
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DailyReport
 *
 * @author mgris
 */
class TaskTimespan {
    public $id;
    public $userid;
    public $taskid;
    public $starteventid;
    public $starteventdate;
    public $starteventtype;
    public $endeventid;
    public $endeventdate;
    public $endeventtype;
    public $timezone;
    public $task_name;
    public $duration;
    public $category_name;
    public $category_id;
    
    public function __construct($tm_id)
    {
        $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
            // Check connection
            if($link === false){
                die("ERROR: Could not connect. " . mysqli_connect_error());
            }

            $max=0;
            // Get max id value
            $sql = "SELECT taskstimespans.id as taskstimespansid, userid, tasks.id AS taskid, starteventid, starteventtype, starteventdate, "
                    ." endeventid, endeventtype, endeventdate, timezone, tasks.name AS taskname, categories.name AS category_name, "
                    . " categories.id AS category_id "
                    . " FROM taskstimespans INNER JOIN tasks ON "
                    . " (tasks.id = taskstimespans.taskid) "
                    . " INNER JOIN categoriestasks ON (categoriestasks.taskid = tasks.id) "
                    . " INNER JOIN categories ON (categoriestasks.categoryid = categories.id)"
                    . " WHERE taskstimespans.id = ?";
            if($stmt = $link->prepare($sql))
            {
                $stmt->bind_param("i", $tm_id);
                $stmt->execute();
                echo $stmt->error;
                $result = $stmt->get_result();
                while($row = $result->fetch_assoc()) {
                     $this->id= $row['taskstimespansid'];
                     $this->userid=$row['userid'];
                     $this->taskid= $row['taskid'];
                     $this->starteventid = $row['starteventid'];
                     $this->starteventtype = $row['starteventtype'];
                     $gmtTimezone = new DateTimeZone('GMT');
                     $this->starteventdate = new DateTime($row['starteventdate'], $gmtTimezone);
                     $this->endeventid = $row['endeventid'];
                     $this->endeventtype = $row['endeventtype'];
                     $gmtTimezone = new DateTimeZone('GMT');
                     $this->endeventdate = new DateTime($row['endeventdate'], $gmtTimezone);
                     $this->timezone = new DateTimeZone($row['timezone']);
                     $this->task_name = $row['taskname'];
                     $this->category_name = $row['category_name'];
                     $this->category_id = $row['category_id'];
                     
                    $diff = $this->endeventdate->diff($this->starteventdate);
                    $seconds = $diff->s;
                    $minutes = $diff->i * 60;
                    $hours = $diff->h * 60 * 60;
                    $days = $diff->days * 24 * 60 * 60;
                    $this->duration = ($seconds + $minutes + $hours + $days)/3600; 
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
