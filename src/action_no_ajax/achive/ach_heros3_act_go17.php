<?php
require_once '../../system/system.php';
echo only_reg();

#-Достижения Герой III ранга-#
switch($act){
case 'get':
#-Проверяем что достижение еще не выполнено-#
if($user['ach_heros_3'] != 1){
	
#-Достижение-#
if($user['ach_heros_1'] == 1 and $user['ach_heros_2'] == 1 and $user['ach_heros_3'] == 0 and $user['ach_level'] == 10 and $user['ach_sila'] == 10 and $user['ach_zashita'] == 10 and $user['ach_health'] == 10){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_heros_3` = 1 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+4000, ':id' => $user['id'])); 	
#-Шлем Охотника-#
$ins_weapon_me = $pdo->prepare("INSERT INTO `weapon_me` SET `type` = :type, `weapon_id` = :weapon_id, `user_id` = :user_id, `time` = :time");
$ins_weapon_me->execute(array(':type' => 1, ':weapon_id' => 155, ':user_id' => $user['id'], ':time' => time()));
#-Доспех Охотника-#
$ins_weapon_me = $pdo->prepare("INSERT INTO `weapon_me` SET `type` = :type, `weapon_id` = :weapon_id, `user_id` = :user_id, `time` = :time");
$ins_weapon_me->execute(array(':type' => 2, ':weapon_id' => 156, ':user_id' => $user['id'], ':time' => time()));
#-Перчатки Охотника-#
$ins_weapon_me = $pdo->prepare("INSERT INTO `weapon_me` SET `type` = :type, `weapon_id` = :weapon_id, `user_id` = :user_id, `time` = :time");
$ins_weapon_me->execute(array(':type' => 3, ':weapon_id' => 157, ':user_id' => $user['id'], ':time' => time()));
#-Щит Охотника-#
$ins_weapon_me = $pdo->prepare("INSERT INTO `weapon_me` SET `type` = :type, `weapon_id` = :weapon_id, `user_id` = :user_id, `time` = :time");
$ins_weapon_me->execute(array(':type' => 4, ':weapon_id' => 158, ':user_id' => $user['id'], ':time' => time()));
#-Меч Охотника-#
$ins_weapon_me = $pdo->prepare("INSERT INTO `weapon_me` SET `type` = :type, `weapon_id` = :weapon_id, `user_id` = :user_id, `time` = :time");
$ins_weapon_me->execute(array(':type' => 5, ':weapon_id' => 159, ':user_id' => $user['id'], ':time' => time()));
#-Сапоги Охотника-#
$ins_weapon_me = $pdo->prepare("INSERT INTO `weapon_me` SET `type` = :type, `weapon_id` = :weapon_id, `user_id` = :user_id, `time` = :time");
$ins_weapon_me->execute(array(':type' => 6, ':weapon_id' => 160, ':user_id' => $user['id'], ':time' => time()));
header('Location: /achive?type=3');
$_SESSION['ok'] = 'Награда получена!';
exit();
}else{
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