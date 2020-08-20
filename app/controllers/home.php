<?php
    $lang = $_SESSION['language'];
    require_once "assets/home_{$lang}.php"; 
    use YaLinqo\Enumerable;

class Home extends Controller
{
    public function index()
    {
        $this->model('UserModel');
        $this->view('home/index');
    }
    
 
    public function thankyou($email = "")
    {
        $this->view('/home/thankyou', ['email' => $email]);
    }
    
    public function activateaccount($account_id, $user_id, $mail, $checksum)
    {
        $activationflag=false;
        $this->model('AccountModel');
        $accmod = new AccountModel($account_id);
        $log = $accmod->log;
        $ret = $accmod->activateaccount($user_id, $mail, $checksum);
        if($ret == 1)
        {
             $activationflag = true;
        }

        $this->view('/home/activateaccount', ['activation' => $activationflag, 'accountid' => $account_id, 'userid'=>$user_id, 'checksum'=>$checksum, 'mail'=>$mail ]);
    }
    
    public function Main($category_id=-1)
    {
        $title = "DailyBoost: Main app";
        $category_list = array();
        $category_filter_name = _ALL_CATEGORIES;
        $tasks_in_execution = array();
        if(isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] !="" && strlen($_SESSION["username"])>0)
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
                $cat->loadTasksInExecution();
                $tasks_in_execution = $cat->tasks;
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
            
              $usr->loadTasksInExecution($category_id);
              $tasks_in_execution = $usr->tasks_in_execution;             
              $tasklist_sorted = from($tasklist)->orderByDescending('$v->plantask')->thenBy('$v->neverending')->thenBy('$v->latefinish')->thenBy('$v->earlystart')->toArray();
            $this->view('/layouts/layout_header', ['title' => $title]);
            $this->view('home/main', ['title'=>"DailyBoost",'tasks' => $tasklist_sorted, 'category' => $category_id,
                'category_filter_name' => $category_filter_name,
                'categories_list' => $category_list,
                'tasks_in_execution' => $tasks_in_execution]);
            $this->view('/layouts/layout_footer');
        }
        else
        {
            header("Location: index");
        }
    }
    
    
}