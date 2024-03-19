<?php
require_once '../../system/system.php';
require_once '../../avenax/Events.php';
require_once '../../copy/copy_func.php';
only_reg();
coliseum_level();

#-Атака в колизее-#
switch ($act) {
    case 'attc':
#-Проверка что игрок участвует в бою-#
        $sel_coliseum_me = $pdo->prepare("SELECT * FROM `coliseum` WHERE `user_id` = :user_id AND `statys` = 2");
        $sel_coliseum_me->execute(array(':user_id' => $user['id']));
        if ($sel_coliseum_me->rowCount() == 0)
            $error = 'Вы не участвуете в бою!';
        $coliseum_me = $sel_coliseum_me->fetch(PDO::FETCH_LAZY);
#-Данные боя оппонента-#


        $sel_coliseum_op = $pdo->prepare("SELECT * FROM `coliseum` WHERE `user_id` = :ank_id AND `statys` = 2");
        $sel_coliseum_op->execute(array(':ank_id' => $coliseum_me['ank_id']));
        if ($sel_coliseum_op->rowCount() == 0)
            $error = 'Ошибка данных оппонента!';
        $coliseum_op = $sel_coliseum_op->fetch(PDO::FETCH_LAZY);
#-Время задержки-#
        $t = ((int)(time() - $_SESSION["telecod_ip"]));
        if ($t < 3) {
            header('Location: /coliseum');
            $_SESSION['err'] = 'Слишком часто!';
            exit();
        }
        $_SESSION["telecod_ip"] = time();

#-Нет ошибок-#
        if (!isset($error)) {
            $col = new Base();
#-Данные оппонента-#
            $sel_users_op = $pdo->prepare("SELECT `id`, `avatar`, `pol`, `nick`, `level`, `sila`, `s_sila`, `sila_bonus`, `zashita`, `s_zashita`, `zashita_bonus`, `health`, `s_health`, `health_bonus`, `coliseum_pobeda`, `coliseum_progrash` FROM `users` WHERE `id` = :id");
            $sel_users_op->execute(array(':id' => $coliseum_me['ank_id']));
            $opponent = $sel_users_op->fetch(PDO::FETCH_LAZY);

            if (empty($opponent)) {
//                $opponent = array_merge($opponent, $col->infoBots($user));
                $opponent = $col->infoBots($user);
                $opponent['id'] = $coliseum_me['ank_id'];
            }
#-Урон игрока-#
            $v_user_uron = ((($user['sila'] + $user['s_sila'] + $user['sila_bonus']) * 0.3) * 1);
            $v_ank_zashita = ((($opponent['zashita'] + $opponent['s_zashita'] + $opponent['zashita_bonus']) * 0.4) * 1);
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
            $health_uron = $coliseum_op['user_t_health'] - $user_uron;

#-Награда за убийство оппонента-#
#-Опыт-#
            $exp_no = rand((($user['level'] + 10) * 10), (($user['level'] + 50) * 30));
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
#-Серебро-#
            $n1 = (($user['level'] * 20) + 200);
            $n2 = (($user['level'] * 50) + 400);
            $silver_no = rand($n1, $n2);
            if ($user['premium'] == 0) {
                $silver_me = $silver_no;
            }
            if ($user['premium'] == 1) {
                $silver_me = round((($silver_no * 0.25) + $silver_no), 0);
            }
            if ($user['premium'] == 2) {
                $silver_me = round((($silver_no * 0.50) + $silver_no), 0);
            }
            $silver_me = bon_mon($silver_me);

            // Проведи 15 поединков в Колизее
            $quest = fch("SELECT * FROM `quest` WHERE `type` = ?", array('coliseum_battle'));
            $user_quest = fch("SELECT * FROM `quest_user` WHERE `id_quest` = ? AND `id_user` = ?", array($quest['id'], $user['id']));
            if($user_quest['ok'] == 0){
                qry("UPDATE `quest_user` SET `kolls` = `kolls` + '1' WHERE `id` = ?", array($user_quest['id']));
            }

            #-ПОБЕДА-#
            if ($user_uron >= $coliseum_op['user_t_health']) {

                // Победи 6 раз в Колизее
                $quest = fch("SELECT * FROM `quest` WHERE `type` = ?", array('coliseum_wins'));
                $user_quest = fch("SELECT * FROM `quest_user` WHERE `id_quest` = ? AND `id_user` = ?", array($quest['id'], $user['id']));
                if($user_quest['ok'] == 0){
                    qry("UPDATE `quest_user` SET `kolls` = `kolls` + '1' WHERE `id` = ?", array($user_quest['id']));
                }

#-Убираем оппонента из боя-#
                $upd_coliseum_op = $pdo->prepare("UPDATE `coliseum` SET `user_health` = 0, `user_t_health` = 0, `statys` = 3 WHERE `user_id` = :ank_id");
                $upd_coliseum_op->execute(array(':ank_id' => $opponent['id']));
#-Засчитываем поражение оппоненту-#
                $upd_users_op = $pdo->prepare("UPDATE `users` SET `coliseum_progrash` = :coliseum_progrash WHERE `id` = :ank_id");
                $upd_users_op->execute(array(':coliseum_progrash' => $opponent['coliseum_progrash'] + 1, ':ank_id' => $opponent['id']));
                // event
                Events::randomItem('other', $opponent['id']);

#-Зачисляем награду победителю-#
                $upd_users_me = $pdo->prepare("UPDATE `users` SET `coliseum_pobeda` = :coliseum_pobeda, `coliseum_t` = :coliseum_t, `exp` = :exp, `silver` = :silver WHERE `id` = :user_id");
                $upd_users_me->execute(array(':coliseum_pobeda' => $user['coliseum_pobeda'] + 1, ':coliseum_t' => $user['coliseum_t'] + 1, ':exp' => $user['exp'] + $exp_me, ':silver' => $user['silver'] + $silver_me, ':user_id' => $user['id']));
                // event
                Events::randomItem('other', $user['id'], 'win');

#-Запись лога-#
                $ins_log = $pdo->prepare("INSERT INTO `coliseum_log` SET `battle_id` = :battle_id, `user_id` = :user_id, `log` = :log, `time` = :time");
                $ins_log->execute(array(':battle_id' => $coliseum_me['battle_id'], ':user_id' => $user['id'], ':log' => "<img src='/style/images/body/rip.png' alt=''/> $user[nick] убил(а) $opponent[nick]", ':time' => time()));

#-Кол-во живых участников-#
                $sel_coliseum_a = $pdo->prepare("SELECT * FROM `coliseum` WHERE `battle_id` = :battle_id AND `user_t_health` > 0 AND `user_id` != :user_id");
                $sel_coliseum_a->execute(array(':battle_id' => $coliseum_me['battle_id'], ':user_id' => $user['id']));
                if ($sel_coliseum_a->rowCount() > 0) {
                    $coliseum_a = $sel_coliseum_a->fetch(PDO::FETCH_LAZY);
#-Добавляем ранг текущему игроку-#
                    $upd_coliseum_me = $pdo->prepare("UPDATE `coliseum` SET `ank_id` = :ank_id, `rang` = `rang` + 1, `exp` = `exp` + :exp, `silver` = `silver` + :silver WHERE `user_id` = :user_id");
                    $upd_coliseum_me->execute(array(':ank_id' => $coliseum_a['user_id'], ':exp' => $exp_me, ':silver' => $silver_me, ':user_id' => $user['id']));
                } else {
#-Убираем игрока из боя-#
                    $upd_coliseum_me = $pdo->prepare("UPDATE `coliseum` SET `statys` = 3, `rang` = `rang` + 1, `exp` = `exp` + :exp, `silver` = `silver` + :silver WHERE `user_id` = :user_id");
                    $upd_coliseum_me->execute(array(':exp' => $exp_me, ':silver' => $silver_me, ':user_id' => $user['id']));
#-Удаляем лог-#
                    $del_log = $pdo->prepare("DELETE FROM `coliseum_log` WHERE `battle_id` = :battle_id");
                    $del_log->execute(array(':battle_id' => $coliseum_me['battle_id']));
                }
                header('Location: /coliseum');
                exit();

            } else {
#-Минус здоровья у оппонента-#
//                echo '<pre>';
//                print_r($opponent);
//                exit();

                $upd_coliseum = $pdo->prepare("UPDATE `coliseum` SET `user_health` = :user_health, `user_t_health` = :user_t_health WHERE `user_id` = :ank_id");
                $upd_coliseum->execute(array(':user_health' => $coliseum_op['user_t_health'], ':user_t_health' => $health_uron, ':ank_id' => $opponent['id']));
#-Запись лога-#
                if ($user['pol'] == 1) {
                    $at = 'нанёс';
                } else {
                    $at = 'нанесла';
                }
                $ins_log = $pdo->prepare("INSERT INTO `coliseum_log` SET `battle_id` = :battle_id, `user_id` = :user_id, `log` = :log, `time` = :time");
                $ins_log->execute(array(':battle_id' => $coliseum_me['battle_id'], ':user_id' => $user['id'], ':log' => "<img src='/style/images/body/attack.png' alt=''/> $user[nick] $at $user_uron урона $opponent[nick]", ':time' => time()));

                if (!empty($opponent['type'])) {

                    $myDamage = $col->randDamage($user_uron, $user['pol']);
                    $updColiseumMe = $pdo->prepare("UPDATE `coliseum` SET `user_health` = :user_health, `user_t_health` = :user_t_health WHERE `user_id` = :ank_id");
                    $updColiseumMe->execute(array(':user_health' => $coliseum_me->user_t_health, ':user_t_health' => $health_uron, ':ank_id' => $coliseum_me->user_id));
                    if ($opponent['pol'] == 1) {
                        $ats = 'нанёс';
                    } else {
                        $ats = 'нанесла';
                    }
                    $ins_log = $pdo->prepare("INSERT INTO `coliseum_log` SET `battle_id` = :battle_id, `user_id` = :user_id, `log` = :log, `time` = :time");
                    $ins_log->execute(array(':battle_id' => $coliseum_me['battle_id'], ':user_id' => $opponent['id'], ':log' => "<img src='/style/images/body/attack.png' alt=''/> $opponent[nick] $ats $myDamage урона $user[nick]", ':time' => time()));


                }
                header('Location: /coliseum');
                exit();
            }
        } else {
            header('Location: /coliseum');
            $_SESSION['err'] = $error;
            exit();
        }
}
?>