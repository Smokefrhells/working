<?php
require_once '../../system/system.php';
$head = 'Зачисление золота';
echo only_reg();
echo admin();
require_once H.'system/head.php';
echo'<div class="page">';
echo'<center>';
echo'<form method="post" action="/admin_act?act=gold">';
echo'<input class="input_form" type="text" name="ank_id" value="'.$_GET['id'].'" placeholder="ID игрока"/><br/>';
echo'<input class="input_form" type="text" name="quatity" placeholder="Количество золота"/><br/>';
echo'<input class="button_green_i" name="submit" type="submit" value="Зачислить"/>';
echo'</form>';
echo'<div style="padding-top: 5px;"></div>';
echo'</div>';
require_once H.'system/footer.php';
?>