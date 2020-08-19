<?php
require_once 'User.php';
require_once 'Category.php';
require_once 'TaskEvent.php';
require_once 'TaskNote.php';
require_once 'UserExternalAccount.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Task
 *
 * @author mgris
 */
class Task {
    public $id;
    public $name;
    public $description;
    
    /* N = Not started yet
     * E = executing
     * P = paused
     * F = finished
     */
    public $status;
    public $neverending;
    public $plannedcycletime;
    public $earlystart;
    public $latestart;
    public $plantask;
    public $earlyfinish;
    public $latefinish;
    public $leadtime;
    public $workingtime;
    public $delay;
    public $realenddate;
    
    public $categories;
    public $category_id;
    public $category_name;
    
    public $users_id; // Users that worked or are working in the task
    public $activeusers_ids;
    public $owners; // Users that belongs from the categories of this task
    
    public $events;
    
    public $notes;
    
    public $external_tasks;
    
    public function __construct($taskid=-1)
    {
        $this->categories = array();
        $this->IsLoggedIn = false;
        $this->plantask = false;
        $this->categories=[];
        if($taskid>-1)
        {
        // Attempt insert query execution
        $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
        // Check connection
        if($link === false){
            die("ERROR: Could not connect. " . mysqli_connect_error());
        }
       
        $sql = "SELECT id, name, description, status, neverending, plannedcycletime, plantask, earlystart, latestart, earlyfinish, latefinish, ".
                " leadtime, workingtime, delay, realenddate FROM tasks WHERE id = ?";
       if($stmt = $link->prepare($sql))
       {
        $stmt->bind_param("i", $taskid);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows === 0) {$this->id = -1; die("No task found");}

        while($row = $result->fetch_assoc()) {
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->description = $row['description'];
            $this->status = $row['status'];
            $this->neverending = (bool)$row['neverending'];
            $this->plannedcycletime = $row['plannedcycletime'];
            $this->plantask = (bool)$row['plantask'];
            $this->earlystart = $row['earlystart'];
            $this->latestart = $row['latestart'];
            $this->earlyfinish = $row['earlyfinish'];
            $this->latefinish = $row['latefinish'];
            $this->leadtime = $row['leadtime'];
            $this->workingtime = $row['workingtime'];
            $this->delay = $row['delay'];
            $this->realenddate = $row['realenddate'];
        }
        $stmt->close();
       }
       else
       {
           $ret =false;
       }
       mysqli_close($link);
        }
        else
        {
            $this->id =-1;
        }
    }
    
    public function loadCategories()
    {
        $this->categories = array();
        if($this->id > -1)
        {
        // Attempt insert query execution
        $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
        // Check connection
        if($link === false){
            die("ERROR: Could not connect. " . mysqli_connect_error());
        }
       
        $sql = "SELECT categoryid FROM categoriestasks WHERE taskid = ?";
        
       if($stmt = $link->prepare($sql))
       {
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();

        //if($result->num_rows === 0) { die("No categories found");}
        while($row = $result->fetch_assoc()) {
            $curr = new Category($row['categoryid']);
            $this->category_id = $curr->id;
            $this->category_name = $curr->name;
            array_push($this->categories, $curr);
        }
        $ret = 1;
        $stmt->close();
       }
       else
       {
           $ret = 2;
       }
       mysqli_close($link);
        }
        else
        {
            $ret = 0;
        }
        return $ret;
    }
    
    /*
     * Returns:
     * 0 if generic error
     * 1 if task started successfully
     * 2 if task not found
     * 3 if user or category not found
     * 4 if task is already ended
     * 5 if user is already working on this task6
     * 6 if max_no_tasks reached  
     */
    public function start($user_id, $pause_def_task = 1)
    {
        $ret = 0;
        if($this->id!=-1)
        {
            if($this->status !='F')
            {
                $usr = new User($user_id);
               // $cat = new Category($category_id);
                if($usr->id!=-1)
                {
                    $usr->loadConfiguration();                    
                    $usr->loadTasksInExecution();
                    $usr->loadDefaultTaskId();
                    
                    // Take in account the default task that will be paused automatically
                    $max_tasks_in_execution2 = $usr->max_tasks_in_execution;
                    for($t=0; $t < sizeof($usr->tasks_in_execution); $t++)
                    {
                        if($usr->tasks_in_execution[$t]->id == $usr->default_task_id)
                        {
                            $max_tasks_in_execution2++;
                        }
                    }
                    
                    if(sizeof($usr->tasks_in_execution) < $max_tasks_in_execution2)//$usr->max_tasks_in_execution)
                    {
                        $event = '';
                        if($this->status == 'N')
                        {
                            $event = 'S';
                        }
                        else
                        {
                            $lv = $this->getLastEvent($usr->id);
                            if($lv->id!=-1)
                            {
                                if($lv->eventtype == 'P') { $event = 'R'; }
                            }
                            else
                            {
                             // Start the task
                                $event = 'S';
                            }
                        }

                        if($event!= '')
                        {
                            if($usr->default_task_id!=-1 && $pause_def_task == 1)
                            {
                                $deftask = new Task($usr->default_task_id);
                                $deftask->pause($usr->id, 0);
                            }
                            // Attempt insert query execution
                           $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
                           // Check connection
                           if($link === false){
                               die("ERROR: Could not connect. " . mysqli_connect_error());
                           }
                            $link->begin_transaction();

                                try
                                {
                                    // Get max id
                                    $sql = "SELECT MAX(id) FROM tasksevents";
                                    $stmt = $link->prepare($sql);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $maxeventid = 0;
                                    if($row = $result->fetch_assoc()) {
                                         $maxeventid = $row['MAX(id)'] + 1;
                                    }
                                    $stmt->close();
                                    $sql = "INSERT INTO tasksevents(id, userid, taskid, date, timezone, eventtype) "
                                        . "VALUES(?,?,?,?,?,?)"; 
                                    $stmt = $link->prepare($sql);
                                    $eventdate = gmdate("Y-m-d\TH:i:s");
                                    $stmt->bind_param("iiisss", $maxeventid, $usr->id, $this->id, $eventdate,$usr->timezone, $event);
                                    $stmt->execute();
                                    $stmt->close();
                                    $link->commit();
                                    $this->setStatus('E');
                                    $ret =1;
                                } 
                                catch (Exception $ex) {
                                    $link->rollback();
                                    $ret =-1;
                                }
                                    $link->close();
                           }
                           else
                           {
                               $ret = 5;
                           }
                    }
                    else
                    {
                        $ret = 6;
                    }
                }
                else
                {
                    $ret = 3;
                }
            }
            else
            {
                $ret = 4;
            }
        }
        else
        {
            $ret = 2;
        }
        return $ret;
    }
    
    /*
     * Returns:
     * 0 if generic error
     * 1 if task paused successfully
     * 2 if task not found
     * 3 if user not found
     * 4 if user is not working on this task
     * 5 if task is not in execution
     */
        public function pause($user_id, $start_def_task = 1)
        {
            $ret = 0;
        if($this->id!=-1)
        {
            if($this->status =='E')
            {
                $usr = new User($user_id);
                if($usr->id!=-1)
                {
                    $usr->loadDefaultTaskId();
                    $usr->loadConfiguration();
                    $this->loadActiveUsersId();
                    if(in_array($user_id, $this->activeusers_ids))
                    {
                        $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
                        // Check connection
                        if($link === false){
                             die("ERROR: Could not connect. " . mysqli_connect_error());
                        }
                        $link->begin_transaction();
                        try
                        {

                        $max=0;
                        // Get max id value
                        $sql = "SELECT MAX(id) FROM tasksevents";
                        if($stmt = $link->prepare($sql))
                        {
                            $stmt->execute();
                            $result = $stmt->get_result();
                            if($row = $result->fetch_assoc()) {
                                 $max = $row['MAX(id)'] + 1;
                            }
                            $stmt->close();
                        }
                        
                        $sql = "INSERT INTO tasksevents(id, userid, taskid, date, timezone, eventtype) "
                                . " VALUES(?,?,?,?,?,?)";
                        if($stmt = $link->prepare($sql))
                        {
                            $eventdate = gmdate("Y-m-d\TH:i:s");
                            $event = 'P';
                            $stmt->bind_param("iiisss", $max, $user_id, $this->id, $eventdate, $usr->timezone, $event);
                            $stmt->execute();
                            $stmt->close();
                        }
                        $link->commit();
                        
                        $this->calculateLeadTime();
                        $this->calculateWorkingTime();
                        
                        $tskev = new TaskEvent($max);
                        $tskev->migrateToTimeSpansTable();
                        
                        $ret = 1;
                        }
                        catch(Exception $ex)
                        {
                            
                        }
                        $link->close();
                    }
                    else
                    {
                        $ret = 4;
                    }
                    
                    $this->loadActiveUsersId();
                    if(sizeof($this->activeusers_ids) == 0)
                    {
                        $this->setStatus('P');
                    }
                    
                    $usr->loadTasksInExecution();
                        //echo "Attempt to run default task... ".sizeof($usr->tasks_in_execution);
                        if(sizeof($usr->tasks_in_execution) == 0)
                        {
                          //  echo $usr->default_task_id ." start_def_task: ". $start_def_task;
                            if($usr->default_task_id!=-1 && $start_def_task == 1)
                            {
                            //    echo "inside the if...";
                                $deftask = new Task($usr->default_task_id);
                                $deftask->start($user_id, 0);
                            }
                        }
                }
                else
                {
                    $ret = 3;
                }
            }
            else
            {
                $ret = 5;
            }
        }
        else
        {
            $ret = 2;
        }
            
            return $ret;
        }
    
    /*
     * Returns:
     * 0 if generic error
     * 1 if task paused successfully
     * 2 if task not found
     * 3 if user or category not found
     * 4 if user is not working on this task
     * 5 if task is not in execution
     */
        public function finish($user_id, $start_def_task = 1)
        {
            $ret = 0;
            if($this->id!=-1)
            {
            if($this->status =='E')
            {
                $usr = new User($user_id);
                if($usr->id!=-1)
                {
                    $usr->loadTasksInExecution();
                    $this->loadActiveUsersId();
                    if(in_array($user_id, $this->activeusers_ids))
                    {
                        $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
                        // Check connection
                        if($link === false){
                             die("ERROR: Could not connect. " . mysqli_connect_error());
                        }
                        $link->begin_transaction();
                        try
                        {

                        $max=0;
                        // Get max id value
                        $sql = "SELECT MAX(id) FROM tasksevents";
                        if($stmt = $link->prepare($sql))
                        {
                            $stmt->execute();
                            $result = $stmt->get_result();
                            if($row = $result->fetch_assoc()) {
                                 $max = $row['MAX(id)'] + 1;
                            }
                            $stmt->close();
                        }
                        
                        $max_ids_array = array();
                        
                        // Ends the task for all active users, no matter who sent the signal.
                        $eventdate = gmdate("Y-m-d\TH:i:s");
                        $event = 'F';
                        for($i = 0; $i < sizeof($this->activeusers_ids); $i++)
                        {
                            $sql = "INSERT INTO tasksevents(id, userid, taskid, date, timezone, eventtype) "
                                . " VALUES(?,?,?,?,?,?)";
                        if($stmt = $link->prepare($sql))
                        {
                            array_push($max_ids_array, $max);
                            $usr = new User($this->activeusers_ids[$i]);
                            $usr->loadConfiguration();
                            $newstatus = 'F';
                            $stmt->bind_param("iiisss", $max, $this->activeusers_ids[$i], $this->id, $eventdate, $usr->timezone, $newstatus);
                            $stmt->execute();
                            $stmt->close();
                            $max++;
                        }
                        }
                        $link->commit();
                        $this->loadActiveUsersId();
                        if(sizeof($this->activeusers_ids) == 0)
                        {
                            $this->setStatus('F');
                        }
                        
                        $usr->loadTasksInExecution();
                        if(sizeof($usr->tasks_in_execution) == 0)
                        {
                            if($usr->default_task_id!=-1 && $start_def_task == 1)
                            {
                                $deftask = new Task($usr->default_task_id);
                                $deftask->start($user_id, 0);
                            }
                        }
                        
                        $this->calculateLeadTime();
                        $this->calculateWorkingTime();
                        
                        for($h = 0; $h < sizeof($max_ids_array); $h++)
                        {
                            $tskev = new TaskEvent($max_ids_array[$h]);
                            //echo "migrate...? ".$max . " " .$tskev->id." ";
                            if($tskev->id != -1)
                            {
                              //  echo "MIGRATE!!! ".$tskev->id;
                                $tskev->migrateToTimeSpansTable();
                            }
                        }
                        
                        $ret = 1;
                        }
                        catch(Exception $ex)
                        {
                            
                        }                        
                        $link->close();
                    }
                    else
                    {
                        $ret = 4;
                    }
                }
                else
                {
                    $ret = 3;
                }
            }
            else
            {
                $ret = 5;
            }
        }
        else
        {
            $ret = 2;
        }            
            return $ret;
        }
    
     public function getLastEvent($user)
    {
        $lastev = new TaskEvent();
        if($this->id)
        {
            $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
            // Check connection
            if($link === false){
                 die("ERROR: Could not connect. " . mysqli_connect_error());
            }

            $max=0;
            // Get max id value
            $sql = "SELECT id FROM tasksevents WHERE taskid =? AND userid = ? ORDER BY date DESC";
            if($stmt = $link->prepare($sql))
            {
                $stmt->bind_param("ii",$this->id, $user);
                $stmt->execute();
                $result = $stmt->get_result();
                if($row = $result->fetch_assoc()) {
                     $lastev = new TaskEvent($row['id']);
                }
                $stmt->close();
            }
            $link->close();
        }
        return $lastev;
    }

    /* Returns:
     * 0 if generic error
     * 1 if all is ok
     * 2 if task not found
     * 3 if input value is incorrect
     */
    public function setStatus($newstatus)
    {
        if($this->id!=-1)
        {
            if($newstatus == 'E' ||
                    $newstatus == 'P' ||
                    $newstatus == 'F')
            {
            // Attempt insert query execution
            $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
            // Check connection
            if($link === false){
                die("ERROR: Could not connect. " . mysqli_connect_error());
            }

            $sql = "UPDATE tasks SET status = ? WHERE id = ?";
            if($newstatus == 'F')
            {
                $sql = "UPDATE tasks SET status = ?, realenddate = ? WHERE id = ?";
            }
           if($stmt = $link->prepare($sql))
           {
               $eventdate = gmdate("Y-m-d\TH:i:s");
                if($newstatus == 'F')
                {
                    $stmt->bind_param("ssi", $newstatus, $eventdate, $this->id);
                }
                else
                {
                    $stmt->bind_param("si", $newstatus, $this->id);
                }
                $stmt->execute();
                $stmt->close();
            }
            else
            {
                
            }
            mysqli_close($link);
            }
            else
            {
                $ret = 3;
            }
        }
        else
        {
            $ret =2;
        }
    }
    
    /* Returns
     * 0 if error
     * 1 if all is ok; moreover, loads the array activeusers_id
     * 2 if Task not found
     */
    public function loadAllUsersId()
    {
        $ret = 0;
        $this->users_id = array();
        if($this->id!=-1)
        {
            if($this->status != 'F')
            {
                $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
                // Check connection
                if($link === false){
                    die("ERROR: Could not connect. " . mysqli_connect_error());
                }

                $max=0;
                // Loads all users that are working or worked at the task
                $sql = "SELECT DISTINCT(userid) AS uuserid FROM tasksevents WHERE taskid = ?";
                if($stmt = $link->prepare($sql))
                {
                    $stmt->bind_param("i", $this->id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $allusersid = array();
                    while($row = $result->fetch_assoc()) {
                         array_push($this->users_id, $row['uuserid']);
                    }
                    $stmt->close();
                }
                $link->close();
            }
        }
        else
        {
            $ret = 2;
        }        
        return $ret;
    }
    
    /* Returns
     * 0 if error
     * 1 if all is ok; moreover, loads the array activeusers_id
     * 2 if Task not found
     */
    public function loadActiveUsersId()
    {
        $ret = 0;
        $this->activeusers_ids = array();
        if($this->id!=-1)
        {
            if($this->status != 'F')
            {
                $this->loadAllUsersId();    
                for($i = 0; $i < sizeof($this->users_id); $i++)
                    {
                        $lastev = $this->getLastEvent($this->users_id[$i]);
                        if($lastev->eventtype == 'S' || $lastev->eventtype == 'R')
                        {
                            array_push($this->activeusers_ids, $this->users_id[$i]);
                        }                            
                    }
            }
        }
        else
        {
            $ret = 2;
        }        
        return $ret;
    }
 
    public function loadEventsByDate($user_id = -1)
    {
        $this->events = array();
        if($this->id!=-1)
        {
             // Attempt insert query execution
            $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
            // Check connection
            if($link === false){
                  die("ERROR: Could not connect. " . mysqli_connect_error());
            }

            $sql = "SELECT id FROM tasksevents WHERE taskid = ?";
            if($user_id !=-1) { $sql .= " AND userid = ? "; }
            $sql .= " ORDER BY date";
           if($stmt = $link->prepare($sql))
           {
               if($user_id!=-1)
               {
                   $stmt->bind_param("ii", $this->id, $user_id);
               }
               else
               {
                $stmt->bind_param("i", $this->id);
               }
                $stmt->execute();
                $result = $stmt->get_result();
                while($row = $result->fetch_assoc())
                {
                    $curr = new TaskEvent($row['id']);
                    array_push($this->events, $curr);
                }
                $stmt->close();
            }
            else
            {
                
            }
            mysqli_close($link);
        }
    }
    
    public function loadEventsByUserAndDate()
    {
        $this->events = array();
        if($this->id!=-1)
        {
             // Attempt insert query execution
            $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
            // Check connection
            if($link === false){
                  die("ERROR: Could not connect. " . mysqli_connect_error());
            }

            $sql = "SELECT id FROM tasksevents WHERE taskid = ? ORDER BY userid, date";
           if($stmt = $link->prepare($sql))
           {
                $stmt->bind_param("i", $this->id);
                $stmt->execute();
                $result = $stmt->get_result();
                while($row = $result->fetch_assoc())
                {
                    $curr = new TaskEvent($row['id']);
                    array_push($this->events, $curr);
                }
                $stmt->close();
            }
            else
            {
                
            }
            mysqli_close($link);
        }
    }
    
    /* Returns
     * 0 if generic error
     * 1 if lead time calculated and saved
     * 2 if task not found
     * 
     * NOTE: in case of un-finished tasks, the lead time will be calculated as 
     * the difference between start and last pause event
     */
    public function calculateLeadTime()
    {
        $ret = 0;
        if($this->id != -1)
        {
            $lt = 0.0;
            $this->loadEventsByDate();
            if(sizeof($this->events) > 0)
            {
                $startdate = $this->events[0]->utcdate;

                // Find the last event
                $found = false;
                for($i = sizeof($this->events) - 1; $i>=0 && !$found; $i--)
                {
                    if($this->events[$i]->eventtype == 'F' || $this->events[$i]->eventtype == 'P')
                    {
                        $found = true;
                        $lastdate = $this->events[$i]->utcdate;
                    }
                }
                
                if($found)
                {
                    $diff = $lastdate->diff($startdate);

                    $seconds = $diff->s;
                    $minutes = $diff->i * 60;
                    $hours = $diff->h * 60 * 60;
                    $days = $diff->days * 24 * 60 * 60;
                    $lt = $seconds + $minutes + $hours + $days;
                }
            }
            
            // Write the lead time in the database
            // 
            // Attempt insert query execution
            $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
            // Check connection
            if($link === false){
                  die("ERROR: Could not connect. " . mysqli_connect_error());
            }

            $sql = "UPDATE tasks SET leadtime = ? WHERE id = ?";
           if($stmt = $link->prepare($sql))
           {
                $stmt->bind_param("ii", $lt, $this->id);
                $stmt->execute();
                $stmt->close();
            }
            else
            {
                
            }
            mysqli_close($link);
        }
        else
        {
            $ret = 2;
        }
    }
    
        /* Returns
     * 0 if generic error
     * 1 if lead time calculated and saved
     * 2 if task not found
     * 
     * NOTE: in case of un-finished tasks, the lead time will be calculated as 
     * the difference between start and last pause event
     */
    public function calculateWorkingTime()
    {
        $ret = 0;
        if($this->id != -1)
        {
            $wt = 0.0;
            $this->loadEventsByUserAndDate();
            if(sizeof($this->events) > 0)
            {
                // Find the last event
                $found = false;
                for($i = 0; $i < sizeof($this->events) - 1; $i+=2)
                {
                    // check if user is the same
                    if($this->events[$i]->userid == $this->events[$i+1]->userid
                            && ($this->events[$i]->eventtype == 'S' ||$this->events[$i]->eventtype == 'R')
                            && ($this->events[$i+1]->eventtype == 'P' ||$this->events[$i+1]->eventtype == 'F'))
                    {
                        $startd = $this->events[$i]->utcdate;
                        $endd = $this->events[$i+1]->utcdate;
                        $diff = $endd->diff($startd);
                        $seconds = $diff->s;
                        $minutes = $diff->i * 60;
                        $hours = $diff->h * 60 * 60;
                        $days = $diff->days * 24 * 60 * 60;
                        $wt = $wt + $seconds + $minutes + $hours + $days;
                    }
                }
            }
            // Write the lead time in the database
            // 
            // Attempt insert query execution
            $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
            // Check connection
            if($link === false){
                  die("ERROR: Could not connect. " . mysqli_connect_error());
            }

            $sql = "UPDATE tasks SET workingtime = ? WHERE id = ?";
           if($stmt = $link->prepare($sql))
           {
                $stmt->bind_param("ii", $wt, $this->id);
                $stmt->execute();
                $stmt->close();
            }
            else
            {
                
            }
            mysqli_close($link);
        }
        else
        {
            $ret = 2;
        }
    }
    
    public function loadOwners()
    {
        $this->owners = array();
        if($this->id != -1)
        {
        // Write the lead time in the database
            // 
            // Attempt insert query execution
            $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
            // Check connection
            if($link === false){
                  die("ERROR: Could not connect. " . mysqli_connect_error());
            }

            $sql = "SELECT tasks.id, categoriestasks.categoryid, categoriesusers.iduser AS userid "
                    ."FROM tasks INNER JOIN categoriestasks ON (tasks.id = categoriestasks.taskid) "
                    ."INNER JOIN categoriesusers ON (categoriesusers.idcategory = categoriestasks.categoryid) "
                    ." WHERE tasks.id=?";
           if($stmt = $link->prepare($sql))
           {
                $stmt->bind_param("i", $this->id);
                $stmt->execute();
                $result = $stmt->get_result();
                while($row = $result->fetch_assoc()) {
                     array_push($this->owners, $row['userid']);
                }
                $stmt->close();
            }
            else
            {
                
            }
            mysqli_close($link);
        }
    }
    
    public function setName($name)
    {
        if($this->id != -1)
        {
        // Write the lead time in the database
            // 
            // Attempt insert query execution
            $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
            // Check connection
            if($link === false){
                  die("ERROR: Could not connect. " . mysqli_connect_error());
            }

            $sql = "UPDATE tasks SET name = ? WHERE id = ?";
           if($stmt = $link->prepare($sql))
           {
                $stmt->bind_param("si", $name, $this->id);
                $stmt->execute();
                $stmt->close();
                
                $this->name = $name;
                //$this->WriteTaskToExternalCalendars();
            }
            else
            {
                
            }
            mysqli_close($link);
        }
    }
    
    public function setDescription($description)
    {
        if($this->id != -1)
        {
        // Write the lead time in the database
            // 
            // Attempt insert query execution
            $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
            // Check connection
            if($link === false){
                  die("ERROR: Could not connect. " . mysqli_connect_error());
            }

            $sql = "UPDATE tasks SET description = ? WHERE id = ?";
           if($stmt = $link->prepare($sql))
           {
                $stmt->bind_param("si", $description, $this->id);
                $stmt->execute();
                $stmt->close();
                
                $this->description = $description;
                //$this->WriteTaskToExternalCalendars();
            }
            else
            {
                
            }
            mysqli_close($link);
        }
    }
    
    public function setNeverEnding($neverending)
    {
        if($this->id != -1)
        {
        // Write the lead time in the database
            // 
            // Attempt insert query execution
            $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
            // Check connection
            if($link === false){
                  die("ERROR: Could not connect. " . mysqli_connect_error());
            }

            $sql = "UPDATE tasks SET neverending = ? WHERE id = ?";
           if($stmt = $link->prepare($sql))
           {
                $neverending1 = false;
                if($neverending=="true")
                {
                    $neverending1 = true;
                }
                $stmt->bind_param("ii", $neverending1, $this->id);
                $stmt->execute();
                $stmt->close();
            }
            else
            {
                
            }
            mysqli_close($link);
        }
    }
    
    public function setPlanTask($plantask)
    {
        if($this->id != -1)
        {
        // Write the lead time in the database
            // 
            // Attempt insert query execution
            $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
            // Check connection
            if($link === false){
                  die("ERROR: Could not connect. " . mysqli_connect_error());
            }

            $sql = "UPDATE tasks SET plantask = ? WHERE id = ?";
           if($stmt = $link->prepare($sql))
           {
                $plantask1 = false;
                if($plantask=="true")
                {
                    $plantask1 = true;
                }
                $stmt->bind_param("ii", $plantask1, $this->id);
                $stmt->execute();
                $stmt->close();
                
                $this->plantask = $plantask1;
                
                if($plantask1)
                {
                    $tsk = new Task($this->id);
                    $tsk->WriteTaskToExternalCalendars();
                }
                else
                {
                    $this->DeleteExternals();
                }
            }
            else
            {
                
            }
            mysqli_close($link);
        }
    }
    
    public function setPlanningDates($start, $end, $timezone)
    {
        if($this->id != -1 && $start < $end)
        {
        // Write the lead time in the database
            // 
            // Attempt insert query execution
            $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
            // Check connection
            if($link === false){
                  die("ERROR: Could not connect. " . mysqli_connect_error());
            }

            $sql = "UPDATE tasks SET earlystart = ?, latestart = ?, earlyfinish = ?, latefinish = ? WHERE id = ?";
           if($stmt = $link->prepare($sql))
           {
                $sdate = new DateTime($start, new DateTimeZone($timezone));
                $edate = new DateTime($end, new DateTimeZone($timezone));
                $earlystart = $sdate->setTimezone(new DateTimeZone('GMT'))->format('Y-m-d H:i:s');
                $latestart = $sdate->setTimezone(new DateTimeZone('GMT'))->format('Y-m-d H:i:s');
                $earlyfinish = $edate->setTimezone(new DateTimeZone('GMT'))->format('Y-m-d H:i:s');
                $latefinish = $edate->setTimezone(new DateTimeZone('GMT'))->format('Y-m-d H:i:s');
                $stmt->bind_param("ssssi", $earlystart, $latestart, $earlyfinish, $latefinish, $this->id);
                $stmt->execute();
                $stmt->close();
                
                $this->earlystart = $sdate;
                $this->latestart = $sdate;
                $this->earlyfinish = $edate;
                $this->latefinish = $edate;
                // $this->WriteTaskToExternalCalendars();
            }
            else
            {
            }
            mysqli_close($link);
        }
    }
    
    /*
     * Returns:
     * 0 if generic error
     * 1 if ok
     * 2 if category not found
     */
    public function changeCategory($category_id)
    {
        $ret = 0;
        if($this->id != -1)
        {
            $cat = new Category($category_id);
            if($cat->id != -1)
            {
                $this->DeleteExternals();
                // Write the lead time in the database
                // 
                // Attempt insert query execution
                $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
                // Check connection
                if($link === false){
                      die("ERROR: Could not connect. " . mysqli_connect_error());
                }

                $sql = "UPDATE categoriestasks SET categoryid = ? WHERE taskid = ?";
               if($stmt = $link->prepare($sql))
               {
                    $stmt->bind_param("ii", $category_id, $this->id);
                    $stmt->execute();
                    $stmt->close();
                }
                else
                {
                }
                mysqli_close($link);
                
                //$this->loadCategories();
                //$this->WriteTaskToExternalCalendars();
            }
            else
            {
                $ret = 2;
            }
        }
        else
        {
            $ret = 3;
        }
        return $ret;
    }

    public function loadNotes()
    {
        $this->notes = array();
        if($this->id!=-1)
        {
            if(isset($_SESSION['userid']) && $_SESSION['userid'] != "")
            {
                $user_id = $_SESSION['userid'];
                $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
                // Check connection
                if($link === false){
                    die("ERROR: Could not connect. " . mysqli_connect_error());
                }
                $sql = "SELECT id FROM tasksnotes WHERE taskid=? AND (userid=? OR private=false)";
                if($stmt = $link->prepare($sql))
                {
                    $stmt->bind_param("ii", $this->id, $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while($row = $result->fetch_assoc()) {
                        $curr = new TaskNote($row['id']);
                         array_push($this->notes, $curr);
                    }
                    $stmt->close();
                }
                mysqli_close($link);
            }
        }
    }
    
    /* Returns:
     * -1 if generic error
     * Note Id if everything is ok
     * -2 if user not found
     * -3 if task not initialized
     */
    public function addNote($user_id, $note, $private)
    {
        $ret = -1;
        if($this->id!=-1)
        {
        // Attempt insert query execution
        $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
        // Check connection
        if($link === false){
                            die("ERROR: Could not connect. " . mysqli_connect_error());
        }
       
        $max=0;
        // Get max id value
        $sql = "SELECT MAX(id) FROM tasksnotes";
       if($stmt = $link->prepare($sql))
       {          
            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows === 0) {$max=0;}
            else{
            while($row = $result->fetch_assoc()) {
                 $max = $row['MAX(id)'] + 1;
            }
            }
            $stmt->close();
        }
        
        $sql = "INSERT INTO tasksnotes(id, taskid, userid, date, note, private) VALUES (?,?,?,?,?,?)";
        if($stmt = $link->prepare($sql))
        {          
            $date = gmdate("Y-m-d H:i:s");
             $stmt->bind_param("iiissi", $max, $this->id, $user_id, $date, $note, $private);
             if($stmt->execute())
             {
                 $ret = $max;
             }
             else
             {
                 $ret = -4;
             }
             $stmt->close();
         }
         else
         {
             echo $link->error;
         }
         mysqli_close($link);
        }
        else
        {
            $ret = -1;
        }
        return $ret;
    }
    
    public function loadExternalTasks($externalcalendarid=-1)
    {
        $this->external_tasks = array();
        if($this->id != -1)
        {
             $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
                // Check connection
                if($link === false){
                    die("ERROR: Could not connect. " . mysqli_connect_error());
                }
                $sql = "SELECT id FROM externalcalendartask WHERE internaltaskid=?";
                if($externalcalendarid!=-1)
                {
                    $sql .= " AND externalcalendarid=?";
                }
                
                if($stmt = $link->prepare($sql))
                {
                   if($externalcalendarid!=-1)
                {
                       $stmt->bind_param("ii", $this->id, $externalcalendarid);
                   }
                   else
                   {
                    $stmt->bind_param("i", $this->id);
                   }
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while($row = $result->fetch_assoc()) {
                        $curr = new ExternalTask($row['id']);
                        array_push($this->external_tasks, $curr);
                    }
                    $stmt->close();
                }
                mysqli_close($link);
        }
    }
   
    /* Returns:
     * 0 if generic error
     * 1 if everything is ok
     * 
     */
    public function WriteTaskToExternalCalendars()
    {
        $ret = 0;
        if($this->id != -1)
        {
            // Checks all external calendars for the category of the task
            $this->loadCategories();
            foreach($this->categories as $cat)
            {
                $cat->loadExternalCalendars();
                foreach($cat->external_calendars as $extcal)
                {
                    // Checks if the current task is already in the calendar
                    $this->loadExternalTasks($extcal->id);
                    if(sizeof($this->external_tasks) > 0)
                    {
                        // Updates the task
                        if($extcal->calendar_type == "Google Calendar")
                        {
                            $ret = $this->UpdateTaskToExternalCalendars_Google($extcal, $this->external_tasks[0]->externaltaskid);
                        }
                    }
                    else
                    {
                        // Creates the external task
                        if($extcal->calendar_type == "Google Calendar")
                        {
                            $ret = $this->WriteTaskToExternalCalendars_Google($extcal, $cat->id);
                        }
                    }
                }
            }
        }
        return $ret;
    }
    
    /* Returns: 
     * 0 if generic error
     * 1 if everything is ok
     * 2 if token not valid
     */
    private function WriteTaskToExternalCalendars_Google($calendar, $category_id)
    {
        $ret =0;
        $calendar->external_account->checkTokenValidity();
        if($calendar->external_account->isTokenValid)
        {
            $util = new utilities();
            $client = $util->getGoogleClient();
            $client->setScopes($calendar->external_account->scope);//"profile email https://www.googleapis.com/auth/calendar");
            $client->fetchAccessTokenWithRefreshToken($calendar->external_account->refresh_token);
            $a_tok = $client->getAccessToken();
            //$service = new Google_Service_Oauth2($client);
            //$user = $service->userinfo->get();
            $calService = new Google_Service_Calendar($client);
            //$calendarList = $calService->calendarList->listCalendarList()->items;
            $timezone = 'GMT';
            if(isset($_SESSION['userid']) && $_SESSION['userid'] != "")
            {
                $usr = new User($_SESSION['userid']);
                if(isset($usr) && $usr->id > -1)
                {
                    $usr->loadConfiguration();
                    $timezone = $usr->timezone;
                }
            }
            $sdate = new DateTime($this->earlystart, new DateTimeZone('GMT'));
            $edate = new DateTime($this->latefinish, new DateTimeZone('GMT'));
           $strdate_start = $sdate->setTimezone(new DateTimeZone($timezone))->format('Y-m-d\TH:i:sP');//$sdate->format('Y-m-d\TH:i:sP');// 
           $strdate_end = $edate->setTimezone(new DateTimeZone($timezone))->format('Y-m-d\TH:i:sP');//$edate->format('Y-m-d\TH:i:sP');//

            $event = new Google_Service_Calendar_Event(array(
                'summary' => $this->name,
                //'location' => '800 Howard St., San Francisco, CA 94103',
                'description' => $this->description,
                'start' => array(
                  'dateTime' => $strdate_start,
                  'timeZone' => $timezone,
                ),
                'end' => array(
                  'dateTime' => $strdate_end, 
                  'timeZone' => $timezone, 
                ),
                /*'recurrence' => array(
                  'RRULE:FREQ=DAILY;COUNT=2'
                ),*/
                /*'attendees' => array(
                  array('email' => 'lpage@example.com'),
                  array('email' => 'sbrin@example.com'),
                ),*/
                'reminders' => array(
                  'useDefault' => FALSE,
                  'overrides' => array(
                  //  array('method' => 'email', 'minutes' => 60),
                    array('method' => 'popup', 'minutes' => 10),
                  ),
                ),
              ));

              $calendarId = $calendar->calendar_name;//'primary';
              $event2 = $calService->events->insert($calendarId, $event);
              //var_dump($event2);

              //echo "\nEvent id: " . $event2->id . "\n";

              // Write to database
              $this->WriteExternalTaskToDatabase($category_id, $calendar->id, $this->id, $event2->id);

              $ret = 1;
        }
        else
        {
            $ret = 2;
        }
        
        return $ret;
    }
    
    private function WriteExternalTaskToDatabase($categoryid, $externalcalendarid, $internaltaskid, $externaltaskid)
    {
        $ret = 0;
        // Attempt insert query execution
            $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
            // Check connection
            if($link === false){
                die("ERROR: Could not connect. " . mysqli_connect_error());
            }
            
            $sql = "SELECT MAX(id) FROM externalcalendartask";
            if($stmt = $link->prepare($sql))
            {
                if($stmt->execute())
                {
                    $result = $stmt->get_result();
                    while($row = $result->fetch_assoc()) {
                        $maxid = $row['MAX(id)'] + 1;
                    }
                }
                else
                {
                    echo $stmt->error;
                    $maxid=0;
                }
                $stmt->close();
            }
            else
            {
                echo $link->error;
            }

            $sql = "INSERT INTO externalcalendartask(id, categoryid, externalcalendarid, internaltaskid, externaltaskid) "
                . " VALUES (?,?,?,?,?)";
            if($stmt = $link->prepare($sql))
            {
                 $stmt->bind_param("iiiis", $maxid, $categoryid, $externalcalendarid, $internaltaskid, $externaltaskid);
                 if($stmt->execute())
                 {
                     $ret = 1;
                 }
                 else
                 {
                     echo $stmt->error;
                     $ret = 3;
                 }
                 $stmt->close();
             }
             else
             {
                 echo $link->error;
             }
            
        return $ret;
        
    }
    
    private function UpdateTaskToExternalCalendars_Google($calendar, $eventid)
    {
        $ret = 0;
        
        $timezone = 'GMT';
        if(isset($_SESSION['userid']) && $_SESSION['userid'] != "")
        {
            $usr = new User($_SESSION['userid']);
            if(isset($usr) && $usr->id > -1)
            {
                $usr->loadConfiguration();
                $timezone = $usr->timezone;
            }
        }
        $sdate = new DateTime($this->earlystart, new DateTimeZone($timezone));
        $edate = new DateTime($this->latefinish, new DateTimeZone($timezone));
        $strdate_start = $sdate->format('Y-m-d\TH:i:sP'); //$sdate->setTimezone(new DateTimeZone($timezone))->format('Y-m-d\TH:i:sP');
        $strdate_end = $edate->format('Y-m-d\TH:i:sP'); //$edate->setTimezone(new DateTimeZone($timezone))->format('Y-m-d\TH:i:sP');
        
        $util = new utilities();
        $client = $util->getGoogleClient();
        $client->setScopes($calendar->external_account->scope);
        $client->fetchAccessTokenWithRefreshToken($calendar->external_account->refresh_token);
        $a_tok = $client->getAccessToken();
        //$service = new Google_Service_Oauth2($client);
        //$user = $service->userinfo->get();
        $calService = new Google_Service_Calendar($client);
        // First retrieve the event from the API.
        $event = $calService->events->get($calendar->calendar_name, $eventid);
        $event->setSummary($this->name);
        $event->setDescription($this->description);
        $event->setStart(new Google_Service_Calendar_EventDateTime(['dateTime' => $strdate_start,'timeZone' => $timezone]));
        $event->setEnd(new Google_Service_Calendar_EventDateTime(['dateTime' => $strdate_end,'timeZone' => $timezone]));
        $updatedEvent = $calService->events->update($calendar->calendar_name, $event->getId(), $event);
        // Print the updated date.
        //echo $updatedEvent->getUpdated();
        $ret = 1;
        return $ret;
    }
    
    public function DeleteExternals()
    {
        $ret = 0;
        if($this->id != -1)
        {
            // Checks all external calendars for the category of the task
            $this->loadExternalTasks();
            foreach($this->external_tasks as $ext)
            {
                $ext->delete();
            }
            $ret = 1;
        }
        return $ret;
    }
}

class ExternalTask
{
    public $id;
    public $categoryid;
    public $externalcalendarid;
    public $externaltaskid;
    public $internaltaskid;
    public $calendartype;
    public $externalcalendar_name;
    public $externalcalendar_id;
    
    public function __construct($id=-1)
    {
        $this->id = -1;
        $this->categoryid = -1;
        $this->externalcalendarid = -1;
        $this->externaltaskid = -1;
        $this->internaltaskid = -1;
        $this->calendartype = -1;
        $this->externalcalendar_id = "";
        $this->externalcalendar_name = "";

        if($id != -1)
        {
            $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
                // Check connection
                if($link === false){
                    die("ERROR: Could not connect. " . mysqli_connect_error());
                }
                $sql = "SELECT externalcalendartask.id, externalcalendartask.categoryid, "
                        ." externalcalendarid, externaltaskid, internaltaskid, " 
                        ." externalcalendarscategories.calendartype, calendarid, calendarname "
                        ." FROM externalcalendartask INNER JOIN externalcalendarscategories ON "
                        ." (externalcalendartask.externalcalendarid = externalcalendarscategories.id) WHERE externalcalendartask.id=?";
                if($stmt = $link->prepare($sql))
                {
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while($row = $result->fetch_assoc()) {
                        $this->id = $row['id'];
                        $this->categoryid = $row['categoryid'];
                        $this->externalcalendarid = $row['externalcalendarid'];
                        $this->externaltaskid = $row['externaltaskid'];
                        $this->internaltaskid = $row['internaltaskid'];
                        $this->calendartype = $row['calendartype'];
                        $this->externalcalendar_id = $row['calendarid'];
                        $this->externalcalendar_name = $row['calendarname'];
                    }
                    $stmt->close();
                }
                else    
                {
                    //echo $link->error;
                }
                mysqli_close($link);
        }
    }
    
    // Removes the external task from the remote calendar and also from the internal database
    public function delete()
    {
        if($this->id != -1)
        {
            if($this->calendartype == "Google Calendar")
            {
                $ecal = new UserExternalCalendar($this->externalcalendarid);
                $eacc = new UserExternalAccount($ecal->external_account_id);
                $util = new utilities();
                $client = $util->getGoogleClient();
                $client->setScopes($eacc->scope);//$calendar->external_account->scope);
                $client->fetchAccessTokenWithRefreshToken($eacc->refresh_token);//$calendar->external_account->refresh_token);
                $a_tok = $client->getAccessToken();
                $calService = new Google_Service_Calendar($client);
                try
                {
                    $event = $calService->events->delete($this->externalcalendar_id, $this->externaltaskid);
                }
                catch(Exception $e)
                {
                    
                }
            }
            else if($this->calendartype == "Microsoft Outlook")
            {
                
            }
            
            // Attempt insert query execution
            $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
            // Check connection
            if($link === false){
                die("ERROR: Could not connect. " . mysqli_connect_error());
            }

            $sql = "DELETE FROM externalcalendartask WHERE id=?";
            if($stmt = $link->prepare($sql))
            {          
                 $stmt->bind_param("i", $this->id);
                 if($stmt->execute())
                 {
                     $ret = 1;
                 }
                 else
                 {
                     $ret = -4;
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