<?php
    $lang = $_SESSION['language'];
    require_once "assets/defaulttasksconfig_{$lang}.php"; 
    ?>

<script>
    $(document).ready(function(){
        $("#ddlDefaultTask").removeClass("is-valid");
        $("#ddlDefaultTask").removeClass("is-invalid");
        
        // Modal info
        $("#defaulttask_popover").click(function(){
            $("#savedefaulttask_title").html('<?= _DEFAULTTASK_INFOTITLE ?>');
            $("#savedefaulttask_result").html('<?= _DEFAULTTASK_INFOBODY ?>');
            $("#savedefaulttask").modal("show");
        });
        
        $("#imgSaveDefaultTask").click(function(){
            $("#ddlDefaultTask").removeClass("is-valid");
            $("#ddlDefaultTask").removeClass("is-invalid");
            
            var deftask = $("#ddlDefaultTask option:selected" ).val();
            $.ajax({ 
             // $account, $user, $checksum, $firstname, $lastname, $email, $password, $password2
             url: "/dailyboost/public/UserConfigurationController/setdefaulttask",
             type: 'POST',
             dataType: "html",
             data: {
                 taskid: deftask,
             },
             success: function (result) {
                console.log(result);
                $("#ddlDefaultTask").addClass("is-valid");
                $("#savedefaulttask_title").html('<?= _DEFAULTTASK_TITLE ?>');
                $("#savedefaulttask_result").html('<?= _DEFAULTTASK_SAVEDOK ?>');
                $('#savedefaulttask').modal('show');
                setTimeout(function() {$('#savedefaulttask').modal('hide');}, 3000);
                $("#ddlDefaultTask").removeClass("is-valid");
                $("#ddlDefaultTask").removeClass("is-invalid");
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
<h5><?= _DEFAULTTASK_TITLE ?>&nbsp;
    <span class="icon-info" style="width:10%; height:10%;color:grey;cursor:pointer;" id="defaulttask_popover" data-inline="false"></span></h5>
<div class="form-inline">
    <select class="form-control form-control-lg col-lg-4" id="ddlDefaultTask">
        <?php 
        if($data['default_task_id'] == -1)
        { ?>
            <option value="-1" selected>No default task</option>    
        <?php } 
        else { ?>
            <option value="-1">No default task</option>
        <?php } 
          $netasks = $data['never_ending_tasks'];
          for($i = 0; $i < sizeof($netasks); $i++)
          { 
              if($data['default_task_id'] == $netasks[$i]->id)
                {  ?>
              <option value="<?= $netasks[$i]->id ?>" selected><?= $netasks[$i]->name ?></option>
         <?php }
         else
         { ?>
             <option value="<?= $netasks[$i]->id ?>"><?= $netasks[$i]->name ?></option>
         <?php }
          }
        ?>
    </select>
    <span id="imgSaveDefaultTask" class="icon-arrow-right" style="color:grey;cursor:pointer;"></span>
</div>


<div class="modal" tabindex="-1" role="dialog" id="savedefaulttask">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="savedefaulttask_title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <p id="savedefaulttask_result"></p>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>