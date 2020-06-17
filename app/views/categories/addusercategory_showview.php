<?php
    $lang = $_SESSION['language'];
    require_once "assets/addusercategory_showview_{$lang}.php"; 
?>
<script>
    $(document).ready(function(){
       $('#addusercategory_showview_modal').modal('show');
       
       $("#btnAddCategory").click(function(){
           var catname = $("#categoryname").val();
           if(catname.length > 0)
           {
               $.ajax({ 
             // $account, $user, $checksum, $firstname, $lastname, $email, $password, $password2
             url: "/dailyboost/public/CategoriesController/addusercategory/" + catname,
             type: 'POST',
             dataType: "html",
             data: {     
             },
             success: function (result) {
                  console.log(result);
                  window.location.href = window.location.href;
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
    });
    </script>

<div class="modal" tabindex="-1" role="dialog" id="addusercategory_showview_modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?= _TITLE ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p><?= _CATEGORYNAME ?><input type="text" id="categoryname" /></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="btnAddCategory"><?= _SAVECHANGES ?></button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= _CLOSE ?></button>
      </div>
    </div>
  </div>
</div>