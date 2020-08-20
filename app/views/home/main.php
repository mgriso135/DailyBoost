<?php
    $lang = $_SESSION['language'];
    require_once "assets/main_{$lang}.php"; 
    $title = $data['title'];
?>


  <?php 
            if(strlen($_SESSION['username'])>0)
            {?>
  <script>
  $(document).ready(function(){
      
      var categoryId = <?= $data['category'] ?>;
      var userId = <?= $_SESSION['userid'] ?>;
      $("#addtask_category").hide();

function init()
{
    if(categoryId == -1)
    {
        $("#addtask_category").show();
    }
}
  
  function loadCategoriesPanel()
  {
      $("#imgloadcategories").fadeIn();
      $.ajax({ 
             // $account, $user, $checksum, $firstname, $lastname, $email, $password, $password2
             url: "/dailyboost/public/CategoriesController/usercategories",
             type: 'POST',
             dataType: "html",
             data: {
                                
             },
             success: function (result) {
                $("#categoriespanel").html(result);
                $("#imgloadcategories").fadeOut();
             },
             error: function (result) {
                 $("#imgloadcategories").fadeOut();
                 alert("Error");
             },
             warning: function (result) {
                 $("#imgloadcategories").fadeOut();
                                alert("Warning");
                            }
    });
  }
  
  $("#addusercategory").click(function(){
      $.ajax({ 
             // $account, $user, $checksum, $firstname, $lastname, $email, $password, $password2
             url: "/dailyboost/public/CategoriesController/addusercategory_showview",
             type: 'POST',
             dataType: "html",
             data: {
                                
             },
             success: function (result) {
                $("#addusercategory_view").html(result);
                $("#addusercategory_view").fadeIn();
             },
             error: function (result) {
                                alert("Error");
             },
             warning: function (result) {
                                alert("Warning");
                            }
    });
  });
  
  $("#starttask").click(function(){
      var taskName = $('#taskid').val();
      var addCategoryId = -1;
      if(taskName.length >0)
      {
        var tid = $("#opentasks_list option[value='" + taskName + "']").attr('data-id');
        if (tid != null && tid.length > 0 && $.isNumeric(tid)) {
        }
        else
        {
            tid=-1;
        }
        if(categoryId == -1)
        {
            var catName = $('#addtask_category').val();
            addCategoryId = $("#categorieslist option[value='" + catName + "']").attr('data-id'); 
            if(categoryId == null || !$.isNumeric(addCategoryId))
            {
                addCategoryId = -1;
            }
        }
        else
        {
            addCategoryId = categoryId;
        }

        // if taskid exists, then resumen. If catgory exists, then create a new one
        if(tid!=-1 || addCategoryId != -1)
        {
            //console.log(encodeURIComponent(taskName));
            var url = "/dailyboost/public/TasksExecutionController/startTask/"
        //console.log(url);
        $.ajax({ 
        url: url,
             type: 'POST',
             dataType: "html",
             contentType: "application/x-www-form-urlencoded",
             data: {
                 user_id: userId,
                 category_id: addCategoryId,
                 task_id: tid,
                 taskname: taskName
             },
             success: function (result) {
                 console.log(result);
                 if(parseInt(result) > 0)
                 {
                     // Delete item in datalist
                   $("#dlitem_" + result).remove();
                   $('#taskid').val('');
                   loadUpComingTasks();
                   loadTasksInExecution();
                 }
                 else if(result == "-6")
                 {
                    $("#modal_title").html('<?= _MAX_TASKS_REACHED_TITLE ?>');
                    $("#modal_body").html('<?= _MAX_TASKS_REACHED_BODY ?>');
                    $("#messages_modal").modal('show');
                    $('#taskid').val('');
                    setTimeout(function() {$('#messages_modal').modal('hide');}, 3000);
                 }
                 else
                 {
                    $("#modal_title").html('<?= _GENERIC_ERROR_TITLE ?>');
                    $("#modal_body").html('<?= _GENERIC_ERROR_BODY ?>'); 
                    $("#messages_modal").modal('show');
                    //$('#taskid').val('');
                    setTimeout(function() {$('#messages_modal').modal('hide');}, 3000);
                 }
             },
             error: function (result) {
                                alert("Error");
                                $('#taskid').val('');
             },
             warning: function (result) {
                                alert("Warning");
                                $('#taskid').val('');
                            }
            });
        }
        else
        {
            $("#modal_title").html('<?= _CATEGORY_UNDEFINED_TITLE ?>');
            $("#modal_body").html('<?= _CATEGORY_UNDEFINED_BODY ?>');
            $("#messages_modal").modal('show');
            //$('#taskid').val('');
            setTimeout(function() {$('#messages_modal').modal('hide');}, 3000);
        }
    }
    else
    {
            $("#modal_title").html('<?= _MISSING_NAME_TITLE ?>');
            $("#modal_body").html('<?= _MISSING_NAME_BODY ?>');
            $("#messages_modal").modal('show');
            $('#taskid').val('');
            setTimeout(function() {$('#messages_modal').modal('hide');}, 3000);
    }    
        
  });
  
  $("#frmUpcomingTasks").on("click", ".btnstartupcomingtask", function(){
    console.log("Start " + $(this).prop("id") + " aaa");
    var ataskid = $(this).prop("id").split('_');
    var tid = ataskid[1];
    var categoryId = $("#upcoming_categoryId_"+tid).val();

            var url = "/dailyboost/public/TasksExecutionController/startTask/"
        //console.log(url);
        $.ajax({ 
        url: url,
             type: 'POST',
             dataType: "html",
             contentType: "application/x-www-form-urlencoded",
             data: {
                 user_id: userId,
                 category_id: categoryId,
                 task_id: tid,
                 taskname: ''
             },
             success: function (result) {
                 if(parseInt(result) > 0)
                 {
                     // Delete item in datalist
                     $("#dlitem_" + result).remove();
                   $('#taskid').val('');
                   loadUpComingTasks();
                   loadTasksInExecution();
                 }
                 else if(result == "-6")
                 {
                    $("#modal_title").html('<?= _MAX_TASKS_REACHED_TITLE ?>');
                    $("#modal_body").html('<?= _MAX_TASKS_REACHED_BODY ?>');
                    $("#messages_modal").modal('show');
                    $('#taskid').val('');
                    setTimeout(function() {$('#messages_modal').modal('hide');}, 3000);
                 }
                 else
                 {
                    $("#modal_title").html('<?= _GENERIC_ERROR_TITLE ?>');
                    $("#modal_body").html('<?= _GENERIC_ERROR_BODY ?>'); 
                    $("#messages_modal").modal('show');
                    //$('#taskid').val('');
                    setTimeout(function() {$('#messages_modal').modal('hide');}, 3000);
                 }
             },
             error: function (result) {
                                alert("Error");
                                $('#taskid').val('');
             },
             warning: function (result) {
                                alert("Warning");
                                $('#taskid').val('');
                            }
            });
  });
  
  $("#frmTasks").on("click", ".btnpausetask", function(){
      var sid = $(this).prop("id").split('_');
      if(sid.length == 2)
      {
          id = sid[1];
          if($.isNumeric(id))
          {
              $("#btnpausetask_"+id).fadeOut();
              $("#btnstoptask_"+id).fadeOut();
              $("#btnnote_"+id).fadeOut();
              
              var url = "/dailyboost/public/TasksExecutionController/pausetask/"
        //console.log(url);
        $.ajax({ 
        url: url,
             type: 'POST',
             dataType: "html",
             contentType: "application/x-www-form-urlencoded",
             data: {
                 task_id: id,
             },
             success: function (result) {
                 console.log(result);
                 if(result == "1")
                 {
                     var taskname = $("#taskname_"+id).val();
                     // remove the row from the table of executing tasks
                     $("#execrow_"+id).remove();
                     // add a row to the datalist
                     var stroption = "<option id='dlitem_"+id+"' data-id='"+id+"' value='" + taskname + "' label='" + taskname + "'></option>";
                     $("#opentasks_list").prepend(stroption);
                     loadUpComingTasks();
                     loadTasksInExecution();
                 }
                 else
                 {
                     $("#btnpausetask_"+id).fadeIn();
                    $("#btnstoptask_"+id).fadeIn();
                    $("#btnnote_"+id).fadeIn();
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
      }
  });
  
  $("#frmTasks").on("click", ".btnfinishtask", function(){
      var sid = $(this).prop("id").split('_');
      if(sid.length == 2)
      {
          id = sid[1];
          if($.isNumeric(id))
          {
              $("#btnpausetask_"+id).fadeOut();
              $("#btnstoptask_"+id).fadeOut();
              $("#btnnote_"+id).fadeOut();
              
              var url = "/dailyboost/public/TasksExecutionController/finishTask/"
        //console.log(url);
        $.ajax({ 
        url: url,
             type: 'POST',
             dataType: "html",
             contentType: "application/x-www-form-urlencoded",
             data: {
                 task_id: id,
             },
             success: function (result) {
                 console.log(result);
                 if(result == "1")
                 {
                     // remove the row from the table of executing tasks
                     $("#execrow_"+id).remove();
                     loadUpComingTasks();
                     loadTasksInExecution();
                 }
                 else
                 {
                    $("#btnpausetask_"+id).fadeIn();
                    $("#btnstoptask_"+id).fadeIn();
                    $("#btnnote_"+id).fadeIn();
                    $("#modal_title").html('<?= _GENERIC_ERROR_TITLE ?>');
                    $("#modal_body").html('<?= _GENERIC_ERROR_BODY ?>');
                    $("#messages_modal").modal('show');
                    $('#taskid').val('');
                    setTimeout(function() {$('#messages_modal').modal('hide');}, 3000);
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
      }
  });
  
  function loadTasksInExecution()
  {
      console.log("loadTasksInExecution");
      var url = "/dailyboost/public/TasksExecutionController/tasks_in_exection_list/"
        //console.log(url);
        $.ajax({ 
        url: url,
             type: 'POST',
             dataType: "html",
             contentType: "application/x-www-form-urlencoded",
             data: {
                 category_id: categoryId,
             },
             success: function (result) {
                 //console.log(result);
                 var tasks = JSON.parse(result);
                 var strrow = "<table class='table table-striped table-hover table-borderless' id='tblTasksInExecution'><tbody>";
                 for(var i = 0; i < tasks.length; i++)
                 {
                     strrow += "<tr id='execrow_" + tasks[i].id + "'>"
                           + "<td><span class='icon-control-pause btnpausetask' style='width:36px;height:36px;color:grey;cursor:pointer;' name='btnpausetask' id='pausetask_"+tasks[i].id+"' title='<?= _ICON_PAUSE ?>'></span></td>"
                           +"<td><span class='icon-note' style='width: 36px; height: 36px; color:grey;cursor:pointer;' name='btnnote' id='note_" + tasks[i].id + "' title='<?= _ICON_NOTE ?>'></span></td>"
                           +"<td><input type='hidden' value='" + tasks[i].category_name + "' id='categoryname_"+tasks[i].id+"' />" + tasks[i].category_name + "</td>"
                           +"<td><input type='hidden' value='" + tasks[i].name + "' id='taskname_"+tasks[i].id+"' />" + tasks[i].name + "</td>"
                   +"<td><input type='hidden' value='" + tasks[i].description + "' id='taskdescription_"+tasks[i].id+"' />" + tasks[i].description + "</td>"
                           +"<td>";
                   if(!tasks[i].neverending)
                   {
                       strrow += "<span class='icon-loop btnfinishtask' style='width: 36px; height: 36px; color:grey;cursor:pointer;' name='btnfinishtask' id='finishtask_"+tasks[i].id+"' title='<?= _ICON_END ?>'></span>"
                   }
                   strrow += "</td></tr>";
                 }
                 strrow += "</tbody></table>";
                 $("#frmTasks").html(strrow);
             },
             error: function (result) {
                                alert("Error");
             },
             warning: function (result) {
                                alert("Warning");
                            }
            });
  }
  
  $("#frmTasks").on("click", ".icon-note", function(){
  var sid = $(this).prop("id");
  var aid = sid.split('_');
  if(aid.length == 2)
  {
      var taskid = aid[1];
      console.log(taskid);
      $.ajax({ 
         // $account, $user, $checksum, $firstname, $lastname, $email, $password, $password2
         url: "/dailyboost/public/TasksExecutionController/tasks_notes_list",
         type: 'POST',
         dataType: "html",
         data: {
             taskid: taskid
         },
         success: function (result) {
            $("#frmTaskNotes").html(result);
         },
         error: function (result) {
             alert("Error");
         },
         warning: function (result) {
             alert("Warning");
         }
    });
  }
      
  });
  
  function loadUpComingTasks()
  {
      $.ajax({ 
             // $account, $user, $checksum, $firstname, $lastname, $email, $password, $password2
             url: "/dailyboost/public/TasksExecutionController/UpComingTasks",
             type: 'POST',
             dataType: "html",
             data: {
                    category_id: categoryId,            
             },
             success: function (result) {
                 //console.log(result);
                 var tasks = JSON.parse(result);
                 var strrow = "<table class='table table-striped table-hover table-borderless' id='tblUpcomingTasks'>"
                    +"<thead><tr><th></th><th><?= _CATEGORY_NAME ?></th>"
            +"<th><?= _TASK_NAME ?></th>"
    +"<th><?= _TASK_DESCRIPTION ?></th>"
    +"<th><?= _DUE_DATE ?></th>"
            +"</tr></thead>"
            +"<tbody>";
    var currdate = new Date();
                 for(var i = 0; i < tasks.length; i++)
                 {
                     var bgcolor ="";
                     if(tasks[i].neverending == 0 && moment(tasks[i].latefinish, "YYYY/MM/DD HH:mm:ss") < currdate)
                     {
                         bgcolor = "background-color: red;";
                     }
                     else if(tasks[i].neverending == 0 && moment(tasks[i].earlystart, "YYYY/MM/DD HH:mm:ss") < currdate && currdate < moment(tasks[i].latefinish, "YYYY/MM/DD HH:mm:ss"))
                     {
                         bgcolor = "background-color: yellow;";
                     }
                     
                     var strdate = "One day";
                     if(tasks[i].plantask)
                     {
                         strdate = moment(tasks[i].latefinish).format("DD/MM/YYYY HH:mm:ss");
                     }
                     
                     strrow += "<tr id='execrow_" + tasks[i].id + "' style='"+bgcolor+"'>"
                     + "<input type='hidden' id='upcoming_categoryId_" + tasks[i].id + "' value='+" + tasks[i].category_id + "+' />"
                           + "<td><span class='icon-control-play btnstartupcomingtask' style='width:36px;height:36px;color:grey;cursor:pointer;' name='btnstartupcomingtask' id='btnstartupcomingtask_"+tasks[i].id+"' title='<?= _ICON_START ?>'></span></td>"
                           //+"<td><span class='icon-note' style='width: 36px; height: 36px; color:grey;cursor:pointer;' name='btnnote' id='note_" + tasks[i].id + "' title='<?= _ICON_NOTE ?>'></span></td>"
                           +"<td><input type='hidden' value='" + tasks[i].category_name + "' id='categoryname_"+tasks[i].id+"' />" + tasks[i].category_name + "</td>"
                           +"<td><input type='hidden' value='" + tasks[i].name + "' id='taskname_"+tasks[i].id+"' />" + tasks[i].name + "</td>"
                   +"<td><input type='hidden' value='" + tasks[i].description + "' id='taskdescription_"+tasks[i].id+"' />" + tasks[i].description + "</td>"
                    + "<td>"+strdate+"</td>"
                         ;
                   strrow += "</tr>";
                 }
                 strrow += "</tbody></table>";
                 $("#frmUpcomingTasks").html(strrow);
             },
             error: function (result) {
                 alert("Error");
             },
             warning: function (result) {
                alert("Warning");
            }
    });
  }
  
 loadTasksInExecution();
 loadUpComingTasks();
  
  $("messages_modal").modal('hide');
  init();
  loadCategoriesPanel();
  
  });
  </script>
  <?php }
  else
  { ?>
  <script>
      alert("Not logged in... Please login");
      </script>
  <?php }
?>
        <!-- Navigation -->  
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-2">
                    
                    <div id="accordion">
                        <div class="card">
                            <div class="card-header" id="headingAccCats">
                            <h5 class="mb-0">
                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapseAccCats" aria-expanded="true" aria-controls="collapseAccCats">
                            Account channels
                            </button>
                            </h5>
                            </div>
                            <div id="collapseAccCats" class="collapse" aria-labelledby="headingAccCats" data-parent="#accordion">
                            <div class="card-body">
                            Here to list of channels of the account
                        </div>
                        </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            User channels
                            </button>
                                <span class="icon-plus" style="width:10%; height:10%;color:grey;cursor:pointer;" data-inline="false" id="addusercategory"></span>
                            </h5>
                            </div>
                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">
                            <img src="../../../img/three-circles.gif" id="imgloadcategories" style="width:10%;height:10%;background-color:transparent;" />
                            <div id="categoriespanel"></div>
                        </div>
                        </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header" id="headingReports">
                            <h5 class="mb-0">
                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapseReports" aria-expanded="true" aria-controls="collapseReports">
                            My reports
                            </button>
                            </h5>
                            </div>
                            <div id="collapseReports" class="collapse" aria-labelledby="headingReports" data-parent="#accordion">
                            <div class="card-body">
                                <div>
                                    <a href="../../ReportsController/DailyReport/" style="text-decoration: none; color: black;"><?= _REPORTS_DAILYREPORT ?></a>
                                </div>
                        </div>
                        </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header" id="headingPlanningBoard">
                            <h5 class="mb-0">
                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapsePlanningBoard" aria-expanded="true" aria-controls="collapsePlanningBoard">
                            Planning board
                            </button>
                            </h5>
                            </div>
                            <div id="collapsePlanningBoard" class="collapse" aria-labelledby="headingPlanningBoard" data-parent="#accordion">
                            <div class="card-body">
                                <div>
                                    <a href="../../PlanningController/Index/" style="text-decoration: none; color: black;"><?= _PLANNINGBOARD ?></a>
                                </div>
                        </div>
                        </div>
                        </div>
</div>
                    
                </div>
                <div class="col-lg-10">
                    <p class="h4"><?php echo _CATEGORY_FILTER . " " . $data['category_filter_name'] ?></p>
                    <div class="form-inline align-middle">                   
            <datalist id="categorieslist">
                <?php
                if(isset($data['categories_list']))
                {
                    $cats = $data['categories_list'];
                    for($i=0; $i < sizeof($cats); $i++)
                    { ?>
                      <option data-id="<?= $cats[$i]->id ?>" value="<?= $cats[$i]->name ?>"></option>
                    <?php 
                    }
                }
                ?>
            </datalist>
            <datalist id="opentasks_list">
                <?php
                if(isset($data['tasks']))
                {
                    $tsks = $data['tasks'];
                    for($i=0; $i < sizeof($tsks); $i++)
                    { ?>
                      <option id="dlitem_<?= $tsks[$i]->id ?>" data-id="<?= $tsks[$i]->id ?>" value="<?= $tsks[$i]->name ?>" label="<?= $tsks[$i]->name ?>"></option>
                    <?php 
                    }
                }
                ?>
            </datalist>
                        <input id="taskid" list="opentasks_list" placeholder="<?= _PLACEHOLDER_STARTTASK ?>" class="form-control form-control-lg col-lg-4" />&nbsp;
                        <input id="addtask_category" list="categorieslist" placeholder="<?= _PLACEHOLDER_SELECTCATEGORY ?>" class="form-control form-control-lg col-lg-3" />&nbsp;
                        <span class="icon-control-play h-100 d-inline-block" style="max-height: 100%; color:grey;cursor:pointer;" id="starttask"></span>
                </div>
                    <p></p>
                    <p class='h4'><?= _TASKS_IN_EXECUTION ?></p>
                <div id="frmTasks" class="table-responsive">
                   
                </div>       
                    <p class='h4'><?= _UPCOMING_TASKS ?></p>
                <div id="frmUpcomingTasks" class="table-responsive">
                   
                </div>   
            </div>
            </div>
        </div>
        <div class="container-fluid">
            
        </div>
        
        <div id="addusercategory_view"></div>
        
<div class="modal" tabindex="-1" role="dialog" id="messages_modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal_title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modal_body">
        
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
        
        <div id="frmTaskNotes"></div>