<?php
require_once H.'system/system.php';
#-Место игрока в турнире-#

#-Статуэтки-#
$sel_count_f = $pdo->prepare("SELECT COUNT(*) FROM `users` WHERE `figur` >= :figur AND `level` = 100 AND `id` != :user_id");
$sel_count_f->execute(array(':figur' => $all_figur, ':user_id' => $all_id));
$amount_f = $sel_count_f->fetch(PDO::FETCH_LAZY);
$sel_count_fm = $pdo->prepare("SELECT COUNT(*) FROM `users` WHERE `figur` = :figur AND `id` > :user_id AND `level` = 100 AND `id` != :user_id");
$sel_count_fm->execute(array(':figur' => $all_figur, ':user_id' => $all_id));
$amount_fm = $sel_count_fm->fetch(PDO::FETCH_LAZY);
$amount_1 = $amount_f[0]-$amount_fm[0];
if($user['figur'] > 0){
$mesto_figur = $amount_1+1;
}else{
$mesto_figur = 0;
}

#-Дуэли-#
$sel_count_d = $pdo->prepare("SELECT COUNT(*) FROM `users` WHERE `duel_t` >= :duel_t AND `id` != :user_id");
$sel_count_d->execute(array(':duel_t' => $all_duel_t, ':user_id' => $all_id));
$amount_d = $sel_count_d->fetch(PDO::FETCH_LAZY);
$sel_count_dm = $pdo->prepare("SELECT COUNT(*) FROM `users` WHERE `duel_t` = :duel_t AND `id` > :user_id AND `id` != :user_id");
$sel_count_dm->execute(array(':duel_t' => $all_duel_t, ':user_id' => $all_id));
$amount_dm = $sel_count_dm->fetch(PDO::FETCH_LAZY);
$amount_2 = $amount_d[0]-$amount_dm[0];
if($user['duel_t'] > 0){
$mesto_duel = $amount_2+1;
}else{
$mesto_duel = 0;
}

#-Колизей-#
$sel_count_c = $pdo->prepare("SELECT COUNT(*) FROM `users` WHERE `coliseum_t` >= :coliseum_t AND `id` != :user_id");
$sel_count_c->execute(array(':coliseum_t' => $all_coliseum_t, ':user_id' => $all_id));
$amount_c = $sel_count_c->fetch(PDO::FETCH_LAZY);
$sel_count_cm = $pdo->prepare("SELECT COUNT(*) FROM `users` WHERE `coliseum_t` = :coliseum_t AND `id` > :user_id AND `id` != :user_id");
$sel_count_cm->execute(array(':coliseum_t' => $all_coliseum_t, ':user_id' => $all_id));
$amount_cm = $sel_count_cm->fetch(PDO::FETCH_LAZY);
$amount_3 = $amount_c[0]-$amount_cm[0];
if($user['coliseum_t'] > 0){
$mesto_coliseum = $amount_3+1;
}else{
$mesto_coliseum = 0;
}

#-Башни-#
$sel_count_to = $pdo->prepare("SELECT COUNT(*) FROM `users` WHERE `towers_t` >= :towers_t AND `id` != :user_id");
$sel_count_to->execute(array(':towers_t' => $all_towers_t, ':user_id' => $all_id));
$amount_to = $sel_count_to->fetch(PDO::FETCH_LAZY);
$sel_count_tom = $pdo->prepare("SELECT COUNT(*) FROM `users` WHERE `towers_t` = :towers_t AND `id` > :user_id AND `id` != :user_id");
$sel_count_tom->execute(array(':towers_t' => $all_towers_t, ':user_id' => $all_id));
$amount_tom = $sel_count_tom->fetch(PDO::FETCH_LAZY);
$amount_4 = $amount_to[0]-$amount_tom[0];
if($user['towers_t'] > 0){
$mesto_towers = $amount_4+1;
}else{
$mesto_towers = 0;
}
?>