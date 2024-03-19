<?php
require_once '../../system/system.php';
echo only_reg();

#-Достижения Уровень здоровья-#
switch($act){
case 'get':
#-Проверяем что достижение еще не выполнено-#
if($user['ach_health'] != 10){
	
#-Первое-#
if($user['ach_health'] == 0 and $user['level_health'] >= 20){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_health` = 1 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+125, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => 1, ':user_id' => $user['id'], ':time' => time())); 
header('Location: /achive?type=3');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Второе-#
if($user['ach_health'] == 1 and $user['level_health'] >= 40){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_health` = 2 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+200, ':id' => $user['id'])); 	
header('Location: /achive?type=3');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Третье-#
if($user['ach_health'] == 2 and $user['level_health'] >= 60){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_health` = 3 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+275, ':id' => $user['id'])); 	
header('Location: /achive?type=3');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Четвертое-#
if($user['ach_health'] == 3 and $user['level_health'] >= 80){
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_health` = 4 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+350, ':id' => $user['id'])); 	
header('Location: /achive?type=3');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Пятое-#
if($user['ach_health'] == 4 and $user['level_health'] >= 100){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_health` = 5 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+425, ':id' => $user['id'])); 	
header('Location: /achive?type=3');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Шестое-#
if($user['ach_health'] == 5 and $user['level_health'] >= 120){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_health` = 6 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+500, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => 2, ':user_id' => $user['id'], ':time' => time())); 
header('Location: /achive?type=3');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Седьмое-#
if($user['ach_health'] == 6 and $user['level_health'] >= 140){
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_health` = 7 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+575, ':id' => $user['id'])); 	
header('Location: /achive?type=3');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Восьмое-#
if($user['ach_health'] == 7 and $user['level_health'] >= 160){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_health` = 8 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+650, ':id' => $user['id'])); 	
header('Location: /achive?type=3');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Девятое-#
if($user['ach_health'] == 8 and $user['level_health'] >= 180){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_health` = 9 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+725, ':id' => $user['id'])); 	
header('Location: /achive?type=3');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Десятое-#
if($user['ach_health'] == 9 and $user['level_health'] >= 200){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_health` = 10 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+800, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => 3, ':user_id' => $user['id'], ':time' => time())); 
header('Location: /achive?type=3');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

if($user['level_health'] < 20 or $user['level_health'] < 40 or $user['level_health'] < 60 or $user['level_health'] < 80 or $user['level_health'] < 100 or $user['level_health'] < 120 or $user['level_health'] < 140 or $user['level_health'] < 160 or $user['level_health'] < 180 or $user['level_health'] < 200){
header('Location: /achive?type=3');
exit();		
}
}else{
header('Location: /achive?type=3');
$_SESSION['err'] = 'Достижение выполнено!';
exit();	
}
}
?>