<?php
    $lang = $_SESSION['language'];
    require_once "assets/usercategories_{$lang}.php"; 
?>

<script>
    $(document).ready(function(){
        $(".channel").click(function(){
            var id = $(this).attr('id');
            var ids = id.split('_');
            if(ids.length ==2)
            {
                window.location.href="/dailyboost/public/home/main/"+ ids[1];
            }
        });
    });
    </script>

<div>
    <div><input type="button" style="border:0px; background-color: transparent;" class="channel" id="channel_-1" value="All"></div>
    <?php
    $cats = $data['categories'];
    
    for($i=0; $i < sizeof($cats);$i++)
    {?>
    <div><input type="button" style="border:0px; background-color: transparent;" class="channel" id="channel_<?= $cats[$i]->id?>" value="<?= $cats[$i]->name ?>"></div>
    <?php }
    ?>
</div>