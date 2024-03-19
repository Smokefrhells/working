<?php
require_once H.'system/system.php';

#-Участвует питомец в дуэльном поединке-#
$sel_pets_duel = $pdo->prepare("SELECT `id`, `user_id`, `battle_id`, `statys` FROM `pets_duel` WHERE `user_id` = :user_id");
$sel_pets_duel->execute(array(':user_id' => $user['id']));
if($sel_pets_duel->rowCount() != 0){
$pets_duel = $sel_pets_duel->fetch(PDO::FETCH_LAZY);
#-Выборка сколько питомцев участвует в сражении-#
$sel_pets_a = $pdo->prepare("SELECT COUNT(*) FROM `pets_duel` WHERE `battle_id` = :battle_id");
$sel_pets_a->execute(array(':battle_id' => $pets_duel['battle_id']));
$pets_a = $sel_pets_a->fetch(PDO::FETCH_LAZY);	

#-Если все игроки собраны-#
if($pets_a[0] == 2 and $pets_duel['statys'] == 0){
$sel_pets_u = $pdo->prepare("SELECT `id`, `battle_id`, `user_id` FROM `pets_duel` WHERE `battle_id` = :battle_id");
$sel_pets_u->execute(array(':battle_id' => $pets_duel['battle_id']));
while($pets_u = $sel_pets_u->fetch(PDO::FETCH_LAZY)){
$upd_pets_duel = $pdo->prepare("UPDATE `pets_duel` SET `statys` = 1, `time` = :time WHERE `user_id` = :user_id LIMIT 1");	
$upd_pets_duel->execute(array(':time' => time()+10, ':user_id' => $pets_u['user_id']));
}
}
#-Начало боя-#
$upd_pets_duel = $pdo->prepare("UPDATE `pets_duel` SET `statys` = 2 WHERE `battle_id` = :battle_id AND `statys` = 1 AND `time` < :time");	
$upd_pets_duel->execute(array(':battle_id' => $pets_duel['battle_id'], ':time' => time()));
}

#-Удаление боя если прошло более 2 дней-#
$del_pets_d = $pdo->prepare("DELETE FROM `pets_duel` WHERE `statys` = 2 AND `time` < :time");	
$del_pets_d->execute(array(':time' => time()-3600));

#-Повышение ранга-#
#-2-ранг-#
if($user['pets_pobeda'] >= 250 and $user['pets_rang'] == 15){
$upd_users = $pdo->prepare("UPDATE `users` SET `pets_rang` = 25 WHERE `id` = :user_id LIMIT 1");
$upd_users->execute(array(':user_id' => $user['id']));	
}
#-3-ранг-#
if($user['pets_pobeda'] >= 750 and $user['pets_rang'] == 25){
$upd_users = $pdo->prepare("UPDATE `users` SET `pets_rang` = 35 WHERE `id` = :user_id LIMIT 1");
$upd_users->execute(array(':user_id' => $user['id']));
}
#-4-ранг-#
if($user['pets_pobeda'] >= 1500 and $user['pets_rang'] == 35){
$upd_users = $pdo->prepare("UPDATE `users` SET `pets_rang` = 45 WHERE `id` = :user_id LIMIT 1");
$upd_users->execute(array(':user_id' => $user['id']));
}
?>