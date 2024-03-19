<?php
require_once '../../system/system.php';
require_once '../../avenax/Events.php';
only_reg();

/*Бой с боссом*/
switch ($act) {
    case 'attc':
#-Выборка данных о бое-#
        $sel_boss_users = $pdo->prepare("SELECT * FROM `boss_users` WHERE `user_id` = :user_id");
        $sel_boss_users->execute(array(':user_id' => $user['id']));
        if ($sel_boss_users->rowCount() != 0) {
            $boss_u = $sel_boss_users->fetch(PDO::FETCH_LAZY);
#-Делаем выборку боя монстра-#
            $sel_boss_battle = $pdo->prepare("SELECT * FROM `boss_battle` WHERE `id` = :id");
            $sel_boss_battle->execute(array(':id' => $boss_u['battle_id']));
            if ($sel_boss_battle->rowCount() != 0) {
                $battle = $sel_boss_battle->fetch(PDO::FETCH_LAZY);
#-Выборка данных о Боссе-#
                $sel_boss = $pdo->prepare("SELECT * FROM `boss` WHERE `id` = :boss_id");
                $sel_boss->execute(array(':boss_id' => $battle['boss_id']));
                $boss = $sel_boss->fetch(PDO::FETCH_LAZY);

#-Урон игрока-#
                $v_user_uron = ((($user['sila'] + $user['s_sila'] + $user['sila_bonus']) * 0.5) * 2);
                $v_boss_zashita = (($boss['zashita'] * 0.2) * 1);
                if ($v_user_uron > $v_boss_zashita) {
                    $r_user_uron_1 = ($v_user_uron - $v_boss_zashita);
                    $r_user_uron_2 = ($v_user_uron - ($v_boss_zashita / 2));
                } else {
                    $r_user_uron_1 = ($v_user_uron / 5);
                    $r_user_uron_2 = ($v_user_uron / 4);
                }
                $user_uron = rand($r_user_uron_1, $r_user_uron_2);
                $user_uron = round($user_uron, 0);

#-Урон монстра-#
                $v_boss_uron = (($boss['sila'] * 0.5) * 5);
                $v_user_zashita = ((($user['zashita'] + $user['s_zashita'] + $user['zashita_bonus']) * 0.5) * 5);
                if ($v_boss_uron > $v_user_zashita) {
                    $r_boss_uron_1 = ($v_boss_uron - $v_user_zashita);
                    $r_boss_uron_2 = ($v_boss_uron - ($v_user_zashita / 2));
                } else {
                    $r_boss_uron_1 = ($v_boss_uron / 5);
                    $r_boss_uron_2 = ($v_boss_uron / 4);
                }
                $boss_uron = rand($r_boss_uron_1, $r_boss_uron_2);
                $boss_uron = round($boss_uron, 0);
                $boss_health = $battle['boss_t_health'] - $user_uron;
                $user_health = $boss_u['user_t_health'] - $boss_uron;

                #-ПОБЕДА-#
                if ($user_uron >= $battle['boss_t_health']) {
#-Обучение-#	
                    if ($user['start'] == 6 and $boss['id'] == 1) {
                        $upd_users = $pdo->prepare("UPDATE `users` SET `start` = 7 WHERE `id` = :user_id LIMIT 1");
                        $upd_users->execute(array(':user_id' => $user['id']));
                    }

#-Выборка всех игроков которые участвували-#
                    $sel_boss_u2 = $pdo->prepare("SELECT * FROM `boss_users` WHERE `battle_id` = :battle_id");
                    $sel_boss_u2->execute(array(':battle_id' => $battle['id']));
                    while ($boss_u2 = $sel_boss_u2->fetch(PDO::FETCH_LAZY)) {
#-Выборка данных игроков-#
                        $sel_users = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :id");
                        $sel_users->execute(array(':id' => $boss_u2['user_id']));
                        $all = $sel_users->fetch(PDO::FETCH_LAZY);

#-Очищаем переменные-#
                        unset($rand_key, $exp_no, $silver_no, $gold, $rand_chest, $snow);

#-ЕЖЕДНЕВНЫЕ ЗАДАНИЯ БОССЫ-#
                        $sel_tasks = $pdo->prepare("SELECT * FROM `daily_tasks` WHERE `user_id` = :user_id AND `type` = 4 AND `type_id` = :type_id");
                        $sel_tasks->execute(array(':user_id' => $all['id'], ':type_id' => $boss['id']));
                        if ($sel_tasks->rowCount() != 0) {
                            $tasks = $sel_tasks->fetch(PDO::FETCH_LAZY);
#-Если еще не выполнено-#
                            if ($tasks['quatity'] == 0 and $tasks['statys'] == 0) {
#-Награда-#
                                $gold_tasks = $boss['id'] + 1;
                                $silver_tasks = bon_mon(($boss['id'] * 2) * 1400);
                                $exp_tasks = bon_exp(($boss['id'] * 3) * 1000);
                                $tasks_q = 1;
#-Записываем в бд-#
                                $upd_tasks = $pdo->prepare("UPDATE `daily_tasks` SET `quatity` = 1, `statys` = 1 WHERE `user_id` = :user_id AND `type` = 4 AND `type_id` = :boss_id LIMIT 1");
                                $upd_tasks->execute(array(':user_id' => $all['id'], ':boss_id' => $boss['id']));
                                $_SESSION['ok'] = '<b>' . $boss['name'] . '</b>: <img src="/style/images/many/gold.png" alt=""/>' . $gold_tasks . ' <img src="/style/images/many/silver.png" alt=""/>' . $silver_tasks . ' <img src="/style/images/user/exp.png" alt=""/>' . $exp_tasks . '';
                            }
                        }

#-Награды-#
#-Ключ-#
                        $rand_key = rand(0, 100);
                        if ($rand_key < 3) {
                            $key = 1;
                            $key_i = "<img src='/style/images/body/key.png' alt=''/>1";
                        } else {
                            $key = 0;
                        }
#-Опыт победа-#
                        $exp_no = rand($boss['exp_1'], $boss['exp_2']);
                        if ($all['premium'] == 0) {
                            $exp_pobeda = $exp_no;
                        }
                        if ($all['premium'] == 1) {
                            $exp_pobeda = $exp_no * 2;
                        }
                        if ($all['premium'] == 2) {
                            $exp_pobeda = $exp_no * 3;
                        }
                        $exp_pobeda = bon_exp($exp_pobeda);
                        if ($all['premium'] == 1 or $all['premium'] == 2) {
                            $exp_no_p = "<span class='white'><strike>$exp_no</strike></span> "; //Опыт без премиума
                        }
#-Серебро-#
                        $silver_no = rand($boss['silver_1'], $boss['silver_2']);
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
#-Золото-#
                        $gold = rand($boss['gold_1'], $boss['gold_2']);
#-Праздничные-#
                        $snow = rand($boss['snow_1'], $boss['snow_2']);

#-Сундук-#
                        $rand_chest = rand(0, 100);
                        if ($rand_chest > 60) {
                            $type_c = 1;
                            $name_c = '<img src="/style/images/body/chest.png" alt=""/>Обычный сундук';
                        }
                        if ($rand_chest > 85) {
                            $type_c = 2;
                            $name_c = '<img src="/style/images/body/chest.png" alt=""/><span class="green">Древний сундук</span>';
                        }
                        if ($rand_chest > 95) {
                            $type_c = 3;
                            $name_c = '<img src="/style/images/body/chest.png" alt=""/><span class="yellow">Золотой сундук</span>';
                        }

#-Зачисляем награду-#
                        $upd_users = $pdo->prepare("UPDATE `users` SET `battle` = :battle, `exp` = :exp, `exp_clan` = :exp_clan, `silver` = :silver, `gold` = :gold, `snow` = :snow, `key` = :key, `boss_pobeda` = :boss_pobeda, `tasks` = :tasks WHERE `id` = :id LIMIT 1");
                        $upd_users->execute(array(':battle' => 0, ':exp' => $all['exp'] + $exp_pobeda + $exp_tasks, ':exp_clan' => $exp_pobeda + $exp_tasks, ':silver' => $all['silver'] + $silver + $silver_tasks, ':gold' => $all['gold'] + $gold + $gold_tasks, ':snow' => $all['snow'] + $snow, ':key' => $all['key'] + $key, ':boss_pobeda' => $all['boss_pobeda'] + 1, ':tasks' => $all['tasks'] + $tasks_q, ':id' => $all['id']));
#-Сундук-#
                        $ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
                        $ins_chest->execute(array(':type' => $type_c, ':user_id' => $all['id'], ':time' => time()));
#-Лог-#
                        if ($boss['type'] < 4) {
                            $ins_event = $pdo->prepare("INSERT INTO `event_log` SET `type` = :type, `log` = :log, `user_id` = :user_id, `time` = :time");
                            $ins_event->execute(array(':type' => 3, ':log' => "Вы победили босса <span class='red'>$boss[name]</span><br/><img src='/style/images/body/gift.png' alt=''/>Ваша награда: <img src='/style/images/user/exp.png' alt=''/>$exp_no_p$exp_pobeda <img src='/style/images/many/silver.png' alt=''/>$silver_no_p$silver <img src='/style/images/many/gold.png' alt=''/>$gold $key_i $name_c</span>", ':user_id' => $all['id'], ':time' => time()));
                        } else {
                            $ins_event = $pdo->prepare("INSERT INTO `event_log` SET `type` = :type, `log` = :log, `user_id` = :user_id, `time` = :time");
                            $ins_event->execute(array(':type' => 3, ':log' => "Вы победили босса <span class='red'>$boss[name]</span><br/><img src='/style/images/body/gift.png' alt=''/>Ваша награда: <img src='/style/images/user/exp.png' alt=''/>$exp_no_p$exp_pobeda <img src='/style/images/many/march.png' alt=''/>$snow</span>", ':user_id' => $all['id'], ':time' => time()));
                        }
#-Ставим время игрокам-#
                        $ins_boss_t = $pdo->prepare("INSERT INTO `boss_time` SET `type` = :type, `boss_id` = :boss_id, `user_id` = :user_id, `time` = :time");
                        $ins_boss_t->execute(array(':type' => 2, ':boss_id' => $boss['id'], ':user_id' => $all['id'], ':time' => time() + $boss['time_otduh']));
#-Удаление игроков которые были в бою-#
                        $del_boss_u = $pdo->prepare("DELETE FROM `boss_users` WHERE `user_id` = :all_id");
                        $del_boss_u->execute(array(':all_id' => $all['id']));
                    }
#-Чистим лог-#
                    $del_log = $pdo->prepare("DELETE FROM `boss_log` WHERE `battle_id` = :battle_id");
                    $del_log->execute(array(':battle_id' => $battle['id']));
#-Удаляем все уведомления-#
                    $del_event = $pdo->prepare("DELETE FROM `event_log` WHERE `type` = :type AND `ev_id` = :battle_id");
                    $del_event->execute(array(':type' => 2, ':battle_id' => $battle['id']));
#-Удаляем бой-#
                    $del_boss_b = $pdo->prepare("DELETE FROM `boss_battle` WHERE `id` = :battle_id");
                    $del_boss_b->execute(array(':battle_id' => $battle['id']));
                    header("Location: /boss?type=$boss[type]");

                    // event
                    Events::randomItem('boss', $user['id']);
                    $_SESSION['notif'] = "<center><div class='pobeda'><span class='line_notif'><img src='/style/images/body/league.png' alt=''/> Победа <img src='/style/images/body/league.png' alt=''/></span></div></center>";
                    exit();
                } else {
                    #-ПОРАЖЕНИЕ-#
                    if ($boss_uron >= $boss_u['user_t_health']) {
#-Обучение-#	
                        if ($user['start'] == 6 and $boss['id'] == 1) {
                            $upd_users = $pdo->prepare("UPDATE `users` SET `start` = 7 WHERE `id` = :user_id LIMIT 1");
                            $upd_users->execute(array(':user_id' => $user['id']));
                        }

#-Сколько живых игроков-#
                        $sel_boss_live = $pdo->prepare("SELECT COUNT(*) FROM `boss_users` WHERE `battle_id` = :battle_id AND `user_t_health` > 0");
                        $sel_boss_live->execute(array(':battle_id' => $battle['id']));
                        $boss_live = $sel_boss_live->fetch(PDO::FETCH_LAZY);
                        if ($boss_live[0] >= 2) {
#-Ставим здоровье на ноль-#
                            $upd_boss_u = $pdo->prepare("UPDATE `boss_users` SET `user_health` = 0, `user_t_health` = 0 WHERE `user_id` = :user_id");
                            $upd_boss_u->execute(array(':user_id' => $user['id']));
#-Лог-#
                            if ($user['pol'] == 1) {
                                $at = 'погиб';
                            } else {
                                $at = 'погибла';
                            }
                            $ins_log = $pdo->prepare("INSERT INTO `boss_log` SET `battle_id` = :battle_id, `log` = :log, `time` = :time");
                            $ins_log->execute(array(':battle_id' => $battle['id'], ':log' => "<img src='/style/images/body/rip.png' alt=''/> <span style='color: #666666;'>$user[nick] $at</span>", ':time' => time()));
                            header('Location: /boss_battle');
                            exit();

                        } else {
#-Выборка всех игроков которые участвували-#
                            $sel_boss_u2 = $pdo->prepare("SELECT * FROM `boss_users` WHERE `battle_id` = :battle_id");
                            $sel_boss_u2->execute(array(':battle_id' => $battle['id']));
                            while ($boss_u2 = $sel_boss_u2->fetch(PDO::FETCH_LAZY)) {
#-Выборка игроков-#
                                $sel_users = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :id");
                                $sel_users->execute(array(':id' => $boss_u2['user_id']));
                                $all = $sel_users->fetch(PDO::FETCH_LAZY);

                                unset($exp_p_no);

#-Награда-#
#-Опыт поражение-#
                                $exp_p_no = rand($boss['exp_1'] / 4, $boss['exp_2'] / 4);
                                if ($all['premium'] == 0) {
                                    $exp_progrash = $exp_p_no;
                                }
                                if ($all['premium'] == 1) {
                                    $exp_progrash = $exp_p_no * 2;
                                }
                                if ($all['premium'] == 2) {
                                    $exp_progrash = $exp_p_no * 3;
                                }
                                $exp_progrash = bon_exp($exp_progrash);
                                if ($all['premium'] == 1 or $all['premium'] == 2) {
                                    $exp_prog_no_p = "<span class='white'><strike>$exp_p_no</strike></span> "; //Опыт без премиума
                                }

#-Зачисляем награду-#
                                $upd_users = $pdo->prepare("UPDATE `users` SET `battle` = :battle, `exp` = :exp, `exp_clan` = :exp_clan, `key` = :key, `boss_progrash` = :boss_progrash WHERE `id` = :id LIMIT 1");
                                $upd_users->execute(array(':battle' => 0, ':exp' => $all['exp'] + $exp_progrash, ':exp_clan' => $exp_progrash, ':key' => $all['key'] + $key, ':boss_progrash' => $all['boss_progrash'] + 1, ':id' => $all['id']));
#-Лог-#
                                $ins_event = $pdo->prepare("INSERT INTO `event_log` SET `type` = :type, `log` = :log, `user_id` = :user_id, `time` = :time");
                                $ins_event->execute(array(':type' => 3, ':log' => "Вы не смогли победить босса <span class='red'>$boss[name]</span><br/><img src='/style/images/body/gift.png' alt=''/>Ваша награда: <img src='/style/images/user/exp.png' alt=''/>$exp_prog_no_p$exp_progrash", ':user_id' => $all['id'], ':time' => time()));
#-Ставим время игрокам-#
                                $ins_boss_t = $pdo->prepare("INSERT INTO `boss_time` SET `type` = :type, `boss_id` = :boss_id, `user_id` = :user_id, `time` = :time");
                                $ins_boss_t->execute(array(':type' => 2, ':boss_id' => $boss['id'], ':user_id' => $all['id'], ':time' => time() + $boss['time_otduh']));
#-Удаление игроков которые были в бою-#
                                $del_boss_u = $pdo->prepare("DELETE FROM `boss_users` WHERE `user_id` = :all_id");
                                $del_boss_u->execute(array(':all_id' => $all['id']));
                            }
#-Чистим лог-#
                            $del_log = $pdo->prepare("DELETE FROM `boss_log` WHERE `battle_id` = :battle_id");
                            $del_log->execute(array(':battle_id' => $battle['id']));
#-Удаляем все уведомления-#
                            $del_event = $pdo->prepare("DELETE FROM `event_log` WHERE `type` = :type AND `ev_id` = :battle_id");
                            $del_event->execute(array(':type' => 2, ':battle_id' => $battle['id']));
#-Удаляем бой-#
                            $del_boss_b = $pdo->prepare("DELETE FROM `boss_battle` WHERE `id` = :battle_id");
                            $del_boss_b->execute(array(':battle_id' => $battle['id']));
                            header("Location: /boss?type=$boss[type]");
                            $_SESSION['notif'] = "<center><div class='porashenie'><span class='line_notif'><img src='/style/images/body/league.png' alt=''/> Поражение <img src='/style/images/body/league.png' alt=''/></span></div></center>";
                            exit();
                        }
                    }
                    #-ПРОДОЛЖЕНИЕ БОЯ-#
                    if ($boss_uron < $boss_u['user_t_health']) {
#-Здоровье босса-#
                        $upd_boss_b = $pdo->prepare("UPDATE `boss_battle` SET `boss_health` = :boss_health, `boss_t_health` = :boss_t_health  WHERE `id` = :id");
                        $upd_boss_b->execute(array(':boss_health' => $battle['boss_t_health'], ':boss_t_health' => $boss_health, ':id' => $battle['id']));
#-Здоровье игрока-#
                        $upd_boss_u = $pdo->prepare("UPDATE `boss_users` SET `user_health` = :user_health, `user_t_health` = :user_t_health, `uron` = :uron WHERE `id` = :id");
                        $upd_boss_u->execute(array(':user_health' => $boss_u['user_t_health'], ':user_t_health' => $user_health, ':uron' => $boss_u['uron'] + $user_uron, ':id' => $boss_u['id']));
#-Лог-#
                        if ($user['pol'] == 1) {
                            $at = 'нанес';
                        } else {
                            $at = 'нанесла';
                        }
                        $ins_log = $pdo->prepare("INSERT INTO `boss_log` SET `battle_id` = :battle_id, `log` = :log, `time` = :time");
                        $ins_log->execute(array(':battle_id' => $battle['id'], ':log' => "<img src='/style/images/body/attack.png' alt=''/> <span style='color: #ff0000;'>$user[nick] $at $user_uron урона</span>", ':time' => time()));
                        header('Location: /boss_battle');
                        $_SESSION['notif'] = '<img src="/style/images/body/attack.png"/> <span class="green">Вы</span> нанесли ' . $user_uron . ' урона<br/><img src="/style/images/body/attack.png"/> <span class="red">' . $boss['name'] . '</span> нанёс вам ' . $boss_uron . ' урона';
                        exit();
                    }
                }
            } else {
                header('Location: /boss');
            }
        } else {
            header('Location: /boss');
        }
}
?>