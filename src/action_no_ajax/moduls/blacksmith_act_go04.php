<?php
require_once '../../system/system.php';
echo only_reg();
echo blacksmith_level();

#-Заточка снаряжения-#
switch ($act) {
    case 'zatoshka':
        if (isset($_GET['id'])) {
            $id = check($_GET['id']);
#-Скидкана заточку-#
            $sel_stock = $pdo->query("SELECT * FROM `stock` WHERE `type` = 5");
            if ($sel_stock->rowCount() != 0) {
                $stock = $sel_stock->fetch(PDO::FETCH_LAZY);
            }
#-Выборка данных о доспехе-#
            $sel_weapon_me = $pdo->prepare("SELECT * FROM `weapon_me` WHERE `id` = :id AND `user_id` = :user_id AND `state` = 0");
            $sel_weapon_me->execute(array(':id' => $id, ':user_id' => $user['id']));
            if ($sel_weapon_me->rowCount() == 0)
                $error = 'Доспех не найден или одет!';
#-Если нет ошибок-#
            if (!isset($error)) {
                $weapon_me = $sel_weapon_me->fetch(PDO::FETCH_LAZY);
                $sel_weapon = $pdo->prepare("SELECT * FROM `weapon` WHERE `id` = :id");
                $sel_weapon->execute(array(':id' => $weapon_me['weapon_id']));
                $weapon = $sel_weapon->fetch(PDO::FETCH_LAZY);
#-Максимальный уровень-#
                if ($weapon_me['max_level'] != 100) {
#-Золото или кристаллы-#
                    if ($weapon_me['b_level'] == 5) {
                        if ($sel_stock->rowCount() == 0) {
                            $many_gold = round(((($weapon['sila'] + $weapon['zashita'] + $weapon['health']) / 2000) * $weapon_me['max_level']), 0); //Золото
                        } else {
                            $gold = round(((($weapon['sila'] + $weapon['zashita'] + $weapon['health']) / 2000) * $weapon_me['max_level']), 0); //Золото
                            $many_gold = round(($gold - (($gold * $stock['prosent']) / 100)), 0);
                        }
                        if ($user['gold'] >= $many_gold) {
#-Минусуем золото-#
                            $upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold WHERE `id` = :id LIMIT 1");
                            $upd_users->execute(array(':gold' => $user['gold'] - $many_gold, ':id' => $user['id']));
#-Записываем параметры-#
                            $upd_weapon_me = $pdo->prepare("UPDATE `weapon_me` SET `b_sila` = :b_sila, `b_zashita` = :b_zashita, `b_health` = :b_health, `b_level` = 1, `max_level` = :max_level WHERE `id` = :id LIMIT 1");
                            $upd_weapon_me->execute(array(':b_sila' => $weapon_me['b_sila'] + (($weapon_me['max_level'] * 2) + $weapon['kashestvo']), ':b_zashita' => $weapon_me['b_zashita'] + (($weapon_me['max_level'] * 2) + $weapon['kashestvo']), ':b_health' => $weapon_me['b_health'] + (($weapon_me['max_level'] * 2) + $weapon['kashestvo']), ':max_level' => $weapon_me['max_level'] + 1, ':id' => $weapon_me['id']));
                        } else {
                            header("Location: /blacksmith?page=$_GET[page]");
                            $_SESSION['err'] = 'Недостаточно золота!';
                            exit();
                        }
                    } else {
                        if ($sel_stock->rowCount() == 0) {
                            $many_crystal = round(((($weapon['sila'] + $weapon['zashita'] + $weapon['health']) / 8) * $weapon_me['max_level']), 0); //Кристаллы
                        } else {
                            $crystal = round(((($weapon['sila'] + $weapon['zashita'] + $weapon['health']) / 8) * $weapon_me['max_level']), 0); //Кристаллы
                            $many_crystal = round(($crystal - (($crystal * $stock['prosent']) / 100)), 0);
                        }
                        if ($user['crystal'] >= $many_crystal) {
#-Минусуем кристаллы-#
                            $upd_users = $pdo->prepare("UPDATE `users` SET `crystal` = :crystal WHERE `id` = :id LIMIT 1");
                            $upd_users->execute(array(':crystal' => $user['crystal'] - $many_crystal, ':id' => $user['id']));
#-Записываем параметры-#
                            $upd_weapon_me = $pdo->prepare("UPDATE `weapon_me` SET `b_sila` = :b_sila, `b_zashita` = :b_zashita, `b_health` = :b_health, `b_level` = :b_level, `max_level` = :max_level WHERE `id` = :id LIMIT 1");
                            $upd_weapon_me->execute(array(':b_sila' => $weapon_me['b_sila'] + (($weapon_me['max_level'] * 2) + $weapon['kashestvo']), ':b_zashita' => $weapon_me['b_zashita'] + (($weapon_me['max_level'] * 2) + $weapon['kashestvo']), ':b_health' => $weapon_me['b_health'] + (($weapon_me['max_level'] * 2) + $weapon['kashestvo']), 'b_level' => $weapon_me['b_level'] + 1, ':max_level' => $weapon_me['max_level'] + 1, ':id' => $weapon_me['id']));
                        } else {
                            header("Location: /blacksmith?page=$_GET[page]");
                            $_SESSION['err'] = 'Недостаточно кристаллов!';
                            exit();
                        }
                    }
                    header("Location: /blacksmith?page=$_GET[page]");
                } else {
                    header("Location: /blacksmith?page=$_GET[page]");
                    $_SESSION['err'] = 'Максимальная заточка!';
                    exit();
                }
            } else {
                header("Location: /blacksmith?page=$_GET[page]");
                $_SESSION['err'] = $error;
                exit();
            }
        } else {
            header('Location: /blacksmith');
            $_SESSION['err'] = 'Данные не получены';
            exit();
        }
}

#-Установка руны-#
switch ($act) {
    case 'runa_add':
        if (isset($_GET['weapon_id']) and isset($_POST['runa'])) {
            $weapon_id = check($_GET['weapon_id']);
            $runa = check($_POST['runa']);
            $page = check($_GET['page']);

#-Проверка что цифры-#
            if (!preg_match('/^[0-9]+$/u', $_POST['runa']))
                $error = 'Только цифры!';
#-Параметры-#
            if ($runa < 250)
                $error = 'Не менее 250!';
            if ($runa > 100000)
                $error = 'Не более 100.000!';
#-Выборка данных о доспехе-#
            $sel_weapon_me = $pdo->prepare("SELECT `id`, `user_id`, `state`, `runa` FROM `weapon_me` WHERE `id` = :id AND `user_id` = :user_id AND `state` = 0 AND `runa` = 0");
            $sel_weapon_me->execute(array(':id' => $weapon_id, ':user_id' => $user['id']));
            if ($sel_weapon_me->rowCount() == 0)
                $error = 'Доспех не найден или руна уже установлена!';
#-Достаточно ли золота-#
            if ($user['gold'] < $runa)
                $error = 'Недостаточно золота!';

#-Если нет ошибок-#
            if (!isset($error)) {
                $runa = floor($runa);
                $weapon_me = $sel_weapon_me->fetch(PDO::FETCH_LAZY);
#-Минус золота-#
                $upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold WHERE `id` = :id LIMIT 1");
                $upd_users->execute(array(':gold' => $user['gold'] - $runa, ':id' => $user['id']));
#-Установка руны оружию-#
                $upd_weapon_me = $pdo->prepare("UPDATE `weapon_me` SET `runa` = :runa WHERE `id` = :id LIMIT 1");
                $upd_weapon_me->execute(array(':runa' => $runa, ':id' => $weapon_me['id']));
#-Создание руны-#
                $ins_weapon_ru = $pdo->prepare("INSERT INTO `weapon_runa` SET `runa` = :runa, `weapon_id` = :weapon_id, `user_id` = :user_id");
                $ins_weapon_ru->execute(array(':runa' => $runa, ':weapon_id' => $weapon_me['id'], ':user_id' => $user['id']));
                header("Location: /blacksmith?page=$page");
                $_SESSION['ok'] = 'Руна установлена';
                exit();
            } else {
                header("Location: /blacksmith?page=$page");
                $_SESSION['err'] = $error;
                exit();
            }
        } else {
            header('Location: /blacksmith');
            $_SESSION['err'] = 'Данные не переданы';
            exit();
        }
}

#-Удаление руны-#
switch ($act) {
    case 'runa_delete':
        if (isset($_GET['weapon_id'])) {
            $weapon_id = check($_GET['weapon_id']);
            $page = check($_GET['page']);
#-Выборка данных о доспехе-#
            $sel_weapon_me = $pdo->prepare("SELECT `id`, `user_id`, `state`, `runa` FROM `weapon_me` WHERE `id` = :id AND `user_id` = :user_id AND `state` = 0 AND `runa` != 0");
            $sel_weapon_me->execute(array(':id' => $weapon_id, ':user_id' => $user['id']));
            if ($sel_weapon_me->rowCount() == 0)
                $error = 'Доспех не найден или руна не установлена!';

#-Если нет ошибок-#
            if (!isset($error)) {
                $weapon_me = $sel_weapon_me->fetch(PDO::FETCH_LAZY);
#-Руна должна существоваать иначе создание новой-#
                $sel_weapon_ru = $pdo->prepare("SELECT * FROM `weapon_runa` WHERE `runa` = :runa AND `user_id` = :user_id AND `weapon_id` = :weapon_id");
                $sel_weapon_ru->execute(array(':runa' => $weapon_me['runa'], ':user_id' => $user['id'], ':weapon_id' => $weapon_id));
                if ($sel_weapon_ru->rowCount() != 0) {
                    $upd_weapon_ru = $pdo->prepare("UPDATE `weapon_runa` SET `weapon_id` = 0 WHERE `user_id` = :user_id AND `weapon_id` = :weapon_id LIMIT 1");
                    $upd_weapon_ru->execute(array(':user_id' => $user['id'], ':weapon_id' => $weapon_me['id']));
                } else {
                    $ins_weapon_ru = $pdo->prepare("INSERT INTO `weapon_runa` SET `runa` = :runa, `weapon_id` = 0, `user_id` = :user_id");
                    $ins_weapon_ru->execute(array(':runa' => $weapon_me['runa'], ':user_id' => $user['id']));
                }
#-Удаление руны-#
                $upd_weapon_me = $pdo->prepare("UPDATE `weapon_me` SET `runa` = 0 WHERE `id` = :id LIMIT 1");
                $upd_weapon_me->execute(array(':id' => $weapon_me['id']));
                header("Location: /blacksmith?page=$page");
            } else {
                header("Location: /blacksmith?page=$page");
                $_SESSION['err'] = $error;
                exit();
            }
        } else {
            header('Location: /blacksmith');
            $_SESSION['err'] = 'Данные не переданы';
            exit();
        }
}

#-Продажа руны-#
switch ($act) {
    case 'runa_sell':
        if (isset($_GET['runa_id'])) {
            $runa_id = check($_GET['runa_id']);
#-Выборка данных руны-#
            $sel_runa = $pdo->prepare("SELECT * FROM `weapon_runa` WHERE `id` = :runa_id AND `user_id` = :user_id AND `weapon_id` = 0");
            $sel_runa->execute(array(':runa_id' => $runa_id, ':user_id' => $user['id']));
            if ($sel_runa->rowCount() == 0)
                $error = 'Руна не найдена!';

#-Если нет ошибок-#
            if (!isset($error)) {
                $runa = $sel_runa->fetch(PDO::FETCH_LAZY);
#-Зачисление золота-#
                $upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold WHERE `id` = :user_id LIMIT 1");
                $upd_users->execute(array(':gold' => $user['gold'] + $runa['runa'], ':user_id' => $user['id']));
#-Удаление руны-#
                $del_weapon_ru = $pdo->prepare("DELETE FROM `weapon_runa` WHERE `id` = :runa_id");
                $del_weapon_ru->execute(array(':runa_id' => $runa['id']));
                header('Location: /runa');
                $_SESSION['ok'] = 'Руна продана!';
                exit();
            } else {
                header('Location: /runa');
                $_SESSION['err'] = $error;
                exit();
            }
        } else {
            header('Location: /runa');
            $_SESSION['err'] = 'Данные не переданы';
            exit();
        }
}
?>