<?php
    $lang = $_SESSION['language'];
    require_once "assets/main_{$lang}.php";
    
    require_once("../bin/initialize.php");
?>

<script>
    $(document).ready(function(){
        function loadTimeZoneConfig()
        {
            $.ajax({ 
             // $account, $user, $checksum, $firstname, $lastname, $email, $password, $password2
             url: "/dailyboost/public/UserConfigurationController/TimezoneConfig",
             type: 'POST',
             dataType: "html",
             data: {
                                
             },
             success: function (result) {
                $("#frmTimezone").html(result);
             },
             error: function (result) {
                 alert("Error");
             },
             warning: function (result) {
                 alert("Warning");
             }
            });
        }
        
        function loadMaxNoTasksConfig()
        {
            $.ajax({ 
             // $account, $user, $checksum, $firstname, $lastname, $email, $password, $password2
             url: "/dailyboost/public/UserConfigurationController/MaxTasksConfig",
             type: 'POST',
             dataType: "html",
             data: {
                                
             },
             success: function (result) {
                $("#frmMaxTasks").html(result);
             },
             error: function (result) {
                 alert("Error");
             },
             warning: function (result) {
                 alert("Warning");
             }
            });
        }
        
        function loadDefaultTaskConfig()
        {
            $("#imgLoadDefaultTask").fadeIn();
            $.ajax({ 
             url: "/dailyboost/public/UserConfigurationController/DefaultTaskConfig",
             type: 'POST',
             dataType: "html",
             data: {
                                
             },
             success: function (result) {
                $("#imgLoadDefaultTask").fadeOut();
                $("#frmDefaultTask").html(result);
             },
             error: function (result) {
                 alert("Error");
             },
             warning: function (result) {
                 alert("Warning");
             }
            });
        }
        
        function loadGoogleCalendarLinkView()
        {
            $("#imgLoadGoogleCalendarLinkView").fadeIn();
            $.ajax({ 
             url: "/dailyboost/public/UserConfigurationController/GoogleCalendarLinkView",
             type: 'POST',
             dataType: "html",
             data: {
                                
             },
             success: function (result) {
                $("#imgLoadGoogleCalendarLinkView").fadeOut();
                $("#frmGoogleCalendarLinkView").html(result);
             },
             error: function (result) {
                 alert("Error");
             },
             warning: function (result) {
                 alert("Warning");
             }
            });
        }
        
        $("#imgLoadDefaultTask").fadeOut();
        $("#imgLoadGoogleCalendarLinkView").fadeOut();
        loadTimeZoneConfig();
        loadMaxNoTasksConfig();
        loadDefaultTaskConfig();
        loadGoogleCalendarLinkView();
    });
    </script>
    
    <div class="container-fluid">
 <div class="row">
                <div class="col-lg-2">
                </div>
                <div class="col-lg-10">
                    <h3><?= _TITLE ?></h3>
                    <div id="frmTimezone"></div>
                    <p></p>
                    <div id="frmMaxTasks"></div>
                    <p></p>
                    <img src="../../../img/three-circles.gif" id="imgLoadDefaultTask" style="max-width: 20px; max-height: 20px;" />
                    <div id="frmDefaultTask"></div>
                    <p></p>
                    <img src="../../../img/three-circles.gif" id="imgLoadGoogleCalendarLinkView" style="max-width: 20px; max-height: 20px;" />
                    <div id="frmGoogleCalendarLinkView"></div>
</div>
</div>
 </div>