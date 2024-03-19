<?php
require_once '../system.php';
//Cron задание выполняеться каждую минуту

#-ОХОТА-#
#-Начало боя за локацию-#
$sel_hunting_u = $pdo->prepare("SELECT * FROM `hunting` WHERE `time_battle` < :time AND `time_battle` != 0 AND `statys_battle` = 0");
$sel_hunting_u->execute(array(':time' => time()));
if ($sel_hunting_u->rowCount() != 0) {
    $hunting_u = $sel_hunting_u->fetch(PDO::FETCH_LAZY);

#-Количество участников-#
    $sel_hunting_y = $pdo->prepare("SELECT `id` FROM `hunting_battle_u` WHERE `location` = :location");
    $sel_hunting_y->execute(array(':location' => $hunting_u['id']));
    if ($sel_hunting_y->rowCount() > 1) {

#-Ставим статуc бой локации и время на бой 2 часа-#
        $upd_hunting = $pdo->prepare("UPDATE `hunting` SET `statys_battle` = 1, `time_battle` = :time WHERE `id` = :id");
        $upd_hunting->execute(array(':id' => $hunting_u['id'], ':time' => time() + 7200));

#-Удаляем лог предыдущего боя-#
        $del_hunting_l = $pdo->prepare("DELETE FROM `hunting_log` WHERE `location` = :location");
        $del_hunting_l->execute(array(':location' => $hunting_u['id']));

#-Выборка участников боя-#
        $sel_hunting_b = $pdo->prepare("SELECT * FROM `hunting_battle_u` WHERE `location` = :location");
        $sel_hunting_b->execute(array(':location' => $hunting_u['id']));
        while ($hunting_b = $sel_hunting_b->fetch(PDO::FETCH_LAZY)) {
#-Выборка игрока и его данных-#
            $sel_users = $pdo->prepare("SELECT `id`, `health`, `s_health`, `health_bonus`, `battle` FROM `users` WHERE `id` = :user_id");
            $sel_users->execute(array(':user_id' => $hunting_b['user_id']));
            $all = $sel_users->fetch(PDO::FETCH_LAZY);
#-Статус боя-#
            $upd_users = $pdo->prepare("UPDATE `users` SET `battle` = 1 WHERE `id` = :all_id");
            $upd_users->execute(array(':all_id' => $all['id']));
#-Добавляем здоровье в базе боя-#
            $upd_hunting_b = $pdo->prepare("UPDATE `hunting_battle_u` SET `user_health` = :user_health, `user_t_health` = :user_health WHERE `user_id` = :all_id");
            $upd_hunting_b->execute(array(':user_health' => $all['health'] + $all['s_health'] + $all['health_bonus'], ':all_id' => $all['id']));
        }
    } else {
#-Если нет участников то ставим время заново-#
        $upd_hunting = $pdo->prepare("UPDATE `hunting` SET `time_battle` = :time WHERE `id` = :id");
        $upd_hunting->execute(array(':id' => $hunting_u['id'], ':time' => time() + ($hunting_u['id'] * 7200)));
    }
}

#-Удаление боя по истечению времени-#
$sel_hunting_u2 = $pdo->prepare("SELECT * FROM `hunting` WHERE `time_battle` < :time AND `time_battle` != 0 AND `statys_battle` = 1");
$sel_hunting_u2->execute(array(':time' => time()));
if ($sel_hunting_u2->rowCount() != 0) {
    $hunting_u2 = $sel_hunting_u2->fetch(PDO::FETCH_LAZY);
#-Удаляем бои-#	
    $del_hunting_u = $pdo->prepare("DELETE FROM `hunting_battle_u` WHERE `location` = :location");
    $del_hunting_u->execute(array(':location' => $hunting_u2['id']));
#-Ставим время заново-#
    $upd_hunting = $pdo->prepare("UPDATE `hunting` SET `statys_battle` = 0, `time_battle` = :time WHERE `id` = :id");

    $upd_hunting->execute(array(':id' => $hunting_u2['id'], ':time' => abs(time() + ($hunting_u2['id'] * 7200))));
}

#-Удаление бонусов-#
$upd_sila = $pdo->prepare("UPDATE `users` SET `sila_time` = 0, `sila_bonus` = 0 WHERE `sila_time` < :time");
$upd_sila->execute(array(':time' => time()));
$upd_zashita = $pdo->prepare("UPDATE `users` SET `zashita_time` = 0, `zashita_bonus` = 0 WHERE `zashita_time` < :time");
$upd_zashita->execute(array(':time' => time()));
$upd_health = $pdo->prepare("UPDATE `users` SET `health_time` = 0, `health_bonus` = 0 WHERE `health_time` < :time");
$upd_health->execute(array(':time' => time()));
#-Удаление премиума-#
$upd_premium = $pdo->prepare("UPDATE `users` SET `premium` = 0, `premium_time` = 0 WHERE `premium_time` < :time AND (`premium` = 1 OR `premium` = 2)");
$upd_premium->execute(array(':time' => time()));
#-Проверка параметров игроков-#
$sel_param_u = $pdo->query("SELECT `id`,`param`,`sila`,`zashita`,`health`,`s_sila`,`s_zashita`,`s_health`,`sila_bonus`,`zashita_bonus`,`health_bonus` FROM `users`");
while ($param_u = $sel_param_u->fetch(PDO::FETCH_LAZY)) {
    $param = $param_u['sila'] + $param_u['zashita'] + $param_u['health'] + $param_u['s_sila'] + $param_u['s_zashita'] + $param_u['s_health'] + $param_u['sila_bonus'] + $param_u['zashita_bonus'] + $param_u['health_bonus'];
    if ($param_u['param'] != $param) {
        $upd_users = $pdo->prepare("UPDATE `users` SET `param` = :param WHERE `id` = :id");
        $upd_users->execute(array(':param' => $param, ':id' => $param_u['id']));
    }
}

#-Аукцион-#
$upd_weapon_me = $pdo->prepare("UPDATE `weapon_me` SET `auction` = 0, `gold` = 0, `silver` = 0, `time` = 0 WHERE `auction` = 1 AND `time` < :time");
$upd_weapon_me->execute(array(':time' => time()));
?>