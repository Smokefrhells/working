<?php
require_once '../../system/system.php';
echo only_reg();
echo clan_level();
echo save();
$id = check($_GET['id']);
if(empty($_GET['id'])) $error = 'Ошибка!';
if(!isset($_GET['id'])) $error = 'Ошибка!';
if(!isset($error)){
$sel_clan = $pdo->prepare("SELECT * FROM `clan` WHERE `id` = :id");
$sel_clan->execute(array(':id' => $id));
if($sel_clan-> rowCount() != 0){
$clan = $sel_clan->fetch(PDO::FETCH_LAZY);
}else{
header('Location: /clan');
$_SESSION['err'] = 'Клан не найден!';
exit();
}
}else{
header('Location: /clan');
$_SESSION['err'] = $error;
exit();
}
$head = 'Объявление';
require_once H.'system/head.php';
#-Выборка игрока клана-#
$sel_clan_u = $pdo->prepare("SELECT `id`, `user_id`, `clan_id`, `prava` FROM `clan_users` WHERE `clan_id` = :clan_id AND `user_id` = :user_id AND (`prava` = 4 OR `prava` = 3)");
$sel_clan_u->execute(array(':clan_id' => $clan['id'], ':user_id' => $user['id']));
if($sel_clan_u-> rowCount() != 0){
echo'<div class="page">';	
echo'<center>';
echo'<form method="post" action="/clan_ad_act?act=send&clan_id='.$clan['id'].'">';
echo"<textarea class='input_form' type='text' name='ad' maxlength='2000'></textarea><br/>";
echo'<input class="button_green_i" name="submit" type="submit"  value="Отправить"/>';
echo'</center>';
echo'<div style="padding-top: 5px;"></div>';
echo'</div>';
}
require_once H.'system/footer.php';
?>