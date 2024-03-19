<?php
require_once '../../system/system.php';
echo only_reg();
echo reid_level();
#-Сражение рейд-#
switch ($act) {
    case 'attc':
#-Время задержки-#
        $t = ((int)(time() - $_SESSION["telecod_ip"]));
        if ($t < 2) {
            header('Location: /reid');
            $_SESSION['err'] = 'Слишком часто!';
            exit();
        }
        $_SESSION["telecod_ip"] = time();

#-Данные босса-#
        $sel_reid = $pdo->query("SELECT * FROM `reid_boss` WHERE `statys` = 1");
        if ($sel_reid->rowCount() != 0) {
            $reid = $sel_reid->fetch(PDO::FETCH_LAZY);

#-Данные игрока-#
            $sel_reid_u = $pdo->prepare("SELECT * FROM `reid_users` WHERE `user_id` = :user_id");
            $sel_reid_u->execute(array(':user_id' => $user['id']));
            if ($sel_reid_u->rowCount() != 0) {
                $reid_u = $sel_reid_u->fetch(PDO::FETCH_LAZY);

#-Урон игрока-#
                $v_user_uron = ((($user['sila'] + $user['s_sila'] + $user['sila_bonus']) * 0.5) * 1);
                $v_monstr_zashita = (($reid['zashita'] * 0.2) * 10);
                if ($v_user_uron > $v_monstr_zashita) {
                    $r_user_uron_1 = ($v_user_uron - $v_monstr_zashita);
                    $r_user_uron_2 = ($v_user_uron - ($v_monstr_zashita / 2));
                } else {
                    $r_user_uron_1 = ($v_user_uron / 5);
                    $r_user_uron_2 = ($v_user_uron / 4);
                }
                $user_uron = rand($r_user_uron_1, $r_user_uron_2);
                $user_uron = round($user_uron, 0);

#-Урон босса-#
                $v_monstr_uron = (($reid['sila'] * 0.5) * 10);
                $v_user_zashita = ((($user['zashita'] + $user['s_zashita'] + $user['zashita_bonus']) * 0.2) * 1);
                if ($v_monstr_uron > $v_user_zashita) {
                    $r_monstr_uron_1 = ($v_monstr_uron - $v_user_zashita);
                    $r_monstr_uron_2 = ($v_monstr_uron - ($v_user_zashita / 2));
                } else {
                    $r_monstr_uron_1 = ($v_monstr_uron / 5);
                    $r_monstr_uron_2 = ($v_monstr_uron / 4);
                }
                $monstr_uron = rand($r_monstr_uron_1, $r_monstr_uron_2);
                $monstr_uron = round($monstr_uron, 0);

#-Здоровье после атаки-#
                $monstr_health = $reid['t_health'] - $user_uron;
                $user_health = $reid_u['user_t_health'] - $monstr_uron;

#-Награда опыт-#
                $exp_no = rand(($user['level'] * 10) * $reid['type'], ($user['level'] * 20) * $reid['type']);
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
                if ($user['premium'] == 1 or $user['premium'] == 2) {
                    $exp_no_p = "<span class='white'><strike>$exp_no</strike></span> "; //Опыт без премиума
                }

                #-ПОБЕДА-#
                if ($user_uron >= $reid['t_health']) {
#-Выборка всех игроков рейда-#
                    $sel_reid_u = $pdo->query("SELECT * FROM `reid_users`");

                    while ($reid_u = $sel_reid_u->fetch(PDO::FETCH_LAZY)) {
#-Делаем выборку игрока-#
                        $sel_users = $pdo->prepare("SELECT `id`, `silver` FROM `users` WHERE `id` = :id");
                        $sel_users->execute(array(':id' => $reid_u['user_id']));
                        $all = $sel_users->fetch(PDO::FETCH_LAZY);
#-Добавляем серебро-#
                        $upd_users = $pdo->prepare("UPDATE `users` SET `silver` = :silver WHERE `id` = :user_id LIMIT 1");
                        $upd_users->execute(array(':silver' => $all['silver'] + bon_mon($reid_u['silver']), ':user_id' => $all['id']));
#-Убираем статус боя у игроков-#
                        $upd_users = $pdo->prepare("UPDATE `users` SET `battle` = 0, `reid_pobeda` = (`reid_pobeda`+1) WHERE `id` = :id LIMIT 1");
                        $upd_users->execute(array(':id' => $reid_u['user_id']));
#-Удаляем игроков-#
                        $del_reid_u = $pdo->prepare("DELETE FROM `reid_users` WHERE `id` = :id");
                        $del_reid_u->execute(array(':id' => $reid_u['id']));
                    }

#-Удаление босса-#
                    $del_reid_b = $pdo->query("DELETE FROM `reid_boss`");
#-Делаем выборку вещи-#
                    $sel_weapon_r = $pdo->prepare("SELECT * FROM `weapon` WHERE `no_magaz` = :no_magaz ORDER BY RAND()");
                    $sel_weapon_r->execute(array(':no_magaz' => $reid['type']));
                    $weapon_r = $sel_weapon_r->fetch(PDO::FETCH_LAZY);
                    $weapon_boss = "<img src='/style/images/body/snarag.png' alt=''/><span class='blue'>$weapon_r[name]</span>";
#-Добавляем вещь-#
                    $ins_weapon_me = $pdo->prepare("INSERT INTO `weapon_me` SET `type` = :type, `weapon_id` = :weapon_id, `user_id` = :user_id, `time` = :time");
                    $ins_weapon_me->execute(array(':type' => $weapon_r['type'], ':weapon_id' => $weapon_r['id'], ':user_id' => $user['id'], ':time' => time()));
#-Лог-#
                    if ($user['pol'] == 1) {
                        $at = 'убил';
                    } else {
                        $at = 'убила';
                    }
                    $ins_log = $pdo->prepare("INSERT INTO `reid_log` SET `log` = :log, `time` = :time");
                    $ins_log->execute(array(':log' => "<img src='/style/images/user/user.png' alt=''/> <span style='color: #00a800;'>$user[nick] $at $reid[name]</span>", ':time' => time()));
                    header('Location: /reid');
                    $_SESSION['notif'] = "<center><div class='pobeda'><span class='line_notif'><img src='/style/images/body/league.png' alt=''/> Победа <img src='/style/images/body/league.png' alt=''/></span></div>$weapon_boss</center>";
                    exit();
                } else {
                    #-ПОРАЖЕНИЕ-#
                    if ($monstr_uron >= $reid_u['user_t_health']) {
#-Ставим здоровье игрока на 0-#
                        $upd_reid_u = $pdo->prepare("UPDATE `reid_users` SET `user_health` = 0, `user_t_health` = 0 WHERE `user_id` = :user_id LIMIT 1");
                        $upd_reid_u->execute(array(':user_id' => $user['id']));
#-Лог-#
                        if ($user['pol'] == 1) {
                            $at = 'погиб';
                        } else {
                            $at = 'погибла';
                        }
                        $ins_log = $pdo->prepare("INSERT INTO `reid_log` SET `log` = :log, `time` = :time");
                        $ins_log->execute(array(':log' => "<img src='/style/images/body/rip.png' alt=''/> <span style='color: #666666;'>$user[nick] $at</span>", ':time' => time()));
                        header('Location: /reid');
                        $_SESSION['notif'] = '<img src="/style/images/body/rip.png" alt=""/> <span class="red">Вас убили</span>';
                        exit();
                    } else {
                        #-ПРОДОЛЖЕНИЕ БОЯ-#
#-Выпадение вещей-#
                        $rand_w = rand(0, 100);
                        if ($rand_w == 86 or $rand_w == 97 or $rand_w == 45) {
#-Делаем выборку вещи-#
                            $sel_weapon_r = $pdo->prepare("SELECT * FROM `weapon` WHERE `no_magaz` = :no_magaz ORDER BY RAND()");
                            $sel_weapon_r->execute(array(':no_magaz' => $reid['type']));
                            $weapon_r = $sel_weapon_r->fetch(PDO::FETCH_LAZY);
                            $weapon_boss = "<img src='/style/images/body/snarag.png' alt=''/><span class='blue'>$weapon_r[name]</span>";
#-Добавляем вещь-#
                            $ins_weapon_me = $pdo->prepare("INSERT INTO `weapon_me` SET `type` = :type, `weapon_id` = :weapon_id, `user_id` = :user_id, `time` = :time");
                            $ins_weapon_me->execute(array(':type' => $weapon_r['type'], ':weapon_id' => $weapon_r['id'], ':user_id' => $user['id'], ':time' => time()));
#-Лог-#
                            if ($user['pol'] == 1) {
                                $at = 'получил';
                            } else {
                                $at = 'получила';
                            }
                            $ins_log = $pdo->prepare("INSERT INTO `reid_log` SET `log` = :log, `time` = :time");
                            $ins_log->execute(array(':log' => "<img src='/style/images/body/snarag.png' alt=''/> <span style='color: #2066ce;'>$user[nick] $at $weapon_r[name]</span>", ':time' => time()));
                        }
#-Выпадение серебра-#
                        $rand_s = rand(0, 100);
                        if ($rand_s == 45 or $rand_s == 45 or $rand_s == 5 or $rand_s == 3 or $rand_s == 67 or $rand_s == 78 or $rand_s == 23 or $rand_s == 11 or $rand_s == 66 or $rand_s == 99) {
                            $silver = rand(($user['level'] * 5) * $reid['type'], ($user['level'] * 10) * $reid['type']);
                            $silver_i = "<img src='/style/images/many/silver.png' alt=''/>$silver";
#-Лог-#
                            if ($user['pol'] == 1) {
                                $at = 'выбил';
                            } else {
                                $at = 'выбила';
                            }
                            $ins_log = $pdo->prepare("INSERT INTO `reid_log` SET `log` = :log, `time` = :time");
                            $ins_log->execute(array(':log' => "<img src='/style/images/many/silver.png' alt=''/> <span style='color: #2066ce;'>$user[nick] $at $silver_i</span>", ':time' => time()));
                        }

#-Здоровье монстра-#													
                        $upd_reid_b = $pdo->prepare("UPDATE `reid_boss` SET `health` = :health, `t_health` = :t_health  WHERE `id` = :id LIMIT 1");
                        $upd_reid_b->execute(array(':health' => $reid['t_health'], ':t_health' => $monstr_health, ':id' => $reid['id']));
#-Здоровье игрока-#
                        $upd_reid_u = $pdo->prepare("UPDATE `reid_users` SET `user_health` = :user_health, `user_t_health` = :user_t_health, `uron` = :uron, `silver` = :silver WHERE `user_id` = :user_id LIMIT 1");
                        $upd_reid_u->execute(array(':user_health' => $reid_u['user_t_health'], ':user_t_health' => $user_health, ':uron' => $user_uron, ':silver' => $reid_u['silver'] + $silver, ':user_id' => $user['id']));
#-Зачисление награды-#
                        $upd_users = $pdo->prepare("UPDATE `users` SET `exp` = :exp WHERE `id` = :user_id LIMIT 1");
                        $upd_users->execute(array(':exp' => $user['exp'] + bon_exp($exp), ':user_id' => $user['id']));
#-Лог-#
                        if ($user['pol'] == 1) {
                            $at = 'нанес';
                        } else {
                            $at = 'нанесла';
                        }
                        $ins_log = $pdo->prepare("INSERT INTO `reid_log` SET `log` = :log, `time` = :time");
                        $ins_log->execute(array(':log' => "<img src='/style/images/body/attack.png' alt=''/> <span style='color: #ff0000;'>$user[nick] $at $user_uron урона</span>", ':time' => time()));
                        header('Location: /reid');
                        $_SESSION['notif'] = '<img src="/style/images/body/attack.png" alt=""/> <span class="green">Вы</span> нанесли ' . $user_uron . ' урона<br/><img src="/style/images/body/attack.png" alt=""/> <span class="red">' . $reid['name'] . '</span> нанёс вам ' . $monstr_uron . ' урона<br/><img src="/style/images/user/exp.png" alt=""/>' . $exp_no_p . '' . $exp . ' ' . $silver_i . ' ' . $weapon_boss . '';
                        exit();
                    }
                }
            } else {
                header('Location: /reid');
                exit();
            }
        } else {
            header('Location: /reid');
            exit();
        }
}
?>