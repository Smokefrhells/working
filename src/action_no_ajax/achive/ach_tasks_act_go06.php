<?php
require_once '../../system/system.php';
echo only_reg();
#-Достижения задания-#
switch($act){
case 'get':
#-Проверяем что достижение еще не выполнено-#
if($user['ach_tasks'] != 10){
	
#-Первое-#
if($user['ach_tasks'] == 0 and $user['tasks'] >= 3){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `silver` = :silver, `ach_tasks` = 1 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':silver' => $user['silver']+10000, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => 1, ':user_id' => $user['id'], ':time' => time())); 
header('Location: /achive');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Второе-#
if($user['ach_tasks'] == 1 and $user['tasks'] >= 5){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `silver` = :silver, `key` = :key, `ach_tasks` = 2 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':silver' => $user['silver']+15500, ':key' => $user['key']+15, ':id' => $user['id'])); 	
header('Location: /achive');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Третье-#
if($user['ach_tasks'] == 2 and $user['tasks'] >= 15){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_tasks` = 3 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+50, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => 1, ':user_id' => $user['id'], ':time' => time())); 
header("Location: /achive");
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Четвертое-#
if($user['ach_tasks'] == 3 and $user['tasks'] >= 25){
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `silver` = :silver, `ach_tasks` = 4 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+75, ':silver' => $user['silver']+50000, ':id' => $user['id'])); 	
header("Location: /achive");
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Пятое-#
if($user['ach_tasks'] == 4 and $user['tasks'] >= 75){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `silver` = :silver, `ach_tasks` = 5 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+150,  ':silver' => $user['silver']+75000, ':id' => $user['id'])); 	
header('Location: /achive');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Шестое-#
if($user['ach_tasks'] == 5 and $user['tasks'] >= 150){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_tasks` = 6 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+200, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => 2, ':user_id' => $user['id'], ':time' => time())); 
header("Location: /achive");
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Седьмое-#
if($user['ach_tasks'] == 6 and $user['tasks'] >= 250){
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `key` = :key, `ach_tasks` = 7 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+300, ':key' => $user['key']+40, ':id' => $user['id'])); 	
header("Location: /achive");
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Восьмое-#
if($user['ach_tasks'] == 7 and $user['tasks'] >= 400){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `silver` = :silver, `ach_tasks` = 8 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+450,  ':silver' => $user['silver']+100000, ':id' => $user['id'])); 	
header('Location: /achive');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Девятое-#
if($user['ach_tasks'] == 8 and $user['tasks'] >= 600){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_tasks` = 9 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+600, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => 2, ':user_id' => $user['id'], ':time' => time())); 
header("Location: /achive");
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Десятое-#
if($user['ach_tasks'] == 9 and $user['tasks'] >= 1000){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_tasks` = 10 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+800, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => 3, ':user_id' => $user['id'], ':time' => time())); 
header("Location: /achive");
$_SESSION['ok'] = 'Награда получена!';
exit();
}

if($user['tasks'] < 3 or $user['tasks'] < 5 or $user['tasks'] < 15 or $user['tasks'] < 25 or $user['tasks'] < 75 or $user['tasks'] < 150 or $user['tasks'] < 250 or $user['tasks'] < 400 or $user['tasks'] < 600 or $user['tasks'] < 1000){
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