<?php
require_once '../../system/system.php';
require_once '../../avenax/Maneken.php';
require_once '../../avenax/Item.php';
$head = 'Бой';
only_reg();
boss_level();
boss_campaign();
require_once H . 'system/head.php';
echo '<div class="page">';
#-Выборка данных о бое-#
$sel_boss_users = $pdo->prepare("SELECT * FROM `boss_users` WHERE `user_id` = :user_id");
$sel_boss_users->execute(array(':user_id' => $user['id']));
if ($sel_boss_users->rowCount() != 0) {
    $boss_u = $sel_boss_users->fetch(PDO::FETCH_LAZY);

#-Делаем выборку боя монстра-#
    $sel_boss_battle = $pdo->prepare("SELECT * FROM `boss_battle` WHERE `id` = :id");
    $sel_boss_battle->execute(array(':id' => $boss_u['battle_id']));
    if ($sel_boss_battle->rowCount() != 0) {
        $battle = $sel_boss_battle->fetch(PDO::FETCH_LAZY);

#-Выборка данных о Боссе-#
        $sel_boss = $pdo->prepare("SELECT * FROM `boss` WHERE `id` = :boss_id");
        $sel_boss->execute(array(':boss_id' => $battle['boss_id']));
        $boss = $sel_boss->fetch(PDO::FETCH_LAZY);
#-Если уровень больше или равен Боссу-#
        if ($user['level'] >= $boss['level']) {
#-Цвет имени Босса-#
            if ($boss['type'] == 1) {
                $color = '#d1d1d1';
                $img = 1;
            }
            if ($boss['type'] == 2) {
                $color = '#9b71b1';
                $img = 2;
            }
            if ($boss['type'] == 3) {
                $color = '#ff0000';
                $img = 3;
            }
            if ($boss['type'] == 4) {
                $color = '#048bbd';
                $img = 4;
            }

#-БОСС-#
            echo '<div class="block_hunting">';
            echo "<img src='$boss[images]' class='img_m_battle' alt='$boss[name]'/><div class='block_monsters'><img src='/style/images/monstru/quality/$img.png' alt=''/><span style='color: $color;'><b>$boss[name]</b></span><br/><div class='param_monsters'><img src='/style/images/user/sila.png' alt=''/>$boss[sila] <img src='/style/images/user/zashita.png' alt=''/>$boss[zashita] <img src='/style/images/user/health.png' alt=''/>$battle[boss_t_health]</div></div>";
            echo "<div class='hp_bar_monster'><div class='health2' style='width:" . round(100 / ($boss['health'] / $battle['boss_health'])) . "%'><div class='health' style='width:" . round(100 / ($battle['boss_health'] / $battle['boss_t_health'])) . "%'></div></div></div>";

#-Оружие игрока-#
            echo '<div style="padding-top: 10px;"></div>';
            echo '<div class="line_1"></div>';
            echo '<div class="body_list">';
            echo '<div style="padding-top: 3px;"></div>';
            if ($boss_u['user_t_health'] > 0) {
#-Выборка оружия для боя которое сейчас надето-#
                $sel_weapon_me = $pdo->prepare("SELECT * FROM `item_user` WHERE `user_id` = :user_id AND `type` = :type AND `state` = :state");
                $sel_weapon_me->execute(array(':user_id' => $user['id'], ':state' => 1));
                $weapon_me = $sel_weapon_me->fetch(PDO::FETCH_LAZY);
#-Если есть оружие-#

                $weapon = Item::getItemOneType($user['id'], ['weapons_1', 'weapons_2']);

                if (!empty($weapon)) {
#-Выборка данных о оружие-#
                    echo "<center><a href='/boss_attack?act=attc'><img width='48' src='/images/items/" . $weapon['weapon_id'] . ".png' class='" . Item::getColor($weapon['weapon_id']) . "' alt=''/></a> " . ($user['start'] == 6 ? "<img src='/style/images/body/left.gif' alt=''/>" : "") . "</center>";
                } else {
                    echo "<center><a href='/boss_attack?act=attc'><img src='/style/images/weapon/arm/0_0.png' class='weapon_me' alt=''/></a> " . ($user['start'] == 6 ? "<img src='/style/images/body/left.gif' alt=''/>" : "") . "</center>";
                }
            }

#-Воскрешение-#
            if ($boss_u['user_t_health'] == 0) {
                if (isset($_GET['inv'])) {
                    echo "<a href='/buy_boss?act=res&boss_u=$boss_u[id]' class='button_green_a'>Да воскреснуть</a>";
                    echo '<div style="padding-top: 5px;"></div>';
                } else {
                    $vosk = ($boss['id'] * 10) + 20;
                    echo "<a href='/boss_battle?inv=1' class='button_green_a'>Воскреснуть за <img src='/style/images/many/gold.png' alt=''>" . ($user['gold'] < $vosk ? "<span class='red'>$vosk</span>" : "$vosk") . "</a>";
                    echo '<div style="padding-top: 5px;"></div>';
                }
            }
            echo '</div>';

#-ГЕРОЙ-#
            echo '<div class="line_1"></div>';
            if ($boss_u['user_t_health'] != 0) {
                echo "<img src='" . avatar_img_min($user['avatar'], $user['pol']) . "' class='img_h_battle' width='50' height='50' alt=''/><div class='block_monsters'><img src='/style/images/user/user.png' alt=''/><span class='name_monsters'>$user[nick]</span><br/><div class='param_monsters'><img src='/style/images/user/sila.png' alt=''/>" . ($user['sila'] + $user['s_sila'] + $user['sila_bonus']) . " <img src='/style/images/user/zashita.png' alt=''/>" . ($user['zashita'] + $user['s_zashita'] + $user['zashita_bonus']) . " <img src='/style/images/user/health.png' alt=''/>$boss_u[user_t_health]</div></div>";
                echo "<div class='hp_bar_users'><div class='health2' style='width:" . round(100 / (($user['health'] + $user['s_health'] + $user['health_bonus']) / $boss_u['user_health'])) . "%'><div class='health' style='width:" . round(100 / ($boss_u['user_health'] / $boss_u['user_t_health'])) . "%'></div></div></div>";
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
                        if ($boss_u['user_isp'] >= time()) {
                            $ostatok = $boss_u['user_isp'] - time();
                            echo "<li><a href='/boss_battle'><span class='white'><img src='$potions[images]' width='13' height='13'/> $potions[name] [$potions_me[quatity] шт.] (" . (int)($ostatok % 60) . " сек.)</span></a></li>";
                        } else {
                            echo "<li><a href='/potions_boss?act=isp&id=$potions_me[potions_id]'><img src='$potions[images]' width='13' height='13'/> $potions[name] [$potions_me[quatity] шт.]</a></li>";
                        }
                        echo '</div>';
                        echo '</div>';
                    }
                }
            } else {
                echo "<div style='opacity: .5;'><img src='" . avatar_img_min($user['avatar'], $user['pol']) . "' class='img_h_battle' width='50' height='50' alt=''/><div class='block_monsters'><img src='/style/images/user/user.png' alt=''/><span class='name_monsters'>$user[nick]</span><br/><div class='param_monsters'><img src='/style/images/user/sila.png' alt=''/>" . ($user['sila'] + $user['s_sila'] + $user['sila_bonus']) . " <img src='/style/images/user/zashita.png' alt=''/>" . ($user['zashita'] + $user['s_zashita'] + $user['zashita_bonus']) . " <img src='/style/images/user/health.png' alt=''/>$boss_u[user_t_health]</div></div></div>";
            }

#-Помощь-#
            echo '<div class="body_list">';
            echo '<div class="menulist">';
#-Если этот игрок создал бой-#
            if ($boss_u['glava'] == 1) {

#-Кол-во участников которые помогают-#
                $sel_boss_y = $pdo->prepare("SELECT COUNT(*) FROM `boss_users` WHERE `battle_id` = :battle_id");
                $sel_boss_y->execute(array(':battle_id' => $battle['id']));
                $boss_y = $sel_boss_y->fetch(PDO::FETCH_LAZY);
                if ($boss_y[0] < 5) {
                    echo '<div class="line_1"></div>';
                    echo "<li><a href='/boss_help'><img src='/style/images/body/help.png' alt=''/> Позвать на помощь</a></li>";
                }
#-Кол-во живых помощников-#
                $sel_users_live = $pdo->prepare("SELECT COUNT(*) FROM `boss_users` WHERE `battle_id` = :battle_id AND `user_t_health` > 0 AND `user_id` != :user_id");
                $sel_users_live->execute(array(':battle_id' => $battle['id'], ':user_id' => $user['id']));
                $users_live = $sel_users_live->fetch(PDO::FETCH_LAZY);
#-Все помощники-#
                $sel_users_all = $pdo->prepare("SELECT COUNT(*) FROM `boss_users` WHERE `battle_id` = :battle_id AND `user_id` != :user_id");
                $sel_users_all->execute(array(':battle_id' => $battle['id'], ':user_id' => $user['id']));
                $users_all = $sel_users_all->fetch(PDO::FETCH_LAZY);
#-Урон который наносят-#
                $sel_uron = $pdo->prepare("SELECT SUM(uron) FROM `boss_users` WHERE `battle_id` = :battle_id AND `user_id` != :user_id");
                $sel_uron->execute(array(':battle_id' => $battle['id'], ':user_id' => $user['id']));
                $uron = $sel_uron->fetch(PDO::FETCH_LAZY);

                echo '<div class="line_1"></div>';
                if ($users_all[0] > 0) {
                    echo "<div class='svg_list'><img src='/style/images/user/user.png' alt=''/> Помогают: <span class='green'>$users_live[0]</span> из <span class='white'>$users_all[0]</span> (<img src='/style/images/body/attack.png' alt=''/>$uron[0])</div>";
                } else {
                    echo '<div class="svg_list"><img src="/style/images/user/user.png" alt=""/> Помогают: <span class="white">0</span></div>';
                }
            } else {
                echo '<div class="line_1"></div>';
                echo "<div class='svg_list'><img src='/style/images/body/help.png' alt=''/> Вы помогаете</div>";
            }
#-Покидание боя-#
            echo '<div class="line_1"></div>';
            if (isset($_GET['conf']) and $_GET['conf'] == 'exit') {
                echo "<li><a href='/boss_exit?act=exit'><img src='/style/images/body/ok.png' alt=''/> Подтверждаю</a></li>";
            } else {
                echo "<li><a href='/boss_battle?conf=exit'><img src='/style/images/body/error.png' alt=''/> Покинуть бой</a></li>";
            }
            echo '</div>';
            echo '</div>';

#-Лог боя-#
            $sel_boss_log = $pdo->prepare("SELECT * FROM `boss_log` WHERE `battle_id` = :battle_id ORDER BY `time` DESC LIMIT 5");
            $sel_boss_log->execute(array(':battle_id' => $battle['id']));
            if ($sel_boss_log->rowCount() != 0) {
                echo '<div class="line_1"></div>';
                while ($boss_log = $sel_boss_log->fetch(PDO::FETCH_LAZY)) {
                    echo '<div class="body_list"><div style="padding: 2px; padding-left: 5px;">';
                    echo "$boss_log[log]";
                    echo '</div>';
                    echo '</div>';
                }
            }
        }
    }
} else {
    echo '<div class="line_1"></div>';
    echo '<div class="body_list">';
    echo '<div class="error_list">';
    echo '<img src="/style/images/body/error.png" alt=""/>Бой окончен!';
    echo '</div>';
    echo '</div>';
}
echo '</div>';
require_once H . 'system/footer.php';
?>