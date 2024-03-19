<?php
require_once '../../system/system.php';
$head = 'Создание клана';
echo only_reg();
echo clan_level();
require_once H.'system/head.php';
#-Проверяем что мы не состоим в клане-#
$sel_clan_u = $pdo->prepare("SELECT `user_id` FROM `clan_users` WHERE `user_id` = :user_id");
$sel_clan_u ->execute(array(':user_id' => $user['id']));
if($sel_clan_u -> rowCount() == 0){
echo'<div class="page">';
echo'<center>';
echo'<form method="post" action="/create_clan_act?act=create">';
echo'<input class="input_form" type="text" name="name" placeholder="Название клана [5-25]" maxlength="25"/><br/>';
echo'<select name="type">';
echo'<option value="0">Открытый</option>';
echo'<option value="1">Закрытый</option>';
echo'</select>';
echo'<input class="button_green_i" name="submit" type="submit"  value="Создать"/>';
echo'</form>';
echo'<div style="padding-top: 5px;"></div>';
echo'</center>';
echo'</div>';
echo'<div class="line_1"></div>';
echo'<div class="body_list">';
echo'<div class="info_list">';
echo'<img src="/style/images/body/imp.png" alt=""/>Стоимость создания клана <img src="/style/images/many/gold.png" alt=""/>1000 золота';
echo'</div>';
echo'</div>';
}else{
echo'<div class="body_list">';	
echo'<div class="error_list">';	
echo'<img src="/style/images/body/error.png" alt=""/>Вы уже состоите в клане!';
echo'</div>';
echo'</div>';
}
require_once H.'system/footer.php';
?>
