<?php
    $lang = $_SESSION['language'];
    require_once "assets/timezone_{$lang}.php"; 
    ?>
<script>

    $(document).ready(function(){
        
        $("#imgSaveTz").click(function(){
            $("#ddlTimezones").removeClass("is-valid");
            $("#ddlTimezones").removeClass("is-invalid");
            var tmz = encodeURI($("#ddlTimezones option:selected" ).val().replace(/\//g, "."));
            $.ajax({ 
             // $account, $user, $checksum, $firstname, $lastname, $email, $password, $password2
             url: "/dailyboost/public/UserConfigurationController/savetimezone",
             type: 'POST',
             dataType: "html",
             data: {
                'timezone' : tmz,
             },
             success: function (result) {
                 if(result == "1")
                 {
                     $("#ddlTimezones").addClass("is-valid");
                     $("#saveresult").html('<?= _SAVEDOK ?>');
                $('#savetimezoneok').modal('show');
                setTimeout(function() {$('#savetimezoneok').modal('hide');}, 3000);
            }
            else
            {
                $("#ddlTimezones").addClass("is-invalid");
                $("#saveresult").html('<?= _SAVEDNOK ?>');
                $('#savetimezoneok').modal('show');
                setTimeout(function() {$('#savetimezoneok').modal('hide');}, 3000);
            }
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

<h5><?= _TITLE ?></h5>
<div class="form-inline">
<select id="ddlTimezones" class="form-control form-control-lg col-lg-4">
<?php
$tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
for($i=0; $i<sizeof($tzlist); $i++)
{
    $sel="";
    if($data['timezone'] == $tzlist[$i])
    {
        $sel = " SELECTED";
    }
    ?>
    <option id="<?= $tzlist[$i] ?>"<?= $sel ?>><?= $tzlist[$i] ?></option>
<?php }
?>
</select>
<span id="imgSaveTz" class="icon-arrow-right" style="color:grey;cursor:pointer;"></span>
</div>

<div class="modal" tabindex="-1" role="dialog" id="savetimezoneok">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?= _SAVEMODALTITLE ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <p id="saveresult"></p>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>