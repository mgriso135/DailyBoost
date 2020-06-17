<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <?php
    $lang = $_SESSION['language'];
    require_once "assets/activateaccount_{$lang}.php"; 
    
    
?>
        <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
        <title>Daily Boost</title>
        <!-- CSS only -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

<!-- JS, Popper.js, and jQuery -->

<script src="https://code.jquery.com/jquery-3.1.1.min.js">
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

 <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom fonts for this template -->
 
  <link href="../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="../../../vendor/simple-line-icons/css/simple-line-icons.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">

  <!-- Custom styles for this template -->
  <link href="../../../../css/landing-page.min.css" rel="stylesheet">

  <!-- This is for MD5 Crypt -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/md5.js"></script>
  
  <?php 
            if($data['activation'])
            {?>
  <script>
  $(document).ready(function(){
  $("#checkresult").fadeOut();
  
    
$("#dologin").click(function(){
    var errorstr ="";
    $("#password").removeClass("is-invalid");
  $("#password").removeClass("is-valid");
    $("#password2").removeClass("is-invalid");
  $("#password2").removeClass("is-valid");
  $("#firstname").removeClass("is-valid");
  $("#firstname").removeClass("is-invalid");
  $("#lastname").removeClass("is-valid");  
  $("#lastname").removeClass("is-invalid");
  
    var checkresult = false;
    var passwordscheck=false;
    var fnamecheck =false;
    var lnamecheck=false;
    
    if($("#firstname").val().length > 0)
    {
        fnamecheck=true;
        $("#firstname").addClass("is-valid");
    }
    else
    {
        $("#firstname").addClass("is-invalid");
        if(errorstr.length > 0) { errorstr += "<br />"};
        errorstr += '<?= _FIRSTNAMEEMPTY ?>';
    }
    
    if($("#lastname").val().length > 0)
    {
        lnamecheck=true;
        $("#lastname").addClass("is-valid");
    }
    else
    {
        $("#lastname").addClass("is-invalid");
        if(errorstr.length > 0) { errorstr += "<br />"};
        errorstr += '<?= _LASTNAMEEMPTY ?>';
    }
    
    var password = "";
    var password2="";
    
    if($("#password").val().length > 6)
    {
        if($("#password").val() == $("#password2").val())
        {
            passwordscheck=true;
            $("#password").addClass("is-valid");
            $("#password2").addClass("is-valid");
            password = CryptoJS.MD5($("#password").val());
            password2 = CryptoJS.MD5($("#password2").val());
        }
        else
        {
            $("#password").addClass("is-invalid");
            $("#password2").addClass("is-invalid");
            if(errorstr.length > 0) { errorstr += "<br />"};
            errorstr += '<?=_PASSWORDSDONOTMATCH ?>';
        }
    }
    else
    {
       $("#password").addClass("is-invalid");
       $("#password2").addClass("is-invalid");
       if(errorstr.length > 0) { errorstr += "<br />"};
       errorstr += '<?=_PASSWORDTOOSHORT ?>';
    }
    
    checkresult = passwordscheck && lnamecheck && fnamecheck;
    if(checkresult)
    {  
        // Activate user
         $.ajax({ 
             // $account, $user, $checksum, $firstname, $lastname, $email, $password, $password2
                            url: "/dailyboost/public/AccountsController/ValidateUser/",                            
                            type: 'POST',
                            dataType: "html",
                            data: {
                                account: <?=$data['accountid']?>,
                                user: <?= $data['userid']?>,
                                checksum:"<?= $data['checksum']?>",
                                firstname:$("#firstname").val(),
                                lastname: $("#lastname").val(),
                                email: "<?= $data['mail']?>",
                                password: password.toString(),
                                password2: password2.toString(),
                            },
                            success: function (result) {
                                   console.log(result);
                               if(result == 1 || result == 7)
                               {
                                    $("#checkresult").html("Welcome in DailyBoost");
                                    $("#checkresult").removeClass("alert-danger");
                                    $("#checkresult").addClass("alert-success");
                                    $("#checkresult").fadeIn();
                                    window.location.href="/dailyboost/public/home/main";
                               }
                               else
                               {
                                   $("#checkresult").html("An error occured during user's validation");
                                   $("#checkresult").removeClass("alert-success");
                                   $("#checkresult").addClass("alert-danger");
                                   $("#checkresult").fadeIn();
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

       
       // Finally, do-login and re-direct to user home
        
    }
    else
    {  
        $("#checkresult").html(errorstr);
       $("#checkresult").fadeIn();
    }
   
});

  });
  </script>
  <?php }?>
    </head>
    <body>
        <!-- Navigation -->
  <nav class="navbar navbar-light bg-light static-top">
    <div class="container">
      <a class="navbar-brand" href="#">Daily Boost</a>
      <a class="btn btn-primary" href="#">Sign In</a>
    </div>
  </nav>
 
  <!-- Call to Action -->
  <section class="call-to-action text-center">
    <div class="overlay"></div>
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12 showcase-text">
                 <?php 
            if($data['activation'])
            {?>
            <h3><?= _MAINTITLE ?></h3>
            <p></p>
            <p class="lead mb-0">Please complete the following form</p>
            <?php
            
            }
            else
            {?>
            <h3>Account not found</h3>    
            <?php
            
            }?>
        </div>
      </div>
         </div>
            <div class="container-fluid">
           <?php 
            if($data['activation'])
            {?>
      
            <div class="row">
                <div class="col-lg-4"></div>
            <div class="col-lg-4">
                <input type="text" id="firstname" class="form-control form-control-lg" placeholder="First name" /><br />
                <input type="text" id="lastname" class="form-control form-control-lg" placeholder="Last name" /><br />
                <input type="password" id="password" class="form-control form-control-lg" placeholder="Password" /><br />
                <input type="password" id="password2" class="form-control form-control-lg" placeholder="Repeat password" /><br />
                <button type="button" id="dologin" class="btn btn-block btn-lg btn-primary">Login!</button>
            </div>
                <div class="col-lg-auto"></div>
              </div>
            </div>
    <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-4"></div>
                    <div class="col-lg-4">
                        <div class="alert alert-danger" role="alert" id="checkresult">
                        
                        </div>
                    </div>
                    <div class="col-lg-auto"></div>
                </div>
                  
                
           <?php
            
            }?>
      </div>
   
  </section>

  <!-- Footer -->
  <footer class="footer bg-light">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 h-100 text-center text-lg-left my-auto">
          <ul class="list-inline mb-2">
            <li class="list-inline-item">
              <a href="#">About</a>
            </li>
            <li class="list-inline-item">&sdot;</li>
            <li class="list-inline-item">
              <a href="#">Contact</a>
            </li>
            <li class="list-inline-item">&sdot;</li>
            <li class="list-inline-item">
              <a href="#">Terms of Use</a>
            </li>
            <li class="list-inline-item">&sdot;</li>
            <li class="list-inline-item">
              <a href="#">Privacy Policy</a>
            </li>
          </ul>
          <p class="text-muted small mb-4 mb-lg-0">&copy; Your Website 2019. All Rights Reserved.</p>
        </div>
        <div class="col-lg-6 h-100 text-center text-lg-right my-auto">
          <ul class="list-inline mb-0">
            <li class="list-inline-item mr-3">
              <a href="#">
                <i class="fab fa-facebook fa-2x fa-fw"></i>
              </a>
            </li>
            <li class="list-inline-item mr-3">
              <a href="#">
                <i class="fab fa-twitter-square fa-2x fa-fw"></i>
              </a>
            </li>
            <li class="list-inline-item">
              <a href="#">
                <i class="fab fa-instagram fa-2x fa-fw"></i>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </footer>
       

    </body>
</html>
