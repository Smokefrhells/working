<?php
require_once H.'system/system.php';

#-Начало сражения-#
$sel_zamki = $pdo->prepare("SELECT * FROM `zamki` WHERE `time` < :time AND `statys` = 0");
$sel_zamki->execute(array(':time' => time()));
if($sel_zamki-> rowCount() != 0){
$zamki = $sel_zamki->fetch(PDO::FETCH_LAZY);
#-Кол-во правых-#
$sel_right_u = $pdo->query("SELECT COUNT(*) FROM `zamki_users` WHERE `storona` = 'right'");
$right_u = $sel_right_u->fetch(PDO::FETCH_LAZY);
#-Кол-во левых-#
$sel_left_u = $pdo->query("SELECT COUNT(*) FROM `zamki_users` WHERE `storona` = 'left'");
$left_u = $sel_left_u->fetch(PDO::FETCH_LAZY);
#-Должно быть минимум по одному игроку из каждой стороны-#
if($right_u[0] > 0 and $left_u[0] > 0){
#-Обнуление урона в замках-#
$upd_users_z = $pdo->query("UPDATE `users` SET `zamki_uron` = 0");
#-Начало боя-#
$upd_zamki = $pdo->prepare("UPDATE `zamki` SET `statys` = 1, `time` = :time WHERE `id` = :zamki_id");
$upd_zamki->execute(array(':zamki_id' => $zamki['id'], ':time' => time()+10800));
#-Игроки сражения-#
$sel_zamki_u = $pdo->query("SELECT * FROM `zamki_users`");
while($zamki_u = $sel_zamki_u->fetch(PDO::FETCH_LAZY)){
#-Ставим игрокам статус в бою-#
$upd_users_z = $pdo->prepare("UPDATE `users` SET `battle` = 1 WHERE `id` = :user_id");
$upd_users_z->execute(array(':user_id' => $zamki_u['user_id']));
}
}else{
$del_zamki = $pdo->query("DELETE FROM `zamki`");
$del_zamki_u = $pdo->query("DELETE FROM `zamki_users`");
}
}

#-Время заморозки-#
$upd_zamki_m = $pdo->prepare("UPDATE `zamki_users` SET `time_freezing` = 0 WHERE `user_id` = :user_id AND `time_freezing` < :time");
$upd_zamki_m->execute(array(':user_id' => $user['id'], ':time' => time()));

#-Истекло время сражения-#
$sel_zamki_b = $pdo->prepare("SELECT * FROM `zamki` WHERE `time` < :time AND `statys` = 1");
$sel_zamki_b->execute(array(':time' => time()));
if($sel_zamki_b-> rowCount() != 0){
#-Игроки сражения-#
$sel_zamki_u = $pdo->query("SELECT * FROM `zamki_users`");
while($zamki_u = $sel_zamki_u->fetch(PDO::FETCH_LAZY)){
#-Убираем статус бой у игроков-#
$upd_users_z = $pdo->prepare("UPDATE `users` SET `battle` = 0 WHERE `id` = :user_id");
$upd_users_z->execute(array(':user_id' => $zamki_u['user_id']));
}
$del_zamki = $pdo->query("DELETE FROM `zamki`");
$del_zamki_u = $pdo->query("DELETE FROM `zamki_users`");
}
?>