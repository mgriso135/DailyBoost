<?php
//require_once("../../../vendor/autoload.php");
require_once("../bin/utilities.php");
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
        if(isset($_SESSION['isLoggedIn']) && strlen($_SESSION["username"])>0)
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
    
    public function GoogleCalendarLinkView()
    {
        $title = "Main app";
        if($_SESSION['isLoggedIn'] && strlen($_SESSION["username"])>0)
        {
            $this->model('User');
            $usr = new User($_SESSION['userid']);
            if($usr->id!=-1)
            {
                $usr->loadConfiguration();
                $this->view('/userconfiguration/GoogleCalendarLinkView');
            }
        }
        else{
        }
    }
    
    public function GoogleCalendarRegisterToken()
    {
        if(isset($_SESSION['userid']) && $_SESSION['userid'] != "" && isset($_SESSION['isLoggedIn']))
        {
            if(isset($_POST['code']))
            {
                $code = $_POST['code'];
                $client = new Google_Client(['client_id' => AppConfig::$GOOGLE_CLIENT_ID]);
                $client->setClientId(AppConfig::$GOOGLE_CLIENT_ID);
                $client->setClientSecret(AppConfig::$GOOGLE_CLIENT_SECRET);
                //$client->setRedirectUri("https://www.virtualchief.net");
                $client->setRedirectUri("http://localhost:88");
                $client->setAccessType("offline");
                $client->setScopes("profile email https://www.googleapis.com/auth/calendar");
                $client->fetchAccessTokenWithAuthCode(urldecode($code));
                $this->model('User');
                $usr = new User($_SESSION['userid']);
                $a_tok = $client->getAccessToken();
                $service = new Google_Service_Oauth2($client);
                $account = $service->userinfo->get();
                $account_name = $account->email;
                $ret = $usr->addExternalApp("Calendar", "Google Calendar", $account_name, $a_tok['token_type'], 
                        $a_tok['scope'], $a_tok['id_token'], $a_tok['access_token'], 
                        $a_tok['refresh_token'], $a_tok['created'], $a_tok['expires_in']);

            }
        }
        else 
        {
            echo "Token not set";
        }
        echo $ret;
    }
    
    public function linkCategoriesToCalendars_View()
    {
        $ret = "";
        if(isset($_SESSION['userid']) && $_SESSION['userid'] != "" && isset($_SESSION['isLoggedIn']))
        {
            $this->model('User');
            $usr = new User($_SESSION['userid']);
            if($usr->id!=-1)
            {
                //$usr->loadConfiguration();
                $ret1 = $usr->getExternalCalendars();
                $this->view('/userconfiguration/linkCategoriesToCalendars_View', ['log' => $ret1]);
            }
        }
        else 
        {
            echo "Token not set";
        }
        echo $ret;
    }
    
    public function listExternalApps()
    {

        $categoryId = "";
        $appslist = array();
        if(isset($_SESSION['userid']) && $_SESSION['userid'] != "" && isset($_SESSION['isLoggedIn']))
        {        
            if(isset($_POST['Category']))
            {
                $categoryId = $_POST['Category'];
            }
            
            $this->model('User');
            $usr = new User($_SESSION['userid']);
            if($usr->id != -1)
            {
                $usr->loadExternalApps($categoryId);
                for($i = 0; $i < sizeof($usr->external_apps); $i++)
                {
                    $usr->external_apps[$i]->checkTokenValidity();
                }
                $this->view('/userconfiguration/UserExternalAppsList', ['apps' => $usr->external_apps]);
            }
        }
        return $appslist;
    }
}
