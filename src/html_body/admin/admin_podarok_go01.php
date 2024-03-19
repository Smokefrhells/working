<?php
require_once '../../system/system.php';
$head = 'Подарок';
echo only_reg();
echo admin();
require_once H.'system/head.php';
echo'<div class="page">';
echo'<center>';
echo'<form method="post" action="/admin_act?act=podarok">';
echo'<input class="input_form" type="text" name="quatity" placeholder="Количество"/><br/>';
echo'<select name="type">';
echo'<option value="1">Ключи</option>';
echo'<option value="2">Серебро</option>';
echo'<option value="3">Золото</option>';
echo'<option value="4">Крислаллы</option>';
echo'</select><br/>';
echo'<input class="button_green_i" name="submit" type="submit" value="Готово"/>';
echo'</form>';
echo'<div style="padding-top: 5px;"></div>';
echo'</div>';
require_once H.'system/footer.php';
?>