<?php
require_once '../../system/system.php';
require_once '../../avenax/Events.php';
only_reg();
towers_level();

#-Атака в башнях-#
switch ($act) {
    case 'attc':
#-Проверка что игрок участвует в бою-#
        $sel_towers_me = $pdo->prepare("SELECT * FROM `towers` WHERE `user_id` = :user_id AND `statys` = 2");
        $sel_towers_me->execute(array(':user_id' => $user['id']));
        if ($sel_towers_me->rowCount() == 0)
            $error = 'Вы не участвуете в бою!';
        $towers_me = $sel_towers_me->fetch(PDO::FETCH_LAZY);
#-Данные боя оппонента-#
        $sel_towers_op = $pdo->prepare("SELECT * FROM `towers` WHERE `user_id` = :ank_id AND `statys` = 2 AND `group` != :group");
        $sel_towers_op->execute(array(':ank_id' => $towers_me['ank_id'], ':group' => $towers_me['group']));
        if ($sel_towers_op->rowCount() == 0)
            $error = 'Ошибка данных оппонента!';
        $towers_op = $sel_towers_op->fetch(PDO::FETCH_LAZY);
#-Время задержки-#
        $t = ((int)(time() - $_SESSION["telecod_ip"]));
        if ($t < 2) {
            header('Location: /towers');
            $_SESSION['err'] = 'Слишком часто!';
            exit();
        }
        $_SESSION["telecod_ip"] = time();

#-Нет ошибок-#
        if (!isset($error)) {
#-Данные оппонента-#
            $sel_users_op = $pdo->prepare("SELECT `id`, `avatar`, `pol`, `nick`, `level`, `sila`, `s_sila`, `sila_bonus`, `zashita`, `s_zashita`, `zashita_bonus`, `health`, `s_health`, `health_bonus`, `towers_pobeda`, `towers_progrash` FROM `users` WHERE `id` = :id");
            $sel_users_op->execute(array(':id' => $towers_me['ank_id']));
            $opponent = $sel_users_op->fetch(PDO::FETCH_LAZY);

#-Урон игрока-#
            $v_user_uron = ((($user['sila'] + $user['s_sila'] + $user['sila_bonus']) * 0.4) * 1);
            $v_ank_zashita = ((($opponent['zashita'] + $opponent['s_zashita'] + $opponent['zashita_bonus']) * 0.5) * 1);
            if ($v_user_uron > $v_ank_zashita) {
                $r_user_uron_1 = ($v_user_uron - $v_ank_zashita);
                $r_user_uron_2 = ($v_user_uron - ($v_ank_zashita / 2));
            } else {
                $r_user_uron_1 = ($v_user_uron / 5);
                $r_user_uron_2 = ($v_user_uron / 4);
            }
            $user_uron = rand($r_user_uron_1, $r_user_uron_2);
            $user_uron = round($user_uron, 0);
#-Здоровье оппонента после атаки-#
            $health_uron = $towers_op['user_t_health'] - $user_uron;

#-Опыт за убийство-#
            $exp_no = rand((($user['level'] + 10) * 15), (($user['level'] + 50) * 35));
            if ($user['premium'] == 0) {
                $exp_me = $exp_no;
            }
            if ($user['premium'] == 1) {
                $exp_me = $exp_no * 2;
            }
            if ($user['premium'] == 2) {
                $exp_me = $exp_no * 3;
            }
            $exp_me = bon_exp($exp_me);

            #-ПОБЕДА-#
            if ($user_uron >= $towers_op['user_t_health']) {

#-Убираем оппонента из боя-#
                $upd_towers_op = $pdo->prepare("UPDATE `towers` SET `user_health` = 0, `user_t_health` = 0, `statys` = 3 WHERE `user_id` = :ank_id LIMIT 1");
                $upd_towers_op->execute(array(':ank_id' => $opponent['id']));
#-Засчитываем поражение оппоненту-#
                $upd_users_op = $pdo->prepare("UPDATE `users` SET `towers_progrash` = :towers_progrash WHERE `id` = :ank_id LIMIT 1");
                $upd_users_op->execute(array(':towers_progrash' => $opponent['towers_progrash'] + 1, ':ank_id' => $opponent['id']));
                // event
                Events::randomItem('boss', $opponent['id']);
#-Зачисляем награду победителю-#
                $upd_users_me = $pdo->prepare("UPDATE `users` SET `towers_pobeda` = :towers_pobeda, `towers_t` = :towers_t, `exp` = :exp WHERE `id` = :user_id LIMIT 1");
                $upd_users_me->execute(array(':towers_pobeda' => $user['towers_pobeda'] + 1, ':towers_t' => $user['towers_t'] + 1, ':exp' => $user['exp'] + $exp_me, ':user_id' => $user['id']));
                // event
                Events::randomItem('boss', $user['id'], 'win');
#-Запись лога-#
                $ins_log = $pdo->prepare("INSERT INTO `towers_log` SET `battle_id` = :battle_id, `user_id` = :user_id, `log` = :log, `time` = :time");
                $ins_log->execute(array(':battle_id' => $towers_me['battle_id'], ':user_id' => $user['id'], ':log' => "<img src='/style/images/body/rip.png' alt=''/> $user[nick] убил(а) $opponent[nick]", ':time' => time()));

#-Какая группа-#
                if ($towers_me['group'] == 1) {
                    $group = 2;
                } else {
                    $group = 1;
                }
#-Кол-во живых участников противоположной группы-#
                $sel_towers_a = $pdo->prepare("SELECT * FROM `towers` WHERE `battle_id` = :battle_id AND `user_t_health` > 0 AND `user_id` != :user_id AND `group` = :group");
                $sel_towers_a->execute(array(':battle_id' => $towers_me['battle_id'], ':user_id' => $user['id'], ':group' => $group));
                if ($sel_towers_a->rowCount() > 0) {
                    $towers_a = $sel_towers_a->fetch(PDO::FETCH_LAZY);
#-Запись другого оппонента-#
                    $upd_towers_me = $pdo->prepare("UPDATE `towers` SET `ank_id` = :ank_id, `exp` = :exp WHERE `user_id` = :user_id LIMIT 1");
                    $upd_towers_me->execute(array(':ank_id' => $towers_a['user_id'], ':exp' => $towers_me['exp'] + $exp_me, ':user_id' => $user['id']));
                } else {

#-Выборка игроков своей группы-#
                    $sel_towers_u = $pdo->prepare("SELECT `id`, `level`, `battle_id`, `user_id`, `group` FROM `towers` WHERE `battle_id` = :battle_id AND `group` = :group");
                    $sel_towers_u->execute(array(':battle_id' => $towers_me['battle_id'], ':group' => $towers_me['group']));
                    while ($towers_u = $sel_towers_u->fetch(PDO::FETCH_LAZY)) {
#-Награда-#
                        if ($towers_u['level'] <= 100) {
                            $gold = 170;
                            $silver = 50000;
                        }
                        if ($towers_u['level'] <= 75) {
                            $gold = 130;
                            $silver = 30000;
                        }
                        if ($towers_u['level'] <= 50) {
                            $gold = 90;
                            $silver = 20000;
                        }
                        if ($towers_u['level'] <= 25) {
                            $gold = 50;
                            $silver = 10000;
                        }
                        $silver = bon_mon($silver);

                        if ($towers_me['type'] == 'gold') {
#-Удаление игрока из боя-#
                            $upd_towers_me = $pdo->prepare("UPDATE `towers` SET `statys` = 3, `many` = :many WHERE `user_id` = :user_id LIMIT 1");
                            $upd_towers_me->execute(array(':user_id' => $towers_u['user_id'], ':many' => $gold));
#-Зачисление золота-#
                            $upd_users_u = $pdo->prepare("UPDATE `users` SET `gold` = `gold` + :gold WHERE `id` = :user_id LIMIT 1");
                            $upd_users_u->execute(array(':gold' => $gold, ':user_id' => $towers_u['user_id']));
                        } else {
#-Удаление игрока из боя-#
                            $upd_towers_me = $pdo->prepare("UPDATE `towers` SET `statys` = 3, `many` = :many WHERE `user_id` = :user_id LIMIT 1");
                            $upd_towers_me->execute(array(':user_id' => $towers_u['user_id'], ':many' => $silver));
#-Зачисление серебра-#
                            $upd_users_u = $pdo->prepare("UPDATE `users` SET `silver` = `silver` + :silver WHERE `id` = :user_id LIMIT 1");
                            $upd_users_u->execute(array(':silver' => $silver, ':user_id' => $towers_u['user_id']));
                        }
                    }
#-Удаление лога-#
                    $del_log = $pdo->prepare("DELETE FROM `towers_log` WHERE `battle_id` = :battle_id");
                    $del_log->execute(array(':battle_id' => $towers_me['battle_id']));
                }
                header('Location: /towers');

            } else {
#-Минус здоровья у оппонента-#
                $upd_towers = $pdo->prepare("UPDATE `towers` SET `user_health` = :user_health, `user_t_health` = :user_t_health WHERE `user_id` = :ank_id");
                $upd_towers->execute(array(':user_health' => $towers_op['user_t_health'], ':user_t_health' => $health_uron, ':ank_id' => $opponent['id']));
#-Запись лога-#
                $ins_log = $pdo->prepare("INSERT INTO `towers_log` SET `battle_id` = :battle_id, `user_id` = :user_id, `log` = :log, `group` = :group, `time` = :time");
                $ins_log->execute(array(':battle_id' => $towers_me['battle_id'], ':user_id' => $user['id'], ':log' => "<img src='/style/images/body/attack.png' alt=''/> $user[nick] нанес(ла) $user_uron урона $opponent[nick]", ':group' => $towers_me['group'], ':time' => time()));
                header('Location: /towers');
            }
        } else {
            header('Location: /towers');
            $_SESSION['err'] = $error;
            exit();
        }
}
?>