  <?php
    $lang = $_SESSION['language'];
    require_once "assets/UserExternalAppsList_{$lang}.php"; 
    ?>



Apps list:<br />
<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>App type</th>
            <th>App name</th>
            <th>Account name</th>
            <th>Token valid</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $apps = $data['apps'];
        for($i = 0; $i < sizeof($apps); $i++)
        { ?>
        <tr>
            <td><?= $apps[$i]->ExternalAppType ?></td>
            <td><?= $apps[$i]->ExternalAppName ?></td>
            <td><?= $apps[$i]->AccountName ?></td>
            <td><?php 
                if($apps[$i]->isTokenValid == true)
                {
                    echo "Token valid";

                }
                else
                {
                    echo "Token expired. Please authenticate the account again";

                }
                ?></td>
        </tr>
        <?php }
        ?>
    </tbody>
</table>
<?= var_dump($data['apps']) ?>