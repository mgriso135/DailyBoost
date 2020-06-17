<?php
    $lang = $_SESSION['language'];
    require_once "assets/dailyreport_{$lang}.php"; 

?>


  <?php 
            if(strlen($_SESSION['username'])>0)
            {?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

  <script>
  $(document).ready(function(){
      var tsResume = [];
      var catsResume = [];
      google.charts.load('current', {'packages':['corechart']});
     google.charts.setOnLoadCallback(drawCharts);

      
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
    
    $('#startdate').datepicker({
        calendarWeeks: true,
            todayBtn: true,
            todayHighlight: true,
            weekStart: 1,
            daysOfWeekHighlighted: "6,0",
            autoclose: true,
    });
    
    $('#enddate').datepicker({
        calendarWeeks: true,
            todayBtn: true,
            todayHighlight: true,
            weekStart: 1,
            daysOfWeekHighlighted: "6,0",
            autoclose: true,
    });
    
    $("#search").click(function()
    {
        var astartd = $("#startdate").val().split('-');
        var aendd = $("#enddate").val().split('-');
        
        if(astartd.length == 3 && aendd.length == 3)
        {
            start = new Date(astartd[2], astartd[1] - 1, astartd[0]);
            end = new Date(aendd[2], aendd[1] - 1, aendd[0]);
            if(start <= end)
            {
                loadTimespans(start, end); 
                loadReportByTaskId(start, end);
            }
            else
            {
                alert("start must be < than end");
            }
        }
        else
        {
            alert("input date error");
        }
        
    });
    
    function loadTimespans(startd, endd)
    {
            if(startd <= endd)
            {
                 $.ajax({ 
               // $account, $user, $checksum, $firstname, $lastname, $email, $password, $password2
               url: "/dailyboost/public/ReportsController/getUserTimespans",
               type: 'POST',
               dataType: "html",
               data: {
                   userid: <?= $_SESSION['userid'] ?>,
                   start: startd.getFullYear() + "-" + (startd.getMonth()+1) + "-" + startd.getDate(),
                   end: endd.getFullYear() + "-" + (endd.getMonth()+1) + "-" + endd.getDate(),
               },
               success: function (result) {
        $("#divTable").html(result);
        var res = JSON.parse(result);
                  

                  var strtable = "<table class='table table-hover table-striped'><thead><tr>"
                  +"<th><?= _TIMESPAN_CATEGORYNAME ?></th>"
                          +"<th><?= _TIMESPAN_ID ?></th>"
                          +"<th><?= _TIMESPAN_USERID ?></th>"
                          +"<th><?= _TIMESPAN_TASKID ?></th>"
                          +"<th><?= _TIMESPAN_TASKNAME ?></th>"
                          +"<th><?= _TIMESPAN_STARTDATE ?></th>"
                          +"<th><?= _TIMESPAN_ENDDATE ?></th>"
                          +"<th><?= _TIMESPAN_DURATION ?></th>"
                          +"</tr></thead><tbody>";

                  for(var i = 0; i < res.length; i++)
                  {
                      var startdate = new Date(res[i].starteventdate.date);
                      strtable += "<tr>"
                      +"<td>"+res[i].category_name+"</td>"
                              +"<td>"+res[i].id+"</td>"
                            +"<td>"+res[i].userid+"</td>"
                            +"<td>"+res[i].taskid+"</td>"
                            +"<td>"+res[i].task_name + "</td>"
                            +"<td>" + res[i].starteventdate.date.substring(0, 19) + "</td>"
                            +"<td>"+res[i].endeventdate.date.substring(0, 19)+"</td>"
                            +"<td>"+Math.round(res[i].duration*100)/100+"</td>"
            +"</tr>"
                  }
                  strtable += "</tbody></table>";
                  
                  $("#divTable").html(strtable);
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
                alert("Start must be < than end");
            }
        }
        
        function loadReportByTaskId(startd, endd)
        {
            if(startd <= endd)
            {
                 $.ajax({ 
               // $account, $user, $checksum, $firstname, $lastname, $email, $password, $password2
               url: "/dailyboost/public/ReportsController/getUserDailyReportGroupedByTaskId",
               type: 'POST',
               dataType: "html",
               data: {
                   userid: <?= $_SESSION['userid'] ?>,
                   start: startd.getFullYear() + "-" + (startd.getMonth()+1) + "-" + startd.getDate(),
                   end: endd.getFullYear() + "-" + (endd.getMonth()+1) + "-" + endd.getDate(),
               },
               success: function (result) {
        console.log(result);
        console.log(JSON.parse(result).tasks);
        var res = JSON.parse(result).tasks;
        tsResume = res;
        var cats = JSON.parse(result).categories;
        catsResume = cats;
        
        
                  var strtable = "<table class='table table-hover table-striped'><thead><tr>"
                    +"<th><?= _TIMESPAN_CATEGORYNAME ?></th>"
                      +"<th><?= _TIMESPAN_TASKID ?></th>"
                      +"<th><?= _TIMESPAN_TASKNAME ?></th>"
                      +"<th><?= _TIMESPAN_DURATION ?></th>"
                      +"</tr></thead><tbody>";

                  for(var i = 0; i < res.length; i++)
                  {
                      strtable += "<tr>"
                      +"<td>"+res[i].category_name+"</td>"
                            +"<td>"+res[i].taskid+"</td>"
                            +"<td>"+res[i].taskname + "</td>"
                            +"<td>"+Math.round(res[i].totalDuration*100)/100+"</td>"
            +"</tr>"
                  }
                  strtable += "</tbody></table>";
                  console.log(strtable);
                  $("#divTableGroupByTaskId").html(strtable);
                  loadTaskCheckboxes();
                  loadCategoriesCheckboxes();
                  drawCharts();
                  drawCategoriesChart();
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
                alert("Start must be < than end");
            }
        }
        
        function loadTaskCheckboxes()
        {
            // divCheckboxesGroupByTaskId
            var strchks = "";
            for(var i = 0; i < tsResume.length; i++)
            {
                strchks +="<input type='checkbox' id='task_" + tsResume[i].taskid + "' class='chkTaskGraph' name='chkTaskGraph' checked />"
             + tsResume[i].taskname + "&nbsp;";
            }
            $("#divCheckboxesGroupByTaskId").html(strchks);
        }
        
        function loadCategoriesCheckboxes()
        {
            // divCheckboxesGroupByTaskId
            var strchks = "";
            for(var i = 0; i < catsResume.length; i++)
            {
                strchks +="<input type='checkbox' id='category_" + catsResume[i].category_id + "' class='chkCategoriesGraph' name='chkCategoriesGraph' checked />"
             + catsResume[i].category_name + "&nbsp;";
            }
            $("#divCheckboxesGroupByCategory").html(strchks);
        }
    
    function drawTasksChart() {
        var hours = [];
        hours.push(['Task Name', 'Duration']);
        for(var i = 0; i < tsResume.length; i++)
        {
            if($("#task_" + tsResume[i].taskid).prop("checked"))
            {
                hours.push([tsResume[i].taskname, tsResume[i].totalDuration]);
            }
        }       
       data = google.visualization.arrayToDataTable(hours);

        var options = {
          title: 'My Daily Activities'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
      
      function drawCategoriesChart() {
        var hours = [];
        hours.push(['Category', 'Duration']);
        for(var i = 0; i < catsResume.length; i++)
        {
            if($("#category_" + catsResume[i].category_id).prop("checked"))
            {
                hours.push([catsResume[i].category_name, catsResume[i].totalDuration]);
            }
        }       
       data = google.visualization.arrayToDataTable(hours);

        var options = {
          title: 'My Daily Activities by Category'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_categories'));

        chart.draw(data, options);
      }
      
      function drawCharts()
      {
          drawTasksChart();
          drawCategoriesChart();
      }
      
      $("#divCheckboxesGroupByTaskId").on("click", ".chkTaskGraph", function(){
          drawTasksChart();
      });
      
      $("#divCheckboxesGroupByCategory").on("click", ".chkCategoriesGraph", function(){
          drawCategoriesChart();
      });
    
    var today = new Date();
    var tomorrow = new Date();
    tomorrow.setDate(today.getDate() + 1);
    
    $('#startdate').datepicker("setDate", today);
    $('#enddate').datepicker("setDate", tomorrow);
    
    loadTimespans(today, tomorrow);
    loadReportByTaskId(today, tomorrow);
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
                            <div id="collapseReports" class="collapse show" aria-labelledby="headingReports" data-parent="#accordion">
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
                    <p class="h3"><?= $data['title'] ?></p>
                    <div>
                        <div class="input-group align-items-md-center date">
                            Start:&nbsp;<input type="text" class="form-control date col-lg-2" id="startdate" data-date-format="dd-mm-yyyy" readonly />
                            End:&nbsp;<input type="text" class="form-control date col-lg-2" id="enddate" data-date-format="dd-mm-yyyy" readonly />
                            <span class="icon-control-play" style="width:10%; height:10%;color:grey;cursor:pointer;" data-inline="false" id="search"></span>
                        </div>
                    </div>
                    <p></p>
                    <div class="col-lg-12">
                    <div id="divTableGroupByTaskId"></div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                    <div id="divCheckboxesGroupByTaskId"></div>
                    
                        <div id="piechart" style="width: 900px; height: 500px;"></div>
                        </div>
                        <div class="col-sm">
                        
                            <div id="divCheckboxesGroupByCategory">Category text</div>
                    
                        <div id="piechart_categories" style="width: 900px; height: 500px;"></div>
                        </div>
                        
                        </div>
                    <div id="divTable"></div>
                    </div>
                    
            
            </div>
        </div>

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