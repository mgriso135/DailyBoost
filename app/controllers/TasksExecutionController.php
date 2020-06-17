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
                    $task_id = $cat->addTask($taskname);
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
}
