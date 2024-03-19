<?php
require_once '../../system/system.php';
echo only_reg();
echo duel_level();
session_start();
#-Атака дуэли-#
switch ($act) {
    case 'attc':
        if (isset($_SESSION['duel_id'])) {
            $ank_id = $_SESSION['duel_id'];
#-Выборка данных о игроке-#
            $sel_users = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :id");
            $sel_users->execute(array(':id' => $ank_id));
            $all = $sel_users->fetch(PDO::FETCH_LAZY);

#-Кол-во боев-#
            $user_level = floor($user['level'] / 2);
            $duel_level = $user_level - 1;
            if ($user['duel_b'] < $duel_level) {
                $upd_users = $pdo->prepare("UPDATE `users` SET `duel_b` = :duel_b WHERE `id` = :id LIMIT 1");
                $upd_users->execute(array(':duel_b' => $user['duel_b'] + 1, ':id' => $user['id']));
            } else {
                $upd_users = $pdo->prepare("UPDATE `users` SET `duel_b` = :duel_b WHERE `id` = :id LIMIT 1");
                $upd_users->execute(array(':duel_b' => $user['duel_b'] + 1, ':id' => $user['id']));
                $ins_duel_t = $pdo->prepare("INSERT INTO `duel_time` SET `duel_time` = :duel_time, `user_id` = :user_id");
                $ins_duel_t->execute(array(':duel_time' => time() + 1800, ':user_id' => $user['id']));
            }

#-Параметры игрока-#
            $user_param = $user['sila'] + $user['zashita'] + $user['health'] + $user['s_sila'] + $user['s_zashita'] + $user['s_health'] + $user['sila_bonus'] + $user['zashita_bonus'] + $user['health_bonus'];
#-Параметры опонента-#
            $ank_param = $all['sila'] + $all['zashita'] + $all['health'] + $all['s_sila'] + $all['s_zashita'] + $all['s_health'] + $all['sila_bonus'] + $all['zashita_bonus'] + $all['health_bonus'];

#-Награда за бой-#
#-Ключ-#
            $rand = rand(0, 100);
            if ($rand > 97) {
                $key = 1;
                $key_i = "<img src='/style/images/body/key.png' alt=''/>1";
            } else {
                $key = 0;
            }
#-Награда за победу-#
#-Опыт-#
            $exp_no = rand((($all['level'] + 20) * 30), (($all['level'] + 80) * 60)); //Победа
#-Серебро-#
            $n1 = (($all['level'] * 20) + 800);
            $n2 = (($all['level'] * 100) + 1000);
            $silver_no = rand($n1, $n2);

#-Серебро и опыт-#
            if ($user['premium'] == 0) {
                $exp = $exp_no;
            }
            if ($user['premium'] == 1) {
                $exp = $exp_no * 2;
            }
            if ($user['premium'] == 2) {
                $exp = $exp_no * 3;
            }
            if ($user['premium'] == 1 or $user['premium'] == 2) {
                $exp_no_p = "<span class='white'><strike>$exp_no</strike></span> "; //Опыт без премиума
            }

            if ($user['premium'] == 0) {
                $silver = $silver_no;
            }
            if ($user['premium'] == 1) {
                $silver = round((($silver_no * 0.25) + $silver_no), 0);
            }
            if ($user['premium'] == 2) {
                $silver = round((($silver_no * 0.50) + $silver_no), 0);
            }
            if ($user['premium'] == 1 or $user['premium'] == 2) {
                $silver_no_p = "<span class='white'><strike>$silver_no</strike></span> "; //Серебро без премиума
            }

            $silver = bon_mon($silver);
            $exp = bon_exp($exp);

            #-ПОБЕДА-#
            if ($user_param >= $ank_param) {

#-ЕЖЕДНЕВНЫЕ ЗАДАНИЯ ДУЭЛИ-#
                $sel_tasks = $pdo->prepare("SELECT * FROM `daily_tasks` WHERE `user_id` = :user_id AND `type` = 2");
                $sel_tasks->execute(array(':user_id' => $user['id']));
                if ($sel_tasks->rowCount() != 0) {
                    $tasks = $sel_tasks->fetch(PDO::FETCH_LAZY);
#-Если еще не выполнено-#
                    if ($tasks['quatity'] < 9) {
                        $upd_tasks = $pdo->prepare("UPDATE `daily_tasks` SET `quatity` = :quatity WHERE `id` = :id LIMIT 1");
                        $upd_tasks->execute(array(':quatity' => $tasks['quatity'] + 1, ':id' => $tasks['id']));
                    } else {
                        if ($tasks['statys'] == 0) {
                            $exp_tasks = bon_exp(4500);
                            $silver_tasks = bon_mon(8000);
                            $gold_tasks = 5;
                            $tasks_q = 1;
                            $upd_tasks = $pdo->prepare("UPDATE `daily_tasks` SET `quatity` = :quatity, `statys` = 1 WHERE `user_id` = :user_id AND `type` = 2 LIMIT 1");
                            $upd_tasks->execute(array(':quatity' => $tasks['quatity'] + 1, ':user_id' => $user['id']));
                            $_SESSION['ok'] = '<b>Дуэли оффлайн</b>: <img src="/style/images/many/gold.png" alt=""/>5 <img src="/style/images/many/silver.png" alt=""/>8000 <img src="/style/images/user/exp.png" alt=""/>4500';
                        }
                    }
                }
                unset($_SESSION['duel_id']); //удаляем сессию
                $upd_users = $pdo->prepare("UPDATE `users` SET `exp` = :exp, `exp_clan` = :exp_clan, `silver` = :silver, `gold` = :gold, `duel_pobeda` = :duel_pobeda, `duel_t` = :duel_t, `key` = :key, `tasks` = :tasks WHERE `id` = :id LIMIT 1");
                $upd_users->execute(array(':exp' => $user['exp'] + $exp + $exp_tasks, ':exp_clan' => $exp + $exp_tasks, ':silver' => $user['silver'] + $silver + $silver_tasks, ':gold' => $user['gold'] + $gold_tasks, ':duel_pobeda' => $user['duel_pobeda'] + 1, ':duel_t' => $user['duel_t'] + 1, ':key' => $user['key'] + $key, ':tasks' => $user['tasks'] + $tasks_q, ':id' => $user['id']));
                header('Location: /duel_act?act=start');
                $_SESSION['notif'] = "<center><div class='pobeda'><span class='line_notif'><img src='/style/images/body/league.png' alt=''/> Победа <img src='/style/images/body/league.png' alt=''/></span></div><img src='/style/images/user/exp.png' alt=''/>$exp_no_p$exp <img src='/style/images/many/silver.png' alt=''/>$silver_no_p$silver $key_i<br/>Боев:  " . ($user['duel_b'] + 1) . " из " . floor($user['level'] / 2) . "</center>";
                exit();
            } else {
                #-ПОРАЖЕНИЕ-#
                unset($_SESSION['duel_id']); //удаляем сессию
                $upd_users = $pdo->prepare("UPDATE `users` SET `exp` = :exp, `exp_clan` = :exp_clan, `duel_progrash` = :duel_progrash, `key` = :key WHERE `id` = :id LIMIT 1");
                $upd_users->execute(array(':exp' => $user['exp'] + $exp, ':exp_clan' => $exp, ':duel_progrash' => $user['duel_progrash'] + 1, ':key' => $user['key'] + $key, ':id' => $user['id']));
                header('Location: /duel_act?act=start');
                $_SESSION['notif'] = "<center><div class='porashenie'><span class='line_notif'><img src='/style/images/body/league.png' alt=''/> Поражение <img src='/style/images/body/league.png' alt=''/></span></div><img src='/style/images/user/exp.png' alt=''/>$exp_no_p$exp $key_i<br/>Боев:  " . ($user['duel_b'] + 1) . " из " . floor($user['level'] / 2) . "</center>";
                exit();
            }
        } else {
            header('Location: /duel');
        }
}
?>