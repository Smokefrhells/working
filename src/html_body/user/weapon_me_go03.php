<?php
require_once '../../system/system.php';
echo only_reg();
echo armor_campaign();
$id = check($_GET['id']);
if (empty($_GET['id'])) $error = 'Ошибка!';
if (!isset($_GET['id'])) $error = 'Ошибка!';
if (!isset($error)) {
    $sel_users = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :id");
    $sel_users->execute(array(':id' => $id));
    if ($sel_users->rowCount() != 0) {
        $all = $sel_users->fetch(PDO::FETCH_LAZY);
    } else {
        header("Location: /weapon_me/$user[id]");
        $_SESSION['err'] = 'Пользователь не найден!';
        exit();
    }
} else {
    header("Location: /hero/$user[id]");
    $_SESSION['err'] = $error;
    exit();
}
$head = 'Экипировка';
require_once H . 'system/head.php';
echo '<div class="page">';
#-Определяем тип-#
$type = check($_GET['type']);

if (!in_array($type, [7, 8, 9])) {
    header("Location: /hero/$user[id]");
    exit();
}

#-То что надето-#
$sel_weapon_me2 = $pdo->prepare("SELECT * FROM `weapon_me` WHERE `type` = :type AND `user_id` = :user_id AND `state` = 1");
$sel_weapon_me2->execute(array(':type' => $type, ':user_id' => $all['id']));
$weapon_me2 = $sel_weapon_me2->fetch(PDO::FETCH_LAZY);
$sel_weapon2 = $pdo->prepare("SELECT * FROM `weapon` WHERE `id` = :weapon_id");
$sel_weapon2->execute(array(':weapon_id' => $weapon_me2['weapon_id']));
$nadeto = $sel_weapon2->fetch(PDO::FETCH_LAZY);
$nadeto_p = $nadeto['sila'] + $nadeto['zashita'] + $nadeto['health'] + $weapon_me2['b_sila'] + $weapon_me2['b_zashita'] + $weapon_me2['b_health'] + ($weapon_me2['runa'] * 3);
if ($all['id'] == $user['id']) {
#-Делаем выборку данных оружия-#
    $sel_weapon_me = $pdo->prepare("SELECT * FROM `weapon_me` WHERE `type` = :type AND `user_id` = :user_id AND `auction` = 0 ORDER BY `state` DESC");
    $sel_weapon_me->execute(array(':type' => $type, ':user_id' => $all['id']));
} else {
    $sel_weapon_me = $pdo->prepare("SELECT * FROM `weapon_me` WHERE `type` = :type AND `user_id` = :user_id AND `state` = 1 ORDER BY `state` DESC");
    $sel_weapon_me->execute(array(':type' => $type, ':user_id' => $all['id']));
}
#-Если есть оружие-#
if ($sel_weapon_me->rowCount() != 0) {

    if (isset($_GET['id_ground'])) {



        // todo

    } else {
        while ($weapon_me = $sel_weapon_me->fetch(PDO::FETCH_LAZY)) {
            $sel_weapon = $pdo->prepare("SELECT * FROM `weapon` WHERE `id` = :weapon_id");
            $sel_weapon->execute(array(':weapon_id' => $weapon_me['weapon_id']));
            $weapon = $sel_weapon->fetch(PDO::FETCH_LAZY);
            #-Высчитываем-#
            $weapon_p = $weapon['sila'] + $weapon['zashita'] + $weapon['health'] + $weapon_me['b_sila'] + $weapon_me['b_zashita'] + $weapon_me['b_health'] + ($weapon_me['runa'] * 3);
            if ($weapon_p > $nadeto_p) {
                $param = $weapon_p - $nadeto_p;
                $w_param = "<span class='green'>[+$param]</span>";
            }
            if ($weapon_p < $nadeto_p) {
                $param = $nadeto_p - $weapon_p;
                $w_param = "<span class='red'>[-$param]</span>";
            }
            if ($weapon_p == $nadeto_p) {
                $w_param = "";
            }
            echo '<div class="img_weapon"><img src="' . $weapon['images'] . '" class="' . ramka($weapon['level']) . '"  alt=""/></div><div class="weapon_setting"><span style="color: ' . color_w($weapon['level']) . ';"><img src="' . star($weapon['level']) . '" alt=""/><b>' . $weapon['name'] . '</b> [' . $weapon['level'] . ' ур.]</span><br/><div class="weapon_param"><img src="/style/images/user/sila.png" alt=""/>' . ($weapon['sila'] + $weapon_me['b_sila'] + $weapon_me['runa']) . ' <img src="/style/images/user/zashita.png" alt=""/>' . ($weapon['zashita'] + $weapon_me['b_zashita'] + $weapon_me['runa']) . ' <img src="/style/images/user/health.png" alt=""/>' . ($weapon['health'] + $weapon_me['b_health'] + $weapon_me['runa']) . ' ' . $w_param . '<br/>Руна: ' . ($weapon_me['runa'] == 0 ? "Не установлена" : "Установлена <span class='green'>[+$weapon_me[runa]]</span>") . '</div></div>';
            echo '<div style="padding-top: 10px;"></div>';
            #-Уровень больше или равен нашему-#
            if ($user['level'] >= $weapon['level']) {
                if ($all['id'] == $user['id']) {
                    if ($weapon_me['state'] == 1) {
                        echo "<a href='/armor_act?act=snat&id=$weapon[id]&me_id=$weapon_me[id]' class='button_red_a'>Снять</a>";
                    } else {
                        echo "<a href='/armor_act?act=nadet&id=$weapon[id]&me_id=$weapon_me[id]' class='button_green_a'>Надеть " . ($user['start'] == 4 ? "<img src='/style/images/body/left_mini.gif' alt=''/>" : "") . "</a>";
                    }
                    echo '<div style="padding-top: 5px;"></div>';
                }
            }
        }
    }


} else {
#-Если нет оружия-#
    if (isset($_GET['type'])) {
        $type = check($_GET['type']);
#-Только от 1 до 8-#
        if (in_array($type, array('7', '8', '9'))) {
            echo '<div class="page">';
#-Определяем тип оружия-#
            if ($type == 1) {
                $img = '/style/images/weapon/head/0.png';
            }
            if ($type == 2) {
                $img = '/style/images/weapon/body/0.png';
            }
            if ($type == 3) {
                $img = '/style/images/weapon/gloves/0.png';
            }
            if ($type == 4) {
                $img = '/style/images/weapon/shield/0.png';
            }
            if ($type == 5) {
                $img = '/style/images/weapon/arm/0.png';
            }
            if ($type == 6) {
                $img = '/style/images/weapon/legs/0.png';
            }
            if ($type == 7) {
                $img = '/style/images/weapon/amulet/0.png';
            }
            if ($type == 8) {
                $img = '/style/images/weapon/ring/0.png';
            }
              if ($type == 9) {
                $img = '/style/images/weapon/ring/0.png';
            }
            echo '<div class="img_weapon"><img src="' . $img . '" class="weapon_0" alt=""/></div><div class="weapon_height"><img src="/style/images/quality/1.png" alt=""/><span style="color: #bfbfbf;"><b>Пустой слот</b></span></div></div>';
            if ($all['id'] == $user['id']) {
                echo "<a href='/armors' class='button_green_a'>Купить снаряжение</a>";


            }
            echo '<div style="padding-top: 5px;"></div>';
        } else {
            echo '<div class="error">Неверный тип оружия!</div>';
        }
    } else {
        echo '<div class="error">Неверный тип оружия!</div>';
    }
    echo '</div>';
}
if ($type == 7 or $type == 8 or $type == 9) {
    echo '<div class="body_list">';
    echo '<div class="menulist">';
    if ($user['id'] != $all['id']) {
        echo '<div class="line_1"></div>';
        echo "<li><a href='/hero/$all[id]'><img src='/style/images/user/user.png' alt=''/> $all[nick]</a></li>";
    }
    if ($type == 7) {
        $t = $type + 1;
        echo '<div class="line_1"></div>';
        echo "<li><a href='/weapon_me/$all[id]/?type=$t'><img src='/style/images/body/forward.png' alt=''/> Следующее снаряжение</a></li>";
    }
    if ($type == 8) {
        $t = $type + 1;
        echo '<div class="line_1"></div>';
        echo "<li><a href='/weapon_me/$all[id]/?type=$t'><img src='/style/images/body/back.png' alt=''/> Следующее снаряжение</a></li>";
    }
        if ($type == 9) {
        $t = $type - 2;
        echo '<div class="line_1"></div>';
        echo "<li><a href='/weapon_me/$all[id]/?type=$t'><img src='/style/images/body/back.png' alt=''/> Предыдущее снаряжение</a></li>";
    }
    echo '</div>';
    echo '</div>';
}
echo '</div>';
require_once H . 'system/footer.php';
?>