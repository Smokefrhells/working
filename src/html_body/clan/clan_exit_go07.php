<?php
require_once '../../system/system.php';
echo only_reg();
echo clan_level();
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
$head = 'Подтверждение';
require_once H.'system/head.php';
echo'<div class="page">';
#-Проверка что игрок состоит в клане-#
$sel_clan_u = $pdo->prepare("SELECT `clan_id`,`user_id`, `prava` FROM `clan_users` WHERE `clan_id` = :clan_id AND `user_id` = :user_id");
$sel_clan_u->execute(array(':clan_id' => $clan['id'], ':user_id' => $user['id']));
if($sel_clan_u -> rowCount() != 0){
$clan_u = $sel_clan_u->fetch(PDO::FETCH_LAZY);
#-Проверяем сколько еще игроков в клане-#
$sel_clan_c = $pdo->prepare("SELECT COUNT(*) FROM `clan_users` WHERE `clan_id` = :clan_id");
$sel_clan_c->execute(array(':clan_id' => $clan['id']));
$amount = $sel_clan_c->fetch(PDO::FETCH_LAZY);
if($amount[0] > 1){
#-Проверяем какие права-#
if($clan_u['prava'] != 4){
echo'<div style="padding-top: 5px;"></div>';
echo"<a href='/clan_exit_act?act=exit&clan_id=$clan[id]' class='button_red_a'>Я покидаю клан</a>";	
echo'<div style="padding-top: 5px;"></div>';
}else{
#-Проверяем есть ли в клане основатель-#
$sel_clan_u2 = $pdo->prepare("SELECT `clan_id`,`user_id`, `prava` FROM `clan_users` WHERE `clan_id` = :clan_id AND `prava` = 4 AND `user_id` != :user_id");
$sel_clan_u2->execute(array(':clan_id' => $clan['id'], ':user_id' => $user['id']));
if($sel_clan_u2 -> rowCount() != 0){
echo'<div style="padding-top: 5px;"></div>';
echo"<a href='/clan_exit_act?act=exit&clan_id=$clan[id]' class='button_red_a'>Я покидаю клан</a>";	
echo'<div style="padding-top: 5px;"></div>';	
}else{
echo'<div class="body_list">';
echo'<div class="error_list">';
echo'<img src="/style/images/body/error.png" alt=""/>Сначала передайте права Основателя!';
echo'</div>';
echo'</div>';
}
}
}else{
echo'<div style="padding-top: 5px;"></div>';
echo"<a href='/clan_exit_act?act=exit&clan_id=$clan[id]' class='button_red_a'>Я покидаю клан</a>";	
echo'<div style="padding-top: 5px;"></div>';	
}
}
echo'</div>';
require_once H.'system/footer.php';
?>
