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
         url: "/dailyboost/public/UserConfigurationController/listExternalAccounts",
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

<h3>Link calendars to categories</h3>
<table class="table table-striped table-hover">
    <thead>
    <tr>
        <th>Category</th>
    </tr>
    </thead>
    <tbody>
<?php
$categories = $data['categories'];
for($i=0; $i < sizeof($categories); $i++)
{ ?>
        <tr><td><?= $categories[$i]->name ?></td>
            <td>
                <?php 
                $avcals = $data['availablecalendars'];
                for($j = 0; $j < sizeof($avcals); $j++)
                { 
                  echo $avcals[$j]['ExternalCalendarSummary'] . "<br />";  
                }
                ?>
            </td>
        </tr>
<?php }
?>
        </tbody>
</table>

<br />
<?= var_dump($data['availablecalendars']) ?>

<?php }
        
        ?>