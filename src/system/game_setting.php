<?php
require_once 'system.php';
#-Игровые настройки-#

#-Если возникнет ошибка с параметрами-#
if($user['s_sila'] < 0 or $user['s_zashita'] < 0 or $user['s_health'] < 0){
#-Снимаем все снаряжение-#
$upd_weapon = $pdo->prepare("UPDATE `weapon_me` SET `state` = 0 WHERE `user_id` = :user_id AND `state` = 1");
$upd_weapon->execute(array(':user_id' => $user['id']));
#-Бонус к параметрам 0-#
$upd_users = $pdo->prepare("UPDATE `users` SET `s_sila` = 0, `s_zashita` = 0, `s_health` = 0 WHERE `id` = :user_id LIMIT 1");
$upd_users->execute(array(':user_id' => $user['id']));
}
?>