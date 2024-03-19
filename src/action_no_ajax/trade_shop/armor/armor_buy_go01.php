<?php
require_once '../../../system/system.php';
echo only_reg();
echo trade_shop_campaign();

switch ($act) {
    case 'buy':
        if (isset($_GET['id'])) {
            $id = check($_GET['id']);
#-Проверяем ввод цифры-#
            if (!preg_match('/^([0-9])+$/u', $_GET['id']))
                $error = 'Введите цифру!';
#-Выборка данных о доспехе-#
            $sel_weapon = $pdo->prepare("SELECT * FROM `weapon` WHERE `id` = :id AND `no_magaz` != 15"); //no_magaz != 10
            $sel_weapon->execute(array(':id' => $id));
#-Только если есть такой доспех-#
            if ($sel_weapon->rowCount() != 0) {
#-И если нет ошибок-#
                if (!isset($error)) {
                    $weapon = $sel_weapon->fetch(PDO::FETCH_LAZY);
#-Купляем только если не скрыто-#
                    if ($weapon['no_magaz'] == 0 or $weapon['no_magaz'] == 15) {
#-Проверяем уровень доспеха и героя-#
                        if ($user['level'] >= $weapon['level']) {

#-Праздничное снаряжение-#
                            if ($weapon['no_magaz'] == 15) {
                                if ($user['snow'] >= $weapon['snow']) {
#-Отнимаем деньги у пользователя-#
                                    $upd_users = $pdo->prepare("UPDATE `users` SET `snow` = :snow WHERE `id` = :id LIMIT 1");
                                    $upd_users->execute(array(':snow' => $user['snow'] - $weapon['snow'], ':id' => $user['id']));
                                } else {
                                    header("Location: /p_shop?type=$weapon[type]");
                                    $_SESSION['err'] = 'Недостаточно денег!';
                                    exit();
                                }
                            } else {
                                if ($user['gold'] >= $weapon['gold'] and $user['silver'] >= $weapon['silver']) {
#-Отнимаем деньги у пользователя-#
                                    $upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `silver` = :silver WHERE `id` = :id LIMIT 1");
                                    $upd_users->execute(array(':gold' => $user['gold'] - $weapon['gold'], ':silver' => $user['silver'] - $weapon['silver'], ':id' => $user['id']));
                                } else {
                                    header("Location: /p_shop?type=$weapon[type]");
                                    $_SESSION['err'] = 'Недостаточно денег!';
                                    exit();
                                }
                            }
#-Обучение-#
                            if ($user['start'] == 3) {
                                $upd_users = $pdo->prepare("UPDATE `users` SET `start` = 4 WHERE `id` = :user_id LIMIT 1");
                                $upd_users->execute(array(':user_id' => $user['id']));
                            }
#-Добавляем снаряжение в базу-#
                            $ins_weapon_me = $pdo->prepare("INSERT INTO `weapon_me` SET `type` = :type, `weapon_id` = :weapon_id, `user_id` = :user_id, `time` = :time");
                            $ins_weapon_me->execute(array(':type' => $weapon['type'], ':weapon_id' => $weapon['id'], ':user_id' => $user['id'], ':time' => time()));
                            header("Location: /p_shop?type=$weapon[type]");
                            $_SESSION['ok'] = 'Успешная покупка!';
                            exit();
                        } else {
                            header("Location: /p_shop?type=$weapon[type]");
                            $_SESSION['err'] = 'Ошибка уровня!';
                            exit();
                        }
                    } else {
                        header("Location: /p_shop?type=$weapon[type]");
                    }
                } else {
                    header("Location: /p_shop?type=$weapon[type]");
                    $_SESSION['err'] = $error;
                    exit();
                }
            } else {
                header("Location: /p_shop?type=$weapon[type]");
                $_SESSION['err'] = 'Снаряжение не найдено!!';
                exit();
            }
        } else {
            header("Location: /p_shop?type=$weapon[type]");
            $_SESSION['err'] = 'Введите данные!';
            exit();
        }
}
?>