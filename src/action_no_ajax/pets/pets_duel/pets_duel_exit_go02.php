<?php
require_once '../../../system/system.php';
echo only_reg();
echo pets_level();

#-Выход из очереди дуэльного поединка-#
switch($act){
case 'exit_osh':
#-Проверка что питомец стоит в очереди-#
$sel_pets_duel = $pdo->prepare("SELECT `id`, `user_id`, `statys`, `pets_id` FROM `pets_duel` WHERE `user_id` = :user_id AND `statys` = 0");
$sel_pets_duel->execute(array(':user_id' => $user['id']));
if($sel_pets_duel->rowCount() == 0) $error = 'Выход невозможен!';

#-Нет ошибок-#
if(!isset($error)){
$pets_duel = $sel_pets_duel->fetch(PDO::FETCH_LAZY);	

#-Статус не в бою-#
$upd_pets_me = $pdo->prepare("UPDATE `pets_me` SET `battle` = 0 WHERE `pets_id` = :pets_id AND `user_id` = :user_id LIMIT 1");
$upd_pets_me->execute(array(':pets_id' => $pets_duel['pets_id'], ':user_id' => $user['id']));
#-Удаление боя-#
$del_pets_duel = $pdo->prepare("DELETE FROM `pets_duel` WHERE `user_id` = :user_id");
$del_pets_duel->execute(array(':user_id' => $user['id']));
header('Location: /pets_duel');
}else{
header('Location: /pets_duel');
$_SESSION['err'] = $error;
exit();
}
}
?>