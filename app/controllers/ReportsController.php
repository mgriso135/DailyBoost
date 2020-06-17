<?php
    $lang = $_SESSION['language'];
    require_once "assets/ReportsController_{$lang}.php"; 
    use YaLinqo\Enumerable;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ReportsController
 *
 * @author mgris
 */
class ReportsController extends Controller {
    public function DailyReport()
    {
        if($_SESSION['isLoggedIn'] && strlen($_SESSION["userid"])>0)
        {
            $this->model('TasksTimespans');
            $gmtTimezone = new DateTimeZone('GMT');
            $start = gmdate('Y-m-d 0:0:0', strtotime('-1 month'));
            $end = gmdate('Y-m-d 0:0:0', strtotime(' +1 day'));
            $tev = new TasksTimespans($_SESSION['userid'], $start, $end);
            
            // Group by task id           
            $timespans =  $tev->timespans_list;
            $tasklist = array();
            for($i = 0; $i < sizeof($timespans); $i++)
            {
                $curr = ['taskid' => $timespans[$i]->taskid, 'taskname' => $timespans[$i]->task_name];
                if(!in_array($curr, $tasklist))
                {
                    array_push($tasklist, $curr);
                }
            }
            $tasklist = json_encode(array_values($tasklist));
            //var_dump($tasklist);
            $header_title = "Daily Report : DailyBoost";
            $title = "Daily Report";
            $this->view('/layouts/layout_header', ['title' => $header_title]);
            $this->view('reports/dailyreport', ['title' => $title, 'tasks' => $tasklist]);
            $this->view('/layouts/layout_footer');
        }
    }
    
    public function getUserTimespans()
    {
        if($_SESSION['isLoggedIn'] && strlen($_SESSION["userid"])>0)
        {
            $this->model('TasksTimespans');
            $userid = $_POST['userid'];
            $start = $_POST['start'];
            $end = $_POST['end'];
            $tev = new TasksTimespans($_SESSION['userid'], $start, $end);
            echo json_encode($tev->timespans_list);
        }
    }
    
    public function getUserDailyReportGroupedByTaskId()
    {
        if($_SESSION['isLoggedIn'] && strlen($_SESSION["userid"])>0)
        {
            $this->model('TasksTimespans');
            $userid = $_POST['userid'];
            $start = $_POST['start'];
            $end = $_POST['end'];
            $tev = new TasksTimespans($_SESSION['userid'], $start, $end);
            $timespans =  $tev->timespans_list;
            $tsGroup = from($timespans)
                ->groupBy(
                    function($v) { return $v->taskid; },
                    null,
                    function($timespans) {
                        return [
                            'taskid' => from($timespans)->first()->taskid,
                            'taskname' => from($timespans)->first()->task_name,
                            'category_name' => from($timespans)->first()->category_name,
                            'totalDuration' => Enumerable::from($timespans)->sum(
                                function($p) { return $p->duration; }
                            )
                        ];
                    }
                )->orderByDescending(function($v){return $v['totalDuration'];});
            $arrTasks = array_values($tsGroup->toArray());
            //echo json_encode($arrTasks);
            
            $tsGroup = from($timespans)
                ->groupBy(
                    function($v) { return $v->category_id; },
                    null,
                    function($timespans) {
                        return [
                            'category_id' => from($timespans)->first()->category_id,
                            'category_name' => from($timespans)->first()->category_name,
                            'totalDuration' => Enumerable::from($timespans)->sum(
                                function($p) { return $p->duration; }
                            )
                        ];
                    }
                )->orderByDescending(function($v){return $v['totalDuration'];});
            $arrCategories = array_values($tsGroup->toArray());
            //echo json_encode($arrCategories);
            
            $arr = array('tasks' => ($arrTasks), 'categories' => ($arrCategories));
            
            echo json_encode($arr);
        }
    }
}
?>