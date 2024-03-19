<?php
require_once H.'system/system.php';

#-Участвует игрок в башнях-#
$sel_towers_me = $pdo->prepare("SELECT `id`, `user_id`, `battle_id`, `statys`, `group` FROM `towers` WHERE `user_id` = :user_id");
$sel_towers_me->execute(array(':user_id' => $user['id']));
if($sel_towers_me->rowCount() != 0){
$towers_me = $sel_towers_me->fetch(PDO::FETCH_LAZY);
#-Выборка сколько игроков участвует в сражении-#
$sel_towers_a = $pdo->prepare("SELECT COUNT(*) FROM `towers` WHERE `battle_id` = :battle_id");
$sel_towers_a->execute(array(':battle_id' => $towers_me['battle_id']));
$towers_a = $sel_towers_a->fetch(PDO::FETCH_LAZY);

#-Если все игроки собраны-#
if($towers_a[0] == 4 and $towers_me['statys'] == 0){
$sel_towers_u = $pdo->prepare("SELECT `id`, `battle_id`, `user_id`, `group` FROM `towers` WHERE `battle_id` = :battle_id");
$sel_towers_u->execute(array(':battle_id' => $towers_me['battle_id']));
while($towers_u = $sel_towers_u->fetch(PDO::FETCH_LAZY)){
$sel_rand = $pdo->prepare("SELECT `id`, `battle_id`, `user_id` FROM `towers` WHERE `battle_id` = :battle_id AND `user_id` != :user_id AND `group` != :group ORDER BY RAND()");
$sel_rand->execute(array(':battle_id' => $towers_u['battle_id'], ':user_id' => $towers_u['user_id'], ':group' => $towers_u['group']));
$rand = $sel_rand->fetch(PDO::FETCH_LAZY);
$upd_towers = $pdo->prepare("UPDATE `towers` SET `statys` = 1, `time` = :time, `ank_id` = :ank_id WHERE `user_id` = :user_id LIMIT 1");	
$upd_towers->execute(array(':time' => time()+10, ':ank_id' => $rand['user_id'], ':user_id' => $towers_u['user_id']));
}
}
#-Начало боя-#
$upd_towers = $pdo->prepare("UPDATE `towers` SET `statys` = 2, `time` = :time_s WHERE `battle_id` = :battle_id AND `statys` = 1 AND `time` < :time");	
$upd_towers->execute(array(':battle_id' => $towers_me['battle_id'], ':time_s' => time()+600, ':time' => time()));

#-Конец боя по истечению времени-#
$sel_towers_u = $pdo->prepare("SELECT `id`, `battle_id`, `statys`, `time` FROM `towers` WHERE `battle_id` = :battle_id AND `time` < :time AND `statys` = 2 ORDER BY `id`");
$sel_towers_u->execute(array(':battle_id' => $towers_me['battle_id'], ':time' => time()));
if($sel_towers_u -> rowCount() != 0){
$del_towers = $pdo->prepare("DELETE FROM `towers` WHERE `battle_id` = :battle_id AND `statys` = 2");	
$del_towers->execute(array(':battle_id' => $towers_me['battle_id']));
#-Удаление лога-#
$del_log = $pdo->prepare("DELETE FROM `towers_log` WHERE `battle_id` = :battle_id");	
$del_log->execute(array(':battle_id' => $towers_me['battle_id']));
}
}

#-Удаление боя если прошло более 2 дней-#
$del_towers_t = $pdo->prepare("DELETE FROM `towers` WHERE `statys` = 3 AND `time` < :time");	
$del_towers_t->execute(array(':time' => time()-172800));
?>