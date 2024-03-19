<?php
require_once '../../system/system.php';
echo only_reg();

#-Достижения Уровень силы-#
switch($act){
case 'get':
#-Проверяем что достижение еще не выполнено-#
if($user['ach_sila'] != 10){
	
#-Первое-#
if($user['ach_sila'] == 0 and $user['level_sila'] >= 20){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_sila` = 1 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+100, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => 1, ':user_id' => $user['id'], ':time' => time())); 
header('Location: /achive?type=3');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Второе-#
if($user['ach_sila'] == 1 and $user['level_sila'] >= 40){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_sila` = 2 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+150, ':id' => $user['id'])); 	
header('Location: /achive?type=3');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Третье-#
if($user['ach_sila'] == 2 and $user['level_sila'] >= 60){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_sila` = 3 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+200, ':id' => $user['id'])); 	
header('Location: /achive?type=3');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Четвертое-#
if($user['ach_sila'] == 3 and $user['level_sila'] >= 80){
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_sila` = 4 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+250, ':id' => $user['id'])); 	
header('Location: /achive?type=3');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Пятое-#
if($user['ach_sila'] == 4 and $user['level_sila'] >= 100){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_sila` = 5 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+300, ':id' => $user['id'])); 	
header('Location: /achive?type=3');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Шестое-#
if($user['ach_sila'] == 5 and $user['level_sila'] >= 120){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_sila` = 6 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+350, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => 2, ':user_id' => $user['id'], ':time' => time())); 
header('Location: /achive?type=3');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Седьмое-#
if($user['ach_sila'] == 6 and $user['level_sila'] >= 140){
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_sila` = 7 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+400, ':id' => $user['id'])); 	
header('Location: /achive?type=3');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Восьмое-#
if($user['ach_sila'] == 7 and $user['level_sila'] >= 160){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_sila` = 8 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+450, ':id' => $user['id'])); 	
header('Location: /achive?type=3');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Девятое-#
if($user['ach_sila'] == 8 and $user['level_sila'] >= 180){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_sila` = 9 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+500, ':id' => $user['id'])); 	
header('Location: /achive?type=3');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Десятое-#
if($user['ach_sila'] == 9 and $user['level_sila'] >= 200){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_sila` = 10 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+550, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => 3, ':user_id' => $user['id'], ':time' => time())); 
header('Location: /achive?type=3');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

if($user['level_sila'] < 20 or $user['level_sila'] < 40 or $user['level_sila'] < 60 or $user['level_sila'] < 80 or $user['level_sila'] < 100 or $user['level_sila'] < 120 or $user['level_sila'] < 140 or $user['level_sila'] < 160 or $user['level_sila'] < 180 or $user['level_sila'] < 200){
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