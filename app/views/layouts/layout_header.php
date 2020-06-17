<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
    $lang = $_SESSION['language'];
    require_once "assets/layout_{$lang}.php"; 
?>
<html>
    <head>

        <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
        <title><?php echo $data['title'] ?></title>
        <!-- CSS only -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

<!-- JS, Popper.js, and jQuery -->

<script src="https://code.jquery.com/jquery-3.1.1.min.js">
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

      


  <!-- Custom fonts for this template -->
 
  <link href="../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="../../../vendor/simple-line-icons/css/simple-line-icons.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">

  <!-- Custom styles for this template -->
  <link href="../../../css/landing-page.min.css" rel="stylesheet">
  
  <?php 
            if(strlen($_SESSION['username'])>0)
            {?>
  <script>
  $(document).ready(function(){
  $("#dologout").click(function(){
      alert("Logout");
      $.ajax({ 
             // $account, $user, $checksum, $firstname, $lastname, $email, $password, $password2
                            url: "/dailyboost/public/login/dologout",
                            type: 'POST',
                            dataType: "html",
                            data: {
                                
                            },
                            success: function (result) {
                                   console.log(result);
                               if(result == "true")
                               {
                                   window.location.href = "../../login/loginform/";
                               }
                               else
                               {
                                   alert("Logout error");
                               }
                          
                             return false;
                           },
                            error: function (result) {
                                alert("Error");
                            },
                            warning: function (result) {
                                alert("Warning");
                            }
                        });
      
  });
  });
  </script>
  <?php }
  else
  { ?>
  <script>
      
      </script>
  <?php }
?>
      <style>
body {
  font-family: "Lato", sans-serif;
}

.sidebar {
  height: 100%;
  width: 0;
  position: fixed;
  z-index: 1;
  top: 0;
  left: 0;
  background-color: #111;
  overflow-x: hidden;
  transition: 0.5s;
  padding-top: 60px;
}

.sidebar a {
  padding: 8px 8px 8px 32px;
  text-decoration: none;
  font-size: 25px;
  color: #818181;
  display: block;
  transition: 0.3s;
}

.sidebar a:hover {
  color: #f1f1f1;
}

.sidebar button {
  padding: 8px 8px 8px 32px;
  text-decoration: none;
  font-size: 25px;
  color: #818181;
  display: block;
  transition: 0.3s;
}

.sidebar button:hover {
   color: #f1f1f1;
}

.sidebar .closebtn {
  position: absolute;
  top: 0;
  right: 25px;
  font-size: 36px;
  margin-left: 50px;
}

.openbtn {
  font-size: 20px;
  cursor: pointer;
  background-color: #111;
  color: white;
  padding: 5px 15px 5px 15px;
  border: none;
}

.openbtn:hover {
  background-color: #444;
}

#main {
  transition: margin-left .5s;
  padding: 0px;
}

/* On smaller screens, where height is less than 450px, change the style of the sidenav (less padding and a smaller font size) */
@media screen and (max-height: 450px) {
  .sidebar {padding-top: 15px;}
  .sidebar a {font-size: 18px;}
}
</style>
<script>
function openNav() {
  document.getElementById("mySidebar").style.width = "250px";
  document.getElementById("main").style.marginLeft = "250px";
}

function closeNav() {
  document.getElementById("mySidebar").style.width = "0";
  document.getElementById("main").style.marginLeft= "0";
}
</script>
    </head>
    <body>
        <!-- Navigation -->
  <nav class="navbar navbar-light bg-light static-top">
    <div class="container-fluid">
        <div id="main">
  <button class="openbtn bg-light" onclick="openNav()">
  <span class="icon-settings" style="width:160%; height:160%;color:grey"></span>
  </button> 
</div>
      <a class="navbar-brand" href="#">Daily Boost</a>
      

      <a class="btn btn-primary" href="#">Sign In</a>
    </div>
  </nav>

        <div id="mySidebar" class="sidebar">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
  <button type="button" class="btn btn-dark" id="home" onclick="window.location.href='/dailyboost/public/home/main/'"><?= _HOME ?></button>
  <button type="button" class="btn btn-dark" id="settings" onclick="window.location.href='/dailyboost/public/UserConfigurationController/Main/'"><?= _SETTINGS ?></button>
  <button type="button" class="btn btn-dark" id="dologout"><?= _LOGOUT ?></button>
</div>