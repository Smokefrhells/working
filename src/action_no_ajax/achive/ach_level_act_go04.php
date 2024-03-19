<?php
require_once '../../system/system.php';
echo only_reg();

#-Достижения уровни-#
switch($act){
case 'get':
#-Проверяем что достижение еще не выполнено-#
if($user['ach_level'] != 10){
	
#-Первое-#
if($user['ach_level'] == 0 and $user['level'] >= 5){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `silver` = :silver, `ach_level` = 1 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':silver' => $user['silver']+25000, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => 1, ':user_id' => $user['id'], ':time' => time())); 
header('Location: /achive');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Второе-#
if($user['ach_level'] == 1 and $user['level'] >= 15){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `silver` = :silver, `key` = :key, `ach_level` = 2 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':silver' => $user['silver']+50000, ':key' => $user['key']+10, ':id' => $user['id'])); 	
header('Location: /achive');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Третье-#
if($user['ach_level'] == 2 and $user['level'] >= 25){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_level` = 3 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+100, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => 1, ':user_id' => $user['id'], ':time' => time())); 
header("Location: /achive");
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Четвертое-#
if($user['ach_level'] == 3 and $user['level'] >= 35){
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `silver` = :silver, `ach_level` = 4 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+125, ':silver' => $user['silver']+75000, ':id' => $user['id'])); 	
header("Location: /achive");
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Пятое-#
if($user['ach_level'] == 4 and $user['level'] >= 45){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `silver` = :silver, `ach_level` = 5 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+175,  ':silver' => $user['silver']+100000, ':id' => $user['id'])); 	
header('Location: /achive');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Шестое-#
if($user['ach_level'] == 5 and $user['level'] >= 55){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_level` = 6 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+250, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => 2, ':user_id' => $user['id'], ':time' => time())); 
header("Location: /achive");
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Седьмое-#
if($user['ach_level'] == 6 and $user['level'] >= 65){
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `key` = :key, `ach_level` = 7 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+325, ':key' => $user['key']+25, ':id' => $user['id'])); 	
header("Location: /achive");
$_SESSION['ok'] = 'Награда получена!';
exit();
}


#-Восьмое-#
if($user['ach_level'] == 7 and $user['level'] >= 75){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `silver` = :silver, `ach_level` = 8 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+400,  ':silver' => $user['silver']+200000, ':id' => $user['id'])); 	
header('Location: /achive');
$_SESSION['ok'] = 'Награда получена!';
exit();
}


#-Девятое-#
if($user['ach_level'] == 8 and $user['level'] >= 95){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_level` = 9 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+550, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => 2, ':user_id' => $user['id'], ':time' => time())); 
header("Location: /achive");
$_SESSION['ok'] = 'Награда получена!';
exit();
}
/*
#-Десятое-#
if($user['ach_level'] == 9 and $user['level'] >= 100){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_level` = 10 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+700, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => 3, ':user_id' => $user['id'], ':time' => time())); 
header("Location: /achive");
$_SESSION['ok'] = 'Награда получена!';
exit();
}
*/

#-Десятое-#
if($user['ach_level'] == 9 and $user['level'] >= 100){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_level` = 10 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+700, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => 3, ':user_id' => $user['id'], ':time' => time())); 
header("Location: /achive");
$_SESSION['ok'] = 'Награда получена!';
exit();
}

//or $user['level'] < 100
if($user['level'] < 5 or $user['level'] < 15 or $user['level'] < 25 or $user['level'] < 35 or $user['level'] < 45 or $user['level'] < 55 or $user['level'] < 65 or $user['level'] < 75 or $user['level'] < 95 or $user['level'] < 100){
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