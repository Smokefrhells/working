<?php
require_once '../../system/system.php';
$head = 'Бой';
only_reg();
require_once H . 'system/head.php';
echo '<div class="page">';
#-Выборка данных текущего героя-#
$sel_hunting_b_u = $pdo->prepare("SELECT * FROM `hunting_battle_u` WHERE `user_id` = :user_id");
$sel_hunting_b_u->execute([':user_id' => $user['id']]);
if ($sel_hunting_b_u->rowCount() != 0) {
    $battle_u = $sel_hunting_b_u->fetch(PDO::FETCH_LAZY);

    #-Локация на которой происходит бой-#
    $sel_hunting = $pdo->prepare("SELECT * FROM `hunting` WHERE `id` = :location");
    $sel_hunting->execute([':location' => $battle_u['location']]);
    $hunting = $sel_hunting->fetch(PDO::FETCH_LAZY);

    #-Бой не начат - Статус боя 0-#
    if ($hunting['statys_battle'] == 0) {
        echo '<div class="block_hunting">';
        echo "<img src='" . avatar_img_min($user['avatar'], $user['pol']) . "' class='img_h_battle' width='50' height='50' alt=''/><div class='block_monsters'><img src='/style/images/user/user.png' alt=''/><span class='name_monsters'>$user[nick]</span><br/><div class='param_monsters'><img src='/style/images/user/sila.png' alt=''/>" . ($user['sila'] + $user['s_sila'] + $user['sila_bonus']) . " <img src='/style/images/user/zashita.png' alt=''/>" . ($user['zashita'] + $user['s_zashita'] + $user['zashita_bonus']) . " <img src='/style/images/user/health.png' alt=''/>$user[health]</div></div>";
        echo "<div class='hp_bar_users'><div class='health2' style='width:" . round(100 / (100 / 100)) . "%'><div class='health' style='width:" . round(100 / (100 / 100)) . "%'></div></div></div>";
        echo '</div>';
        echo '<div style="padding-top: 10px;"></div>';
    } else {
        #-Бой начат - Статус боя 1-#
        #-Если есть враг-#
        if ($battle_u['ank_id'] != 0) {
            $ank_id = $battle_u['ank_id'];
            $sel_vrag = $pdo->prepare("SELECT * FROM `hunting_battle_u` WHERE `user_id` = :ank_id");
            $sel_vrag->execute([':ank_id' => $ank_id]);
            if ($sel_vrag->rowCount() == 0) {
                $upd_battle_u = $pdo->prepare("UPDATE `hunting_battle_u` SET `ank_id` = 0 WHERE `user_id` = :user_id");
                $upd_battle_u->execute([':user_id' => $user['id']]);
            }
        }
        #-Если нет-#
        if ($battle_u['ank_id'] == 0) {
            $sel_vrag = $pdo->prepare("SELECT * FROM `hunting_battle_u` WHERE `location` = :location AND `user_id` != :user_id ORDER BY RAND()");
            $sel_vrag->execute([':location' => $battle_u['location'], ':user_id' => $user['id']]);
            $vrag = $sel_vrag->fetch(PDO::FETCH_LAZY);
            if ($sel_vrag->rowCount() != 0) {
                $upd_battle_u = $pdo->prepare("UPDATE `hunting_battle_u` SET `ank_id` = :ank_id WHERE `user_id` = :user_id");
                $upd_battle_u->execute([':ank_id' => $vrag['user_id'], ':user_id' => $user['id']]);
                $ank_id = $vrag['user_id'];
            }
        }

        #-Выборка данных врага-#
        $sel_users_op = $pdo->prepare("SELECT `id`, `sila`, `zashita`, `health`, `s_sila`, `s_zashita`, `s_health`, `sila_bonus`, `zashita_bonus`, `health_bonus`, `pol`, `level`, `nick`, `avatar` FROM `users` WHERE `id` = :id");
        $sel_users_op->execute([':id' => $ank_id]);
        $all = $sel_users_op->fetch(PDO::FETCH_LAZY);
        #-Выборка данных в базе боя-#
        $sel_hunting_b_v = $pdo->prepare("SELECT * FROM `hunting_battle_u` WHERE `user_id` = :all_id");
        $sel_hunting_b_v->execute([':all_id' => $all['id']]);
        $battle_v = $sel_hunting_b_v->fetch(PDO::FETCH_LAZY);

        echo '<div class="block_hunting">';
        #-Враг-#
        echo "<img src='" . avatar_img_min($all['avatar'], $all['pol']) . "' class='img_h_battle' width='50' height='50' alt=''/><div class='block_monsters'><img src='/style/images/user/user.png' alt=''/><b>$all[nick]</b> <span style='font-size: 13px;'>[$all[level] ур.]</span><br/><div class='param_monsters'><img src='/style/images/user/sila.png' alt=''/>" . ($all['sila'] + $all['s_sila'] + $all['sila_bonus']) . " <img src='/style/images/user/zashita.png' alt=''/>" . ($all['zashita'] + $all['s_zashita'] + $all['zashita_bonus']) . " <img src='/style/images/user/health.png' alt=''/>$battle_v[user_t_health]</div></div>";
        echo "<div class='hp_bar_monster'><div class='health2' style='width:" . round(100 / (($all['health'] + $all['s_health'] + $all['health_bonus']) / $battle_v['user_health'])) . "%'><div class='health' style='width:" . round(100 / ($battle_v['user_health'] / $battle_v['user_t_health'])) . "%'></div></div></div>";

        #-Оружие героя-#
        echo '<div style="padding-top: 10px;"></div>';
        echo '<div class="line_1"></div>';
        echo '<div class="body_list">';
        echo '<div style="padding-top: 3px;"></div>';
        #-Выборка оружия для боя которое сейчас надето-#
        $sel_weapon_me = $pdo->prepare("SELECT * FROM `weapon_me` WHERE `user_id` = :user_id AND `type` = :type AND `state` = :state");
        $sel_weapon_me->execute([':user_id' => $user['id'], ':type' => 5, ':state' => 1]);
        $weapon_me = $sel_weapon_me->fetch(PDO::FETCH_LAZY);
        #-Если есть оружие-#
        if ($sel_weapon_me->rowCount() != 0) {
            #-Выборка данных о оружие-#
            $sel_weapon = $pdo->prepare("SELECT * FROM `weapon` WHERE `id` = :id");
            $sel_weapon->execute([':id' => $weapon_me['weapon_id']]);
            $weapon = $sel_weapon->fetch(PDO::FETCH_LAZY);
            echo "<center><a href='/hunting_attack_u?act=attc'><img src='$weapon[images]' class='" . ramka($weapon_me['max_level']) . "' alt=''/></a></center>";
        } else {
            echo "<center><a href='/hunting_attack_u?act=attc'><img src='/style/images/weapon/arm/0_0.png' class='weapon_me' alt=''/></a></center>";
        }
        echo '<div style="padding-top: 3px;"></div>';
        echo '</div>';
        echo '<div class="line_1"></div>';

        #-Выборка данных текущего героя-#
        $sel_users_me = $pdo->prepare("SELECT `id`, `sila`, `zashita`, `health`, `s_sila`, `s_zashita`, `s_health`, `pol`, `level`, `nick`, `avatar` FROM `users` WHERE `id` = :id");
        $sel_users_me->execute([':id' => $user['id']]);
        $me = $sel_users_me->fetch(PDO::FETCH_LAZY);
        #-Герой-#
        echo "<img src='" . avatar_img_min($me['avatar'], $me['pol']) . "' class='img_h_battle' width='50' height='50' alt=''/><div class='block_monsters'><img src='/style/images/user/user.png' alt=''/><span class='name_monsters'>$me[nick]</span><br/><div class='param_monsters'><img src='/style/images/user/sila.png' alt=''/>" . ($me['sila'] + $me['s_sila'] + $me['sila_bonus']) . " <img src='/style/images/user/zashita.png' alt=''/>" . ($me['zashita'] + $me['s_zashita'] + $me['zashita_bonus']) . " <img src='/style/images/user/health.png' alt=''/>$battle_u[user_t_health]</div></div>";
        echo "<div class='hp_bar_users'><div class='health2' style='width:" . round(100 / (($me['health'] + $me['s_health'] + $me['health_bonus']) / $battle_u['user_health'])) . "%'><div class='health' style='width:" . round(100 / ($battle_u['user_health'] / $battle_u['user_t_health'])) . "%'></div></div></div>";
        echo '</div>';

        echo '<div style="padding-top: 10px;"></div>';
        echo '</div>';

        #-Нижнее меню-#
        echo '<div class="body_list">';
        echo '<div class="menulist">';
        #-Зелье которое есть-#
        $sel_potions_me = $pdo->prepare("SELECT * FROM `potions_me` WHERE `user_id` = :user_id AND (`potions_id` = 1 OR `potions_id` = 2 OR `potions_id` = 3) ORDER BY `potions_id`");
        $sel_potions_me->execute([':user_id' => $user['id']]);
        #-Если есть зелье-#
        if ($sel_potions_me->rowCount() != 0) {
            while ($potions_me = $sel_potions_me->fetch(PDO::FETCH_LAZY)) {
                #-Выборка зелья-#
                $sel_potions = $pdo->prepare("SELECT * FROM `potions` WHERE `id` = :id");
                $sel_potions->execute([':id' => $potions_me['potions_id']]);
                $potions = $sel_potions->fetch(PDO::FETCH_LAZY);
                echo '<div class="line_1"></div>';
                #-Время задержки использования-#
                if ($battle_u['user_isp'] >= time()) {
                    $ostatok = $battle_u['user_isp'] - time();
                    echo "<li><a href='/hunting_battle_u'><span class='white'><img src='$potions[images]' width='13' height='13'/> $potions[name] [$potions_me[quatity] шт.] (" . (int)($ostatok % 60) . " сек.)</span></a></li>";
                } else {
                    echo "<li><a href='/hunting_potions_u?act=isp&id=$potions_me[potions_id]'><img src='$potions[images]' width='13' height='13'/> $potions[name] <span class='white'>($potions_me[quatity] шт.)</span></a></li>";
                }
            }
        }

        #-Игроки которые бьют текущего героя-#
        $sel_atk_u = $pdo->prepare("SELECT COUNT(*) FROM `hunting_battle_u` WHERE `ank_id` = :user_id");
        $sel_atk_u->execute([':user_id' => $user['id']]);
        $amount_atk = $sel_atk_u->fetch(PDO::FETCH_LAZY);
        echo '<div class="line_1"></div>';
        echo "<li><a href='/battle_me?act=me'><img src='/style/images/body/league.png' alt=''/> Бьют меня <span class='white'>(" . $amount_atk[0] . ")</span></a></li>";

        #-Выборка другого игрока-#
        echo '<div class="line_1"></div>';
        echo "<li><a href='/battle_me?act=next'><img src='/style/images/body/refresh.png' alt=''/> Другой</a></li>";
        echo '</div>';
        echo '</div>';

        #-Лог боя во время боя-#
        $sel_log = $pdo->prepare("SELECT * FROM `hunting_log` WHERE `location` = :location ORDER BY `time` DESC LIMIT 4");
        $sel_log->execute([':location' => $battle_u['location']]);
        if ($sel_log->rowCount() != 0) {
            echo '<div class="line_1"></div>';
            while ($log = $sel_log->fetch(PDO::FETCH_LAZY)) {
                echo '<div class="body_list"><div style="padding: 2px;">';
                echo "$log[log]";
                echo '</div></div>';
            }
        }
    }
} else {
    #-Вывод лога боя локации если мертвы-#
    echo '<div class="body_list">';
    if (isset($_GET['loc'])) {
        $location = check($_GET['loc']);
    } else {
        $sel_log_me = $pdo->prepare("SELECT `id`, `user_id`, `location` FROM `hunting_log` WHERE `user_id` = :user_id");
        $sel_log_me->execute([':user_id' => $user['id']]);
        if ($sel_log_me->rowCount() != 0) {
            $log_me = $sel_log_me->fetch(PDO::FETCH_LAZY);
            $location = $log_me['location'];
        }
    }
    #-Кол-во сообщений лога-#
    $sel_count = $pdo->prepare("SELECT COUNT(*) FROM `hunting_log` WHERE `location` = :location");
    $sel_count->execute([':location' => $location]);
    $amount = $sel_count->fetch(PDO::FETCH_LAZY);
    #-Действия постраничной навигации-#
    $num = 10;
    $page = $_GET['page'];
    $posts = $amount[0];
    $total = intval(($posts - 1) / $num) + 1;
    $page = intval($page);
    if (empty($page) or $page < 0)
        $page = 1;
    if ($page > $total)
        $page = $total;
    $start = $page * $num - $num;
    $sel_log = $pdo->prepare("SELECT * FROM `hunting_log` WHERE `location` = :location ORDER BY `time` DESC LIMIT $start, $num");
    $sel_log->execute([':location' => $location]);
    #-Если есть записи-#
    if ($sel_log->rowCount() != 0) {
        while ($log = $sel_log->fetch(PDO::FETCH_LAZY)) {
            echo '<div style="padding: 2px;">';
            echo "$log[log]";
            echo '</div>';
        }
        #-Постраничная навигация-#
        if ($posts > $num) {
            $action = "&loc=$location";
            echo '<div class="line_1"></div>';
            $z = pages($posts, $total, $action);
        }
    } else {
        echo '<div class="line_1"></div>';
        echo '<div class="error_list">';
        echo '<img src="/style/images/body/error.png" alt=""/>Лог боя не найден!';
        echo '</div>';
    }
    echo '</div>';
}
echo '</div>';
require_once H . 'system/footer.php';
?>