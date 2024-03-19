<?php
require_once '../../system/system.php';
only_reg();
require_once H . 'copy/copy_func.php';
require_once H . 'avenax/Item.php';

$listItems = Item::Items();

$arrayItem = [];
for ($i = 1; $i <= 5; $i ++) {
    $arrayItem[$i] = $listItems->$i->id;
}

$glava_quest_q = acc("SELECT * FROM `quest_glava` WHERE `glava` = ? ORDER BY `id` ASC", array($user['glava']));
foreach($glava_quest_q as $quest){
    $my_quest = fch("SELECT * FROM `quest_glava_user` WHERE `id_quest` = ? AND `id_user` = ?", array($quest['id'], $user['id']));
    if(!$my_quest){
        qry("INSERT INTO `quest_glava_user` SET `id_quest` = ?, `id_user` = ?", array($quest['id'], $user['id']));
    }

    if($quest['type'] == 'item'){
        $ids = implode(",", $arrayItem[$quest['param_type'] - 1]);
        $count_item = $pdo->query("SELECT COUNT(*) FROM `item_user` WHERE `id` NOT IN ($ids) AND `user_id` = '" . $user['id'] . "' AND `state` = '1'")->fetchColumn();
        qry("UPDATE `quest_glava_user` SET `kolls` = ? WHERE `id` = ?", array($count_item, $my_quest['id']));
    }

    if($quest['type'] == 'smitchy'){
        // 	заточка
        $count_item = $pdo->query("SELECT COUNT(*) FROM `item_user` WHERE `user_id` = '" . $user['id'] . "' AND `state` = '1' AND `level_sharpening` >= '" . $quest['param_type'] . "'")->fetchColumn();
        qry("UPDATE `quest_glava_user` SET `kolls` = ? WHERE `id` = ?", array($count_item, $my_quest['id']));
    }

    if($quest['type'] == 'runes'){
        // руна
        $count_item = cnt("SELECT * FROM `item_user` WHERE `user_id` = ? AND `state` = ? AND `runa` >= ?", array($user['id'], 1, $quest['param_type']));
        qry("UPDATE `quest_glava_user` SET `kolls` = ? WHERE `id` = ?", array($count_item, $my_quest['id']));
    }

    if($quest['type'] == 'power'){
        // сила
        qry("UPDATE `quest_glava_user` SET `kolls` = ? WHERE `id` = ?", array($user['s_sila'], $my_quest['id']));
    }

    if($quest['type'] == 'kvest'){
        $quest_kvest = fch("SELECT * FROM `kvest` WHERE `id_user` = ?", array($user['id']));
        if($quest_kvest and $quest_kvest['id_monster'] > $quest['param_type']){
            qry("UPDATE `quest_glava_user` SET `kolls` = ? WHERE `id` = ?", array(1, $my_quest['id']));
        }
    }

    if($my_quest['ok'] < 1 and $my_quest['kolls'] >= $quest['kolls']){
        qry("UPDATE `quest_glava_user` SET `ok` = ? WHERE `id` = ?", array(1, $my_quest['id']));
        header('Location: ?');
        exit();
    }
}
$quest_q = acc("SELECT * FROM `quest` ORDER BY `id` ASC");
foreach($quest_q as $quest){
    $my_quest = fch("SELECT * FROM `quest_user` WHERE `id_quest` = ? AND `id_user` = ?", array($quest['id'], $user['id']));
    if(!$my_quest){
        qry("INSERT INTO `quest_user` SET `id_quest` = ?, `id_user` = ?", array($quest['id'], $user['id']));
    }
    if($my_quest['ok'] < 1 and $my_quest['kolls'] >= $quest['kolls']){
        qry("UPDATE `quest_user` SET `ok` = ? WHERE `id` = ?", array(1, $my_quest['id']));
    }
}

$act = isset($_GET['act']) ? 'esh' : null;

$max_glava = 4;
switch ($act) {
    case 'esh':
        if (isset($_GET['nagrada'])) {
            $my_quest = fch("SELECT * FROM `quest_user` WHERE `id` = ?", array($_GET['nagrada']));
            $quest = fch("SELECT * FROM `quest` WHERE `id` = ?", array($my_quest['id_quest']));
            if ($my_quest['ok'] != 1 or $user['id'] != $my_quest['id_user']) {
                header('Location: /tasks/daily');
                exit();
            }
            qry("UPDATE `users` SET `gold` = ?, `silver` = ?, `kluch` = ? WHERE `id` = ?", array($user['gold'] + $quest['gold'], $user['silver'] + $quest['silver'], $user['kluch'] + $quest['kluch'], $user['id']));
            qry("UPDATE `quest_user` SET `ok` = ? WHERE `id` = ?", array(2, $my_quest['id']));
            $_SESSION['ok'] = 'Награда: <img src="/style/images/many/gold.png" width="16px"> ' . $quest['gold'] . ' золота и <img src="/style/images/many/silver.png" width="16px"> ' . $quest['silver'] . ' серебра';
            header('Location: /tasks/daily');
            exit();
        }
        $head = 'Ежедневные задания';
        require_once H . 'system/head.php';
        $count_glava = cnt("SELECT * FROM `quest_glava_user` WHERE `id_user` = ? AND `ok` = ?", array($user['id'], 1));
        echo '<div class="block">';
        echo '<img src="/images/icon/quest.jpg" width="16px"> <a href="/tasks/">Сюжетные</a> ' . ($count_glava >= 1 ? '<font color="green">(+)</font>' : '') . ' | Ежедневные';
        echo '</div>';
        $count = cnt("SELECT * FROM `quest_user` WHERE `id_user` = ? AND `ok` != ?", array($user['id'], 2));
        $count_quest = cnt("SELECT * FROM `quest`");
        $q = acc("SELECT * FROM `quest_user` WHERE `id_user` = ? AND `ok` != ? ORDER BY `ok` DESC, `id` ASC", array($user['id'], 2));
        if ($count == 0) {
            echo '<div class="block center">Все задания выполнены</div>';
        }
        foreach ($q as $my_quest) {
            $quest = fch("SELECT * FROM `quest` WHERE `id` = ?", array($my_quest['id_quest']));
            echo '<div class="block">';
            echo '<table width="100%">';
            echo '<td>';
            echo '' . $quest['name'] . '<br>';
            if ($my_quest['ok'] == 0) {
                echo 'Прогресс: ' . $my_quest['kolls'] . ' из ' . $quest['kolls'] . '';
            } else {
                echo '<font color="green">Задание выполнено</font>';
            }
            echo '<br>';
            echo '<font color="goldenrod">Награда:</font> <img src="/style/images/many/gold.png" width="16px"> ' . $quest['gold'] . ' ' . ($quest['silver'] > 0 ? '<img src="/style/images/many/silver.png" width="16px"> ' . $quest['silver'] . '' : '') . ' <img src="/images/icon/kluch.png" width="16px"> ' . $quest['kluch'] . '';
            echo '</td>';
            echo '<td width="55px" align="right"><img src="/images/gold/gold-l.png" width="50px"></td>';
            echo '</table>';
            echo '</div>';
            if ($my_quest['ok'] == 1) {
                echo '<a href="/tasks/daily/reward/' . $my_quest['id'] . '" class="link center"><font color="Goldenrod">Получить награду</font></a>';
            }
        }
        echo '<div class="block center">Собрано <img src="/images/icon/kluch.png" width="16px"> ' . $user['kluch'] . ' ключей</div>';
        require_once H . 'system/footer.php';
        break;
    default:
        if (isset($_GET['nagrada'])) {
            $my_quest = fch("SELECT * FROM `quest_glava_user` WHERE `id` = ?", array($_GET['nagrada']));
            $quest = fch("SELECT * FROM `quest_glava` WHERE `id` = ?", array($my_quest['id_quest']));
            if ($my_quest['ok'] != 1 or $user['id'] != $my_quest['id_user']) {
                header('Location: ?');
                exit();
            }
            qry("UPDATE `users` SET `gold` = ? WHERE `id` = ?", array($user['gold'] + $quest['nagrada'], $user['id']));
            qry("UPDATE `quest_glava_user` SET `ok` = ? WHERE `id` = ?", array(2, $my_quest['id']));
            $_SESSION['ok'] = 'Награда: <img src="/style/images/many/gold.png" width="16px"> ' . $quest['nagrada'] . ' золота';
            header('Location: ?');
            exit();
        }
        $count = cnt("SELECT * FROM `quest_glava_user` WHERE `id_user` = ? AND `ok` != ?", array($user['id'], 2));
        $count_quest = cnt("SELECT * FROM `quest_glava` WHERE `glava` = ?", array($user['glava']));
        $head = 'Глава ' . $user['glava'];
        require_once H . 'system/head.php';
        $count_esh = cnt("SELECT * FROM `quest_user` WHERE `id_user` = ? AND `ok` = ?", array($user['id'], 1));
        echo '<div class="block">';
        echo '<img src="/images/icon/quest.jpg" width="16px"> Сюжетные | <a href="/tasks/daily/">Ежедневные</a> ' . ($count_esh >= 1 ? '<font color="green">(+)</font>' : '') . '';
        echo '</div>';
        if ($count_quest >= 1) {
            $q = acc("SELECT * FROM `quest_glava_user` WHERE `id_user` = ? AND `ok` != ? ORDER BY `ok` DESC, `id` ASC", array($user['id'], 2));
            if ($count == 0) {
                echo '<div class="block center">Все задания выполнены</div>';
            }
            foreach ($q as $my_quest) {
                $quest = fch("SELECT * FROM `quest_glava` WHERE `id` = ?", array($my_quest['id_quest']));
                echo '<div class="block">';
                echo '<table width="100%">';
                echo '<td>';
                echo '' . $quest['name'] . '<br>';
                if ($my_quest['ok'] == 0) {
                    echo 'Прогресс: ' . $my_quest['kolls'] . ' из ' . $quest['kolls'] . '';
                } else {
                    echo '<font color="green">Задание выполнено</font>';
                }
                echo '<br>';
                echo '<font color="goldenrod">Награда:</font> <img src="/style/images/many/gold.png" width="16px"> ' . $quest['nagrada'] . ' золота';
                echo '</td>';
                echo '<td width="55px" align="right"><img src="/images/gold/gold-l.png" width="50px"></td>';
                echo '</table>';
                echo '</div>';
                if ($my_quest['ok'] == 1) {
                    echo '<a href="?nagrada=' . $my_quest['id'] . '" class="link center"><font color="Goldenrod">Получить награду</font></a>';
                }
            }
            echo '<div class="line"></div>';
            echo '<div class="block">';
            $trofei = fch("SELECT * FROM `trofeis` WHERE `id` = ?", array($user['glava']));
            echo '<div class="center">Выполни все задания и получи трофей</div>';
            echo '<table align="center">';
            echo '<td><img src="/images/trofeis/' . $trofei['id'] . '.png" width="90px"></td>';
            echo '<td>';
            echo '<img src="/images/trofeis/' . $trofei['id'] . '.png" width="16px"> <span class="bold">' . $trofei['name'] . '</span><br>';
            echo '<img src="/style/images/body/all.png" width="16px"> +' . $trofei['param'] . ' к параметрам<br>';
//            echo '<img src="/images/icon/krit.png" width="16px"> +' . $trofei['krit'] . '% к ярости<br>';
            echo '<img src="/style/images/user/exp.png" width="16px"> +' . $trofei['exp'] . '% к опыту<br>';
            echo '<img src="/style/images/many/silver.png" width="16px"> +' . $trofei['silver'] . '% к серебру<br>';
            echo '</td>';
            echo '</table>';
            echo '</div>';
            if ($count == 0 and $user['glava'] <= $max_glava) {
                if (isset($_GET['trofei'])) {
                    qry("INSERT INTO `trofeis_user` SET `id_trofei` = ?, `id_user` = ?", array($trofei['id'], $user['id']));
                    qry("UPDATE `users` SET `bon_exp` = `bon_exp` + :bon_exp, `bon_mon` = `bon_mon` + :bon_mon, `glava` = :glava, `sila` = `sila` + :props, `zashita` = `zashita` + :props, `health` = `health` + :props WHERE `id` = :id",
                        array(':glava' => $user['glava'] + 1, ':id' => $user['id'], ':props' => $trofei['param'], ':bon_exp' => $trofei['exp'], ':bon_mon' => $trofei['silver']));
                    header('Location: ?');
                    exit();
                }
                echo '<a href="?trofei" class="link center"><font color="Goldenrod">Забрать трофей</font></a>';
            }
        } else {
            echo '<div class="block center">Глава не реализована</div>';
        }
        require_once H . 'system/footer.php';
        break;
}
?>