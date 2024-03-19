<?php
require_once '../../system/system.php';
echo only_reg();

#-Достижения незнакомец-#
switch($act){
case 'get':
#-Проверяем что достижение еще не выполнено-#
if($user['ach_save'] != 1){
	
#-Достижение-#
if($user['ach_save'] == 0 and $user['save'] == 1){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `silver` = :silver, `ach_save` = 1 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':silver' => $user['silver']+2000, ':id' => $user['id'])); 	
#-Доспех-#
$ins_weapon_me = $pdo->prepare("INSERT INTO `weapon_me` SET `type` = :type, `weapon_id` = :weapon_id, `user_id` = :user_id, `time` = :time");
$ins_weapon_me->execute(array(':type' => 2, ':weapon_id' => 2, ':user_id' => $user['id'], ':time' => time()));
header('Location: /achive');
$_SESSION['ok'] = 'Награда получена!';
exit();
}else{
header('Location: /achive');
exit();	
}
}else{
header('Location: /achive');
$_SESSION['err'] = 'Достижение выполнено!';
exit();	
}
}
?>