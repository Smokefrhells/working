<?php
require_once '../../system/system.php';
echo only_reg();
echo coliseum_level();

#-Выборка игрока который атакует текущего игрока-#
switch($act){
case 'me':
#-Проверка что игрок участвует в бою-#
$sel_coliseum_me = $pdo->prepare("SELECT `id`, `user_id`, `battle_id`, `ank_id`, `statys` FROM `coliseum` WHERE `user_id` = :user_id AND `statys` = 2");
$sel_coliseum_me->execute(array(':user_id' => $user['id']));
if($sel_coliseum_me->rowCount() == 0) $error = 'Данные не найдены!';

#-Нет ошибок-#
if(!isset($error)){
$coliseum_me = $sel_coliseum_me->fetch(PDO::FETCH_LAZY);
#-Выборка игрока который атакует-#
$sel_coliseum_u = $pdo->prepare("SELECT `id`, `ank_id`, `statys`, `user_id`, `battle_id` FROM `coliseum` WHERE `ank_id` = :user_id AND `user_id` != :user_id AND `user_id` != :ank_id AND `statys` = 2 AND `battle_id` = :battle_id");
$sel_coliseum_u->execute(array(':user_id' => $user['id'], ':ank_id' => $coliseum_me['ank_id'], ':battle_id' => $coliseum_me['battle_id']));
if($sel_coliseum_u -> rowCount() != 0){
$coliseum_u = $sel_coliseum_u->fetch(PDO::FETCH_LAZY);
#-Смена оппонента-#
$upd_coliseum = $pdo->prepare("UPDATE `coliseum` SET `ank_id` = :ank_id WHERE `user_id` = :user_id");
$upd_coliseum->execute(array(':ank_id' => $coliseum_u['user_id'], ':user_id' => $user['id']));
}
header('Location: /coliseum');
}else{
header('Location: /coliseum');
$_SESSION['err'] = $error;
exit();
}
}
?>