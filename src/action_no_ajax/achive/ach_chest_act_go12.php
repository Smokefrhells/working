<?php
require_once '../../system/system.php';
echo only_reg();

#-Достижения сундуки-#
switch($act){
case 'get':
#-Проверяем что достижение еще не выполнено-#
if($user['ach_chest'] != 10){
	
#-Первое-#
if($user['ach_chest'] == 0 and $user['chest'] >= 3){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `silver` = :silver, `key` = :key, `ach_chest` = 1 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':silver' => $user['silver']+15000, ':key' => $user['key']+2, ':id' => $user['id'])); 	
header('Location: /achive?type=2');
$_SESSION['ok'] = 'Награда получена!';
exit();
} 

#-Второе-#
if($user['ach_chest'] == 1 and $user['chest'] >= 7){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `silver` = :silver, `key` = :key, `ach_chest` = 2 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':silver' => $user['silver']+30000, ':key' => $user['key']+5, ':id' => $user['id'])); 	
header('Location: /achive?type=2');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Третье-#
if($user['ach_chest'] == 2 and $user['chest'] >= 25){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `silver` = :silver, `ach_chest` = 3 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+50, ':silver' => $user['silver']+50000, ':id' => $user['id'])); 	
header('Location: /achive?type=2');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Четвертое-#
if($user['ach_chest'] == 3 and $user['chest'] >= 40){
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `silver` = :silver, `ach_chest` = 4 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+100, ':silver' => $user['silver']+75000, ':id' => $user['id'])); 	
header('Location: /achive?type=2');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Пятое-#
if($user['ach_chest'] == 4 and $user['chest'] >= 100){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `silver` = :silver, `ach_chest` = 5 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+150, ':silver' => $user['silver']+100000, ':id' => $user['id'])); 	
header('Location: /achive?type=2');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Шестое-#
if($user['ach_chest'] == 5 and $user['chest'] >= 200){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_chest` = 6 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+250, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => 2, ':user_id' => $user['id'], ':time' => time())); 
header('Location: /achive?type=2');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Седьмое-#
if($user['ach_chest'] == 6 and $user['chest'] >= 400){
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `key` = :key, `ach_chest` = 7 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+300, ':key' => $user['key']+15, ':id' => $user['id'])); 	
header('Location: /achive?type=2');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Восьмое-#
if($user['ach_chest'] == 7 and $user['chest'] >= 600){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `silver` = :silver, `ach_chest` = 8 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+450,  ':silver' => $user['silver']+150000, ':id' => $user['id'])); 	
header('Location: /achive?type=2');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Девятое-#
if($user['ach_chest'] == 8 and $user['chest'] >= 800){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `silver` = :silver, `ach_chest` = 9 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+500,  ':silver' => $user['silver']+250000, ':id' => $user['id'])); 	
header('Location: /achive?type=2');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

#-Десятое-#
if($user['ach_chest'] == 9 and $user['chest'] >= 1000){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_chest` = 10 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+600, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => 3, ':user_id' => $user['id'], ':time' => time())); 
header('Location: /achive?type=2');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

if($user['chest'] < 3 or $user['chest'] < 7 or $user['chest'] < 25 or $user['chest'] < 40 or $user['chest'] < 100 or $user['chest'] < 200 or $user['chest'] < 400 or $user['chest'] < 600 or $user['chest'] < 800 or $user['chest'] < 1000){
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