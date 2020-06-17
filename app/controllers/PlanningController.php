<?php
    $lang = $_SESSION['language'];
    require_once "assets/PlanningController_{$lang}.php"; 
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PlanningController
 *
 * @author mgris
 */
class PlanningController extends Controller {
    public function Index()
    {
        if($_SESSION['isLoggedIn'] && strlen($_SESSION["userid"])>0)
        {

            $header_title = "Planning Board : DailyBoost";
            $title = "Planning Board";
            $this->model('User');
            $usr = new User($_SESSION['userid']);
            //$this->model('Category');
            $category_list = array();
            $usr->loadCategories();
            for($i=0; $i < sizeof($usr->categories); $i++)
            {
                array_push($category_list, $usr->categories[$i]);
            }
            
            // Accounts categories
            $usr->loadAccounts();
            for($i=0; $i < sizeof($usr->accounts); $i++)
            {
                $curracc = $usr->accounts[$i];
                $curracc->loadCategories();
                for($j=0;$j< sizeof($curracc->categories);$j++)
                {
                    array_push($category_list, $curracc->categories[$j]);
                }
            }
            
            $usr->loadConfiguration();
            $this->view('/layouts/layout_header', ['title' => $header_title]);
            $this->view('/planningboard/index', ['title' => $title, 'categories_list' => $category_list, 'timezone' => $usr->timezone]);
            $this->view('/layouts/layout_footer');
        }
    }
    
    /* Returns:
     * 0 if generic error
     * Task Object if everything is ok
     * -1 if user not logged in
     * -2 if category not found
     * -3 if user does not own the category
     */
    public function addPlannedTask()
    {
        $ret = 0;
        $category_id = $_POST['category_id'];
        $taskname = $_POST['taskname'];
        $neverending = $_POST['neverending'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        if($_SESSION['isLoggedIn'] && $_SESSION['userid'])
        {
            $this->model('User');
            $usr = new User($_SESSION['userid']);
            $usr->loadCategories();
            
            $ownscategory = false;
            for($i=0;$i<sizeof($usr->categories) && !$ownscategory; $i++)
            {
                if($usr->categories[$i]->id == $category_id) { $ownscategory = true; }
            }
            if($ownscategory)
            {
                if($category_id >= 0 && strlen($taskname) > 0 && $start_date <= $end_date)
                {
                    $this->model('Category');
                    $cat = new Category($category_id);
                    if($cat->id !=-1)
                    {
                        $usr->loadConfiguration();
                        $ret = $cat->addTask($taskname, "", $start_date, $end_date, $neverending, 0.0, $usr->timezone);
                        if($ret > -1)
                        {
                            $this->model('Task');
                            $tsk = new Task($ret);
                            if($tsk->id!=-1)
                            {
                                $ret = json_encode($tsk);
                            }
                        }
                    }
                    else
                    {
                        $ret = -2;
                    }
                }
            }
            else
            {
                $ret = -3;
            }
        }
        else {
            $ret = -1;
        }
        echo $ret;
    }
    
    public function listPlannedTasks()
    {
        if($_SESSION["isLoggedIn"] && strlen($_SESSION['userid'])>0)
        {
            $cat_filter = json_decode($_POST['categories_filter']);
            $this->model("User");
            $usr = new User($_SESSION["userid"]);
            if($usr->id != -1 && sizeof($cat_filter) > 0)
            {
                $usr->loadTasksPlanned($cat_filter);
                echo json_encode($usr->tasks_planned);
            }
        }
    }
}
