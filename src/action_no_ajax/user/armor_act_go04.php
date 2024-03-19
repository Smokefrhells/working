<?php
require_once '../../system/system.php';
only_reg();

#-Надеваем снаряжение-#
switch ($act) {
    case 'nadet':
        if (isset($_GET['id']) and isset($_GET['me_id'])) {
            $id = check($_GET['id']);
            $me_id = check($_GET['me_id']);
            #-Проверяем ввод цифры-#
            if (!preg_match('/^([0-9])+$/u', $_GET['id']))
                $error = 'Введите цифру!';
            #-Выборка данных о доспехе-#
            $sel_weapon_me = $pdo->prepare("SELECT * FROM `weapon_me` WHERE `id` = :me_id AND `weapon_id` = :weapon_id AND `user_id` = :user_id AND `auction` = 0");
            $sel_weapon_me->execute([':me_id' => $me_id, ':weapon_id' => $id, ':user_id' => $user['id']]);
            #-Только если есть такой доспех-#
            if ($sel_weapon_me->rowCount() != 0) {
                #-Если нет ошибок-#
                if (!isset($error)) {
                    $weapon_me = $sel_weapon_me->fetch(PDO::FETCH_LAZY);
                    #-Во время боя нельзя-#
                    if ($user['battle'] == 0) {
                        #-Выборка параметров-#
                        $sel_weapon = $pdo->prepare("SELECT * FROM `weapon` WHERE `id` = :weapon_id");
                        $sel_weapon->execute([':weapon_id' => $weapon_me['weapon_id']]);
                        $weapon = $sel_weapon->fetch(PDO::FETCH_LAZY);
                        #-Уровень больше или равен нашему-#
                        if ($user['level'] >= $weapon['level']) {

                            #-Снимаем доспех который надет-#
                            $sel_weapon_me2 = $pdo->prepare("SELECT * FROM `weapon_me` WHERE `type` = :type AND `user_id` = :user_id AND `state` = :state");
                            $sel_weapon_me2->execute([':type' => $weapon['type'], ':user_id' => $user['id'], ':state' => 1]);
                            if ($sel_weapon_me2->rowCount() != 0) {
                                $weapon_me2 = $sel_weapon_me2->fetch(PDO::FETCH_LAZY);
                                $upd_weapon_me = $pdo->prepare("UPDATE `weapon_me` SET `state` = :state WHERE `id` = :id LIMIT 1");
                                $upd_weapon_me->execute([':state' => 0, ':id' => $weapon_me2['id']]);
                                #-Выборка параметров надетого снаряжения-#
                                $sel_weapon2 = $pdo->prepare("SELECT * FROM `weapon` WHERE `id` = :id");
                                $sel_weapon2->execute([':id' => $weapon_me2['weapon_id']]);
                                $weapon2 = $sel_weapon2->fetch(PDO::FETCH_LAZY);
                                #-Сила-#
                                $sila_m = $user['s_sila'] - ($weapon2['sila'] + $weapon_me2['b_sila'] + $weapon_me2['runa']);
                                $sila = $sila_m + ($weapon['sila'] + $weapon_me['b_sila'] + $weapon_me['runa']);
                                #-Защита-#
                                $zashita_m = $user['s_zashita'] - ($weapon2['zashita'] + $weapon_me2['b_zashita'] + $weapon_me2['runa']);
                                $zashita = $zashita_m + ($weapon['zashita'] + $weapon_me['b_zashita'] + $weapon_me['runa']);
                                #-Здоровье-#
                                $health_m = $user['s_health'] - ($weapon2['health'] + $weapon_me2['b_health'] + $weapon_me2['runa']);
                                $health = $health_m + ($weapon['health'] + $weapon_me['b_health'] + $weapon_me['runa']);
                            } else {
                                $sila = $user['s_sila'] + ($weapon['sila'] + $weapon_me['b_sila'] + $weapon_me['runa']);
                                $zashita = $user['s_zashita'] + ($weapon['zashita'] + $weapon_me['b_zashita'] + $weapon_me['runa']);
                                $health = $user['s_health'] + ($weapon['health'] + $weapon_me['b_health'] + $weapon_me['runa']);
                            }
                            #-Обучение-#
                            if ($user['start'] == 4 and $weapon['id'] != 5) {
                                $upd_users = $pdo->prepare("UPDATE `users` SET `start` = 5 WHERE `id` = :user_id LIMIT 1");
                                $upd_users->execute([':user_id' => $user['id']]);
                            }
                            #-Надеваем новый доспех-#
                            $upd_users2 = $pdo->prepare("UPDATE `users` SET `s_sila` = :s_sila, `s_zashita` = :s_zashita, `s_health` = :s_health WHERE `id` = :id LIMIT 1");
                            $upd_users2->execute([':s_sila' => $sila, ':s_zashita' => $zashita, ':s_health' => $health, ':id' => $user['id']]);
                            $upd_weapon_me2 = $pdo->prepare("UPDATE `weapon_me` SET `state` = :state WHERE `id` = :id LIMIT 1");
                            $upd_weapon_me2->execute([':state' => 1, ':id' => $weapon_me['id']]);
                            header("Location: /weapon_me/$user[id]/?type=$weapon_me[type]");
                        } else {
                            header("Location: /weapon_me/$user[id]/?type=$weapon_me[type]");
                        }
                    } else {
                        header("Location: /weapon_me/$user[id]/?type=$weapon_me[type]");
                        $_SESSION['err'] = 'Во время боя действие невозможно!';
                        exit();
                    }
                } else {
                    header("Location: /weapon_me/$user[id]/?type=$weapon_me[type]");
                    $_SESSION['err'] = $error;
                    exit();
                }
            } else {
                header("Location: /weapon_me/$user[id]/?type=$weapon_me[type]");
                $_SESSION['err'] = 'Ошибка!';
                exit();
            }
        } else {
            header("Location: /weapon_me/$user[id]/?type=$weapon_me[type]");
            $_SESSION['err'] = 'Неверный идентификатор!';
            exit();
        }
}
switch ($act) {
    case 'nadet2':
        if (isset($_GET['id']) and isset($_GET['me_id'])) {
            $id = check($_GET['id']);
            $me_id = check($_GET['me_id']);
            #-Проверяем ввод цифры-#
            if (!preg_match('/^([0-9])+$/u', $_GET['id']))
                $error = 'Введите цифру!';
            #-Выборка данных о доспехе-#
            $sel_weapon_me = $pdo->prepare("SELECT * FROM `weapon_me` WHERE `id` = :me_id AND `weapon_id` = :weapon_id AND `user_id` = :user_id AND `auction` = 0");
            $sel_weapon_me->execute([':me_id' => $me_id, ':weapon_id' => $id, ':user_id' => $user['id']]);
            #-Только если есть такой доспех-#
            if ($sel_weapon_me->rowCount() != 0) {
                #-Если нет ошибок-#
                if (!isset($error)) {
                    $weapon_me = $sel_weapon_me->fetch(PDO::FETCH_LAZY);
                    #-Во время боя нельзя-#
                    if ($user['battle'] == 0) {
                        #-Выборка параметров-#
                        $sel_weapon = $pdo->prepare("SELECT * FROM `weapon` WHERE `id` = :weapon_id");
                        $sel_weapon->execute([':weapon_id' => $weapon_me['weapon_id']]);
                        $weapon = $sel_weapon->fetch(PDO::FETCH_LAZY);
                        #-Уровень больше или равен нашему-#
                        if ($user['level'] >= $weapon['level']) {
                            #-Снимаем доспех который надет-#
                            $sel_weapon_me2 = $pdo->prepare("SELECT * FROM `weapon_me` WHERE `type` = :type AND `user_id` = :user_id AND `state` = :state");
                            $sel_weapon_me2->execute([':type' => $weapon['type'], ':user_id' => $user['id'], ':state' => 1]);
                            if ($sel_weapon_me2->rowCount() != 0) {
                                $weapon_me2 = $sel_weapon_me2->fetch(PDO::FETCH_LAZY);
                                $upd_weapon_me = $pdo->prepare("UPDATE `weapon_me` SET `state` = :state WHERE `id` = :id LIMIT 1");
                                $upd_weapon_me->execute([':state' => 0, ':id' => $weapon_me2['id']]);
                                #-Выборка параметров надетого снаряжения-#
                                $sel_weapon2 = $pdo->prepare("SELECT * FROM `weapon` WHERE `id` = :id");
                                $sel_weapon2->execute([':id' => $weapon_me2['weapon_id']]);
                                $weapon2 = $sel_weapon2->fetch(PDO::FETCH_LAZY);
                                #-Сила-#
                                $sila_m = $user['s_sila'] - ($weapon2['sila'] + $weapon_me2['b_sila'] + $weapon_me2['runa']);
                                $sila = $sila_m + ($weapon['sila'] + $weapon_me['b_sila'] + $weapon_me['runa']);
                                #-Защита-#
                                $zashita_m = $user['s_zashita'] - ($weapon2['zashita'] + $weapon_me2['b_zashita'] + $weapon_me2['runa']);
                                $zashita = $zashita_m + ($weapon['zashita'] + $weapon_me['b_zashita'] + $weapon_me['runa']);
                                #-Здоровье-#
                                $health_m = $user['s_health'] - ($weapon2['health'] + $weapon_me2['b_health'] + $weapon_me2['runa']);
                                $health = $health_m + ($weapon['health'] + $weapon_me['b_health'] + $weapon_me['runa']);
                            } else {
                                $sila = $user['s_sila'] + ($weapon['sila'] + $weapon_me['b_sila'] + $weapon_me['runa']);
                                $zashita = $user['s_zashita'] + ($weapon['zashita'] + $weapon_me['b_zashita'] + $weapon_me['runa']);
                                $health = $user['s_health'] + ($weapon['health'] + $weapon_me['b_health'] + $weapon_me['runa']);
                            }
                            #-Обучение-#
                            if ($user['start'] == 4 and $weapon['id'] != 5) {
                                $upd_users = $pdo->prepare("UPDATE `users` SET `start` = 5 WHERE `id` = :user_id LIMIT 1");
                                $upd_users->execute([':user_id' => $user['id']]);
                            }
                            #-Надеваем новый доспех-#
                            $upd_users2 = $pdo->prepare("UPDATE `users` SET `s_sila` = :s_sila, `s_zashita` = :s_zashita, `s_health` = :s_health WHERE `id` = :id LIMIT 1");
                            $upd_users2->execute([':s_sila' => $sila, ':s_zashita' => $zashita, ':s_health' => $health, ':id' => $user['id']]);
                            $upd_weapon_me2 = $pdo->prepare("UPDATE `weapon_me` SET `state` = :state WHERE `id` = :id LIMIT 1");
                            $upd_weapon_me2->execute([':state' => 1, ':id' => $weapon_me['id']]);
                            header("Location: /bag");
                        } else {
                            header("Location: /bag");
                        }
                    } else {
                        header('Location: /bag');
                        $_SESSION['err'] = 'Во время боя это действие невозможно!';
                        exit();
                    }
                } else {
                    header("Location: /bag");
                    $_SESSION['err'] = $error;
                    exit();
                }
            } else {
                header("Location: /bag");
                $_SESSION['err'] = 'Ошибка!';
                exit();
            }
        } else {
            header("Location: /bag");
            $_SESSION['err'] = 'Неверный идентификатор!';
            exit();
        }
}


/*Снимаем снаряжение*/
switch ($act) {
    case 'snat':
        if (isset($_GET['id']) and isset($_GET['me_id'])) {
            $id = check($_GET['id']);
            $me_id = check($_GET['me_id']);
            #-Проверяем ввод цифры-#
            if (!preg_match('/^([0-9])+$/u', $_GET['id']))
                $error = 'Введите цифру!';
            #-Выборка данных о доспехе-#
            $sel_weapon_me = $pdo->prepare("SELECT * FROM `weapon_me` WHERE `id` = :me_id AND `weapon_id` = :weapon_id AND `user_id` = :user_id AND `auction` = 0");
            $sel_weapon_me->execute([':me_id' => $me_id, ':weapon_id' => $id, ':user_id' => $user['id']]);
            #-Только если есть такой доспех-#
            if ($sel_weapon_me->rowCount() != 0) {
                #-Если нет ошибок-#
                if (!isset($error)) {
                    $weapon_me = $sel_weapon_me->fetch(PDO::FETCH_LAZY);
                    #-Во время боя нельзя-#
                    if ($user['battle'] == 0) {
                        #-Выборка параметров-#
                        $sel_weapon = $pdo->prepare("SELECT * FROM `weapon` WHERE `id` = :weapon_id");
                        $sel_weapon->execute([':weapon_id' => $weapon_me['weapon_id']]);
                        $weapon = $sel_weapon->fetch(PDO::FETCH_LAZY);
                        $upd_users = $pdo->prepare("UPDATE `users` SET `s_sila` = :s_sila, `s_zashita` = :s_zashita, `s_health` = :s_health WHERE `id` = :id LIMIT 1");
                        $upd_users->execute([':s_sila' => $user['s_sila'] - ($weapon['sila'] + $weapon_me['b_sila'] + $weapon_me['runa']), ':s_zashita' => $user['s_zashita'] - ($weapon['zashita'] + $weapon_me['b_zashita'] + $weapon_me['runa']), ':s_health' => $user['s_health'] - ($weapon['health'] + $weapon_me['b_health'] + $weapon_me['runa']), ':id' => $user['id']]);
                        $upd_weapon_me = $pdo->prepare("UPDATE `weapon_me` SET `state` = :state WHERE `id` = :id LIMIT 1");
                        $upd_weapon_me->execute([':state' => 0, ':id' => $weapon_me['id']]);
                        header("Location: /weapon_me/$user[id]/?type=$weapon_me[type]");
                    } else {
                        header("Location: /weapon_me/$user[id]/?type=$weapon_me[type]");
                        $_SESSION['err'] = 'Во время боя это действие невозможно!';
                        exit();
                    }
                } else {
                    header("Location: /weapon_me/$user[id]/?type=$weapon_me[type]");
                }
            } else {
                header("Location: /weapon_me/$user[id]/?type=$weapon_me[type]");
                $_SESSION['err'] = $error;
                exit();
            }
        } else {
            header("Location: /weapon_me/$user[id]/?type=$weapon_me[type]");
            $_SESSION['err'] = 'Ошибка!';
            exit();
        }
}


/*Продажа снаряжения*/
switch ($act) {
    case 'sell':
        if (isset($_GET['id'])) {
            $id = check($_GET['id']);
            #-Проверяем ввод цифры-#
            if (!preg_match('/^([0-9])+$/u', $_GET['id']))
                $error = 'Введите цифру!';
            #-Выборка данных о доспехе-#
            $sel_weapon_me = $pdo->prepare("SELECT * FROM `weapon_me` WHERE `id` = :id AND `user_id` = :user_id AND `auction` = 0");
            $sel_weapon_me->execute([':id' => $id, ':user_id' => $user['id']]);
            #-Только если есть такой доспех-#
            if ($sel_weapon_me->rowCount() != 0) {
                #-Если нет ошибок-#
                if (!isset($error)) {
                    $weapon_me = $sel_weapon_me->fetch(PDO::FETCH_LAZY);
                    #-Выборка оружия и его цены-#
                    $sel_weapon = $pdo->prepare("SELECT * FROM `weapon` WHERE `id` = :weapon_id");
                    $sel_weapon->execute([':weapon_id' => $weapon_me['weapon_id']]);
                    $weapon = $sel_weapon->fetch(PDO::FETCH_LAZY);
                    #-Цена-#
                    $crystal = round((($weapon['gold'] * 2) + 50) + ($weapon_me['b_sila'] / 4) * 8, 0);
                    if ($weapon_me['state'] == 0) {
                        $upd_users = $pdo->prepare("UPDATE `users` SET `crystal` = :crystal WHERE `id` = :id");
                        $upd_users->execute([':crystal' => $user['crystal'] + $crystal, ':id' => $user['id']]);
                        $del_weapon_me = $pdo->prepare("DELETE FROM `weapon_me` WHERE `id` = :id AND `user_id` = :user_id");
                        $del_weapon_me->execute([':id' => $weapon_me['id'], ':user_id' => $user['id']]);

                        #-Убираем руну если стоит-#
                        $sel_weapon_ru = $pdo->prepare("SELECT * FROM `weapon_runa` WHERE `user_id` = :user_id AND `weapon_id` = :weapon_id");
                        $sel_weapon_ru->execute([':user_id' => $user['id'], ':weapon_id' => $weapon_me['id']]);
                        if ($sel_weapon_ru->rowCount() != 0) {
                            $weapon_ru = $sel_weapon_ru->fetch(PDO::FETCH_LAZY);
                            $upd_weapon_ru = $pdo->prepare("UPDATE `weapon_runa` SET `weapon_id` = 0 WHERE `id` = :id LIMIT 1");
                            $upd_weapon_ru->execute([':id' => $weapon_ru['id']]);
                        }
                        header('Location: /bag');
                        $_SESSION['ok'] = 'Продано!';
                        exit();
                    } else {
                        header('Location: /bag');
                        $_SESSION['err'] = 'Снаряжение сейчас надето!';
                        exit();
                    }
                } else {
                    header('Location: /bag');
                    $_SESSION['err'] = $error;
                    exit();
                }
            } else {
                header('Location: /bag');
                $_SESSION['err'] = 'Ошибка!';
                exit();
            }
        } else {
            header('Location: /bag');
            $_SESSION['err'] = 'Неверный идентификатор!';
            exit();
        }
}


/*Продажа всего снаряжения*/
switch ($act) {
    case 'sell_all':
        if (isset($_GET['type_s'])) {
            $type_s = check($_GET['type_s']);
            #-Выборка данных всего снаряжения-#
            $sel_weapon_me = $pdo->prepare("SELECT * FROM `weapon_me` WHERE `user_id` = :user_id AND `state` = 0 AND `type` = :type AND `auction` = 0");
            $sel_weapon_me->execute([':type' => $type_s, ':user_id' => $user['id']]);
        } else {
            $sel_weapon_me = $pdo->prepare("SELECT * FROM `weapon_me` WHERE `user_id` = :user_id AND `state` = 0 AND `auction` = 0");
            $sel_weapon_me->execute([':user_id' => $user['id']]);
        }

        #-Только если есть снаряжение-#
        if ($sel_weapon_me->rowCount() != 0) {
            while ($weapon_me = $sel_weapon_me->fetch(PDO::FETCH_LAZY)) {
                #-Выборка оружия и его цены-#
                $sel_weapon = $pdo->prepare("SELECT * FROM `weapon` WHERE `id` = :weapon_id");
                $sel_weapon->execute([':weapon_id' => $weapon_me['weapon_id']]);
                $weapon = $sel_weapon->fetch(PDO::FETCH_LAZY);
                #-Цена-#
                $crystal = round((($weapon['gold'] * 2) + 50) + ($weapon_me['b_sila'] / 4) * 8, 0);
                $many = $many + $crystal;
                $upd_users = $pdo->prepare("UPDATE `users` SET `crystal` = :crystal WHERE `id` = :id");
                $upd_users->execute([':crystal' => $user['crystal'] + $many, ':id' => $user['id']]);
                $del_weapon_me = $pdo->prepare("DELETE FROM `weapon_me` WHERE `id` = :id AND `user_id` = :user_id");
                $del_weapon_me->execute([':id' => $weapon_me['id'], ':user_id' => $user['id']]);

                #-Убираем руну если стоит-#
                $sel_weapon_ru = $pdo->prepare("SELECT * FROM `weapon_runa` WHERE `user_id` = :user_id AND `weapon_id` = :weapon_id");
                $sel_weapon_ru->execute([':user_id' => $user['id'], ':weapon_id' => $weapon_me['id']]);
                if ($sel_weapon_ru->rowCount() != 0) {
                    $weapon_ru = $sel_weapon_ru->fetch(PDO::FETCH_LAZY);
                    $upd_weapon_ru = $pdo->prepare("UPDATE `weapon_runa` SET `weapon_id` = 0 WHERE `id` = :id LIMIT 1");
                    $upd_weapon_ru->execute([':id' => $weapon_ru['id']]);
                }
            }
            header('Location: /bag');
            $_SESSION['ok'] = "Продано за <img src='/style/images/many/crystal.png' alt=''/>$many!";
            exit();
        } else {
            header('Location: /bag');
            $_SESSION['err'] = 'Ошибка!';
            exit();
        }
}
?>