<?php
require_once H.'system/system.php';
#-Место игрока в рейтинге-#

#-Параметры-#
$sel_count_p = $pdo->prepare("SELECT COUNT(*) FROM `users` WHERE `param` >= :param AND `save` = 1 AND `id` != :user_id");
$sel_count_p->execute(array(':param' => $all_param, ':user_id' => $all_id));
$amount_p = $sel_count_p->fetch(PDO::FETCH_LAZY);
$sel_count_pm = $pdo->prepare("SELECT COUNT(*) FROM `users` WHERE `param` = :user_param AND `id` > :user_id AND `save` = 1 AND `id` != :user_id");
$sel_count_pm->execute(array(':user_param' => $all_param, ':user_id' => $all_id));
$amount_pm = $sel_count_pm->fetch(PDO::FETCH_LAZY);
$amount_1 = $amount_p[0]-$amount_pm[0];
$mesto_param = $amount_1+1;

#-Охота-#
$sel_count_h = $pdo->prepare("SELECT COUNT(*) FROM `users` WHERE `hunting_pobeda` >= :hunting_pobeda AND `save` = 1 AND `id` != :user_id");
$sel_count_h->execute(array(':hunting_pobeda' => $all_pobeda_h, ':user_id' => $all_id));
$amount_h = $sel_count_h->fetch(PDO::FETCH_LAZY);
$sel_count_hm = $pdo->prepare("SELECT COUNT(*) FROM `users` WHERE `hunting_pobeda` = :hunting_pobeda AND (`hunting_progrash` > :hunting_progrash OR `id` > :user_id) AND `save` = 1 AND `id` != :user_id");
$sel_count_hm->execute(array(':hunting_pobeda' => $all_pobeda_h, ':hunting_progrash' => $all_progrash_h, ':user_id' => $all_id));
$amount_hm = $sel_count_hm->fetch(PDO::FETCH_LAZY);
$amount_2 = $amount_h[0]-$amount_hm[0];
$mesto_hunting = $amount_2+1;

#-Дуэли-#
$sel_count_d = $pdo->prepare("SELECT COUNT(*) FROM `users` WHERE `duel_pobeda` >= :duel_pobeda AND `save` = 1 AND `id` != :user_id");
$sel_count_d->execute(array(':duel_pobeda' => $all_pobeda_d, ':user_id' => $all_id));
$amount_d = $sel_count_d->fetch(PDO::FETCH_LAZY);
$sel_count_dm = $pdo->prepare("SELECT COUNT(*) FROM `users` WHERE `duel_pobeda` = :duel_pobeda AND (`duel_progrash` > :duel_progrash OR `id` > :user_id) AND `save` = 1 AND `id` != :user_id");
$sel_count_dm->execute(array(':duel_pobeda' => $all_pobeda_d, ':duel_progrash' => $all_progrash_d, ':user_id' => $all_id));
$amount_dm = $sel_count_dm->fetch(PDO::FETCH_LAZY);
$amount_3 = $amount_d[0]-$amount_dm[0];
$mesto_duel = $amount_3+1;

#-Боссы-#
$sel_count_b = $pdo->prepare("SELECT COUNT(*) FROM `users` WHERE `boss_pobeda` >= :boss_pobeda AND `save` = 1 AND `id` != :user_id");
$sel_count_b->execute(array(':boss_pobeda' => $all_pobeda_b, ':user_id' => $all_id));
$amount_b = $sel_count_b->fetch(PDO::FETCH_LAZY);
$sel_count_bm = $pdo->prepare("SELECT COUNT(*) FROM `users` WHERE `boss_pobeda` = :boss_pobeda AND (`boss_progrash` > :boss_progrash OR `id` > :user_id) AND `save` = 1 AND `id` != :user_id");
$sel_count_bm->execute(array(':boss_pobeda' => $all_pobeda_b, ':boss_progrash' => $all_progrash_b, ':user_id' => $user['id']));
$amount_bm = $sel_count_bm->fetch(PDO::FETCH_LAZY);
$amount_4 = $amount_b[0]-$amount_bm[0];
$mesto_boss = $amount_4+1;

#-Задания-#
$sel_count_t = $pdo->prepare("SELECT COUNT(*) FROM `users` WHERE `tasks` >= :tasks AND `save` = 1 AND `id` != :user_id");
$sel_count_t->execute(array(':tasks' => $all_tasks, ':user_id' => $all_id));
$amount_t = $sel_count_t->fetch(PDO::FETCH_LAZY);
$sel_count_tm = $pdo->prepare("SELECT COUNT(*) FROM `users` WHERE `tasks` = :tasks AND `id` > :user_id AND `save` = 1 AND `id` != :user_id");
$sel_count_tm->execute(array(':tasks' => $all_tasks, ':user_id' => $all_id));
$amount_tm = $sel_count_tm->fetch(PDO::FETCH_LAZY);
$amount_5 = $amount_t[0]-$amount_tm[0];
$mesto_tasks = $amount_5+1;
?>