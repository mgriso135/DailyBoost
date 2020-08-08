<?php
require_once("../bin/utilities.php");
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ExternalApps
 *
 * @author mgris
 */
class ExternalAppsController extends Controller {
    
    /* Returns:
     * 0 if user not logged in
     * 1 if calendar linked correctly to the category and to the account
     * 2 if calendar was already linked to the involved category
     * 3 if error in input variables
     * 4 if user not found
     * 5 if user does not have acces to the chosen category
     */
    public function linkExternalCalendarToCategory()
    {
        $ret = 0;
        if(isset($_SESSION['userid']) && $_SESSION['userid'] != "" && isset($_SESSION['isLoggedIn']))
        {
            if(isset($_POST['categoryid']) && $_POST['categoryid'] >= 0
                    && isset($_POST['externalAccountId']) && $_POST['externalAccountId'] >= 0
                    && isset($_POST['externalcalendarid']) && $_POST['externalcalendarid'] != "")
            {
                $catId = $_POST['categoryid'];
                $externalAccountId = $_POST['externalAccountId'];
                $externalcalendarid = $_POST['externalcalendarid'];
                $externalcalendarname = $_POST['externalcalendarname'];
                $this->model('User');
                $usr = new User($_SESSION['userid']);
                if($usr->id != -1)
                {
                    $usr->loadCategories();
                    $foundCat = false;
                    $cat = null;
                    for($p=0; $p < sizeof($usr->categories); $p++)
                    {
                        if($usr->categories[$p]->id == $catId)
                        {
                            $foundCat = true;
                            $cat = $usr->categories[$p];
                        }
                    }
                    if($foundCat == true)
                    {
                        $this->model('Category');
                        $cat = new Category($catId);
                        $cat->loadExternalCalendars($usr->id);
                        // Let's look if the external calendar already depends by our user/category
                        $foundCalendar = false;
                        for($o=0; $o < sizeof($cat->external_calendars); $o++)
                        {
                            if($cat->external_calendars[$o]->external_account_id == $externalAccountId
                                    && $cat->external_calendars[$o]->calendar_id == $externalcalendarid)
                            {
                                $foundCalendar = true;
                            }
                        }
                        if($foundCalendar == false)
                        {
                            // Let's add the calendar
                            $ret = $cat->addExternalCalendar($usr->id, $externalAccountId, $externalcalendarid, $externalcalendarname);
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

                }
            }
            else
            {
                $ret = 3;
            }
        }
        
        echo $ret;
    }
    
    /* Returns:
     * json_array if all is ok
     * 2 if user not found
     * 3 if category not found
     * 4 if user does not hace access to the specified category
     */
    public function loadExternalCalendarsUserCategory()
    {
        $ret = array();
        if(isset($_SESSION['userid']) && $_SESSION['userid'] != "" && isset($_SESSION['isLoggedIn']))
        {
            if(isset($_POST['categoryid']) && $_POST['categoryid'] >= 0)
            {
                $this->model('User');
                $usr = new User($_SESSION['userid']);
                if($usr->id!=-1)
                {
                    $category_id=$_POST['categoryid'];
                    $this->model('Category');
                    $cat = new Category($category_id);
                    if($cat->id != -1)
                    {
                        $usr->loadCategories();
                        $foundCat = false;
                        for($p=0; $p < sizeof($usr->categories); $p++)
                        {
                            if($usr->categories[$p]->id == $category_id)
                            {
                                $foundCat = true;
                            }
                        }
                        
                        if($foundCat == true)
                        {
                            $cat->loadExternalCalendars($usr->id);
                            echo json_decode($cat->external_calendars);
                            $ret = $cat->external_calendars;
                        }
                        else
                        {
                            $ret = 4;
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
            }
        }
        
        echo json_encode($ret);
    }
    
}
