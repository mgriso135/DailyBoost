<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserConfiguration
 *
 * @author mgris
 */
class UserConfigurationController extends Controller {
    public function Index()
    {
        $title = "Main app";
        if($_SESSION['isLoggedIn'] && strlen($_SESSION["username"])>0)
        {
            $this->model('User');
            $usr = new User($_SESSION['userid']);
            if($usr->id!=-1)
            {
                $usr->loadConfiguration();
                $this->view('/layouts/layout_header', ['title' => $title]);
                $this->view('/userconfiguration/main', ['timezone' => $usr->timezone]);
                $this->view('/layouts/layout_footer');
            }
        }
        else{
        }
    }
    
    public function TimezoneConfig()
    {
        if($_SESSION['isLoggedIn'] && strlen($_SESSION["username"])>0)
        {
            $this->model('User');
            $usr = new User($_SESSION['userid']);
            $usr->loadConfiguration();
            $tz = $usr->timezone;
            $this->view('/userconfiguration/timezone', ['timezone'=>$tz]);
        }
        else{
        }
    }
    
    public function savetimezone()
    {
        $newtimezone = $_POST['timezone'];
        $ret = 0;
        if($_SESSION['isLoggedIn'] && strlen($_SESSION["username"])>0)
        {
            $this->model('User');
            $usr = new User($_SESSION['userid']);
            $ret = $usr->setTimezone($newtimezone);
        }
        
        echo $ret;
    }
    
    public function MaxTasksConfig()
    {
        if($_SESSION['isLoggedIn'] && strlen($_SESSION["username"])>0)
        {
            $this->model('User');
            $usr = new User($_SESSION['userid']);
            $usr->loadConfiguration();
            $max_no_tasks = $usr->max_tasks_in_execution;
            $this->view('/userconfiguration/maxtasksconfig', ['maxtasks'=>$max_no_tasks]);
        }
        else{
        }
    }    
    public function savemaxtasks()
    {
        $max_no_tasks = $_POST['max_no_tasks'];
        $ret = 0;
        if($_SESSION['isLoggedIn'] && strlen($_SESSION["username"])>0 && $max_no_tasks>0)
        {
            $this->model('User');
            $usr = new User($_SESSION['userid']);
            $ret = $usr->setMaxTasksInExecution($max_no_tasks);
        }        
        echo $ret;
    }
    
    public function DefaultTaskConfig()
    {
        if($_SESSION['isLoggedIn'] && strlen($_SESSION["username"])>0)
        {
            $this->model('User');
            $usr = new User($_SESSION['userid']);
            $usr->loadDefaultTaskId();
            $default_task_id = $usr->default_task_id;
            $usr->loadNeverEndingTaks();
            $neverending_tasks = $usr->never_ending_tasks;
            $this->view('/userconfiguration/defaulttaskconfig', ['default_task_id'=>$default_task_id, 'never_ending_tasks' => $neverending_tasks]);
        }
        else{
        }
    }
    
    public function setdefaulttask()
    {
        $ret = 0;
        if($_SESSION['isLoggedIn'] && strlen($_SESSION["userid"]) > 0)
        {
            $taskid = $_POST['taskid'];
            $this->model('User');
            $usr = new User($_SESSION['userid']);
            $tsk = new Task($taskid);
            $usr->setDefaultTaskId($tsk->id);
            $ret = 1;
        }
        echo $ret;
    }
}
