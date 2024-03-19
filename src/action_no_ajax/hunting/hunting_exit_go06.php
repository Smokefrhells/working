<?php
require_once '../../system/system.php';
echo only_reg();
echo hunting_campaign();
/*Покидаем бой*/
switch($act){
case 'exit':
#-Проверка на ошибки-#
if($user['start'] == 1) $error = 'Пройдите обучение!';
#-Проверяем в бою мы или нет-#
$sel_hunting_b = $pdo->prepare("SELECT * FROM `hunting_battle` WHERE `user_id` = :user_id");
$sel_hunting_b->execute(array(':user_id' => $user['id']));
if($sel_hunting_b-> rowCount() == 0) $error = 'Бой не найден!';
#-Если нет ошибок-#
if(!isset($error)){
#-Удаляем бой-#	
$del_hunting_b = $pdo->prepare("DELETE FROM `hunting_battle` WHERE `user_id` = :user_id");	
$del_hunting_b->execute(array(':user_id' => $user['id']));
#-Убираем статус боя у игрока-#
$upd_users = $pdo->prepare("UPDATE `users` SET `battle` = :battle, `hunting_progrash` = :hunting_progrash WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':battle' => 0, ':hunting_progrash' => $user['hunting_progrash']+1, ':id' => $user['id']));
header('Location: /select_location');
exit();	
}else{
header('Location: /select_location');
$_SESSION['err'] = $error;
exit();	
}
}
?>