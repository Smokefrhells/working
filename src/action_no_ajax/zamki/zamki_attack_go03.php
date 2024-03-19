<?php
require_once '../../system/system.php';
require_once '../../avenax/Events.php';
only_reg();
zamki_level();

#-Атака в замках-#
switch ($act) {
    case 'attc':
#-Время задержки-#
        $t = ((int)(time() - $_SESSION["telecod_ip"]));
        if ($t < 3) {
            header('Location: /zamki_battle');
            $_SESSION['err'] = 'Слишком часто!';
            exit();
        }
        $_SESSION["telecod_ip"] = time();
#-Выборка данных замка-#
        $sel_zamki = $pdo->query("SELECT * FROM `zamki` WHERE `statys` = 1 AND `health_t_right` > 0 AND `health_t_left` > 0");
        if ($sel_zamki->rowCount() != 0) {
            $zamki = $sel_zamki->fetch(PDO::FETCH_LAZY);
#-Участвует игрок в бою-#
            $sel_zamki_u = $pdo->prepare("SELECT * FROM `zamki_users` WHERE `user_id` = :user_id AND `time_freezing` = 0");
            $sel_zamki_u->execute(array(':user_id' => $user['id']));
            if ($sel_zamki_u->rowCount() != 0) {
                $zamki_u = $sel_zamki_u->fetch(PDO::FETCH_LAZY);
#-Урон который наносит игрок-#
                $r_user_uron_1 = ((($user['sila'] + $user['s_sila'] + $user['sila_bonus']) * 0.5) * 1);
                $r_user_uron_2 = ((($user['sila'] + $user['s_sila'] + $user['sila_bonus']) * 0.4) * 1);
                $user_uron = rand($r_user_uron_1, $r_user_uron_2);
                $user_uron = round($user_uron, 0);
#-Здоровье замков после атаки-#
                $left_health = $zamki['health_t_left'] - $user_uron;
                $right_health = $zamki['health_t_right'] - $user_uron;
#-Какую сторону атакуем-#
                if ($zamki_u['storona'] == 'right') {
                    $health_v = $zamki['health_t_left'];
                } else {
                    $health_v = $zamki['health_t_right'];
                }

#-Заносим урон в базу-#
                $upd_uron = $pdo->prepare("UPDATE `users` SET `zamki_uron` = :zamki_uron WHERE `id` = :user_id LIMIT 1");
                $upd_uron->execute(array(':zamki_uron' => $user['zamki_uron'] + $user_uron, ':user_id' => $user['id']));

#-ПОБЕДА-#
                if ($user_uron >= $health_v) {
#-Здоровье на 0-#
                    $upd_zamki = $pdo->prepare("UPDATE `zamki` SET `health_t_right` = 0, `health_t_left` = 0 WHERE `id` = :zamki_id");
                    $upd_zamki->execute(array(':zamki_id' => $zamki['id']));

#-Какая сторона-#
                    if ($zamki_u['storona'] == 'right') {
                        $storona = 'left';
                        $pobeda_storona = 'Победили Правые';
                    } else {
                        $storona = 'right';
                        $pobeda_storona = 'Победили Левые';
                    }
#-Урон врага для расчета награды-#
                    $sel_uron_vrag = $pdo->prepare("SELECT SUM(uron) FROM `zamki_users` WHERE `storona` = :storona");
                    $sel_uron_vrag->execute(array(':storona' => $storona));
                    $uron_vrag = $sel_uron_vrag->fetch(PDO::FETCH_LAZY);
#-Запись лога-#
                    $ins_log = $pdo->prepare("INSERT INTO `zamki_log` SET `log` = :log, `time` = :time");
                    $ins_log->execute(array(':log' => '<img src="/style/images/body/zamki.png" alt=""/> ' . $pobeda_storona . '', ':time' => time()));

#-Кол-во игроков с тей же стороной-#
                    $sel_zamki_me = $pdo->prepare("SELECT COUNT(*) FROM `zamki_users` WHERE `storona` = :storona AND `uron` > 0");
                    $sel_zamki_me->execute(array(':storona' => $zamki_u['storona']));
                    $zamki_me = $sel_zamki_me->fetch(PDO::FETCH_LAZY);

#-Выборка всех игроков которые участвуют в сражении-#
                    $sel_zamki_all = $pdo->query("SELECT * FROM `zamki_users`");
                    while ($zamki_all = $sel_zamki_all->fetch(PDO::FETCH_LAZY)) {
#-Выборка данных игроков-#
                        $sel_users = $pdo->prepare("SELECT `id`, `premium`, `exp`, `silver`, `zamki_pobeda`, `zamki_progrash` FROM `users` WHERE `id` = :user_id");
                        $sel_users->execute(array(':user_id' => $zamki_all['user_id']));
                        $all = $sel_users->fetch(PDO::FETCH_LAZY);

#-Если победила сторона игрока-#
                        if ($zamki_all['storona'] == $zamki_u['storona']) {
#-Только если урон больше 0-#
                            if ($zamki_all['uron'] > 0) {
                                $zamki_uron_me = $zamki_all['uron'];
                            } else {
                                $zamki_uron_me = $user_uron;
                            }
#-Серебро врагов (распределяется между победителями)-#
                            $silver_vrag = intval(round((($uron_vrag[0] / 65) / $zamki_me[0]), 0));
                            $silver_vrag = bon_mon($silver_vrag);
#-Серебро-#
                            $silver_no = round(($zamki_uron_me / 5), 0);
                            if ($all['premium'] == 0) {
                                $silver = $silver_no;
                            }
                            if ($all['premium'] == 1) {
                                $silver = round((($silver_no * 0.25) + $silver_no), 0);
                            }
                            if ($all['premium'] == 2) {
                                $silver = round((($silver_no * 0.50) + $silver_no), 0);
                            }
                            $silver = bon_mon($silver);
                            if ($all['premium'] == 1 or $all['premium'] == 2) {
                                $silver_no_p = "<span class='white'><strike>$silver_no</strike></span> "; //Серебро без премиума
                            }
                            // event
                            Events::randomItem('boss', $all['id'], 'win');
#-Зачисляем награду и убираем статус боя-#
                            $upd_users = $pdo->prepare("UPDATE `users` SET `battle` = 0, `exp` = :exp, `silver` = :silver, `zamki_pobeda` = :zamki_pobeda WHERE `id` = :user_id LIMIT 1");
                            $upd_users->execute(array(':exp' => $all['exp'] + $exp, ':silver' => $all['silver'] + $silver + $silver_vrag, ':zamki_pobeda' => $all['zamki_pobeda'] + 1, ':user_id' => $all['id']));
#-Запись лога-#
                            $ins_event = $pdo->prepare("INSERT INTO `event_log` SET `type` = :type, `log` = :log, `user_id` = :user_id, `time` = :time");
                            $ins_event->execute(array(':type' => 9, ':log' => "Ваша сторона победила в сражении<br/><img src='/style/images/body/ok.png' alt=''/>Награда: <img src='/style/images/many/silver.png' alt=''/>$silver_no_p$silver <span class='green'>+$silver_vrag</span>", ':user_id' => $all['id'], ':time' => time()));
                        } else {
#-Убираем статус боя-#// event
                            Events::randomItem('boss', $all['id']);
                            $upd_users = $pdo->prepare("UPDATE `users` SET `battle` = 0, `zamki_progrash` = :zamki_progrash WHERE `id` = :user_id LIMIT 1");
                            $upd_users->execute(array(':zamki_progrash' => $all['zamki_progrash'] + 1, ':user_id' => $all['id']));
#-Запись лога-#
                            $ins_event = $pdo->prepare("INSERT INTO `event_log` SET `type` = :type, `log` = :log, `user_id` = :user_id, `time` = :time");
                            $ins_event->execute(array(':type' => 9, ':log' => "Ваша сторона проиграла в сражении", ':user_id' => $all['id'], ':time' => time()));
                        }
                    }
#-Удаление сражения-#
                    $del_zamki = $pdo->query("DELETE FROM `zamki`");
#-Удаление игроков сражения-#
                    $del_zamki_u = $pdo->query("DELETE FROM `zamki_users`");
                    header('Location: /zamki');
                    exit();

                } else {
#-ПРОДОЛЖЕНИЕ БОЯ-#
#-Опыт-#
                    $exp_no = rand(($user['level'] + 5) * 2, ($user['level'] + 10) * 5);
                    if ($user['premium'] == 0) {
                        $exp = $exp_no;
                    }
                    if ($user['premium'] == 1) {
                        $exp = $exp_no * 2;
                    }
                    if ($user['premium'] == 2) {
                        $exp = $exp_no * 3;
                    }
#-Акция на опыт-#
                    $sel_stock = $pdo->query("SELECT * FROM `stock` WHERE `type` = 13");
                    if ($sel_stock->rowCount() != 0) {
                        $stock = $sel_stock->fetch(PDO::FETCH_LAZY);
                        $exp = round($exp + (($exp / 100) * $stock['prosent']), 0);
                    }
                    $exp = bon_exp($exp);
                    if ($user['premium'] == 1 or $user['premium'] == 2) {
                        $exp_no_p = "<span class='white'><strike>$exp_no</strike></span> "; //Опыт без премиума
                    }
#-Зачисление опыта-#
                    $upd_users = $pdo->prepare("UPDATE `users` SET `exp` = :exp WHERE `id` = :user_id");
                    $upd_users->execute(array(':exp' => $user['exp'] + $exp, ':user_id' => $user['id']));

#-Минус здоровье замка-#
                    if ($zamki_u['storona'] == 'right') {
                        $upd_zamki = $pdo->prepare("UPDATE `zamki` SET `health_left` = :health_t_left, `health_t_left` = :left_health WHERE `id` = :zamki_id");
                        $upd_zamki->execute(array(':health_t_left' => $zamki['health_t_left'], ':left_health' => $left_health, ':zamki_id' => $zamki['id']));
                    } else {
                        $upd_zamki = $pdo->prepare("UPDATE `zamki` SET `health_right` = :health_t_right, `health_t_right` = :right_health WHERE `id` = :zamki_id");
                        $upd_zamki->execute(array(':health_t_right' => $zamki['health_t_right'], ':right_health' => $right_health, ':zamki_id' => $zamki['id']));
                    }

#-Заносим урон игрока-#
                    $upd_zamki_u = $pdo->prepare("UPDATE `zamki_users` SET `uron` = :uron  WHERE `id` = :id");
                    $upd_zamki_u->execute(array(':uron' => $zamki_u['uron'] + $user_uron, ':id' => $zamki_u['id']));

#-Запись лога-#
                    $ins_log = $pdo->prepare("INSERT INTO `zamki_log` SET `log` = :log, `storona` = :storona, `time` = :time");
                    $ins_log->execute(array(':log' => "<img src='/style/images/body/attack.png' alt=''/> $user[nick] нанес(ла) $user_uron урона", ':storona' => $zamki_u['storona'], ':time' => time()));
                    header('Location: /zamki_battle');
                    $_SESSION['notif'] = "<img src='/style/images/body/attack.png' alt=''/> <span class='green'>Вы</span> нанесли $user_uron урона<br/><img src='/style/images/user/exp.png' alt=''/>Опыт: $exp_no_p$exp";
                    exit();
                }
            } else {
                header('Location: /zamki_battle');
                exit();
            }
        } else {
            header('Location: /zamki');
            exit();
        }
}
?>