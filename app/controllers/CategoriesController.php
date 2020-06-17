<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of categories
 *
 * @author mgris
 */
class CategoriesController extends Controller {
    
    public function usercategories()
    {
        if(isset($_SESSION['userid']))
        {
            $userid = $_SESSION['userid'];
            $this->model('User');
            $curr = new User($userid);
            $curr->loadCategories();
            $strCategories = array();
            for($i=0; $i < sizeof($curr->categories); $i++)
            {
                array_push($strCategories, $curr->categories[$i]->name);
            }
            $this->view('/categories/usercategories', ['categories'=> $curr->categories ]);
        }
        else
        {
            $this->view('/categories/usercategories');
        }
    }
    
    public function addusercategory_showview()
    {
        $ret = false;
        if(isset($_SESSION['userid']))
        {
            $this->view('/categories/addusercategory_showview');
        }
        else
        {
            
        }
        return $ret;
    }
    
    public function addusercategory($category_name)
    {
        $ret = 0;
        if(isset($_SESSION['userid']))
        {
            $userid = $_SESSION['userid'];
            $this->model('User');
            $curr = new User($userid);
            $ret = $curr->addCategory($category_name, "");
        }
        return $ret;
    }
}
