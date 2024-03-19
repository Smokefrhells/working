<?php
require_once '../../system/system.php';
only_reg();


switch ($act) {
    case 'attc':
        #-Время задержки-#
        $t = ((int)(time() - $_SESSION["telecod_ip"]));
        if ($t < 1) {
            header('Location: /hunting_battle_u');
            $_SESSION['err'] = 'Слишком часто!';
            exit();
        }
        $_SESSION["telecod_ip"] = time();

        #-Данные текущего героя в бою-#
        $sel_hunting_b_u = $pdo->prepare("SELECT * FROM `hunting_battle_u` WHERE `user_id` = :user_id");
        $sel_hunting_b_u->execute([':user_id' => $user['id']]);

        if ($sel_hunting_b_u->rowCount() != 0) {
            $battle_u = $sel_hunting_b_u->fetch(PDO::FETCH_LAZY);
            #-Выборка текущего героя-#
            $sel_users_me = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :id");
            $sel_users_me->execute([':id' => $user['id']]);
            $me = $sel_users_me->fetch(PDO::FETCH_LAZY);

            #-Локация на которой происходит бой-#
            $sel_hunting = $pdo->prepare("SELECT * FROM `hunting` WHERE `id` = :location");
            $sel_hunting->execute([':location' => $battle_u['location']]);
            $hunting = $sel_hunting->fetch(PDO::FETCH_LAZY);

            #-Бой должен быть начат-#
            if ($hunting['statys_battle'] == 1) {

                #-Выборка данных врага-#
                $sel_users_v = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :id");
                $sel_users_v->execute([':id' => $battle_u['ank_id']]);
                $vrag = $sel_users_v->fetch(PDO::FETCH_LAZY);
                #-Данные врага в бою-#
                $sel_hunting_b_v = $pdo->prepare("SELECT * FROM `hunting_battle_u` WHERE `user_id` = :vrag_id");
                $sel_hunting_b_v->execute([':vrag_id' => $battle_u['ank_id']]);
                $battle_v = $sel_hunting_b_v->fetch(PDO::FETCH_LAZY);

                #-Урон игрока-#
                $v_user_uron = ((($me['sila'] + $me['s_sila'] + $me['sila_bonus']) * 0.5) * 10);
                $v_ank_zashita = ((($vrag['zashita'] + $vrag['s_zashita'] + $vrag['zashita_bonus']) * 0.2) * 10);
                if ($v_user_uron > $v_ank_zashita) {
                    $r_user_uron_1 = ($v_user_uron - $v_ank_zashita);
                    $r_user_uron_2 = ($v_user_uron - ($v_ank_zashita / 2));
                } else {
                    $r_user_uron_1 = ($v_user_uron / 5);
                    $r_user_uron_2 = ($v_user_uron / 4);
                }
                $user_uron = rand($r_user_uron_1, $r_user_uron_2);
                $user_uron = round($user_uron, 0);

                #-Ключ-#
                $rand = rand(0, 100);
                if ($rand < 5) {
                    $key = 1;
                    $key_inf = "<img src='/style/images/body/key.png' alt=''/>1";
                } else {
                    $key = 0;
                }
                #-Кристаллы-#
                $rand_2 = rand(0, 100);
                if ($rand_2 >= 80) {
                    $q1 = 20 * $hunting['id'];
                    $q2 = 130 * $hunting['id'];
                    $crystal = rand($q1, $q2);
                    $crystal_inf = "<img src='/style/images/many/crystal.png' alt=''/>$crystal";
                } else {
                    $crystal = 0;
                }

                #-Награда за бой-#
                #-Опыт победа-#
                $exp_no = rand((($user['level'] + 50) * 20), (($user['level'] + 150) * 50)); //Победа
                if ($user['premium'] == 0) {
                    $exp_pobeda = $exp_no;
                }
                if ($user['premium'] == 1) {
                    $exp_pobeda = $exp_no * 2;
                }
                if ($user['premium'] == 2) {
                    $exp_pobeda = $exp_no * 3;
                }
                if ($user['premium'] == 1 or $user['premium'] == 2) {
                    $exp_no_p = "<span class='white'><strike>$exp_no</strike></span> "; //Опыт без премиума
                }
                #-Серебро-#
                $silver_no = rand(($user['level'] * 150) + 800, ($user['level'] * 250) + 1500);
                if ($user['premium'] == 0) {
                    $silver_pobeda = $silver_no;
                }
                if ($user['premium'] == 1) {
                    $silvepobeda = round((($silver_no * 0.25) + $silver_no), 0);
                }
                if ($user['premium'] == 2) {
                    $silver_pobeda = round((($silver_no * 0.50) + $silver_no), 0);
                }
                if ($user['premium'] == 1 or $user['premium'] == 2) {
                    $silver_no_p = "<span class='white'><strike>$silver_no</strike></span> "; //Серебро без премиума
                }

                #-Урон-#
                $health_uron = $battle_v['user_t_health'] - $user_uron;

                #-ПОБЕДА-#
                if ($user_uron >= $battle_v['user_t_health']) {
                    #-Редактируем нашие данные боя-#
                    $upd_hunting_b = $pdo->prepare("UPDATE `hunting_battle_u` SET `ank_id` = 0 WHERE `id` = :id");
                    $upd_hunting_b->execute([':id' => $battle_u['id']]);
                    #-Добавляем награду текущему герою-#
                    $upd_users_me = $pdo->prepare("UPDATE `users` SET `exp` = :exp, `exp_clan` = :exp_clan, `silver` = :silver, `crystal` = :crystal, `key` = :key WHERE `id` = :user_id LIMIT 1");
                    $upd_users_me->execute([':exp' => $me['exp'] + $exp_pobeda, ':exp_clan' => $exp_pobeda, ':silver' => $me['silver'] + $silver_pobeda, ':crystal' => $me['crystal'] + $crystal, ':key' => $me['key'] + $key, ':user_id' => $me['id']]);
                    #-Удаляем бой врага-#
                    $del_hunting_b = $pdo->prepare("DELETE FROM `hunting_battle_u` WHERE `user_id` = :vrag_id");
                    $del_hunting_b->execute([':vrag_id' => $vrag['id']]);
                    #-Убираем статус боя у врага-#
                    $upd_users_v = $pdo->prepare("UPDATE `users` SET `battle` = :battle WHERE `id` = :vrag_id LIMIT 1");
                    $upd_users_v->execute([':battle' => 0, ':vrag_id' => $vrag['id']]);
                    #-Лог боя охоте-#
                    if ($me['pol'] == 1) {
                        $at = 'убил';
                    } else {
                        $at = 'убила';
                    }
                    $ins_log = $pdo->prepare("INSERT INTO `hunting_log` SET `location` = :location, `log` = :log, `user_id` = :user_id, `time` = :time");
                    $ins_log->execute([':location' => $battle_u['location'], ':log' => "<img src='/style/images/body/rip.png' alt=''/> <span style='color: #ff0000;'>$me[nick] $at $vrag[nick]</span>", ':user_id' => $user['id'], ':time' => time()]);

                    #-Считаем количество живих игроков и если их нет то завершаем бой-#
                    $sel_atk_u = $pdo->prepare("SELECT COUNT(*) FROM `hunting_battle_u` WHERE `user_id` != :user_id AND `location` = :location");
                    $sel_atk_u->execute([':user_id' => $user['id'], ':location' => $battle_u['location']]);
                    $amount_atk = $sel_atk_u->fetch(PDO::FETCH_LAZY);
                    #-Если нет живых игроков-#
                    if ($amount_atk[0] == 0) {
                        #-Убираем статус боя у нашего игрока-#
                        $upd_users_m = $pdo->prepare("UPDATE `users` SET `battle` = 0 WHERE `id` = :user_id LIMIT 1");
                        $upd_users_m->execute([':user_id' => $user['id']]);
                        #-Ставим время локации-#
                        $upd_hunting = $pdo->prepare("UPDATE `hunting` SET `user_id` = :user_id, `time_battle` = :time_battle, `statys_battle` = 0 WHERE `id` = :id");
                        $upd_hunting->execute([':user_id' => $user['id'], ':time_battle' => time() + ($hunting['id'] * 7200), ':id' => $battle_u['location']]);
                        #-Удаляем текущий бой-#
                        $del_hunting_b = $pdo->prepare("DELETE FROM `hunting_battle_u` WHERE `id` = :id");
                        $del_hunting_b->execute([':id' => $battle_u['id']]);
                        #-Записываем лог-#
                        if ($me['pol'] == 1) {
                            $at = 'захватил';
                        } else {
                            $at = 'захватила';
                        }
                        $ins_log = $pdo->prepare("INSERT INTO `hunting_log` SET `location` = :location, `log` = :log, `user_id` = :user_id, `time` = :time");
                        $ins_log->execute([':location' => $battle_u['location'], ':log' => "<img src='/style/images/body/ok.png' alt=''/> <span style='color: #00a800;'>$me[nick] $at локацию: $hunting[name]</span>", ':user_id' => $user['id'], ':time' => time()]);

                        #-Только если наш герой не владелец локации-#
                        if ($hunting['user_id'] != $user['id']) {
                            #-Плюсуем параметры нашему игроку-#
                            $params = params($hunting['id']);
                            $upd_users_me = $pdo->prepare("UPDATE `users` SET `s_sila` = :s_sila, `s_zashita` = :s_zashita, `s_health` = :s_health WHERE `id` = :user_id LIMIT 1");
                            $upd_users_me->execute([':s_sila' => $user['s_sila'] + $params, ':s_zashita' => $user['s_zashita'] + $params, ':s_health' => $user['s_health'] + $params, ':user_id' => $user['id']]);
                            #-Выборка захватчика локации-#
                            $sel_users_z = $pdo->prepare("SELECT `id`, `s_sila`, `s_zashita`, `s_health` FROM `users` WHERE `id` = :id");
                            $sel_users_z->execute([':id' => $hunting['user_id']]);
                            $zahvat = $sel_users_z->fetch(PDO::FETCH_LAZY);
                            #-Отнимаем параметры у захватчика локации-#
                            $upd_users_v = $pdo->prepare("UPDATE `users` SET `s_sila` = :s_sila, `s_zashita` = :s_zashita, `s_health` = :s_health WHERE `id` = :vrag_id LIMIT 1");
                            $upd_users_v->execute([':s_sila' => $zahvat['s_sila'] - $params, ':s_zashita' => $zahvat['s_zashita'] - $params, ':s_health' => $zahvat['s_health'] - $params, ':vrag_id' => $zahvat['id']]);

                            #-Если мы снова захватили локацию то награда сундук-#
                        } else {
                            #-Сундук-#
                            $chest_r = rand(0, 100);
                            if ($chest_r > 0) {
                                $type_c = 1;
                                $name_c = '<img src="/style/images/body/chest.png" alt=""/>Обычный сундук';
                            }
                            if ($chest_r > 85) {
                                $type_c = 2;
                                $name_c = '<img src="/style/images/body/chest.png" alt=""/>Древний сундук';
                            }
                            if ($chest_r > 95) {
                                $type_c = 3;
                                $name_c = '<img src="/style/images/body/chest.png" alt=""/>Золотой сундук';
                            }
                            #-Добавляем сундук-#
                            $ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
                            $ins_chest->execute([':type' => $type_c, ':user_id' => $user['id'], ':time' => time()]);
                        }
                    }
                    header('Location: /hunting_battle_u');
                    $_SESSION['notif'] = "<center><img src='/style/images/user/exp.png' alt=''/>$exp_no_p$exp_pobeda <img src='/style/images/many/silver.png' alt=''/>$silver_no_p$silver_pobeda $crystal_inf $key_inf<br/><a href='/chest'>$name_c</a></center>";
                    exit();
                } else {
                    #-ПРОДОЛЖЕНИЕ БОЯ-#
                    $upd_hunting_b = $pdo->prepare("UPDATE `hunting_battle_u` SET `user_health` = `user_t_health`, `user_t_health` = :user_t_health  WHERE `user_id` = :vrag_id LIMIT 1");
                    $upd_hunting_b->execute([':user_t_health' => $health_uron, ':vrag_id' => $vrag['id']]);
                    #-Записываем лог дуэли-#
                    if ($me['pol'] == 1) {
                        $at = 'ударил';
                    } else {
                        $at = 'ударила';
                    }
                    $ins_log = $pdo->prepare("INSERT INTO `hunting_log` SET `location` = :location, `log` = :log, `user_id` = :user_id, `time` = :time");
                    $ins_log->execute([':location' => $battle_u['location'], ':log' => "<img src='/style/images/body/attack.png' alt=''/> <span style='color: #ff0000;'>$me[nick] $at $vrag[nick] на $user_uron</span>", ':user_id' => $user['id'], ':time' => time()]);
                    header('Location: /hunting_battle_u');
                }
            } else {
                header('Location: /hunting_');
            }
        } else {
            header('Location: /hunting_battle_u');
        }
}
?>