<?php
    $lang = $_SESSION['language'];
    require_once "assets/tasksnotesform_{$lang}.php"; 
?>
 <!-- Moment.js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js" integrity="sha256-5oApc/wMda1ntIEK4qoWJ4YItnV4fBHMwywunj8gPqc=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.31/moment-timezone.min.js" integrity="sha256-feldwaIKmjN0728wBssgenKywsqNHZ6dIziXDVaq9oc=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function(){
        var taskid = <?= $data['task_id'] ?>;
       $("#tasksnotesform_modal").modal('show');
       
       $("#btnAddTaskNote").click(function(){
           console.log("Add note");
           $.ajax({ 
                // $account, $user, $checksum, $firstname, $lastname, $email, $password, $password2
                url: "/dailyboost/public/TasksExecutionController/addNote",
                type: 'POST',
                dataType: "html",
                data: {
                    taskid: taskid,
                    note: $("#note").val(),
                    private: $("#chkPrivate").prop("checked"),
                },
                success: function (result) {
                   var res = JSON.parse(result);
                   $('#tblNotesList > tbody:last-child').append(
                    '<tr>'
                        +'<td>'+moment(res.date.date, "YYYY-MM-DD HH:mm:ss").format("DD/MM/YYYY HH:mm:ss")+'</td>'
                        +'<td>'+res.fullname+'</td>'
                                +'<td>'+res.note+'</td>'
                        +'<td>'+res.private+'</td>'
                    +'</tr>');
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

<div class="modal" tabindex="-1" role="dialog" id="tasksnotesform_modal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?= _TITLE ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="row">
              <div class="col-lg-10">
            <div class="form-group">
                <textarea style="width:100%" id="note" class="form-control-lg" placeholder="<?= _TASK_NOTE ?>"></textarea>
            </div>
                  <div class="form-check">
                <input type="checkbox" id="chkPrivate" class="form-check-input" />
                <label class="form-check-label" for="privateCheck">Private note</label>
            </div>
                  </div>
              <div class="col-lg-2">
                  <div class="input-group">
                    <button type="button" class="btn btn-primary" id="btnAddTaskNote" class="btn btn-primary btn-lg pull-left"><?= _SAVECHANGES ?></button>
                  </div>
              </div>
          </div>
          <hr />
          <div class="row">
              <div class="col-lg-12">
                  <table id="tblNotesList" class="table table-striped">
                      <thead></thead>
                      <tbody>
                          <?php 
                          $notes = $data['tasks_notes'];
                          for($i = 0; $i < sizeof($notes); $i++)
                          { ?>
                          <tr>
                              <td><?= $notes[$i]->date->format('d/m/Y h:i:s') ?></td>
                              <td><?= $notes[$i]->fullname ?></td>
                              <td><?= $notes[$i]->note ?></td>
                              <td><?php
                              if($notes[$i]->private == "1") { echo _TASK_NOTE_PRIVATE;} else { echo _TASK_NOTE_PUBLIC;}
                                  ?></td>
                          </tr>
                          <?php }
                          ?>
                      </tbody>
                  </table>
              </div>
          </div>
      </div>
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-primary" id="btnAddCategory"><?= _SAVECHANGES ?></button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= _CLOSE ?></button>-->
      </div>
    </div>
  </div>
</div>