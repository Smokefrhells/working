<?php
require_once '../../system/system.php';
echo admin();
#-СКИДКИ-#

#-Установка скидки-#
switch ($act) {
    case 'setup':
        if (isset($_POST['type']) and isset($_POST['prosent']) and isset($_POST['time_hour']) and isset($_POST['time_minut'])) {
            $type = check($_POST['type']);
            $prosent = check($_POST['prosent']);
            $time_hour = check($_POST['time_hour']);
            $time_minut = check($_POST['time_minut']);
            $time = ($time_hour * 3600) + ($time_minut * 60);
            #-Создание скидки-#
            $ins_stock = $pdo->prepare("INSERT INTO `stock` SET `type` = :type, `prosent` = :prosent, `time` = :time");
            $ins_stock->execute([':type' => $type, ':prosent' => $prosent, ':time' => time() + $time]);
            header("Location: /admin");
            $_SESSION['ok'] = 'Скидка установлена!';
            exit();
        } else {
            header("Location: /admin");
            exit();
        }
}

#-Удаление скидки-#
switch ($act) {
    case 'delete':
        if (isset($_GET['type'])) {
            $type = check($_GET['type']);
            #-Удаление скидки-#
            $del_stock = $pdo->prepare("DELETE FROM `stock` WHERE `type` = :type");
            $del_stock->execute([':type' => $type]);
            header("Location: /admin");
            $_SESSION['ok'] = 'Скидка удалена!';
            exit();
        } else {
            header("Location: /admin");
            exit();
        }
}

#-АКЦИИ-#
#-Акция на клан-#
switch ($act) {
    case 'clan':
        #-Есть акция или нет-#
        $sel_stock = $pdo->query("SELECT * FROM `stock` WHERE `type` = 1");
        if ($sel_stock->rowCount() == 0) {
            $ins_stock = $pdo->prepare("INSERT INTO `stock` SET `type` = 1, `time` = :time");
            $ins_stock->execute([':time' => time() + 86400]);
        } else {
            $del_stock = $pdo->query("DELETE FROM `stock` WHERE `type` = 1");
        }
        header('Location: /admin');
}

#-Акция x2 золото-#
switch ($act) {
    case 'goldx2':
        #-Есть акция или нет-#
        $sel_stock = $pdo->query("SELECT * FROM `stock` WHERE `type` = 2");
        if ($sel_stock->rowCount() == 0) {
            $ins_stock = $pdo->prepare("INSERT INTO `stock` SET `type` = 2, `time` = :time");
            $ins_stock->execute([':time' => time() + 86400]);
        } else {
            $del_stock = $pdo->query("DELETE FROM `stock` WHERE `type` = 2");
        }
        header('Location: /admin');
}

#-Акция x3 золото-#
switch ($act) {
    case 'goldx3':
        #-Есть акция или нет-#
        $sel_stock = $pdo->query("SELECT * FROM `stock` WHERE `type` = 3");
        if ($sel_stock->rowCount() == 0) {
            $ins_stock = $pdo->prepare("INSERT INTO `stock` SET `type` = 3, `time` = :time");
            $ins_stock->execute([':time' => time() + 86400]);
        } else {
            $del_stock = $pdo->query("DELETE FROM `stock` WHERE `type` = 3");
        }
        header('Location: /admin');
}

#-ВЫГОДНЫЕ ПРЕДЛОЖЕНИЯ-#
#-Питомец-#
switch ($act) {
    case 'pets':
        #-Есть предложение или нет-#
        $sel_stock = $pdo->query("SELECT * FROM `stock` WHERE `type` = 8");
        if ($sel_stock->rowCount() == 0) {
            $ins_stock = $pdo->prepare("INSERT INTO `stock` SET `type` = 8, `time` = :time");
            $ins_stock->execute([':time' => time() + 86400]);
        } else {
            $del_stock = $pdo->query("DELETE FROM `stock` WHERE `type` = 8");
        }
        header('Location: /admin');
}
#-ВЫГОДНЫЕ ПРЕДЛОЖЕНИЯ-#
#-shmot-#
switch ($act) {
    case 'shop':
        #-Есть предложение или нет-#
        $sel_stock = $pdo->query("SELECT * FROM `stock` WHERE `type` = 30");
        if ($sel_stock->rowCount() == 0) {
            $ins_stock = $pdo->prepare("INSERT INTO `stock` SET `type` = 30, `time` = :time");
            $ins_stock->execute([':time' => time() + 86400]);
        } else {
            $del_stock = $pdo->query("DELETE FROM `stock` WHERE `type` = 30");
        }
        header('Location: /admin');
}
?>