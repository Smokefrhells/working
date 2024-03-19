<?php
require_once '../../system/system.php';
require_once '../../avenax/Maneken.php';
only_reg();

/*Начало обучения охоты*/
switch ($act) {
    case 'battle':
#-Если еще не проходили обучение-#
        if ($user['start'] == 8)
            $error = 'Обучение пройдено!';
#-Проверка в бою или нет-#
        $sel_hunting_b = $pdo->prepare("SELECT * FROM `hunting_battle` WHERE `user_id` = :user_id");
        $sel_hunting_b->execute(array(':user_id' => $user['id']));
        if ($sel_hunting_b->rowCount() != 0)
            $error = 'Вы уже в бою!';
 
#-Если нет ошибок-#	
        if (!isset($error)) {
            $monstr_id = 1;
#-Выборка монстра-#
            $sel_monsters2 = $pdo->prepare("SELECT * FROM `monsters` WHERE `id` = :id");
            $sel_monsters2->execute(array(':id' => $monstr_id));
            $monstrs = $sel_monsters2->fetch(PDO::FETCH_LAZY);
#-Одето снаряжение или нет-#
            if ($user['s_sila'] == 0) {
                $upd_users = $pdo->prepare("UPDATE `users` SET `start` = :start, `s_sila` = :s_sila, `s_zashita` = :s_zashita, `s_health` = :s_health WHERE `id` = :user_id LIMIT 1");
                $upd_users->execute(array(':start' => 1, ':s_sila' => $user['s_sila'] + 50, ':s_zashita' => $user['s_zashita'] + 50, ':s_health' => $user['s_health'] + 50, ':user_id' => $user['id']));
                $s_health = 50;
            } else {
                $upd_users = $pdo->prepare("UPDATE `users` SET `start` = :start  WHERE `id` = :user_id LIMIT 1");
                $upd_users->execute(array(':start' => 1, ':user_id' => $user['id']));
                $s_health = $user['s_health'];
            }
#-Запись боя-#
            $ins_h_battle = $pdo->prepare("INSERT INTO `hunting_battle` SET `monstr_id` = :monstr_id, `monstr_t_health` = :monstr_t_health, `monstr_health` = :monstr_health, `user_t_health` = :user_t_health, `user_health` = :user_health, `user_id` = :user_id, `time` = :time");
            $ins_h_battle->execute(array(':monstr_id' => $monstr_id, ':monstr_t_health' => $monstrs['health'], ':monstr_health' => $monstrs['health'], ':user_t_health' => $user['health'] + $s_health, ':user_health' => $user['health'] + $s_health, ':user_id' => $user['id'], ':time' => time()));
#-Статус снаряжения 1-#
            $upd_weapon_me = $pdo->prepare("UPDATE `item_user` SET `state` = :state WHERE `weapon_id` = :weapon_id AND `user_id` = :user_id LIMIT 1");
            $upd_weapon_me->execute(array(':state' => 1, ':weapon_id' => Maneken::$idItemReg, ':user_id' => $user['id']));
            header('Location: /hunting_battle');
        } else {
            header('Location: /');
            $_SESSION['err'] = $error;
            exit();
        }
}
?>