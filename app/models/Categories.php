<?php
require_once('../bin/utilities.php');
require_once('Category.php');
$lang = $_SESSION['language'];

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Category
 *
 * @author mgris
 */
class Categories {
    public $list;
    
    public function __construct()
    {
        $this->list = array();
    }
    
    public function loadCategories()
    {
        $this->list = array();
        // Attempt insert query execution
        $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
        // Check connection
        if($link === false){
                            die("ERROR: Could not connect. " . mysqli_connect_error());
        }
       
        $sql = "SELECT id, name, description FROM categories WHERE active IS true";
       if($stmt = $link->prepare($sql))
       {
            $stmt->bind_param("i", $category_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows === 0) {
            }
            else
            {
                while($row = $result->fetch_assoc()) {
                    $curr = new Category($row['id']);
                    array_push($this->list, $curr);
                }
            }
            $stmt->close();
            
        }
        mysqli_close($link);
    }

    /* Returns:
     * 0 if generic error
     * Category_id if everything is ok 
     */
public function add($name, $description)
{
    // Attempt insert query execution
        $link = mysqli_connect(AppConfig::$DB_SERVER, AppConfig::$DB_USERNAME, AppConfig::$DB_PASSWORD, AppConfig::$DB_NAME);
        // Check connection
        if($link === false){
                            die("ERROR: Could not connect. " . mysqli_connect_error());
        }
       
        $max=0;
        // Get max id value
        $sql = "SELECT MAX(id) FROM categories";
       if($stmt = $link->prepare($sql))
       {          
            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows === 0) {$max=0;}
            else{
            while($row = $result->fetch_assoc()) {
                 $max = $row['MAX(id)'] + 1;
            }
            }
            $stmt->close();
            
        }
        
        $sql = "INSERT INTO categories(id, name, description, active) VALUES (?,?,?,?)";
       if($stmt = $link->prepare($sql))
       {          
           $enabled=true;
            $stmt->bind_param("issi", $max, $name, $description, $enabled);
            if(!$stmt->execute())
            {
                $max = 0;
                $log = $stmt->error;
            }
            $stmt->close();
        }
        mysqli_close($link);
        
        return $max;
}
}

