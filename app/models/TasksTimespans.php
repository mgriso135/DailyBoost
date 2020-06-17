<?php
require_once('../bin/initialize.php');
require_once('../bin/utilities.php');
require_once('TaskTimespan.php');


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
class TasksTimespans {
    
    public $start;
    public $end;
    public $timespans_list;
    
    public function __construct($userid=-1, $start="", $end="")
    {
        $this->timespans_list = array();
        if($userid!=-1)
        {
            $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
            // Check connection
            if($link === false){
                die("ERROR: Could not connect. " . mysqli_connect_error());
            }

            $max=0;
            $sql = "SELECT id FROM taskstimespans WHERE userid = ? AND starteventdate >= ? AND endeventdate <= ? ORDER BY starteventdate";
            if($stmt = $link->prepare($sql))
            {
                $stmt->bind_param("iss", $userid, $start, $end);
                $stmt->execute();
                $result = $stmt->get_result();
                while($row = $result->fetch_assoc()) {
                    $curr = new TaskTimespan($row['id']);
                    array_push($this->timespans_list, $curr);
                 }
                $stmt->close();
            }
            mysqli_close($link);
        }
    }
}
