<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TasksExecutionController
 *
 * @author mgris
 */
class TasksExecutionController extends Controller {
    
    /* Returns:
     * 0 if generic error
     * TaskId if task created and started correctly
     * -2 if error in input data
     * -3 if task not found
     * -4 if user not authenticated
     */
    public function startTask()
    {
        $user_id = $_POST['user_id'];
        $category_id = $_POST['category_id'];
        $task_id = $_POST['task_id'];
        $taskname = $_POST['taskname'];

        $ret =0;
        if($_SESSION['userid'] > -1) // && $category_id > -1)
        {
            // Checks that category exists
            $this->model('Category');
            $cat = new Category($category_id);
            
            if($task_id!=-1 || $cat->id != -1)
            {
                $cat_id = -1;
                if($task_id ==-1)
                {

                    // Creates the new task
                    $task_id = $cat->addTask($taskname, "", 'now', '+1 hour');
                }

                // Then we can start the task!
                $this->model('Task');
                $tsk = new Task($task_id);
                if($tsk->id!=-1)
                {
                    $ret = $tsk->start($_SESSION['userid']);
                    if($ret == 1)
                    {
                        $ret = $tsk->id;
                    }
                    else
                    {
                        $ret = -$ret;
                    }
                }
                else
                {
                    $ret = -3;
                }
            }
            else
            {
                $ret = -2;
            }
        }
        else
        {
            $ret = -4;
        }
        echo $ret;
    }
    
    /*Returns:
     * 0 if generic error
     * 1 if tasked paused successfully
     * 2 if user not authenticated
     * 3 if task not found
     * 4 if category not found
     * 5 if task is not in execution
     */
    public function pauseTask()
    {
        $task_id = $_POST['task_id'];
        $this->model('Task');
        $ret =0;
        if($_SESSION['userid'] > -1 && $_SESSION['isLoggedIn'])
        {
            // Checks that category exists
            //$this->model('Category');
                $tsk = new Task($task_id);
                if($tsk->id != -1)
                {
                    if($tsk->status == 'E')
                    {
                        $ret = 1;
                        $tsk->pause($_SESSION['userid']);
                    }
                    else
                    {
                        $ret = 5;
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
        echo $ret;
    }
    
    /*Returns:
     * 0 if generic error
     * 1 if tasked paused successfully
     * 2 if user not authenticated
     * 3 if task not found
     * 5 if task is not in execution
     */
    public function finishTask()
    {
        $task_id = $_POST['task_id'];
        $ret =0;
        if($_SESSION['userid'] > -1 && $_SESSION['isLoggedIn'])
        {
            $this->model('Task');
                $tsk = new Task($task_id);
                if($tsk->id !=-1)
                {
                    if($tsk->status == 'E')
                    {
                        $ret = $tsk->finish($_SESSION['userid']);
                    }
                    else
                    {
                        $ret = 5;
                    }
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
        echo $ret;
    }
    
    public function tasks_in_exection_list()
    {
        $category_id = $_POST['category_id'];
        if($_SESSION['isLoggedIn'] && $_SESSION['userid'] != "" && is_numeric($category_id))
        {
            $this->model('User');
            $usr = new User($_SESSION['userid']);
            if($usr->id != -1)
            {
                $usr->loadTasksInExecution($category_id);
                echo json_encode($usr->tasks_in_execution);
            }
        }
        return null;
    }
    
    public function tasks_notes_list()
    {
        $taskid = $_POST['taskid'];
        if($_SESSION['isLoggedIn'] && strlen($_SESSION["userid"])>0)
        {
            if($taskid>0)
            {
                $this->model('Task');
                $tsk = new Task($taskid);
                if($tsk->id!=-1)
                {
                    $tsk->loadNotes();
                    $this->view('tasksexecution/tasksnotesform', ['task_id' => $tsk->id, 'tasks_notes' => $tsk->notes]);
                }
            }
        }
    }
    
    public function addNote()
    {
        $taskid = $_POST['taskid'];
        $note = $_POST['note'];
        $private = $_POST['private'];
        if($private == "true")
        {
            $private = 1;
        }
        else {
            $private = 0;
        }
        
        if($_SESSION['userid'] > -1 && $_SESSION['isLoggedIn'])
        {
            $user_id = $_SESSION['userid'];
            if($taskid > 0 && strlen($note)>0)
            {
                $this->model('Task');
                $tsk = new Task($taskid);
                $tsknote = $tsk->addNote($user_id, $note, $private);
                if($tsknote >=0)
                {
                    $tsknt = new TaskNote($tsknote);
                    $ret = json_encode($tsknt);
                }
                else
                {
                    $ret= -2;
                }
            }
        }
        echo $ret;
    }

    public function UpComingTasks()
    {
        $category_id=$_POST['category_id'];
        $title = "DailyBoost: Main app";
        $category_list = array();
        $tasks_in_execution = array();
        if($_SESSION['isLoggedIn'] && strlen($_SESSION["username"])>0)
        {            
            $this->model('User');
            $usr = new User($_SESSION['userid']);
            $this->model('Category');
            if($category_id!=-1)
            {
                $cat = new Category($category_id);
                if($cat->id == -1)
                {
                    $category_id = -1;
                }
            }
            
            $tasklist = array();
            if($category_id!=-1)
            {
                // Loads tasks of the chosen category
                $cat = new Category($category_id);
                $cat->loadPausedTasks();
                $tasklist = $cat->tasks;
                $category_filter_name = $cat->name;
            }
            else
            {
                $this->model('Task');
                $usr->loadCategories();
                for($i=0; $i < sizeof($usr->categories); $i++)
                {
                    array_push($category_list, $usr->categories[$i]);
                    $usr->categories[$i]->loadPausedTasks();        
                    for($j=0;$j< sizeof($usr->categories[$i]->tasks);$j++)
                    {
                        array_push($tasklist, $usr->categories[$i]->tasks[$j]);
                    }
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
                        $curracc->categories[$j]->loadPausedTasks();
                        for($k=0;$j< sizeof($curracc->categories[$j]->tasks);$k++)
                        {
                            array_push($tasklist, $curracc->categories[$j]->tasks[$k]);
                        }
                    }                    
                }
            }
            $tasklist_sorted = from($tasklist)->orderBy('$v->neverending')->thenBy('$v->latefinish')->thenBy('$v->earlystart')->toArray();
            echo json_encode($tasklist_sorted);
        }
        else
        {
        }
    }
    
}