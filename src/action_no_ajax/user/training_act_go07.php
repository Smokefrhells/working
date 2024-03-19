<?php
require_once '../../system/system.php';
require_once '../../copy/copy_func.php';
only_reg();
require_once H . 'avenax/.settings.php';
/*Тренировка*/

#-Акция тренировка-#
$sel_stock = $pdo->query("SELECT * FROM `stock` WHERE `type` = 4");
$stock = $sel_stock->fetch(PDO::FETCH_LAZY);
#-Сколько прибавлять-#
$levelUp = COUNT_TRAINING;

#-Тренировка силы-#
switch ($act) {
    case 'sila':
        if ($user['start'] >= 5) {
            #-Обучение-#
            if ($user['start'] == 5 and $user['level_sila'] == 0 and $user['level_zashita'] >= 1 and $user['level_health'] >= 1) {
                $upd_users = $pdo->prepare("UPDATE `users` SET `start` = 6 WHERE `id` = :user_id LIMIT 1");
                $upd_users->execute(array(':user_id' => $user['id']));
            }

            #-Уровень прокачки не должен быть больше 200-#
            if ($user['level_sila'] < 200) {


                $strPrice = priceTraining($user['level_sila']);
                if (isset($strPrice->s)) {
                    $many_sila = $strPrice->s;
                    $sila_img = 'silver';
                    $err = 'Недостаточно серебра!';
                } else {
                    $many_sila = $strPrice->g;
                    $sila_img = 'gold';
                    $err = 'Недостаточно золота!';
                }

                if ($sel_stock->rowCount() == true) {
                    $many_sila = round(($many_sila - (($many_sila * $stock['prosent']) / 100)), 0);
                }

                if ($user[$sila_img] >= $many_sila) {
                    $upd_users = $pdo->prepare("UPDATE `users` SET `sila` = :sila, `{$sila_img}` = :gold, `level_sila` = :level_sila WHERE `id` = :id");
                    $upd_users->execute(array(':sila' => $user['sila'] + $levelUp, ':gold' => $user[$sila_img] - $many_sila, 'level_sila' => $user['level_sila'] + 1, ':id' => $user['id']));

                    $quest = fch("SELECT * FROM `quest` WHERE `type` = ?", array('param_up'));
                    $user_quest = fch("SELECT * FROM `quest_user` WHERE `id_quest` = ? AND `id_user` = ?", array($quest['id'], $user['id']));
                    if($user_quest['ok'] == 0){
                        qry("UPDATE `quest_user` SET `kolls` = `kolls` + '1' WHERE `id` = ?", array($user_quest['id']));
                    }

                    header('Location: /training');
                    exit();
                } else {
                    header('Location: /training');
                    $_SESSION['err'] = $err;
                    exit();
                }
            } else {
                header('Location: /training');
                $_SESSION['err'] = 'Сила прокачана на максимум!';
                exit();
            }
        } else {
            header('Location: /training');
            exit();
        }
        break;
}

#-Тренировка защиты-#
switch ($act) {
    case 'zashita':
        if ($user['start'] >= 5) {
#-Обучение-#
            if ($user['start'] == 5 and $user['level_sila'] >= 1 and $user['level_zashita'] == 0 and $user['level_health'] >= 1) {
                $upd_users = $pdo->prepare("UPDATE `users` SET `start` = 6 WHERE `id` = :user_id LIMIT 1");
                $upd_users->execute(array(':user_id' => $user['id']));
            }

#-Уровень прокачки не должен быть больше 200-#
            if ($user['level_zashita'] < 200) {


                $strPrice = priceTraining($user['level_zashita']);
                if (isset($strPrice->s)) {
                    $many_sila = $strPrice->s;
                    $sila_img = 'silver';
                    $err = 'Недостаточно серебра!';
                } else {
                    $many_sila = $strPrice->g;
                    $sila_img = 'gold';
                    $err = 'Недостаточно золота!';
                }

                if ($sel_stock->rowCount() == true) {
                    $many_sila = round(($many_sila - (($many_sila * $stock['prosent']) / 100)), 0);
                }

                if ($user[$sila_img] >= $many_sila) {
                    $upd_users = $pdo->prepare("UPDATE `users` SET `zashita` = :zashita, `{$sila_img}` = :gold, `level_zashita` = :level_zashita WHERE `id` = :id");
                    $upd_users->execute(array(':zashita' => $user['zashita'] + $levelUp, ':gold' => $user[$sila_img] - $many_sila, 'level_zashita' => $user['level_zashita'] + 1, ':id' => $user['id']));
                    $quest = fch("SELECT * FROM `quest` WHERE `type` = ?", array('param_up'));
                    $user_quest = fch("SELECT * FROM `quest_user` WHERE `id_quest` = ? AND `id_user` = ?", array($quest['id'], $user['id']));
                    if($user_quest['ok'] == 0){
                        qry("UPDATE `quest_user` SET `kolls` = `kolls` + '1' WHERE `id` = ?", array($user_quest['id']));
                    }
                    header('Location: /training');
                    exit();
                } else {
                    header('Location: /training');
                    $_SESSION['err'] = $err;
                    exit();
                }

            } else {
                header('Location: /training');
                $_SESSION['err'] = 'Защита прокачана на максимум!';
                exit();
            }
        } else {
            header('Location: /training');
            exit();
        }
        break;
}


#-Тренировка здоровья-#
switch ($act) {
    case 'health':
        if ($user['start'] >= 5) {
#-Обучение-#
            if ($user['start'] == 5 and $user['level_sila'] >= 1 and $user['level_zashita'] >= 1 and $user['level_health'] == 0) {
                $upd_users = $pdo->prepare("UPDATE `users` SET `start` = 6 WHERE `id` = :user_id LIMIT 1");
                $upd_users->execute(array(':user_id' => $user['id']));
            }

            if ($user['level_health'] < 200) {
                $strPrice = priceTraining($user['level_health']);
                if (isset($strPrice->s)) {
                    $many_sila = $strPrice->s;
                    $sila_img = 'silver';
                    $err = 'Недостаточно серебра!';
                } else {
                    $many_sila = $strPrice->g;
                    $sila_img = 'gold';
                    $err = 'Недостаточно золота!';
                }

                if ($sel_stock->rowCount() == true) {
                    $many_sila = round(($many_sila - (($many_sila * $stock['prosent']) / 100)), 0);
                }

                if ($user[$sila_img] >= $many_sila) {
                    $upd_users = $pdo->prepare("UPDATE `users` SET `health` = :health, `{$sila_img}` = :gold, `level_health` = :level_health WHERE `id` = :id");
                    $upd_users->execute(array(':health' => $user['health'] + $levelUp, ':gold' => $user[$sila_img] - $many_sila, 'level_health' => $user['level_health'] + 1, ':id' => $user['id']));
                    $quest = fch("SELECT * FROM `quest` WHERE `type` = ?", array('param_up'));
                    $user_quest = fch("SELECT * FROM `quest_user` WHERE `id_quest` = ? AND `id_user` = ?", array($quest['id'], $user['id']));
                    if($user_quest['ok'] == 0){
                        qry("UPDATE `quest_user` SET `kolls` = `kolls` + '1' WHERE `id` = ?", array($user_quest['id']));
                    }
                    header('Location: /training');
                    exit();
                } else {
                    header('Location: /training');
                    $_SESSION['err'] = $err;
                    exit();
                }
            } else {
                header('Location: /training');
                $_SESSION['err'] = 'Здоровье прокачано на максимум!';
                exit();
            }
        } else {
            header('Location: /training');
        }
        break;
}