<?php
    $lang = $_SESSION['language'];
    require_once "assets/index_{$lang}.php"; 

?>


  <?php 
            if(strlen($_SESSION['username'])>0)
            {?>



<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.2/js/umd/collapse.js" integrity="sha256-B5yhRli9R8eddiwQBLJekW6CKEuOO3qXRWLdg4mvdy8=" crossorigin="anonymous"></script>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  <!-- Moment.js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js" integrity="sha256-5oApc/wMda1ntIEK4qoWJ4YItnV4fBHMwywunj8gPqc=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.31/moment-timezone.min.js" integrity="sha256-feldwaIKmjN0728wBssgenKywsqNHZ6dIziXDVaq9oc=" crossorigin="anonymous"></script>
<!-- Datetimepicker -->        
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js" integrity="sha256-5YmaxAwMjIpMrVlK84Y/+NjCpKnFYa8bWWBbUHSBGfU=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.css" integrity="sha256-b5ZKCi55IX+24Jqn638cP/q3Nb2nlx+MH/vMMqrId6k=" crossorigin="anonymous" />


  <script>
  $(document).ready(function(){
      
      var userId = <?= $_SESSION['userid'] ?>;
      
      var categories = [];
      
      <?php
        if(isset($data['categories_list']))
        {
            $cats = $data['categories_list'];
            for($i=0; $i < sizeof($cats); $i++)
            { ?>
                    categories.push(<?= $cats[$i]->id ?>);
            <?php 
            }
        }
     ?>
 
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
  
  loadCategoriesPanel();

        $(function () {
            /*var currDate = new Date();
            var tomorrow = currDate.getDate() + 1;*/
        var currDate = moment().add(1,'days').format('YYYY-MM-DD 08:00:00');
        var tomorrow = moment().add(1,'days').format('YYYY-MM-DD 19:00:00');
        var minDate = moment().format('YYYY-MM-DD HH:mm:ss');
            $('#datetimepicker1').datetimepicker({
                locale: '<?= $lang ?>',
                format: 'YYYY-MM-DD HH:mm:ss',
                useCurrent: true,
                minDate: minDate,
                defaultDate: currDate,
                calendarWeeks: true,
                showTodayButton: true,
                showClose: true,
            });
            $('#datetimepicker2').datetimepicker({
                locale: '<?= $lang ?>',
                format: 'YYYY-MM-DD HH:mm:ss',
                useCurrent: true,
                minDate: minDate,
                defaultDate: tomorrow,
                calendarWeeks: true,
                showTodayButton: true,
                showClose: true,
            });
        });
        
        $("#btnSaveTask").click(function(){
        
        $("#taskid").removeClass("is-invalid");
        $("#taskid").removeClass("is-valid");
        $("#addtask_category").removeClass("is-valid");
        $("#addtask_category").removeClass("is-invalid");
        $("#startdate").removeClass("is-invalid");
        $("#enddate").removeClass("is-invalid");
        $("#startdate").removeClass("is-valid");
        $("#enddate").removeClass("is-valid");
        
            var start = new Date(moment($("#startdate").val()).format());
            var end = new Date(moment($("#enddate").val()).format());
            
            var taskName = $('#taskid').val();
            var catName = $('#addtask_category').val();
            categoryId = $("#categorieslist option[value='" + catName + "']").attr('data-id'); 
            if(categoryId == null || !$.isNumeric(categoryId))
            {
                categoryId = -1;
            }
            var neverending = $("#chkNeverEnding").prop("checked");
            
            var check = false;
            var checkTaskName = false;
            var strInputVal = "";
            if(taskName.length > 0 && taskName.length < 255)
            {
                checkTaskName = true;
                $("#taskid").addClass("is-valid");
            }
            else
            {
                checkTaskName = false;
                strInputVal += "<?= _INPUT_VALUIATION_ERROR_TASKNAME ?><br />";
                $("#taskid").addClass("is-invalid");
            }
            
            var checkCategory = false;
            if(categoryId >=0 )
            {
                checkCategory = true;
                $("#addtask_category").addClass("is-valid");
            }
            else
            {
                checkCategory = false;
                strInputVal += "<?= _INPUT_VALIDATION_ERROR_CATEGORY ?><br />";
                $("#addtask_category").addClass("is-invalid");
            }
            
            var checkNeverEnding = false;
            if(neverending == true || neverending == false)
            {
                checkNeverEnding = true;
                $("#chkNeverEnding").addClass("is-valid");
            }
            else
            {
                checkNeverEnding = false;
                strInputVal += "<?= _INPUT_VALIDATION_ERROR_NEVERENDING ?><br />";
                $("#chkNeverEnding").addClass("is-invalid");
            }
            
            var checkDates = false;
            if(start <=end)
            {
                checkDates = true;
                $("#startdate").addClass("is-valid");
                $("#enddate").addClass("is-valid");
            }
            else
            {
                checkDates = false;
                strInputVal += "<?= _INPUT_VALIDATION_ERROR_DATES ?><br />";
                $("#startdate").addClass("is-invalid");
                $("#enddate").addClass("is-invalid");
            }
            
            check = checkTaskName && checkCategory && checkNeverEnding && checkDates;
            
            if(check)
            {
                $.ajax({ 
                    url: "/dailyboost/public/PlanningController/addPlannedTask/",
                    type: 'POST',
                    dataType: "html",
                    contentType: "application/x-www-form-urlencoded",
                    data: {
                        category_id: categoryId,
                        taskname: taskName,
                        neverending: neverending,
                        start_date: moment(start).format('YYYY-MM-DD HH:mm:ss'),
                        end_date: moment(end).format('YYYY-MM-DD HH:mm:ss'),
                    },
                    success: function (result) {
                        console.log(result);
                        if(result.length > 0)
                        {
                            $("#taskid").val('');
                            $("#addtask_category").val('');
                            $("#taskid").removeClass("is-invalid");
                            $("#taskid").removeClass("is-valid");
                            $("#addtask_category").removeClass("is-valid");
                            $("#addtask_category").removeClass("is-invalid");
                            $("#startdate").removeClass("is-invalid");
                            $("#enddate").removeClass("is-invalid");
                            $("#startdate").removeClass("is-valid");
                            $("#enddate").removeClass("is-valid");
                            loadTasksPlanned();
                        }
                        else
                        {
                           $("#modal_title").html('<?= _GENERIC_ERROR_TITLE ?>');
                           $("#modal_body").html('<?= _GENERIC_ERROR_BODY ?>'); 
                           $("#messages_modal").modal('show');
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
            else
            {
                $("#taskid").addClass("is-invalid");
                $("#modal_title").html('<?= _INPUT_VALIDATION_ERROR_TITLE ?>');
                $("#modal_body").html(strInputVal); 
                $("#messages_modal").modal('show');
                setTimeout(function() {$('#messages_modal').modal('hide');}, 3000);
            }
        });
        
        
        function loadTasksPlanned()
        {
            $("#imgloadtasksinexecution").fadeIn();
            $.ajax({ 
                   // $account, $user, $checksum, $firstname, $lastname, $email, $password, $password2
                   url: "/dailyboost/public/PlanningController/listPlannedTasks",
                   type: 'POST',
                   dataType: "html",
                   data: {
                       categories_filter: JSON.stringify(categories)
                   },
                   success: function (result) {
                      //console.log(result);
                      if(result.length > 0)
                      {
                          var res = JSON.parse(result);
                          if(res.length > 0)
                          {
                      var strTable = "<table class='table table-striped table-hover table-borderless' id='tblTasksInExecution'>"
                              +"<thead><tr>"
                              +"<th><?= TASKLIST_CATEGORYNAME ?></th>"
                                +"<th><?= TASKLIST_TASKNAME ?></th>"
                                +"<th><?= TASKLIST_STATUS ?></th>"
                                +"<th><?= TASKLIST_EARLYSTART ?></th>"
                                +"<th><?= TASKLIST_LATESTART ?></th>"
                        +"<th><?= TASKLIST_NEVERENDING ?></th>"
                              +"</tr></thead><tbody>";
                      
                      for(var i = 0; i < res.length; i++)
                      {
                          var strstatus = "";
                              switch(res[i].status)
                              {
                                  case "N":
                                      strstatus = "<?= TASKLIST_STATUS_N ?>";
                                      break;
                                      case "E":
                                      strstatus = "<?= TASKLIST_STATUS_E ?>";
                                      break;
                                      case "P":
                                      strstatus = "<?= TASKLIST_STATUS_P ?>";
                                      break;
                                      case "F":
                                      strstatus = "<?= TASKLIST_STATUS_F ?>";
                                      break;
                                }
                                
                                strStart = "";
                                strEnd = "";
                                
                                var dt = new Date(2000,1,1);                                
                                if(new Date(res[i].earlystart.date) > dt)
                                {
                                    strStart = moment(res[i].earlystart.date).format('DD/MM/YYYY HH:mm:ss');
                                }
                                
                                if(new Date(res[i].latefinish.date) > dt)
                                {
                                    strEnd = moment(res[i].latefinish.date).format('DD/MM/YYYY HH:mm:ss');
                                }
                                    
                                
                          strTable += "<tr>"
                          +"<td>"+res[i].category_name+"</td>"
                                  +"<td>" + res[i].name + "</td>"
                                +"<td>"+strstatus+"</td>"
                                    +"<td>"+ strStart + "</td>"
                                    +"<td>" + strEnd + "</td>"
                                    +"<td>" + res[i].neverending + "</td>"
                                  +"</tr>";
                      }
                      strTable+="</tbody></table>";
                      
                      $("#dvLstPlannedTasks").html(strTable);
                      }
                      }
                      else
                      {
                        $("#dvLstPlannedTasks").html('');
                      }
                      $("#imgloadtasksinexecution").fadeOut();
                   },
                   error: function (result) {
                       $("#imgloadtasksinexecution").fadeOut();
                       alert("Error");
                   },
                   warning: function (result) {
                       $("#imgloadtasksinexecution").fadeOut();
                                      alert("Warning");
                                  }
          });
        }
        
        $(".catfilter").click(function(){
            var acatid = $(this).prop("id").split('_');
            var catid = acatid[1];
            var ind = $.inArray(parseInt(catid), categories);
            if(ind != -1)
            {
                    categories.splice(ind, 1);
                    $(this).removeClass("label-primary");
                    $(this).addClass("label-warning");
            }
            else
            {
                categories.push(parseInt(catid));
                $(this).removeClass("label-warning");
                $(this).addClass("label-primary");
            }
            loadTasksPlanned();
        });
        
        
        $("#imgloadtasksinexecution").fadeOut();
        loadTasksPlanned();
    
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
                            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
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
                            Your reports
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
                            <div id="collapsePlanningBoard" class="collapse show" aria-labelledby="headingPlanningBoard" data-parent="#accordion">
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
                    <p class="h3"><?= $data['title'] ?></p>
                    <p class="h4"><?= _NEW_PLANNED_TASK ?></p>
                    <div class="row">
                        <div class="col-lg-6 align-middle">
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
                            <div class="input-group-lg mb-3">
                                <input id="taskid" list="opentasks_list" placeholder="<?= _PLACEHOLDER_STARTTASK ?>" class="form-control" maxlength="254" />&nbsp;</div>
                                <div class="input-group-lg mb-3">
                                      <input id="addtask_category" list="categorieslist" placeholder="<?= _PLACEHOLDER_SELECTCATEGORY ?>" class="form-control" maxlength="254" />
                                </div>
                            </div>
                            <p></p>
                      
                            <div class="input-group-lg input-daterange align-items-center form-inline">
                                <div class='input-group date' id='datetimepicker1'>
                                    <input type='text' class="form-control" id="startdate"  />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>&nbsp;
                                <span><?= _PLANNINGBOARD_TO_DATE ?></span>&nbsp;
                                <div class='input-group date' id='datetimepicker2'>
                                    <input type='text' class="form-control" id="enddate" />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <div class="input-group"  />
                                <input type="checkbox" id="chkNeverEnding" class="form-check-input" /><?= _PLANNINGBOARD_NEVER_ENDING_TASK_CHK ?></div>

                            </div>
                        </div>
                        <div class="col-lg-6 align-middle">
                        <div class="input-group form-control-lg pull-left"><button type="button" class="btn btn-primary btn-lg pull-left" id="btnSaveTask"><?= _PLANNINGBOARD_SAVE ?></button></div>
                        </div>
                    </div>
                    
                    
                     <div class="row">
                        <div class="col-lg">
                            <p class="h4"><?= _PLANNEDTASKS_LIST_TITLE ?></p>
                        </div>  
                    </div>
                     <div class="row">
                        <div class="col-lg">
                            <?php
                                if(isset($data['categories_list']))
                                {
                                    $cats = $data['categories_list'];
                                    for($i=0; $i < sizeof($cats); $i++)
                                    { ?>
                            <span id="taskscatfilter_<?= $cats[$i]->id ?>" class="label label-pill label-primary catfilter" style="cursor: pointer;"><?= $cats[$i]->name ?></span>
                            </span>
                                    <?php 
                                    }
                                }
                                ?>
                        </div>  
                    </div>
                    <div class="row">
                        <div class="col-lg" id="dvLstPlannedTasks">
                            <img src="../../../img/three-circles.gif" id="imgloadtasksinexecution" style="background-color:transparent;" />
                        </div>
                    </div>
                    
            </div>
        </div>
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