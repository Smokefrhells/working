<?php
require_once '../../system/system.php';
echo only_reg();

#-Достижения боссы-#
switch($act){
case 'get':
#-Проверяем что достижение еще не выполнено-#
if($user['ach_boss'] != 10){
	
#-Первое-#
if($user['ach_boss'] == 0 and $user['boss_pobeda'] >= 3){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `silver` = :silver, `ach_boss` = 1 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':silver' => $user['silver']+10000, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => 1, ':user_id' => $user['id'], ':time' => time())); 
header('Location: /achive');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Второе-#
if($user['ach_boss'] == 1 and $user['boss_pobeda'] >= 10){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `silver` = :silver, `key` = :key, `ach_boss` = 2 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':silver' => $user['silver']+15000, ':key' => $user['key']+10, ':id' => $user['id'])); 	
header('Location: /achive');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Третье-#
if($user['ach_boss'] == 2 and $user['boss_pobeda'] >= 25){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_boss` = 3 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+50, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => 1, ':user_id' => $user['id'], ':time' => time())); 
header("Location: /achive");
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Четвертое-#
if($user['ach_boss'] == 3 and $user['boss_pobeda'] >= 75){
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `silver` = :silver, `ach_boss` = 4 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+100, ':silver' => $user['silver']+35000, ':id' => $user['id'])); 	
header("Location: /achive");
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Пятое-#
if($user['ach_boss'] == 4 and $user['boss_pobeda'] >= 175){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `silver` = :silver, `ach_boss` = 5 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+150,  ':silver' => $user['silver']+50000, ':id' => $user['id'])); 	
header('Location: /achive');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Шестое-#
if($user['ach_boss'] == 5 and $user['boss_pobeda'] >= 325){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_boss` = 6 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+225, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => 2, ':user_id' => $user['id'], ':time' => time())); 
header("Location: /achive");
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Седьмое-#
if($user['ach_boss'] == 6 and $user['boss_pobeda'] >= 725){
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `key` = :key, `ach_boss` = 7 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+300, ':key' => $user['key']+25, ':id' => $user['id'])); 	
header("Location: /achive");
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Восьмое-#
if($user['ach_boss'] == 7 and $user['boss_pobeda'] >= 1475){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `silver` = :silver, `ach_boss` = 8 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+400,  ':silver' => $user['silver']+150000, ':id' => $user['id'])); 	
header('Location: /achive');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Девятое-#
if($user['ach_boss'] == 8 and $user['boss_pobeda'] >= 2975){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_boss` = 9 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+460, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => 2, ':user_id' => $user['id'], ':time' => time())); 
header("Location: /achive");
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Десятое-#
if($user['ach_boss'] == 9 and $user['boss_pobeda'] >= 5975){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_boss` = 10 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+600, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => 3, ':user_id' => $user['id'], ':time' => time())); 
header("Location: /achive");
$_SESSION['ok'] = 'Награда получена!';
exit();
}

if($user['boss_pobeda'] < 3 or $user['boss_pobeda'] < 10 or $user['boss_pobeda'] < 25 or $user['boss_pobeda'] < 75 or $user['boss_pobeda'] < 175 or $user['boss_pobeda'] < 325 or $user['boss_pobeda'] < 725 or $user['boss_pobeda'] < 1475 or $user['boss_pobeda'] < 2975 or $user['boss_pobeda'] < 5975){
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