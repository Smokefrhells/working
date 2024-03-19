<?php
require_once '../../system/system.php';
only_reg();
save();
#-Создание боя-#
switch ($act) {
    case 'battle':
        if (isset($_GET['loc'])) {
            $loc = check($_GET['loc']); //Локация
            #-Проверяем существует ли локация и она захвачена-#
            $sel_hunting = $pdo->prepare("SELECT `id`, `user_id`, `level`, `statys_battle` FROM `hunting` WHERE `id` = :loc AND `user_id` != 0 AND `statys_battle` = 0");
            $sel_hunting->execute([':loc' => $loc]);
            if ($sel_hunting->rowCount() == 0)
                $error = 'Локация не найдена или не захвачена!';
            #-Проверка что нет других захваченных локаций у игрока-#
            $sel_hunting_c = $pdo->prepare("SELECT `id`, `user_id` FROM `hunting` WHERE `user_id` = :user_id");
            $sel_hunting_c->execute([':user_id' => $user['id']]);
            if ($sel_hunting_c->rowCount() != 0)
                $error = 'У вас есть захваченные локации!';
            #-Проверка что игрок не сражаеться за другие локации-#
            $sel_hunting_b = $pdo->prepare("SELECT `id`, `user_id` FROM `hunting_battle_u` WHERE `user_id` = :user_id");
            $sel_hunting_b->execute([':user_id' => $user['id']]);
            if ($sel_hunting_b->rowCount() != 0)
                $error = 'Вы сражаетесь за другую локацию!';
            #-Если нет ошибок-#
            if (!isset($error)) {
                $hunting = $sel_hunting->fetch(PDO::FETCH_LAZY);
                #-Уровень локации и наш-#
                if ($user['level'] < $hunting['level'] + 15) {
                    $sel_hunting_b = $pdo->prepare("SELECT `id`, `user_id` FROM `hunting_battle_u` WHERE `user_id` = :ank_id");
                    $sel_hunting_b->execute([':ank_id' => $hunting['user_id']]);
                    if ($sel_hunting_b->rowCount() == 0) {
                        #-Записываем игрока который владеет локацией-#
                        $ins_hunting_v = $pdo->prepare("INSERT INTO `hunting_battle_u` SET `user_id` = :ank_id, `location` = :location, `time` = :time");
                        $ins_hunting_v->execute([':ank_id' => $hunting['user_id'], ':location' => $loc, ':time' => time()]);
                    }
                    #-Добавляем игрока в бой-#
                    $ins_hunting_b = $pdo->prepare("INSERT INTO `hunting_battle_u` SET `user_id` = :user_id, `location` = :loc, `time` = :time");
                    $ins_hunting_b->execute([':user_id' => $user['id'], ':loc' => $loc, ':time' => time()]);
                    header('Location: /select_location');
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