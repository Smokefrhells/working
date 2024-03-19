<?php
require "../../pass/lib/password.php";
require_once '../../system/system.php';
require_once '../../avenax/Maneken.php';
global $user;
/*Создание персонажа*/
switch ($act) {
    case 'create':
        if (!isset($user['nick']) and isset($_POST['pol'])) { //Только если не авторизованы
            $num = rand(1, 999999999); //Рандомные цифры
            $pol = check($_POST['pol']); //Пол игрока
//$storona = check($_POST['storona']); //Сторона игрока
            $nick = "Герой$num"; //Ник
            $hash = hash_cod('0'); //Hash код
            if (!in_array($pol, array('1', '2')))
                $error = 'Вы вообще какого пола?!';
//if(!in_array($storona, array('1','2'))) $error = 'Сторона выбрана неверно!';
#-Нет ошибок-#
            if (!isset($error)) {
#-Выборка ника из бд-#
                $sel_users = $pdo->prepare("SELECT `nick` FROM `users` WHERE `nick` = :num_1");
                $sel_users->execute(array(':num_1' => $nick));
#-Если нет такого персонажа-#
                if ($sel_users->rowCount() == 0) {
#-Если реф ссылка-#
                    if (isset($_GET['ref'])) {
                        $ref_id = check($_GET['ref']);
                        $sel_users_ref = $pdo->prepare("SELECT `id`, `ip` FROM `users` WHERE `id` = :id AND `ip` != :ip");
                        $sel_users_ref->execute(array(':id' => $ref_id, ':ip' => $_SERVER['REMOTE_ADDR']));
                        if ($sel_users_ref->rowCount() != 0) {
                            $ref = check($_GET['ref']);
                        } else {
                            $ref = 0;
                        }
                    } else {
                        $ref = 0;
                    }
#-Создание персонажа-#
                    $ins_users = $pdo->prepare("INSERT INTO `users` SET `ip` = :ip, `nick` = :nick, `pol` = :pol, `storona` = :storona, `hash` = :hash, `ref_id` = :ref, `time` = :time, `premium` = :premium, `premium_time` = :premium_time");
                    $ins_users->execute(array(':ip' => $_SERVER['REMOTE_ADDR'], ':nick' => $nick, ':pol' => $pol, ':storona' => rand(1, 2), ':hash' => $hash, ':ref' => $ref, ':time' => time(), ':premium' => 2, ':premium_time' => time() + 0));
#-Добавление оружия-#
                    $user_id = $pdo->lastInsertId();
                    $ins_wea_me = $pdo->prepare("INSERT INTO `item_user` SET `weapon_id` = :weapon, `user_id` = :user_id, `state` = :state, `str` = '50', `def` = '50', `hp` = '50'");
                    $ins_wea_me->execute(array(':weapon' => Maneken::$idItemReg, ':user_id' => $user_id, ':state' => '0'));
#-Если конкурс рефералов-#
                    /*
                    if($ref != 0){
                    $ins_ref = $pdo->prepare("INSERT INTO `referal` SET `ip` = :ip, `ref_id` = :ref_id, `user_id` = :user_id, `time` = :time");
                    $ins_ref->execute(array(':ip' => $_SERVER['REMOTE_ADDR'], ':ref_id' => $ref, ':user_id' => $user_id, ':time' => time()));
                    }
                    */
#-Ставим ежедневное задание-#
                    $ins_tasks = $pdo->prepare("INSERT INTO `daily_tasks` SET `type` = :type, `user_id` = :user_id, `time` = :time");
                    $ins_tasks->execute(array(':type' => 1, ':user_id' => $user_id, ':time' => time()));
#-Добавляем зелье-#
                    $ins_potions_me = $pdo->prepare("INSERT INTO `potions_me` SET `user_id` = :user_id, `quatity` = 3, `potions_id` = :potions_id, `time` = :time");
                    $ins_potions_me->execute(array(':user_id' => $user_id, ':potions_id' => 3, ':time' => time()));
#-Ставим куки-#
                    setcookie('UsN', $nick, time() + 604800, '/');
                    setcookie('UsH', $hash, time() + 604800, '/');
                    header('Location: /');
                    exit();
                } else {
                    header('Location: /');
                    $_SESSION['err'] = 'Ошибка создания игрока!';
                    exit();
                }
            } else {
                header("Location: /road");
                $_SESSION['err'] = $error;
                exit();
            }
        } else {
            header("Location: /");
            exit();
        }
}
?>