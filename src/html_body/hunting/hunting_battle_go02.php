<?php
require_once '../../system/system.php';
$head = 'Бой';
only_reg();
hunting_campaign();
require_once H . 'system/head.php';
require_once H . 'avenax/Maneken.php';
require_once H . 'avenax/Item.php';
echo '<div class="page">';
#-Выборка данных о бое-#
$sel_hunting_battle = $pdo->prepare("SELECT * FROM `hunting_battle` WHERE `user_id` = :user_id");
$sel_hunting_battle->execute(array(':user_id' => $user['id']));
if ($sel_hunting_battle->rowCount() != 0) {
    $battle = $sel_hunting_battle->fetch(PDO::FETCH_LAZY);
#-Выборка данных о монстре-#
    $sel_monsters = $pdo->prepare("SELECT * FROM `monsters` WHERE `id` = :monstr_id");
    $sel_monsters->execute(array(':monstr_id' => $battle['monstr_id']));
    $monsters = $sel_monsters->fetch(PDO::FETCH_LAZY);
#-Цвет имени монстра-#
    if ($monsters['type'] == 0) {
        $color = '#d1d1d1';
        $img = '1';
    }
    if ($monsters['type'] == 1) {
        $color = '#7490b7';
        $img = '4';
    }
    if ($monsters['type'] == 2) {
        $color = '#9b71b1';
        $img = '3';
    }
#-Вывод данных-#
    echo '<div class="block_hunting">';
#-Монстр-#
    echo "<img src='$monsters[images]' class='img_m_battle' alt='$monsters[name]'/><div class='block_monsters'><img src='/style/images/monstru/quality/$img.png' alt='Охота'/><span style='color: $color;'><b>$monsters[name]</b></span><br/><div class='param_monsters'><img src='/style/images/user/sila.png' alt='Нож' title='Сила'>$monsters[sila] <img src='/style/images/user/zashita.png' alt='Щит' title='Защита'>$monsters[zashita] <img src='/style/images/user/health.png' alt='Сердце' title='Здоровья'>$battle[monstr_t_health]</div></div>";
    echo "<div class='hp_bar_monster'><div class='health2' style='width:" . round(100 / ($monsters['health'] / $battle['monstr_health'])) . "%'><div class='health' style='width:" . round(100 / ($battle['monstr_health'] / $battle['monstr_t_health'])) . "%'></div></div></div>";
#-Оружие-#
    echo '<div style="padding-top: 10px;"></div>';
    echo '<div class="line_1"></div>';
    echo '<div class="body_list">';
    echo '<div style="padding-top: 3px;"></div>';
#-Выборка оружия для боя которое сейчас надето-#
//    $sel_weapon_me = $pdo->prepare("SELECT COUNT(*) FROM `item_user` WHERE `user_id` = :user_id AND `weapon_id` = :weapon_id AND `state` = :state");
//    $sel_weapon_me->execute(array(':user_id' => $user['id'], ':weapon_id' => Maneken::$idItemReg, ':state' => '1'));
    $weapon = Item::getItemOneType($user['id'], ['weapons_1', 'weapons_2']);
#-Если есть оружие-#
    if (!empty($weapon)) {
        echo "<center>" . ($user['start'] == 1 ? "<div style='margin-left:25px;'><a href='/hunting_attack?act=attc'><img width='48' src='/images/items/" . $weapon['weapon_id'] . ".png' class='" . Item::getColor($weapon['weapon_id']) . "' alt=''/></a> <img src='/style/images/body/left.gif' alt=''/></div>" : "<a href='/hunting_attack?act=attc'><img width='48' src='/images/items/" . $weapon['weapon_id'] . ".png' class='" . Item::getColor($weapon['weapon_id']) . "' alt=''/></a>") . "</center>";
    } else {
        echo "<center><a href='/hunting_attack?act=attc'><img src='/style/images/weapon/arm/0_0.png' class='weapon_me' alt=''/></a></center>";
    }
    echo '</div>';
    echo '<div class="line_1"></div>';
#-Герой-#
    echo "<img src='" . avatar_img_min($user['avatar'], $user['pol']) . "' class='img_h_battle' width='50' height='50' alt=''/><div class='block_monsters'><img src='/style/images/user/user.png' alt='$users[nick]'/><span class='name_monsters'>$user[nick]</span><br/><div class='param_monsters'><img src='/style/images/user/sila.png' alt='Нож' title='Сила'>" . ($user['sila'] + $user['s_sila'] + $user['sila_bonus']) . " <img src='/style/images/user/zashita.png' alt='Щит' title='Защита'>" . ($user['zashita'] + $user['s_zashita'] + $user['zashita_bonus']) . " <img src='/style/images/user/health.png' alt='Сердце' title='Здоровья'>$battle[user_t_health]</div></div>";
    echo "<div class='hp_bar_users'><div class='health2' style='width:" . round(100 / (($user['health'] + $user['s_health'] + $user['health_bonus']) / $battle['user_health'])) . "%'><div class='health' style='width:" . round(100 / ($battle['user_health'] / $battle['user_t_health'])) . "%'></div></div></div>";
    echo '<div style="padding-top: 10px;"></div>';
    echo '</div>';
#-Зелье которое есть-#
    $sel_potions_me = $pdo->prepare("SELECT * FROM `potions_me` WHERE `user_id` = :user_id AND (`potions_id` = 1 OR `potions_id` = 2 OR `potions_id` = 3) ORDER BY `potions_id`");
    $sel_potions_me->execute(array(':user_id' => $user['id']));
#-Если есть зелье-#
    if ($sel_potions_me->rowCount() != 0) {
        echo '<div class="line_4"></div>';
        while ($potions_me = $sel_potions_me->fetch(PDO::FETCH_LAZY)) {
            $sel_potions = $pdo->prepare("SELECT * FROM `potions` WHERE `id` = :id");
            $sel_potions->execute(array(':id' => $potions_me['potions_id']));
            $potions = $sel_potions->fetch(PDO::FETCH_LAZY);
            echo '<div class="body_list">';
            echo '<div class="menulist">';
            echo '<div class="line_1"></div>';
#-Если время восстановления здоровья еще не прошло-#
            if ($battle['user_isp'] >= time()) {
                $ostatok = $battle['user_isp'] - time();
                echo "<li><a href='/hunting_battle'><span class='white'><img src='$potions[images]' width='13' height='13'/> $potions[name] [$potions_me[quatity] шт.] (" . (int)($ostatok % 60) . " сек.)</span></a></li>";
            } else {
                echo "<li><a href='/potions_hunting?act=isp&id=$potions_me[potions_id]'><img src='$potions[images]' width='13' height='13'/> $potions[name] [$potions_me[quatity] шт.]</a></li>";
            }
            echo '</div>';
            echo '</div>';
        }
    }

#-Покидаем бой-#
    if ($user['start'] > 2) {
        echo '<div class="body_list">';
        echo '<div class="menulist">';
#-Подтверждение-#
        if (isset($_GET['conf']) and $_GET['conf'] == 'exit') {
            echo '<div class="line_1"></div>';
            echo '<li><a href="/hunting_exit?act=exit"><img src="/style/images/body/ok.png" alt=""/> Подтверждаю</a></li>';
        } else {
            echo '<div class="line_1"></div>';
            echo '<li><a href="/hunting_battle?conf=exit"><img src="/style/images/body/error.png" alt=""/> Покинуть бой</a></li>';
        }
        echo '</div>';
        echo '</div>';
    }
}
echo '</div>';
require_once H . 'system/footer.php';
?>