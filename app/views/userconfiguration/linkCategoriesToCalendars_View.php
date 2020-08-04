  <?php
    $lang = $_SESSION['language'];
    require_once "assets/linkCategoriesToCalendars_View_{$lang}.php"; 
    ?>

<?php
if(isset($_SESSION['userid']) && $_SESSION['userid'] != "" && isset($_SESSION['isLoggedIn']))
{ ?>

<script>
$(document).ready(function(){
    
    function loadExternalApps()
        {
            $.ajax({ 
             url: "/dailyboost/public/UserConfigurationController/listExternalApps",
             type: 'POST',
             dataType: "html",
             data: {
                                
             },
             success: function (result) {
                $("#frmExternalApps").html(result);
             },
             error: function (result) {
                 alert("Error");
             },
             warning: function (result) {
                 alert("Warning");
             }
            });
        }
    
    loadExternalApps();
});
</script>

<h3>External calendars</h3>

<div id="frmExternalApps"></div>

<?= $data['log'] ?>

<?php }
        
        ?>