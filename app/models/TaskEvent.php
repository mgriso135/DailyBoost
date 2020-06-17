<?php
require_once "Task.php";
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TaskEvent
 *
 * @author mgris
 */
class TaskEvent {
    public $id;
    public $taskid;
    public $userid;
    public $utcdate;
    //public $localdate;
    
    /* S = start
     * R = resume
     * P = pause
     * F = finish
     */
    public $eventtype;
    public $timezone;
    
    public function __construct($event_id=-1)
    {
         $this->id=-1;
        $this->taskid=-1;
        $this->userid=-1;
        if($event_id!=-1)
        {
            $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
            // Check connection
            if($link === false){
                                die("ERROR: Could not connect. " . mysqli_connect_error());
            }

            $max=0;
            // Get max id value
            $sql = "SELECT id, userid, taskid, date, timezone, eventtype FROM tasksevents WHERE id = ?";
            if($stmt = $link->prepare($sql))
            {
                $stmt->bind_param("i", $event_id);
                $stmt->execute();
                $result = $stmt->get_result();


                while($row = $result->fetch_assoc()) {
                     $this->id= $row['id'];
                     $this->taskid= $row['taskid'];
                     $this->userid=$row['userid'];
                     $this->timezone = $row['timezone'];
                     $this->eventtype = $row['eventtype'];
                     $gmtTimezone = new DateTimeZone('GMT');
                     $this->utcdate = new DateTime($row['date'], $gmtTimezone);
                    // $localtimezone = new DateTimeZone($this->timezone);
                     //$this->localdate = new DateTime($row['date'], $localtimezone);
                    // $this->localdate = $this->utcdate;
                     //$offset = $localtimezone->getOffset($this->utcdate);
                     //$myInterval=DateInterval::createFromDateString((string)$offset . 'seconds');
                     //$this->localdate->add($myInterval);
                }
                $stmt->close();
            }
            mysqli_close($link);
        }
    }    

    
        /* Returns:
     * 0 if generic error
     * 1 if everything ok
     * 2 if timespan is not the end of pause
         * 3 if task not found
     */
    public function migrateToTimeSpansTable()
    {
        //echo "Migrate... " . $this->eventtype . " ".$this->userid." ".$this->taskid.", ";
        $ret = 0;
        if($this->id != -1 && 
                ($this->eventtype == 'P' || $this->eventtype =='F')
                && $this->userid != -1
                && $this->taskid != -1)
        {
            $found = 0;
            $startevent_id = -1;
            $startevent_date = new DateTime('01/01/1970', new DateTimeZone('UTC'));
            $startevent_type = '';
            // Find the realted opening event
            $tsk = new Task($this->taskid);
            if($tsk->id!=-1)
            {
                $tsk->loadEventsByDate($this->userid);

                for($j = sizeof($tsk->events) - 1; $j >= 1 && $found == 0; $j--)
                {
                    if($tsk->events[$j]->id == $this->id
                            && ($tsk->events[$j - 1]->eventtype == 'R' || $tsk->events[$j - 1]->eventtype == 'S')
                            && ($tsk->events[$j - 1]->userid == $this->userid)
                            )
                    {
                        // Next one is our needed event!!!
                        $startevent_type = $tsk->events[$j - 1]->eventtype;
                        $startevent_date = $tsk->events[$j - 1]->utcdate;
                        $startevent_id = $tsk->events[$j - 1]->id;
                        $found = 1;
                    }
                }
                //echo $startevent_type . " " . $startevent_id . " ";
                if($startevent_type != '' && $startevent_id != -1)
                {
                    $tsstruct = array();
                    // Cycle to find all days!
                    $start = clone $startevent_date;
                    $end = $this->utcdate;
                    $tomorrow = clone $start;
                    $tomorrow->modify('+1 day'); 
                    $tomorrow->setTime(0, 0, 0);

                    if($this->utcdate > $tomorrow)
                    {
                        $end = clone $tomorrow;
                    }
                    while($start < $end)
                    {
                    //    echo "start: " . $start->format('Y-m-d H:i:s') .  " end " .$end->format('Y-m-d H:i:s') . " - ";
                        $curr = new TimeSpanStruct();
                        $curr->taskid = $this->taskid;
                        $curr->userid = $this->userid;
                        $curr->starteventid = $startevent_id;
                        $curr->starteventdate = $start;
                        $curr->starteventtype = $startevent_type;
                        $curr->endeventid = $this->id;
                        $curr->endeventtype = $this->eventtype;
                        $curr->endeventdate = $end;
                        $curr->timezone = $this->timezone;
                        array_push($tsstruct, $curr);
                        
                        $start = clone $end;
                        $tomorrow->modify('+1 day');
                        if($this->utcdate > $tomorrow)
                        {
                            $startevent_type = 'R';
                            $end = clone $tomorrow;
                        }
                        else
                        {
                            $end = clone $this->utcdate;
                        }
                    }
                    //echo "Inside the if...";
                    // write to the database
                    $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
                    // Check connection
                    if($link === false){
                          die("ERROR: Could not connect. " . mysqli_connect_error());
                    }

                    // Found max id
                    $sql = "SELECT MAX(id) FROM taskstimespans";
                    $max = 0;
                   if($stmt = $link->prepare($sql))
                   {
                        $stmt->execute();
                        $result = $stmt->get_result();
                        while($row = $result->fetch_assoc())
                        {
                            $max = $row['MAX(id)'] + 1;
                        }
                        $stmt->close();
                    }
                    $sql = "INSERT INTO taskstimespans(id, userid, taskid, "
                            . " starteventid, starteventdate, starteventtype, "
                            . " endeventid, endeventdate, endeventtype, timezone) "
                            . " VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    for($g = 0; $g < sizeof($tsstruct); $g++)
                    {
                        
                    
                        if($stmt = $link->prepare($sql))
                       {
                            $endvw = $tsstruct[$g]->endeventtype;
                            //echo $g . " " . sizeof($tsstruct) ." ". $tsstruct[$g]->endeventtype ." - ";
                            if($g < sizeof($tsstruct) - 1 && $tsstruct[$g]->endeventtype == "F")
                            {
                                $endvw = 'P';
                            }
                            //echo "endvw: ".$endvw . " --- ";
                            $stmt->bind_param("iiiississs", $max, $tsstruct[$g]->userid, $tsstruct[$g]->taskid, 
                                    $tsstruct[$g]->starteventid, $tsstruct[$g]->starteventdate->format("Y-m-d\TH:i:s"), 
                                    $tsstruct[$g]->starteventtype, $tsstruct[$g]->endeventid, $tsstruct[$g]->endeventdate->format("Y-m-d\TH:i:s"), 
                                    $endvw, $tsstruct[$g]->timezone);
                            if($stmt->execute())
                            {
                                $ret = 1;
                            }
                            else {
                                //echo $stmt->error;
                                $ret = 0;
                            }
                            $stmt->close();
                        }
                        else
                        {
                            //echo "Error " . $sql . " ".$link->error;
                            $ret = 0;
                        }
                        $max++;
                    }
                    mysqli_close($link);
                }
                else
                {
                    $ret = 0;
                }
            }
            else
            {
                $ret = 3;
            }
        }
        else {
            $ret = 2;
        }
        return $ret;
    }
}

class TimeSpanStruct
{
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
}
