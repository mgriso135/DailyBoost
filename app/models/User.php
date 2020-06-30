<?php
require_once('../bin/utilities.php');
require_once('Categories.php');
require_once('AccountModel.php');
require_once('Task.php');
$lang = $_SESSION['language'];
require_once "assets/User_{$lang}.php"; 

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author mgris
 */
class User
{
    public $name;
    public $id;
    public $GoogleID;
    public $username;
    public $email;
    public $firstname;
    public $lastname;
    public $password;
    public $language;
    public $region;
    public $verified;
    public $checksum;
    public $creationdate;
    public $enabled;
    public $IsLoggedIn;
    
    public $max_tasks_in_execution; // The max number of tasks that the user can run simultaneously
    public $timezone;
    public $default_task_id; // Task that is automatically executed when there are no tasks running
    
    public $categories;
    public $accounts;
    
    public $tasks_in_execution;
    public $never_ending_tasks;
    public $tasks;
    public $tasks_planned;
    
    public function __construct($user_id=-1)
    {
        $this->IsLoggedIn = false;
        $this->categories=[];
        $this->accounts = array();
        if($user_id>-1)
        {
        // Attempt insert query execution
        $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
        // Check connection
        if($link === false){
            die("ERROR: Could not connect. " . mysqli_connect_error());
        }
       
        $sql = "SELECT id, GoogleId, username, email, firstname, lastname, language, region ".
                " FROM users WHERE enabled = true AND id = ?";
       if($stmt = $link->prepare($sql))
       {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows === 0) {$this->id = -1; die("No user found");}

        while($row = $result->fetch_assoc()) {
            $this->id = $row['id'];
            $this->GoogleID = $row['GoogleId'];
            $this->username = $row['username'];
            $this->email = $row['email'];
            $this->firstname = $row['firstname'];
            $this->lastname = $row['lastname'];
            $this->language = $row['language'];
            $this->username = $row['region'];
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
    
    public function login($username, $password)
    {
        $ret = false;
        $this->IsLoggedIn = false;
        // Attempt insert query execution
        $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
        // Check connection
        if($link === false){
                            die("ERROR: Could not connect. " . mysqli_connect_error());
        }
       
        $sql = "SELECT id, GoogleId, username, email, firstname, lastname, language, region ".
                " FROM users WHERE enabled = true AND username LIKE ? AND password = ?";
       if($stmt = $link->prepare($sql))
       {
        $stmt->bind_param("ss", $username, md5($password));
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows === 0) {session_destroy(); $_SESSION = array();  return false;}
            
        while($row = $result->fetch_assoc()) {
            $this->id = $row['id'];
            $this->GoogleID = $row['GoogleId'];
            $this->username = $row['username'];
            $this->email = $row['email'];
            $this->firstname = $row['firstname'];
            $this->lastname = $row['lastname'];
            $this->language = $row['language'];
            $this->username = $row['region'];
        }
        $stmt->close();
        
        $this->IsLoggedIn = true;
        $ret = true;
        $_SESSION['userid'] = $this->id;
        $_SESSION['email'] = $this->email;
        $_SESSION['username'] = $this->email;        
        $_SESSION['language'] = $this->language;
        $_SESSION['isLoggedIn'] = true;
       }
       else
       {
           $ret =false;
       }
       mysqli_close($link);
        return $ret;
    }
    
       /* Returns:
     * 0 if User not found
     * 1 if everything is ok
     */
    public function loadCategories()
    {
        $ret = 0;
        $this->categories=array();
        if($this->id!=-1)
        {
            $usrid=$this->id;
             // Attempt insert query execution
        $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
        // Check connection
        if($link === false){
                            die("ERROR: Could not connect. " . mysqli_connect_error());
        }
       
        $sql = "SELECT id FROM categories INNER JOIN categoriesusers ON "
                . " (categories.id = categoriesusers.idcategory) WHERE categoriesusers.iduser = ? ORDER BY name";
       if($stmt = $link->prepare($sql))
       {
            $stmt->bind_param("i", $usrid);
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
    
    /* Returns:
     * 0 if generic error
     * 1 if everything went ok
     * 2 if error while creating the new category
     * 3 if error while linking the category to the user
     * 4 if user not found
     */
    public function addCategory($name, $description)
    {
        if($this->id!=-1)
        {
            
        $cats = new Categories();
        $catid = $cats->add($name, $description);

        if($catid!=-1)
        {
        // Attempt insert query execution
        $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
        // Check connection
        if($link === false){
            die("ERROR: Could not connect. " . mysqli_connect_error());
        }
       
        $sql = "INSERT INTO categoriesusers(idcategory, iduser) VALUES (?,?)";
       if($stmt = $link->prepare($sql))
       {
            $stmt->bind_param("ii", $catid, $this->id);
            if($stmt->execute())
            {
                $ret = 1;
            }
            else
            {
                $ret = 3;
            }
            $stmt->close();
        }
        mysqli_close($link);
        }
        else
        {
            $ret = 2;
        }
        }
        else {
            $ret = 4;
        }
        return $ret;
    }

    public function loadAccounts()
    {
        $ret = 0;
        $this->accounts=array();
        if($this->id!=-1)
        {
            $usrid=$this->id;
             // Attempt insert query execution
        $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
        // Check connection
        if($link === false){
                            die("ERROR: Could not connect. " . mysqli_connect_error());
        }
       
        $sql = "SELECT accountid FROM useraccounts INNER JOIN accounts ON (useraccounts.accountid = accounts.id) "
                ."WHERE useraccounts.enabled = true AND userid = ?";
       if($stmt = $link->prepare($sql))
       {
            $stmt->bind_param("i", $usrid);
            $stmt->execute();
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()) {
                $curr = new AccountModel($row['accountid']);
                array_push($this->accounts, $curr);
             }
            $stmt->close();
            $ret = 1;
        }
        mysqli_close($link);
        }
        return $ret;
    }
    
    public function loadConfiguration()
    {
        $this->timezone = "UTC";
        $this->max_tasks_in_execution = 1;
        if($this->id!=-1)
        {
            $usrid=$this->id;
             // Attempt insert query execution
        $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
        // Check connection
        if($link === false){
                            die("ERROR: Could not connect. " . mysqli_connect_error());
        }
       
        $sql = "SELECT section, parameter, value FROM userconfiguration WHERE userid = ?";
       if($stmt = $link->prepare($sql))
       {
            $stmt->bind_param("i", $usrid);
            $stmt->execute();
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()) {
                switch ($row['parameter']) {
                    case "Timezone":
                        $this->timezone = $row['value'];
                        break;
                    case "max_tasks_in_execution":
                        //echo "found... ".$row['value'];
                        $this->max_tasks_in_execution = (int)$row['value'];
                        break;
                    case "default_task_id":
                        $this->default_task_id = (int)$row['value'];
                        break;
                    default: break;
        }
             }
            $stmt->close();
            $ret = 1;
        }
        mysqli_close($link);
        }
    }
    
    /* Returns:
     * 0 if generic error
     * 1 if set ok
     * 2 if user not found
     * 3 if timezone not found
     * 4 error while executing the query
     */
    public function setTimezone($tz)
    {
        $ret = 0;
        $tz1 = urldecode($tz);
        $tz1 = str_replace('.','/',$tz1);
        if($this->id)
        {
            $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
            if(in_array($tz1, $tzlist, true))
            {
                $cfgexists=0;
                // Attempt insert query execution
                $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
                // Check connection
                if($link === false){
                    die("ERROR: Could not connect. " . mysqli_connect_error());
                }
                
                $sql = "SELECT * FROM userconfiguration WHERE userid = ? AND parameter = 'Timezone'";
                if($stmt = $link->prepare($sql))
                {
                    $stmt->bind_param("i", $this->id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while($row = $result->fetch_assoc()) {
                        $cfgexists = 1;
                    }
                }

                if($cfgexists == 0)
                {
                    // Update
                    $sql = "INSERT INTO userconfiguration(userid, section, parameter, value) VALUES (?, 'Main', ?, ?)";
                    if($stmt = $link->prepare($sql))
                    {
                        $parameter="Timezone";
                        $stmt->bind_param("iss", $this->id, $parameter, $tz1);
                        if($stmt->execute())
                        {
                            $ret = 1;
                        }
                        else
                        {
                            $ret = 4;
                        }
                        $stmt->close();
                    }
                }
                else
                {
                    // Insert into
                    $sql = "UPDATE userconfiguration SET value=? WHERE parameter = 'Timezone' AND userid=?";
                    if($stmt = $link->prepare($sql))
                    {
                        $stmt->bind_param("si", $tz1, $this->id);
                        if($stmt->execute())
                        {
                            $ret = 1;
                        }
                        else
                        {
                            $ret = 3;
                        }
                        $stmt->close();
                    }
                    else
                    {
                        $ret =0;
                    }
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
            $ret = 2;
        }
        return $ret;
    }
    
       /* Returns:
     * 0 if generic error
     * 1 if set ok
     * 2 if user not found
     * 3 if timezone not found
     * 4 error while executing the query
     * 5 if input is not greather than 0
     */
    // max_tasks_in_execution
    public function setMaxTasksInExecution($no_tasks)
    {
        $ret = 0;
        if($no_tasks > 0)
        {
            if($this->id)
            {
                $cfgexists=0;
                // Attempt insert query execution
                $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
                // Check connection
                if($link === false){
                    die("ERROR: Could not connect. " . mysqli_connect_error());
                }
                
                $sql = "SELECT * FROM userconfiguration WHERE userid = ? AND parameter = 'max_tasks_in_execution'";
                if($stmt = $link->prepare($sql))
                {
                    $stmt->bind_param("i", $this->id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while($row = $result->fetch_assoc()) {
                        $cfgexists = 1;
                    }
                }

                if($cfgexists == 0)
                {
                    // Update
                    $sql = "INSERT INTO userconfiguration(userid, section, parameter, value) VALUES (?, 'Main', ?, ?)";
                    if($stmt = $link->prepare($sql))
                    {
                        $parameter="max_tasks_in_execution";
                        $stmt->bind_param("isi", $this->id, $parameter, $no_tasks);
                        if($stmt->execute())
                        {
                            $ret = 1;
                        }
                        else
                        {
                            $ret = 4;
                        }
                        $stmt->close();
                    }
                }
                else
                {
                    // Insert into
                    $sql = "UPDATE userconfiguration SET value=? WHERE parameter = 'max_tasks_in_execution' AND userid=?";
                    if($stmt = $link->prepare($sql))
                    {
                        $stmt->bind_param("ii", $no_tasks, $this->id);
                        if($stmt->execute())
                        {
                            $ret = 1;
                        }
                        else
                        {
                            $ret = 3;
                        }
                        $stmt->close();
                    }
                    else
                    {
                        $ret =0;
                    }
                }
                
                mysqli_close($link);    
            }
            else
            {
                $ret = 2;
            }
        }
        else
        {
            $ret = 5;
        }
        return $ret;
    }
    
    public function loadTasksInExecution($category_id=-1)
    {
        $this->tasks_in_execution = array();
        if($this->id!=-1)
        {
            $usrid=$this->id;
             // Attempt insert query execution
            $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
            // Check connection
            if($link === false){
                die("ERROR: Could not connect. " . mysqli_connect_error());
            }
            $sql = "SELECT DISTINCT(tasks.id) AS tasks_id FROM tasks INNER JOIN tasksevents ON(tasks.id = tasksevents.taskid) WHERE tasks.status = 'E' "
                    . " AND tasksevents.userid=?";
            if($stmt = $link->prepare($sql))
            {
                
            $stmt->bind_param("i", $usrid);
            $stmt->execute();
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()) {
                $curr = new Task($row['tasks_id']);
                $lastev = $curr->getLastEvent($this->id);
                if($lastev->eventtype == "S" || $lastev->eventtype == "R")
                {
                    if($category_id == -1)
                    {
                        array_push($this->tasks_in_execution, $curr);
                    }
                    else
                    {
                        $curr->loadCategories();
                        $found = 0;
                        for($q = 0; $q < sizeof($curr->categories) && $found == 0; $q++)
                        {
                            if($curr->categories[$q]->id == $category_id)
                            {
                                $found = 1;
                            }
                        }
                        if($found == 1) {
                            array_push($this->tasks_in_execution, $curr);
                        }
                    }
                }
            }
            $stmt->close();
            }
            
            $ret = 1;
        
            mysqli_close($link);
        }
        //return $ret;
    }
    
    public function loadDefaultTaskId()
    {
        $this->default_task_id = -1;
        if($this->id!=-1)
        {
            $usrid=$this->id;
             // Attempt insert query execution
            $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
            // Check connection
            if($link === false){
                            die("ERROR: Could not connect. " . mysqli_connect_error());
            }
       
            $sql = "SELECT section, parameter, value FROM userconfiguration WHERE userid = ? AND parameter = 'default_task_id'";
            if($stmt = $link->prepare($sql))
            {
                $stmt->bind_param("i", $usrid);
                $stmt->execute();
                $result = $stmt->get_result();
                while($row = $result->fetch_assoc()) {
                    $this->default_task_id = (int)$row['value'];
                }
                $stmt->close();
                $ret = 1;
            }
            mysqli_close($link);
        }
    }
    
    /*
     * Returns:
     * 0 if generic error
     * 1 if set correctly
     * 2 if task does not belong to this user
     * 3 if task is not neverending
     */
    public function setDefaultTaskId($task_id)
    {
        $ret = 0;
        $founduser = 0;
        if($task_id != -1)
        {
            $tsk = new Task($task_id);
            
            if($tsk->id!=-1 && $tsk->neverending == true)
            {
                $tsk->loadCategories();
                for($i=0; $i < sizeof($tsk->categories) && $founduser == 0; $i++)
                {
                    $tsk->categories[$i]->loadUsers();
                    for($j=0; $j < sizeof($tsk->categories[$i]->users) && $founduser == 0; $j++)
                    {
                        $usr = $tsk->categories[$i]->users[$j];
                        if($usr->id == $this->id)
                        {
                            $founduser = 1;
                            $task_id = $tsk->id;
                        }
                    }
                }
            }
            else
            {
                $ret = 3;
            }
        }
        else if($task_id == -1)
        {
            $founduser=1;
        }
        
        if($founduser == 1)
        {
            // Attempt insert query execution
            $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
            // Check connection
            if($link === false){
                die("ERROR: Could not connect. " . mysqli_connect_error());
            }
            $sql = "SELECT section, parameter, value FROM userconfiguration WHERE userid = ? AND parameter = 'default_task_id'";
            $update_or_insert = 0;
            if($stmt = $link->prepare($sql))
            {
                $stmt->bind_param("i", $this->id);
                $stmt->execute();
                $result = $stmt->get_result();
                while($row = $result->fetch_assoc()) {
                    $update_or_insert = 1;
                }
                $stmt->close();
            }

            if($update_or_insert == 1)
            {
                // Update
                $sql = "UPDATE userconfiguration SET value = ? WHERE userid = ? AND parameter = 'default_task_id'";
            }
            else
            {
                // Insert
                $sql = "INSERT INTO userconfiguration(userid, section, parameter, value) VALUES(?, 'Main', 'default_task_id', ?)";
            }
            $link->begin_transaction();
            try
            {
                if($stmt = $link->prepare($sql))
                {
                    if($update_or_insert == 1)
                    {
                        $stmt->bind_param("ii", $task_id, $this->id);
                    }
                    else
                    {
                        $stmt->bind_param("ii", $this->id, $task_id);
                    }
                    $stmt->execute();
                    $link->commit();
                    $stmt->close();
                }
                else
                {
                }
                
            } catch (Exception $ex) {
                $link->rollback();
            }
            $link->close();

        }
        
        return $ret;
    }
    
    public function loadNeverEndingTaks()
    {
        $this->tasks = array();
        $this->never_ending_tasks = array();
        if($this->id)
        {
            // Attempt insert query execution
            $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
            // Check connection
            if($link === false){
                            die("ERROR: Could not connect. " . mysqli_connect_error());
            }
       
            $sql = "SELECT tasks.id AS taskid from users INNER JOIN categoriesusers ON "
                    ."(users.id = categoriesusers.iduser) INNER JOIN categoriestasks ON "
                    ." (categoriestasks.categoryid = categoriesusers.idcategory) INNER JOIN "
                    ." tasks ON (tasks.id = categoriestasks.taskid) "
                    ." WHERE neverending = true and tasks.status <> 'F' AND users.id = ?";
            if($stmt = $link->prepare($sql))
            {
                $stmt->bind_param("i", $this->id);
                $stmt->execute();
                $result = $stmt->get_result();
                while($row = $result->fetch_assoc()) {
                    $curr = new Task($row['taskid']);
                    array_push($this->never_ending_tasks, $curr);
                }
                $stmt->close();
                $ret = 1;
            }
            mysqli_close($link);
        }
    }
    
    public function loadTasksPlanned($categories_filter=[])
    {
        $this->tasks_planned = array();
        if($this->id!=-1)
        {
            $usrid=$this->id;
             // Attempt insert query execution
            $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
            // Check connection
            if($link === false){
                die("ERROR: Could not connect. " . mysqli_connect_error());
            }
            $sql = "SELECT tasks.id AS tasks_id, tasks.name AS task_name, tasks.description AS task_description, tasks.status AS tasks_status, "
                    . " tasks.neverending AS neverending, tasks.plannedcycletime AS plannedcycletime, "
                    . " earlystart, latefinish, categories.id AS category_id, categories.name AS categories_name "
                    . " FROM tasks INNER JOIN categoriestasks ON(tasks.id = categoriestasks.taskid) "
                    . " INNER JOIN categories ON (categoriestasks.categoryid = categories.id)"
                    . " INNER JOIN categoriesusers ON (categoriesusers.idcategory = categoriestasks.categoryid) "
                    ." WHERE tasks.status <> 'F' "
                    . " AND categoriesusers.iduser=? ";
            
            if(sizeof($categories_filter) > 0)
            {
                $sql .= " AND (";
                for($i = 0; $i < sizeof($categories_filter) - 1; $i++)
                {
                    if(is_numeric($categories_filter[$i]))
                    {
                        $sql .= "categories.id = " . $categories_filter[$i] . " OR ";
                    }
                }
                $sql .= "categories.id = " . $categories_filter[sizeof($categories_filter) - 1] 
                        . ")";
            }
            $sql.= " ORDER BY neverending ASC, latefinish ASC, earlystart ASC";
            
            if($stmt = $link->prepare($sql))
            {
                $this->loadConfiguration();
                $stmt->bind_param("i", $this->id);
                $stmt->execute();
                $result = $stmt->get_result();
                while($row = $result->fetch_assoc()) {
                    $curr = new Task();
                    $curr->id = $row['tasks_id'];
                    $curr->name = $row['task_name'];
                    $curr->description = $row['task_description'];
                    $curr->status = $row['tasks_status'];
                    $curr->neverending = $row['neverending'];
                    $curr->plannedcycletime = $row['plannedcycletime'];
                    $curr->earlystart = new DateTime($row['earlystart'], new DateTimeZone('GMT'));
                    $curr->earlystart->setTimezone(new DateTimeZone($this->timezone))->format('Y-m-d H:i:s');
                    $curr->latefinish = new DateTime($row['latefinish'], new DateTimeZone('GMT'));
                    $curr->latefinish->setTimezone(new DateTimeZone($this->timezone))->format('Y-m-d H:i:s');
                    $curr->category_id = $row['category_id'];
                    $curr->category_name = $row['categories_name'];
                    array_push($this->tasks_planned, $curr);
                }
                $stmt->close();
            }
            
            $ret = 1;
        
            mysqli_close($link);
        }
        //return $ret;
    }
}