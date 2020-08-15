<?php
require_once('../bin/utilities.php');
require_once('UserExternalAccount.php');
$lang = $_SESSION['language'];

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Category
 *
 * @author mgris
 */
class Category {
    public $id;
    public $name;
    public $description;
    public $active;
    
    public $tasks;
    public $users;
    public $accounts;
    public $external_calendars;
    
    public function __construct($category_id=-1)
    {
        $this->id=-1;   
        $this->tasks = array();
        $this->users = array();
        $this->accounts = array();
        
        // Attempt insert query execution
        $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
        // Check connection
        if($link === false){
                            die("ERROR: Could not connect. " . mysqli_connect_error());
        }
       
        $sql = "SELECT id, name, description, active FROM categories WHERE id = ?";
       if($stmt = $link->prepare($sql))
       {
            $stmt->bind_param("i", $category_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows === 0) 
            {
                $this->id=-1;                
            }
            else
            {
                while($row = $result->fetch_assoc()) {
                    $this->id = $row['id'];
                    $this->name = $row['name'];
                    $this->description = $row['description'];
                    $this->active = $row['active'];
                }
            }
            $stmt->close();
        }
        mysqli_close($link);
    }
    
    public function setName($value)
    {
        if($this->id!=-1)
        {
        // Attempt insert query execution
        $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
        // Check connection
        if($link === false){
                            die("ERROR: Could not connect. " . mysqli_connect_error());
        }
       
        $sql = "UPDATE categories SET name = ? WHERE id = ?";
       if($stmt = $link->prepare($sql))
       {
            $stmt->bind_param("si", $value, $this->id);
            $stmt->execute();
            $stmt->close();
        }
        mysqli_close($link);
    }
    }
    
    public function setDescription($value)
    {
        if($this->id!=-1)
        {
        // Attempt insert query execution
        $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
        // Check connection
        if($link === false){
                            die("ERROR: Could not connect. " . mysqli_connect_error());
        }
       
        $sql = "UPDATE categories SET description = ? WHERE id = ?";
       if($stmt = $link->prepare($sql))
       {
            $stmt->bind_param("si", $value, $this->id);
            $stmt->execute();
            $stmt->close();
        }
        mysqli_close($link);
    }
    }

    public function loadTasks()
    {
     $this->tasks = array();
     if($this->id != -1)
     {
     // Attempt insert query execution
        $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
        // Check connection
        if($link === false){
                            die("ERROR: Could not connect. " . mysqli_connect_error());
        }
       
        $sql = "SELECT taskid FROM categoriestasks WHERE categoryid = ?";
       if($stmt = $link->prepare($sql))
       {
            $stmt->bind_param("i", $this->id);
            $stmt->execute();
            $result = $stmt->get_result();
                while($row = $result->fetch_assoc()) {
                    $curr = new Task($row['taskid']);
                    array_push($this->tasks, $curr);
                }
            $stmt->close();
        }
        mysqli_close($link);
        }
     }
     
    public function loadPausedTasks()
    {
     $this->tasks = array();
     if($this->id != -1)
     {
     // Attempt insert query execution
        $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
        // Check connection
        if($link === false){
                            die("ERROR: Could not connect. " . mysqli_connect_error());
        }
       
        $sql = "SELECT taskid FROM categoriestasks INNER JOIN tasks ON (tasks.id = categoriestasks.taskid) WHERE (status='P' OR status='N') AND categoryid = ?";
       if($stmt = $link->prepare($sql))
       {
            $stmt->bind_param("i", $this->id);
            $stmt->execute();
            $result = $stmt->get_result();
                while($row = $result->fetch_assoc()) {
                    $curr = new Task($row['taskid']);
                    $curr->loadCategories();
                    array_push($this->tasks, $curr);
                }
            $stmt->close();
        }
        mysqli_close($link);
        }
     }
     
     public function loadTasksInExecution()
    {
     $this->tasks = array();
     if($this->id != -1)
     {
     // Attempt insert query execution
        $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
        // Check connection
        if($link === false){
                            die("ERROR: Could not connect. " . mysqli_connect_error());
        }
       
        $sql = "SELECT taskid FROM categoriestasks INNER JOIN tasks ON (tasks.id = categoriestasks.taskid) WHERE status='E' AND categoryid = ?";
       if($stmt = $link->prepare($sql))
       {
            $stmt->bind_param("i", $this->id);
            $stmt->execute();
            $result = $stmt->get_result();
                while($row = $result->fetch_assoc()) {
                    $curr = new Task($row['taskid']);
                    array_push($this->tasks, $curr);
                }
            $stmt->close();
        }
        mysqli_close($link);
        }
     }
     
     /* Returns:
      * 0 if generic error
      * TaskID if task added correctly
      */
     public function addTask($taskname, $description="", $startdate, $enddate, $neverending=false, $plannedcycletime=0.0, $timezone='UTC')
     {
         $ret = 0;
         // Attempt insert query execution
        $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
        // Check connection
        if($link === false){
           die("ERROR: Could not connect. " . mysqli_connect_error());
        }
       
        $max=0;
        // Get max id value
        $sql = "SELECT MAX(id) FROM tasks";
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
        $link->begin_transaction();
        try
        {
            $sql = "INSERT INTO tasks(id, name, description, status, neverending, plannedcycletime, "
                ."earlystart, latestart, earlyfinish, latefinish, leadtime, workingtime, "
                . "delay, realenddate) "
                . "VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)"; 
            $stmt = $link->prepare($sql);
            $status = 'N';
            
            $sdate = new DateTime($startdate, new DateTimeZone($timezone));
            $edate = new DateTime($enddate, new DateTimeZone($timezone));
           $earlystart = $sdate->setTimezone(new DateTimeZone('GMT'))->format('Y-m-d H:i:s');
           $latestart = $sdate->setTimezone(new DateTimeZone('GMT'))->format('Y-m-d H:i:s');
           $earlyfinish = $edate->setTimezone(new DateTimeZone('GMT'))->format('Y-m-d H:i:s');
           $latefinish = $edate->setTimezone(new DateTimeZone('GMT'))->format('Y-m-d H:i:s');
           $leadtime = 0.0;
           $workingtime = 0.0;
           $delay = 0.0;
           $realenddate = null;
           $plannedcycletime=0;
           
           $neverending1 = false;
           if($neverending=="true")
           {
               $neverending1 = true;
           }

            $stmt->bind_param("isssiissssiiis", $max, $taskname, $description, $status,
            $neverending1, $plannedcycletime, $earlystart, $latestart, $earlyfinish, $latefinish, $leadtime, 
                    $workingtime, $delay, $realenddate);
            $stmt->execute();
            $stmt->close();
            
            $sql = "INSERT INTO categoriestasks(categoryid, taskid) VALUES (?,?)";
            $stmt = $link->prepare($sql);
            $stmt->bind_param("ii", $this->id, $max);
            $stmt->execute();
            $stmt->close();
            
            $ret = $max;
            $link->commit();
            
        } catch (Exception $ex) {
            $link->rollback();
            $ret =-1;
        }
                
        mysqli_close($link);
        return $ret;
     }
     
     public function loadUsers()
     {
        $this->users = array();
        if($this->id != -1)
        {
        // Attempt insert query execution
           $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
           // Check connection
           if($link === false){
                               die("ERROR: Could not connect. " . mysqli_connect_error());
           }

           $sql = "SELECT iduser FROM categoriesusers WHERE idcategory = ?";
          if($stmt = $link->prepare($sql))
          {
               $stmt->bind_param("i", $this->id);
               $stmt->execute();
               $result = $stmt->get_result();
                   while($row = $result->fetch_assoc()) {
                       $curr = new User($row['iduser']);
                       array_push($this->users, $curr);
                   }
               $stmt->close();
           }
           mysqli_close($link);
           }
     }
     
     public function loadExternalCalendars($userid=-1)
     {
         $this->external_calendars = array();
         if($this->id != -1)
         {
           // Attempt insert query execution
           $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
           // Check connection
           if($link === false){
                die("ERROR: Could not connect. " . mysqli_connect_error());
           }

            $sql = "SELECT externalcalendarscategories.id, categoryid, calendartype, externalaccountid, calendarid FROM externalcalendarscategories";
            if($userid!=-1)
            {
                $sql .= " INNER JOIN usersexternalaccounts ON (usersexternalaccounts.id = externalcalendarscategories.externalaccountid) ";
            }
            $sql .=  " WHERE categoryid = ?";
            if($userid != -1)
            {
                $sql .= " AND usersexternalaccounts.userid=?";
            }
            
            
            if($stmt = $link->prepare($sql))
            {
                if($userid!=-1)
                {
                 $stmt->bind_param("ii", $this->id, $userid);
                }
                else
                {
                 $stmt->bind_param("i", $this->id);
                }
                 if($stmt->execute())
                 {
                 $result = $stmt->get_result();
                     while($row = $result->fetch_assoc()) {
                         $curr = new UserExternalCalendar($row['externalaccountid'], $row['calendartype'], $row['calendarid']);
                         array_push($this->external_calendars, $curr);
                     }
                 }
                 else
                 {
                     echo $stmt->error;
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
     
     public function addExternalCalendar($user_id, $external_account_id, $calendar_type, $external_calendar_id, $external_calendar_name)
     {
         $ret = 0;
         // Attempt insert query execution
        $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
        // Check connection
        if($link === false){
           die("ERROR: Could not connect. " . mysqli_connect_error());
        }
       
        $max=0;
        // Get max id value
        $sql = "SELECT MAX(id) FROM externalcalendarscategories";
        if($stmt = $link->prepare($sql))
        {          
            $stmt->execute();
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()) {
                 $max = $row['MAX(id)'] + 1;
            }
            $stmt->close();
        }
        $link->begin_transaction();
        try
        {
            $sql = "INSERT INTO externalcalendarscategories(id, categoryid, externalaccountid, calendarid, calendarname, calendartype) "
                . "VALUES(?,?,?,?,?,?)"; 
            if($stmt = $link->prepare($sql))
            {
                $stmt->bind_param("iiisss", $max, $this->id, $external_account_id, $external_calendar_id, $external_calendar_name, $calendar_type);
                $stmt->execute();
                $stmt->close();
                $ret = 1;
                $link->commit();
            }
            else
            {
                echo "ERROR " . $link->error;
            }
            
        } catch (Exception $ex) {
            $link->rollback();
            $ret = -1;
        }
                
        mysqli_close($link);
        return $ret;
     }
     
     public function deleteExternalCalendar($id)
     {
         $ret = 0;         
         if($this->id != -1 && isset($_SESSION['userid']) && $_SESSION['userid'] != "")
         {
             $user_id = $_SESSION['userid'];
            // Attempt insert query execution
           $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
           // Check connection
           if($link === false){
              die("ERROR: Could not connect. " . mysqli_connect_error());
           }
           $link->begin_transaction();
           try
           {
               $sql = "DELETE FROM externalcalendarscategories WHERE id=? AND categoryid=?"; 
               if($stmt = $link->prepare($sql))
               {
                   $stmt->bind_param("ii", $id, $this->id);
                   $stmt->execute();
                   $stmt->close();
                   $ret = 1;
                   $link->commit();
               }
               else
               {
                   echo "ERROR " . $link->error;
               }

           } catch (Exception $ex) {
               $link->rollback();
               $ret = -1;
           }

           mysqli_close($link);
         }
        return $ret;
     }
}

