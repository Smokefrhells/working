<?php
require_once '../../system/system.php';
echo only_reg();

#-Достижения Герой II ранга-#
switch($act){
case 'get':
#-Проверяем что достижение еще не выполнено-#
if($user['ach_heros_2'] != 1){
	
#-Достижение-#
if($user['ach_heros_1'] == 1 and $user['ach_heros_2'] == 0 and $user['ach_zamki'] == 10 and $user['ach_reid'] == 10 and $user['ach_chest'] == 10 and $user['ach_clan'] == 1){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_heros_2` = 1 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+3000, ':id' => $user['id'])); 	
#-Шлем Следопыта-#
$ins_weapon_me = $pdo->prepare("INSERT INTO `weapon_me` SET `type` = :type, `weapon_id` = :weapon_id, `user_id` = :user_id, `time` = :time");
$ins_weapon_me->execute(array(':type' => 1, ':weapon_id' => 149, ':user_id' => $user['id'], ':time' => time()));
#-Доспех Следопыта-#
$ins_weapon_me = $pdo->prepare("INSERT INTO `weapon_me` SET `type` = :type, `weapon_id` = :weapon_id, `user_id` = :user_id, `time` = :time");
$ins_weapon_me->execute(array(':type' => 2, ':weapon_id' => 150, ':user_id' => $user['id'], ':time' => time()));
#-Перчатки Следопыта-#
$ins_weapon_me = $pdo->prepare("INSERT INTO `weapon_me` SET `type` = :type, `weapon_id` = :weapon_id, `user_id` = :user_id, `time` = :time");
$ins_weapon_me->execute(array(':type' => 3, ':weapon_id' => 151, ':user_id' => $user['id'], ':time' => time()));
#-Щит Следопыта-#
$ins_weapon_me = $pdo->prepare("INSERT INTO `weapon_me` SET `type` = :type, `weapon_id` = :weapon_id, `user_id` = :user_id, `time` = :time");
$ins_weapon_me->execute(array(':type' => 4, ':weapon_id' => 152, ':user_id' => $user['id'], ':time' => time()));
#-Меч Следопыта-#
$ins_weapon_me = $pdo->prepare("INSERT INTO `weapon_me` SET `type` = :type, `weapon_id` = :weapon_id, `user_id` = :user_id, `time` = :time");
$ins_weapon_me->execute(array(':type' => 5, ':weapon_id' => 153, ':user_id' => $user['id'], ':time' => time()));
#-Сапоги Следопыта-#
$ins_weapon_me = $pdo->prepare("INSERT INTO `weapon_me` SET `type` = :type, `weapon_id` = :weapon_id, `user_id` = :user_id, `time` = :time");
$ins_weapon_me->execute(array(':type' => 6, ':weapon_id' => 154, ':user_id' => $user['id'], ':time' => time()));
header('Location: /achive?type=2');
$_SESSION['ok'] = 'Награда получена!';
exit();
}else{
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