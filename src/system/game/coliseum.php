<?php
require_once H.'system/system.php';

#-Участвует игрок в колизеии-#
$sel_coliseum_me = $pdo->prepare("SELECT `id`, `user_id`, `battle_id`, `statys` FROM `coliseum` WHERE `user_id` = :user_id");
$sel_coliseum_me->execute(array(':user_id' => $user['id']));
if($sel_coliseum_me->rowCount() != 0){
$coliseum_me = $sel_coliseum_me->fetch(PDO::FETCH_LAZY);
#-Выборка сколько игроков участвует в сражении-#
$sel_coliseum_a = $pdo->prepare("SELECT COUNT(*) FROM `coliseum` WHERE `battle_id` = :battle_id");
$sel_coliseum_a->execute(array(':battle_id' => $coliseum_me['battle_id']));
$coliseum_a = $sel_coliseum_a->fetch(PDO::FETCH_LAZY);

#-Если все игроки собраны-#
if($coliseum_a[0] == 5 and $coliseum_me['statys'] == 0){
$sel_coliseum_u = $pdo->prepare("SELECT `id`, `battle_id`, `user_id` FROM `coliseum` WHERE `battle_id` = :battle_id");
$sel_coliseum_u->execute(array(':battle_id' => $coliseum_me['battle_id']));
while($coliseum_u = $sel_coliseum_u->fetch(PDO::FETCH_LAZY)){
$sel_rand = $pdo->prepare("SELECT `id`, `battle_id`, `user_id` FROM `coliseum` WHERE `battle_id` = :battle_id AND `user_id` != :user_id ORDER BY RAND()");
$sel_rand->execute(array(':battle_id' => $coliseum_u['battle_id'], ':user_id' => $coliseum_u['user_id']));
$rand = $sel_rand->fetch(PDO::FETCH_LAZY);
$upd_coliseum = $pdo->prepare("UPDATE `coliseum` SET `statys` = 1, `time` = :time, `ank_id` = :ank_id WHERE `user_id` = :user_id LIMIT 1");	
$upd_coliseum->execute(array(':time' => time()+10, ':ank_id' => $rand['user_id'], ':user_id' => $coliseum_u['user_id']));
}
}
#-Начало боя-#
$upd_coliseum = $pdo->prepare("UPDATE `coliseum` SET `statys` = 2, `time` = :time_s WHERE `battle_id` = :battle_id AND `statys` = 1 AND `time` < :time");	
$upd_coliseum->execute(array(':battle_id' => $coliseum_me['battle_id'], ':time_s' => time()+300, ':time' => time()));

#-Конец боя по истечению времени-#
$sel_coliseum_u = $pdo->prepare("SELECT `id`, `battle_id`, `statys`, `time` FROM `coliseum` WHERE `battle_id` = :battle_id AND `time` < :time AND `statys` = 2 ORDER BY `id`");
$sel_coliseum_u->execute(array(':battle_id' => $coliseum_me['battle_id'], ':time' => time()));
if($sel_coliseum_u -> rowCount() != 0){
$del_coliseum = $pdo->prepare("DELETE FROM `coliseum` WHERE `battle_id` = :battle_id AND `statys` = 2");	
$del_coliseum->execute(array(':battle_id' => $coliseum_me['battle_id']));
#-Удаление лога-#
$del_log = $pdo->prepare("DELETE FROM `coliseum_log` WHERE `battle_id` = :battle_id");	
$del_log->execute(array(':battle_id' => $coliseum_me['battle_id']));
}
}
?>