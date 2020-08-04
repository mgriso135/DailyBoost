  <?php
    $lang = $_SESSION['language'];
    require_once "assets/linkCategoriesToCalendars_View_{$lang}.php"; 
    ?>

<?php
if(isset($_SESSION['userid']) && $_SESSION['userid'] != "" && isset($_SESSION['isLoggedIn']))
{ ?>
<h3>External calendars</h3>

<?= $data['log'] ?>

<?php }
        
        ?>