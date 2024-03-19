<?php
require_once '../../system/system.php';
echo only_reg();
echo save();
#-Выкуп локации-#
switch ($act) {
    case 'buy':
        if (isset($_GET['loc'])) {
            $loc = check($_GET['loc']); //Локация
#-Проверяем существует ли локация и она не захвачена-#
            $sel_hunting = $pdo->prepare("SELECT `id`, `user_id`, `level` FROM `hunting` WHERE `id` = :loc AND `user_id` = 0");
            $sel_hunting->execute(array(':loc' => $loc));
            if ($sel_hunting->rowCount() == 0)
                $error = 'Локация не найдена или захвачена!';
#-Проверка что нет других захваченных локаций у игрока-#
            $sel_hunting_c = $pdo->prepare("SELECT `id`, `user_id` FROM `hunting` WHERE `user_id` = :user_id");
            $sel_hunting_c->execute(array(':user_id' => $user['id']));
            if ($sel_hunting_c->rowCount() != 0)
                $error = 'У вас есть захваченные локации!';
#-Если нет ошибок-#
            if (!isset($error)) {
                $hunting = $sel_hunting->fetch(PDO::FETCH_LAZY);
#-Уровень локации и наш-#
                if ($user['level'] < $hunting['level'] + 15) {
#-Хватает золота у игрока-#
                    $gold = $loc * 40;
                    if ($user['gold'] >= $gold) {
#-Минусуем золото-#
                        $upd_users = $pdo->prepare("UPDATE `users` SET `gold` = :gold, `s_sila` = :s_sila, `s_zashita` = :s_zashita, `s_health` = :s_health WHERE `id` = :user_id");
                        $upd_users->execute(array(':gold' => $user['gold'] - $gold, ':s_sila' => $user['s_sila'] + params($hunting['id']), ':s_zashita' => $user['s_zashita'] + params($hunting['id']), ':s_health' => $user['s_health'] + params($hunting['id']), ':user_id' => $user['id']));
#-Записываем локацию-#
                        $upd_hunting = $pdo->prepare("UPDATE `hunting` SET `user_id` = :user_id, `time_battle` = :time_battle WHERE `id` = :loc");
                        $upd_hunting->execute(array(':user_id' => $user['id'], ':time_battle' => time() + ($hunting['id'] * 7200), ':loc' => $loc));
                        header('Location: /select_location');
                    } else {
                        header('Location: /select_location');
                        $_SESSION['err'] = 'Недостаточно золота!';
                        exit();
                    }
                } else {
                    header('Location: /select_location');
                    $_SESSION['err'] = 'Не для вашего уровня!';
                    exit();
                }
            } else {
                header('Location: /select_location');
                $_SESSION['err'] = $error;
                exit();
            }
        } else {
            header('Location: /select_location');
            $_SESSION['err'] = 'Данные не получены!';
            exit();
        }
}
?>