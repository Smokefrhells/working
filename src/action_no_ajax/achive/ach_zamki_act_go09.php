<?php
require_once '../../system/system.php';
echo only_reg();

#-Достижения замки-#
switch($act){
case 'get':
#-Проверяем что достижение еще не выполнено-#
if($user['ach_zamki'] != 10){
	
#-Первое-#
if($user['ach_zamki'] == 0 and $user['zamki_pobeda'] >= 2){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `silver` = :silver, `ach_zamki` = 1 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':silver' => $user['silver']+13000, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => 1, ':user_id' => $user['id'], ':time' => time())); 
header('Location: /achive?type=2');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Второе-#
if($user['ach_zamki'] == 1 and $user['zamki_pobeda'] >= 5){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `silver` = :silver, `key` = :key, `ach_zamki` = 2 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':silver' => $user['silver']+20000, ':key' => $user['key']+10, ':id' => $user['id'])); 	
header('Location: /achive?type=2');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Третье-#
if($user['ach_zamki'] == 2 and $user['zamki_pobeda'] >= 15){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_zamki` = 3 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+100, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => 1, ':user_id' => $user['id'], ':time' => time())); 
header('Location: /achive?type=2');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Четвертое-#
if($user['ach_zamki'] == 3 and $user['zamki_pobeda'] >= 30){
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `silver` = :silver, `ach_zamki` = 4 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+150, ':silver' => $user['silver']+100000, ':id' => $user['id'])); 	
header('Location: /achive?type=2');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Пятое-#
if($user['ach_zamki'] == 4 and $user['zamki_pobeda'] >= 60){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `silver` = :silver, `ach_zamki` = 5 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+200,  ':silver' => $user['silver']+150000, ':id' => $user['id'])); 	
header('Location: /achive?type=2');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Шестое-#
if($user['ach_zamki'] == 5 and $user['zamki_pobeda'] >= 100){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_zamki` = 6 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+250, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => 2, ':user_id' => $user['id'], ':time' => time())); 
header('Location: /achive?type=2');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Седьмое-#
if($user['ach_zamki'] == 6 and $user['zamki_pobeda'] >= 150){
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `key` = :key, `ach_zamki` = 7 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+400, ':key' => $user['key']+20, ':id' => $user['id'])); 	
header('Location: /achive?type=2');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Восьмое-#
if($user['ach_zamki'] == 7 and $user['zamki_pobeda'] >= 250){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `silver` = :silver, `ach_zamki` = 8 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+600,  ':silver' => $user['silver']+150000, ':id' => $user['id'])); 	
header('Location: /achive?type=2');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Девятое-#
if($user['ach_zamki'] == 8 and $user['zamki_pobeda'] >= 400){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_zamki` = 9 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+800, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => 2, ':user_id' => $user['id'], ':time' => time())); 
header('Location: /achive?type=2');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Десятое-#
if($user['ach_zamki'] == 9 and $user['zamki_pobeda'] >= 500){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_zamki` = 10 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+1000, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => 3, ':user_id' => $user['id'], ':time' => time())); 
header('Location: /achive?type=2');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

if($user['zamki_pobeda'] < 2 or $user['zamki_pobeda'] < 5 or $user['zamki_pobeda'] < 15 or $user['zamki_pobeda'] < 30 or $user['zamki_pobeda'] < 60 or $user['zamki_pobeda'] < 100 or $user['zamki_pobeda'] < 150 or $user['zamki_pobeda'] < 250 or $user['zamki_pobeda'] < 400 or $user['zamki_pobeda'] < 500){
header('Location: /achive?type=2');
exit();		
}
}else{
header('Location: /achive?type=2');
$_SESSION['err'] = 'Достижение выполнено!';
exit();	
}
}
?>