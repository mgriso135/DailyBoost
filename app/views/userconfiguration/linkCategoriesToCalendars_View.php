  <?php
    $lang = $_SESSION['language'];
    require_once "assets/linkCategoriesToCalendars_View_{$lang}.php"; 
    ?>

<?php
if(isset($_SESSION['userid']) && $_SESSION['userid'] != "" && isset($_SESSION['isLoggedIn']))
{ 
    $categories = $data['categories'];
    $avcals = $data['availablecalendars'];
    ?>

<script>
$(document).ready(function(){
    
    var categories = [];
    <?php
    for($i=0;$i<sizeof($categories);$i++)
    { ?>
      categories[<?= $i ?>] = <?= $categories[$i]->id ?>;  
    <?php }
    ?>
            
    var calendars = [];
    <?php
    for($i=0;$i<sizeof($avcals);$i++)
    { ?>
      calendars["<?= $avcals[$i]['ExternalCalendarId'] ?>"] = "<?= $avcals[$i]['ExternalCalendarSummary'] ?>";  
    <?php }
    ?>
    
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
    
    $(".tblmanagecals").on("click", ".addCalendar", function(){
        var aCatId = $(this).prop("id").split("_");
        var catId = aCatId[1];
        var selcal = $("#addSel_" + catId).val();
        var aext = selcal.split('_');
        var externalaccountid = aext[0];
        var extcalendarid = aext[1];
        
        $.ajax({ 
         url: "/dailyboost/public/ExternalAppsController/linkExternalCalendarToCategory",
         type: 'POST',
         dataType: "html",
         data: {
             categoryid: catId,
             externalAccountId: externalaccountid,
             externalcalendarid: extcalendarid,
             externalcalendarname: calendars[extcalendarid]
         },
         success: function (result) {
            //$("#frmExternalApps").html(result);
            console.log(result);
            loadLinkedCalendars(catId);
         },
         error: function (result) {
             alert("Error");
         },
         warning: function (result) {
             alert("Warning");
         }
      }); 
    });
    
    function loadLinkedCalendars(categoryId)
    {
        $.ajax({ 
         url: "/dailyboost/public/ExternalAppsController/loadExternalCalendarsUserCategory",
         type: 'POST',
         dataType: "html",
         data: {
             categoryid: categoryId
         },
         success: function (result) {
            console.log(result);
            if(result == "2")
            {
                
            }
            else if(result == "3")
            {
                
            }
            else if(result == "4")
            {
                
            }
            else
            {
                jsRes = JSON.parse(result);
                var strCalendars = "";
                for(var i = 0; i < jsRes.length; i++)
                {                    
                    strCalendars += "<span class='badge badge-pill badge-primary'>"+jsRes[i].calendar_name + " <span class='icon-close' style='cursor:pointer'></span></span>";
                }
                $("#linkedCals_" + categoryId).html(strCalendars);
            }
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
    
    for(var i =0; i < categories.length; i++)
    {
        console.log("Category: " + categories[i] + "\n");
       loadLinkedCalendars(categories[i]);
    }
});
</script>

<h3>External calendars</h3>

<div id="frmExternalApps"></div>

<h3>Link calendars to categories</h3>
<table class="table table-striped table-hover tblmanagecals">
    <thead>
    <tr>
        <th>Category</th>
    </tr>
    </thead>
    <tbody>
<?php
for($i=0; $i < sizeof($categories); $i++)
{ ?>
        <tr><td><?= $categories[$i]->name ?></td>
            <td>
                <select id="addSel_<?= $categories[$i]->id ?>">
                <?php 
                
                for($j = 0; $j < sizeof($avcals); $j++)
                { ?>
                    <option value="<?= $avcals[$j]["ExternalAccount"]->ExternalAccountId . "_". $avcals[$j]['ExternalCalendarId'] ?>">
                        <?= $avcals[$j]["ExternalAccount"]->AccountName ?> - <?= $avcals[$j]['ExternalCalendarSummary'] ?></option>
                <?php }
                ?>
                    </select>
                <span class='icon-arrow-right addCalendar' id="btnAddCalendar_<?= $categories[$i]->id ?>" value="<?= $categories[$i]->id ?>" style="cursor:pointer;"></span>
            </td>
            <td>
                <div id="linkedCals_<?= $categories[$i]->id ?>"></div>
            </td>
        </tr>
<?php }
?>
        </tbody>
</table>


<?php }
        
        ?>