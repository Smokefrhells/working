<?php
require_once '../../system/system.php';
echo only_reg();
echo coliseum_level();

#-Начало боя колизея-#
switch ($act) {
    case 'start':
        $user_health = $user['health'] + $user['s_health'] + $user['health_bonus']; //Здоровье игрока
        #-Проверка что игрок не участвует в бою-#
        $sel_coliseum = $pdo->prepare("SELECT * FROM `coliseum` WHERE `user_id` = :user_id");
        $sel_coliseum->execute([':user_id' => $user['id']]);
        if ($sel_coliseum->rowCount() != 0)
            $error = 'Вы уже участвуете в бою!';

        if (!isset($error)) {
            #-Уровни боя-#
            if ($user['level'] >= 13 and $user['level'] < 40) {
                $min_level = 13;
                $max_level = 20;
            }
            if ($user['level'] >= 20 and $user['level'] < 40) {
                $min_level = 20;
                $max_level = 40;
            }
            if ($user['level'] >= 40 and $user['level'] < 60) {
                $min_level = 40;
                $max_level = 60;
            }
            if ($user['level'] >= 60 and $user['level'] < 80) {
                $min_level = 60;
                $max_level = 80;
            }
            if ($user['level'] >= 80 and $user['level'] <= 100) {
                $min_level = 80;
                $max_level = 100;
            }

            #-Есть бой или создавать-#
            $sel_coliseum_b = $pdo->prepare("SELECT * FROM `coliseum` WHERE `level` >= :user_min_lvl AND `level` <= :user_max_lvl AND `statys` = 0");
            $sel_coliseum_b->execute([':user_min_lvl' => $min_level, ':user_max_lvl' => $max_level]);
            if ($sel_coliseum_b->rowCount() == 0) {
                $battle_id = rand(0, 1000);
            } else {
                $coliseum_b = $sel_coliseum_b->fetch(PDO::FETCH_LAZY);

                #-Кол-во игроков с одинаковым battle_id-#
                $sel_coliseum_a = $pdo->prepare("SELECT COUNT(*) FROM `coliseum` WHERE `battle_id` = :battle_id");
                $sel_coliseum_a->execute([':battle_id' => $coliseum_b['battle_id']]);
                $coliseum_a = $sel_coliseum_a->fetch(PDO::FETCH_LAZY);
                if ($coliseum_a[0] < 5) {
                    $battle_id = $coliseum_b['battle_id'];
                } else {
                    $battle_id = rand(0, 1000);
                }
            }
            #-Записываем данные для боя-#
            $ins_coliseum = $pdo->prepare("INSERT INTO `coliseum` SET `level` = :level, `user_health` = :user_health, `user_t_health` = :user_health, `user_id` = :user_id, `battle_id` = :battle_id, `statys` = :statys, `last_add` = :last_add");
            $ins_coliseum->execute([':level' => $user['level'], ':user_health' => $user_health, ':user_id' => $user['id'], ':battle_id' => $battle_id, ':statys' => 0, ':last_add' => time()]);
            header('Location: /coliseum');
        } else {
            header('Location: /coliseum');
            $_SESSION['err'] = $error;
            exit();
        }
}
?>