<?php
require_once '../../system/system.php';
echo only_reg();

#-Достижения Герой I ранга-#
switch($act){
case 'get':
#-Проверяем что достижение еще не выполнено-#
if($user['ach_heros_1'] != 1){
	
#-Достижение-#
if($user['ach_heros_1'] == 0 and $user['ach_save'] == 1 and $user['ach_ohota'] == 10 and $user['ach_duel'] == 10 and $user['ach_boss'] == 10 and $user['ach_tasks'] == 10){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_heros_1` = 1 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+1000, ':id' => $user['id'])); 	
#-Шлем Паладина-#
$ins_weapon_me = $pdo->prepare("INSERT INTO `weapon_me` SET `type` = :type, `weapon_id` = :weapon_id, `user_id` = :user_id, `time` = :time");
$ins_weapon_me->execute(array(':type' => 1, ':weapon_id' => 143, ':user_id' => $user['id'], ':time' => time()));
#-Доспех Паладина-#
$ins_weapon_me = $pdo->prepare("INSERT INTO `weapon_me` SET `type` = :type, `weapon_id` = :weapon_id, `user_id` = :user_id, `time` = :time");
$ins_weapon_me->execute(array(':type' => 2, ':weapon_id' => 144, ':user_id' => $user['id'], ':time' => time()));
#-Перчатки Паладина-#
$ins_weapon_me = $pdo->prepare("INSERT INTO `weapon_me` SET `type` = :type, `weapon_id` = :weapon_id, `user_id` = :user_id, `time` = :time");
$ins_weapon_me->execute(array(':type' => 3, ':weapon_id' => 145, ':user_id' => $user['id'], ':time' => time()));
#-Щит Паладина-#
$ins_weapon_me = $pdo->prepare("INSERT INTO `weapon_me` SET `type` = :type, `weapon_id` = :weapon_id, `user_id` = :user_id, `time` = :time");
$ins_weapon_me->execute(array(':type' => 4, ':weapon_id' => 146, ':user_id' => $user['id'], ':time' => time()));
#-Меч Паладина-#
$ins_weapon_me = $pdo->prepare("INSERT INTO `weapon_me` SET `type` = :type, `weapon_id` = :weapon_id, `user_id` = :user_id, `time` = :time");
$ins_weapon_me->execute(array(':type' => 5, ':weapon_id' => 147, ':user_id' => $user['id'], ':time' => time()));
#-Сапоги Паладина-#
$ins_weapon_me = $pdo->prepare("INSERT INTO `weapon_me` SET `type` = :type, `weapon_id` = :weapon_id, `user_id` = :user_id, `time` = :time");
$ins_weapon_me->execute(array(':type' => 6, ':weapon_id' => 148, ':user_id' => $user['id'], ':time' => time()));
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