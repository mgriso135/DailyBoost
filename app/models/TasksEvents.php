<?php
require_once('../bin/initialize.php');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TasksEvents
 *
 * @author mgris
 */
class TasksEvents {
    public $events;
    
    public function __construct($user_id=-1)
    {
        if($user_id!=-1)
        {
            $this->events = array();
            // Attempt insert query execution
            $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
            // Check connection
            if($link === false){
                die("ERROR: Could not connect. " . mysqli_connect_error());
            }

            $sql = "SELECT tasks.id AS taskid, tasks.name AS taskname, tasks.status AS taskstatus, "
                    . "tasks.leadtime AS taskleadtime, tasks.workingtime AS taskworkingtime, "
                    . " tasks.delay AS taskdelay, tasks.realenddate AS taskrealenddate,"
                . " tasksevents.id AS eventid, tasksevents.userid AS eventuserid, tasksevents.date AS eventdate, "
                ." tasksevents.timezone AS eventtimezone, tasksevents.eventtype AS eventtype "
                . " FROM tasks "
                . " INNER JOIN tasksevents ON (tasks.id = tasksevents.taskid)"
                . " WHERE tasksevents.userid = ? "
                . " ORDER BY tasksevents.userid, date";
           if($stmt = $link->prepare($sql))
           {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()) {
               $curr = new TasksEventsAnalysisStruct();
                $curr->id = $row['taskid'];
                $curr->name = $row['taskname'];
                //$curr->description = $row['taskdescription'];
                $curr->status = $row['taskstatus'];
                //$this->neverending = (bool)$row['taskneverending'];
                //$this->plannedcycletime = $row['taskplannedcycletime'];
                //$this->earlystart = $row['taskearlystart'];
                //$this->latestart = $row['tasklatestart'];
                //$this->earlyfinish = $row['taskearlyfinish'];
                //$this->latefinish = $row['tasklatefinish'];
                $curr->leadtime = $row['taskleadtime'];
                $curr->workingtime = $row['taskworkingtime'];
                $curr->delay = $row['taskdelay'];
                $curr->realenddate = $row['taskrealenddate'];
                $curr->eventid = $row['eventid'];
                $curr->eventuserid = $row['eventuserid'];
                $gmtTimezone = new DateTimeZone('GMT');
                $utcdate = new DateTime($row['eventdate'], $gmtTimezone);
                $curr->eventdateutc = $utcdate;
                $curr->timezone = $row['eventtimezone'];
                $curr->eventtype = $row['eventtype'];
                array_push($this->events, $curr);
            }
            $stmt->close();
           }
           mysqli_close($link);
        }
    }
}

class TasksEventsAnalysisStruct
{
    public $id;
    public $name;
    public $edescription;
    public $status;
    public $neverending;
    public $plannedcycletime;
    public $earlystart;
    public $latestart;
    public $earlyfinish;
    public $latefinish;
    public $leadtime;
    public $workingtime;
    public $delay;
    public $realenddate;
    public $eventid;
    public $eventuserid;
    public $eventdateutc;
    public $timezone;
    public $eventtype;
}