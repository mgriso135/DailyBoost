<?php
    $lang = $_SESSION['language'];
    require_once "assets/maxtasksconfig_{$lang}.php"; 
    ?>

<script>

    $(document).ready(function(){
        
        $("#imgSaveMaxNo").click(function(){
            $("#maxtasks").removeClass("is-valid");
            $("#maxtasks").removeClass("is-invalid");
            var tmz = $("#maxtasks").val();
            $.ajax({ 
             // $account, $user, $checksum, $firstname, $lastname, $email, $password, $password2
             url: "/dailyboost/public/UserConfigurationController/savemaxtasks/" + tmz,
             type: 'POST',
             dataType: "html",
             data: {
                                
             },
             success: function (result) {
            if(result == "1")
            {
                $("#maxtasks").addClass("is-valid");
                $("#saveMaxTasksresult").html('<?= _SAVEDOK ?>');
                $('#savemaxtasks').modal('show');
                setTimeout(function() {$('#savemaxtasks').modal('hide');}, 3000);
            }
            else
            {
                $("#maxtasks").addClass("is-invalid");
                $("#saveMaxTasksresult").html('<?= _SAVEDNOK ?>');
                $('#savemaxtasks').modal('show');
                setTimeout(function() {$('#savemaxtasks').modal('hide');}, 3000);
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
    <input type="number" id="maxtasks" min="0" max="999" class="form-control form-control-lg col-lg-2" value="<?= $data['maxtasks'] ?>" />
<span id="imgSaveMaxNo" class="icon-arrow-right" style="color:grey;cursor:pointer;"></span>
</div>

<div class="modal" tabindex="-1" role="dialog" id="savemaxtasks">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?= _SAVEMODALTITLE ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <p id="saveMaxTasksresult"></p>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>