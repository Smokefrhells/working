<?php
require_once '../../system/system.php';
echo only_reg();
echo zamki_level();
#-Старт сражения замки-#
switch ($act) {
    case 'start':
        #-Проверяем что нет сражения-#
        $sel_zamki = $pdo->query("SELECT `id` FROM `zamki`");
        if ($sel_zamki->rowCount() != 0)
            $error = 'Битва уже началась!';
        if (!isset($error)) {
            #-Здоровье игрока-#
            $user_health = $user['health'] + $user['s_health'] + $user['health_bonus'];
            $rand = rand(1, 2); //Рандомно выбираем сторону
            #-Выборка игрока с найбольшим уроном-#
            $sel_uron = $pdo->query("SELECT `id`, `zamki_uron` FROM `users` WHERE `zamki_uron` > 0 ORDER BY `zamki_uron` DESC");
            if ($sel_uron->rowCount() != 0) {
                $uron = $sel_uron->fetch(PDO::FETCH_LAZY);
                $max_uron_id = $uron['id'];
            } else {
                $max_uron_id = 0;
            }

            $newHealth = 200000;
            #-Правые-#
            if ($rand == 1) {
                $storona = 'right';
                $ins_zamki = $pdo->prepare("INSERT INTO `zamki` SET `max_uron_id` = :max_uron_id, `health_right` = :user_health, `health_t_right` = :user_health, `health_max_right` = :user_health, `time` = :time");
                $ins_zamki->execute([':max_uron_id' => $max_uron_id, ':user_health' => $user_health, ':time' => time() + 600]);
            }
            #-Левые-#
            if ($rand == 2) {
                $storona = 'left';
                $ins_zamki = $pdo->prepare("INSERT INTO `zamki` SET `max_uron_id` = :max_uron_id, `health_left` = :user_health, `health_t_left` = :user_health, `health_max_left` = :user_health, `time` = :time");
                $ins_zamki->execute([':max_uron_id' => $max_uron_id, ':user_health' => $user_health, ':time' => time() + 600]);
            }
            #-Записываем игрока-#
            $ins_zamki_u = $pdo->prepare("INSERT INTO `zamki_users` SET `user_id` = :user_id, `storona` = :storona");
            $ins_zamki_u->execute([':user_id' => $user['id'], ':storona' => $storona]);

            $upd_users2 = $pdo->prepare("UPDATE `zamki` SET `health_t_right` = `health_t_right` + :health_right, `health_t_left` = `health_t_left` + :health_right, `health_max_left` = :health_right, `health_max_right` = :health_right");
            $upd_users2->execute(array(':health_right' => $newHealth));


            #-Чистим лог прежнего боя-#
            $del_zamki_log = $pdo->query("DELETE FROM `zamki_log`");
            header('Location: /zamki');
            exit();
        } else {
            header('Location: /zamki');
            $_SESSION['err'] = $error;
            exit();
        }
}
?>