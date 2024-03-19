<?php
require_once '../../system/system.php';
$head = 'Магазин снаряжения';
echo only_reg();
echo trade_shop_campaign();
require_once H . 'system/head.php';

echo '<div class="page">';
echo '<img src="/style/images/location/trade_shop/armor.jpg" class="img"/>';

#-Навигация-#
$head = '<a href="/armor?type=1" style="text-decoration:none;"><span class="body_sel">' . (($_GET['type'] == 1 or $_GET['type'] > 6 or !isset($_GET['type'])) ? "<b>Голова</b>" : "Голова") . '</span></a>';
$body = '<a href="/armor?type=2" style="text-decoration:none;"><span class="body_sel">' . ($_GET['type'] == 2 ? "<b>Торс</b>" : "Торс") . '</span></a>';
$gloves = '<a href="/armor?type=3" style="text-decoration:none;"><span class="body_sel">' . ($_GET['type'] == 3 ? "<b>Руки</b>" : "Руки") . '</span></a>';
$shield = '<a href="/armor?type=4" style="text-decoration:none;"><span class="body_sel">' . ($_GET['type'] == 4 ? "<b>Защита</b>" : "Защита") . '</span></a>';
$arm = '<a href="/armor?type=5" style="text-decoration:none;"><span class="body_sel">' . ($_GET['type'] == 5 ? "<b>Оружие</b>" : "Оружие") . '</span></a>';
$legs = '<a href="/armor?type=6" style="text-decoration:none;"><span class="body_sel">' . ($_GET['type'] == 6 ? "<b>Ноги</b>" : "Ноги") . '</span></a>';

#-Вывод-#
echo '<div class="body_list">';
echo '<div style="padding: 5px;">';
echo '' . $all . ' ' . $head . ' ' . $body . ' ' . $gloves . ' ' . $shield . ' ' . $arm . ' ' . $legs . ' ' . $bijouterie . '';
echo '</div>';
echo '<div class="line_1"></div>';
echo '</div>';

#-Тип снаряжения-#
if ($_GET['type'] > 6 or !isset($_GET['type'])) {
    $type = 1;
} else {
    $type = check($_GET['type']);
}

#-Выборка данных снаряжения-#
$sel_weapon = $pdo->prepare("SELECT * FROM `weapon` WHERE `type` = :type AND (`no_magaz` = 0 OR `no_magaz` = 10) ORDER BY `level`");
$sel_weapon->execute(array(':type' => $type));
if ($sel_weapon->rowCount() != 0) {
    while ($weapon = $sel_weapon->fetch(PDO::FETCH_LAZY)) {
#-Достаточно золота или нет-#
        if ($user['gold'] < $weapon['gold']) {
            $color_g = '#ff0000';
        } else {
            $color_g = '#e6c4ad';
        }
#-Достаточно серебра или нет-#
        if ($user['silver'] < $weapon['silver']) {
            $color_s = '#ff0000';
        } else {
            $color_s = '#e6c4ad';
        }
#-Достаточно праздничных монет или нет-#
        if ($user['snow'] < $weapon['snow']) {
            $color_sn = '#ff0000';
        } else {
            $color_sn = '#e6c4ad';
        }

#-Снаряжение которое надето-#
        $sel_weapon_me2 = $pdo->prepare("SELECT * FROM `weapon_me` WHERE `type` = :type AND `user_id` = :user_id AND `state` = 1");
        $sel_weapon_me2->execute(array(':type' => $type, ':user_id' => $user['id']));
        $weapon_me2 = $sel_weapon_me2->fetch(PDO::FETCH_LAZY);
#-Выбор параметров снаряжения-#
        $sel_weapon2 = $pdo->prepare("SELECT * FROM `weapon` WHERE `id` = :weapon_id");
        $sel_weapon2->execute(array(':weapon_id' => $weapon_me2['weapon_id']));
        $nadeto = $sel_weapon2->fetch(PDO::FETCH_LAZY);
        $nadeto_p = $nadeto['sila'] + $nadeto['zashita'] + $nadeto['health'];
        $weapon_p = $weapon['sila'] + $weapon['zashita'] + $weapon['health'];
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

#-Вывод снаряжения-#
        echo '<div class="img_weapon"><img src="' . $weapon['images'] . '" class="' . ramka($weapon['level']) . '"  alt=""/></div>';
        echo '<div class="weapon_setting">';
        echo '<span style="color: ' . color_w($weapon['level']) . ';"><img src="' . star($weapon['level']) . '" alt=""/><b>' . $weapon['name'] . '</b> ' . ($user['level'] >= $weapon['level'] ? "<span class='green'>[$weapon[level] ур.]</span>" : "<span class='red'>[$weapon[level] ур.]</span>") . ' ' . ($weapon['no_magaz'] == 10 ? "<span class='green'>[Праздничный]</span>" : "") . '</span><br/>';
        echo '<div class="weapon_param"><img src="/style/images/user/sila.png" alt=""/>' . $weapon['sila'] . ' <img src="/style/images/user/zashita.png" alt=""/>' . $weapon['zashita'] . ' <img src="/style/images/user/health.png" alt=""/>' . $weapon['health'] . ' ' . $w_param . '<br/>' . ($weapon['no_magaz'] == 10 ? "Праздничная валюта" : "<img src='/style/images/many/gold.png' alt=''/><span style='color: $color_g;'>$weapon[gold]</span> <img src='/style/images/many/silver.png' alt=''/><span style='color: $color_s;'>$weapon[silver]</span>") . '<br/>';
        echo '</div></div>';
        echo '<div style="padding-top: 10px;"></div>';
#-Покупка снаряжения-#
        if ($user['level'] >= $weapon['level']) {
            echo "<a href='/armor_buy?act=buy&id=$weapon[id]' class='button_green_a'>Купить</a>";
        }
        echo '<div style="padding-top: 5px;"></div>';
    }

} else {
    echo '<div class="error_list">Ошибка! Снаряжение недоступно.</div>';
}

echo '<div class="body_list">';
echo '<div class="menulist">';
echo '<div class="line_1"></div>';
echo "<li><a href='/trade_shop'><img src='/style/images/body/back.png' alt=''/> Торговая лавка</a></li>";
echo '</div>';
echo '</div>';
echo '</div>';
require_once H . 'system/footer.php';
?>