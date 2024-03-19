<?php
require_once '../../system/system.php';
echo only_reg();
echo save();
#-Выбор игроков которые бьют текущего героя-#
switch($act){
case 'me':
#-Проверка что игрок сражаеться-#
$sel_battle_u = $pdo->prepare("SELECT `id`, `user_id`, `ank_id`, `location` FROM `hunting_battle_u` WHERE `user_id` = :user_id");
$sel_battle_u ->execute(array(':user_id' => $user['id']));
if($sel_battle_u->rowCount() == 0) $error = 'Вы не сражаетесь!';
#-Если нет ошибок-#
if(!isset($error)){
$battle_u = $sel_battle_u->fetch(PDO::FETCH_LAZY);
#-Делаем выборку доступных врагов-#
$sel_vrag = $pdo->prepare("SELECT * FROM `hunting_battle_u` WHERE `location` = :location AND `user_id` != :user_id AND `user_id` != :ank_id AND `ank_id` = :user_id");
$sel_vrag->execute(array(':location' => $battle_u['location'], ':user_id' => $user['id'], ':ank_id' => $battle_u['ank_id'])); 
$vrag = $sel_vrag->fetch(PDO::FETCH_LAZY);
if($sel_vrag-> rowCount() != 0){
$upd_battle_u = $pdo->prepare("UPDATE `hunting_battle_u` SET `ank_id` = :ank_id WHERE `user_id` = :user_id");
$upd_battle_u->execute(array(':ank_id' => $vrag['user_id'], ':user_id' => $user['id']));
}
header('Location: /hunting_battle_u');
}else{
header('Location: /hunting_battle_u');
$_SESSION['err'] = $error;
exit();	
}
}
#-Выбор другого врага-#
switch($act){
case 'next':
#-Проверка что игрок сражаеться-#
$sel_battle_u = $pdo->prepare("SELECT `id`, `user_id`, `ank_id`, `location` FROM `hunting_battle_u` WHERE `user_id` = :user_id");
$sel_battle_u ->execute(array(':user_id' => $user['id']));
if($sel_battle_u->rowCount() == 0) $error = 'Вы не сражаетесь!';
#-Если нет ошибок-#
if(!isset($error)){
$battle_u = $sel_battle_u->fetch(PDO::FETCH_LAZY);
#-Делаем выборку доступных врагов-#
$sel_vrag = $pdo->prepare("SELECT * FROM `hunting_battle_u` WHERE `location` = :location AND `user_id` != :user_id AND `user_id` != :ank_id");
$sel_vrag->execute(array(':location' => $battle_u['location'], ':user_id' => $user['id'], ':ank_id' => $battle_u['ank_id'])); 
$vrag = $sel_vrag->fetch(PDO::FETCH_LAZY);
if($sel_vrag-> rowCount() != 0){
$upd_battle_u = $pdo->prepare("UPDATE `hunting_battle_u` SET `ank_id` = :ank_id WHERE `user_id` = :user_id");
$upd_battle_u->execute(array(':ank_id' => $vrag['user_id'], ':user_id' => $user['id']));
}
header('Location: /hunting_battle_u');
}else{
header('Location: /hunting_battle_u');
$_SESSION['err'] = $error;
exit();	
}
}
?>