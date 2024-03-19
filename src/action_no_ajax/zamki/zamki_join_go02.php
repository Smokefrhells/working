<?php
require_once '../../system/system.php';
echo only_reg();
echo zamki_level();
#-Вступление в замки-#
switch ($act) {
    case 'join':
        #-Проверяем что есть сражения-#
        $sel_zamki = $pdo->query("SELECT * FROM `zamki` WHERE `statys` = 0");
        if ($sel_zamki->rowCount() == 0)
            $error = 'Ошибка!';
        #-Проверяем что не участвуем в сражении-#
        $sel_zamki_u = $pdo->prepare("SELECT `id`, `user_id` FROM `zamki_users` WHERE `user_id` = :user_id");
        $sel_zamki_u->execute([':user_id' => $user['id']]);
        if ($sel_zamki_u->rowCount() != 0)
            $error = 'Вы участвуете в сражении!';

        #-Если нет ошибок-#
        if (!isset($error)) {
            $zamki = $sel_zamki->fetch(PDO::FETCH_LAZY);
            #-Здоровье игрока-#
            $user_health = $user['health'] + $user['s_health'] + $user['health_bonus'];

            #-Если здоровье правых больше от левых-#
            if ($zamki['health_max_right'] >= $zamki['health_max_left']) {
                #-Левые-#
                $storona = 'left';
                $upd_zamki = $pdo->prepare("UPDATE `zamki` SET `health_left` = :user_health, `health_t_left` = :user_health, `health_max_left` = :user_health LIMIT 1");
                $upd_zamki->execute([':user_health' => $zamki['health_max_left'] + $user_health]);
            } else {
                #-Правые-#
                $storona = 'right';
                $upd_zamki = $pdo->prepare("UPDATE `zamki` SET `health_right` = :user_health, `health_t_right` = :user_health, `health_max_right` = :user_health LIMIT 1");
                $upd_zamki->execute([':user_health' => $zamki['health_max_right'] + $user_health]);
            }
            #-Записываем игрока-#
            $ins_zamki_u = $pdo->prepare("INSERT INTO `zamki_users` SET `user_id` = :user_id, `storona` = :storona");
            $ins_zamki_u->execute([':user_id' => $user['id'], ':storona' => $storona]);
            header('Location: /zamki');
            exit();
        } else {
            header('Location: /zamki');
            $_SESSION['err'] = $error;
            exit();
        }
}
?>