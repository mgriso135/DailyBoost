  <?php
    $lang = $_SESSION['language'];
    require_once "assets/UserExternalAccountsList_{$lang}.php"; 
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
        $apps = $data['accounts'];
        for($i = 0; $i < sizeof($apps); $i++)
        { ?>
        <tr>
            <td><?= $apps[$i]->ExternalAccountType ?></td>
            <td><?= $apps[$i]->ExternalAccountName ?></td>
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