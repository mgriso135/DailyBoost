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
                // create Client Request to access Google API
                $client = new Google_Client(['client_id' => AppConfig::$GOOGLE_CLIENT_ID]);
                $client->setClientId(AppConfig::$GOOGLE_CLIENT_ID);
                $client->setClientSecret(AppConfig::$GOOGLE_CLIENT_SECRET);
                $client->setRedirectUri("http://localhost:88");
                $client->setAccessType("offline");
                $client->fetchAccessTokenWithAuthCode(urldecode($code));
                //echo "Expired: " . json_encode($client->isAccessTokenExpired());
                $this->model('User');
                $usr = new User($_SESSION['userid']);
                //echo "\nAccess token: " . json_encode($client->getAccessToken()) . "\n\n" . $client->getRefreshToken();
                $a_tok = $client->getAccessToken();
                /*echo "Calendar " . " " . "Google Calendar" . " " . $a_tok['token_type'] . " Scope: " .  
                        $a_tok['scope'] . " id token: " . $a_tok['id_token'] . " access_token: " . $a_tok['access_token'] . " refresh token: " .  
                        $a_tok['refresh_token'] . " created: " . $a_tok['created'] . " expires in: " . $a_tok['expires_in'];*/
                $ret = $usr->addExternalApp("Calendar", "Google Calendar", $a_tok['token_type'], 
                        $a_tok['scope'], $a_tok['id_token'], $a_tok['access_token'], 
                        $a_tok['refresh_token'], $a_tok['created'], $a_tok['expires_in']);
                /*if ($payload) {
                    echo "Expired: " . $client->isAccessTokenExpired()."\n";
                    echo "AT: ". $client->getOAuth2Service()->getRefreshToken() . "\n";
                  $userid = $payload['sub'];
                  echo json_encode($payload);
                  
                } else {
                  // Invalid ID token
                    echo "Invalid ID token";
                }*/
            }
        }
        else 
        {
            echo "Token not set";
        }
        echo $ret;
    }
}
