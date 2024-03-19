<?php
require_once '../../system/system.php';
echo only_reg();

#-Достижения клан-#
switch($act){
case 'get':
#-Проверяем что достижение еще не выполнено-#
if($user['ach_clan'] != 1){

#-Проверка что клан существует-#
$sel_clan = $pdo->prepare("SELECT `id` FROM `clan` WHERE `id` = :clan_id");
$sel_clan->execute(array(':clan_id' => $user['clan_id']));
#-Проверка что игрок состоит в клане-#
$sel_clan_u = $pdo->prepare("SELECT `id`, `clan_id`, `user_id` FROM `clan_users` WHERE `clan_id` = :clan_id AND `user_id` = :user_id");
$sel_clan_u->execute(array(':clan_id' => $user['clan_id'], ':user_id' => $user['id']));

if($user['ach_clan'] == 0 and $user['clan_id'] != 0 and $sel_clan->rowCount() != 0 and $sel_clan_u->rowCount() != 0){
#-Награда игроку-#
$upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `ach_clan` = 1 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':gold' => $user['gold']+250, ':id' => $user['id'])); 	
#-Сундук-#
$ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
$ins_chest ->execute(array(':type' => 3, ':user_id' => $user['id'], ':time' => time())); 
header('Location: /achive?type=2');
$_SESSION['ok'] = 'Награда получена!';
exit();
}

}else{
header('Location: /achive?type=2');
$_SESSION['err'] = 'Достижение выполнено!';
exit();	
}
}
?>