<?php
require_once '../../system/system.php';
$head = 'Колизей';
only_reg();
coliseum_level();
require_once H . 'system/head.php';
require_once H . 'avenax/Base.php';
echo '<div class="page">';

#-Игроки которые в очереди-#
$sel_osh_u = $pdo->query("SELECT COUNT(*) FROM `coliseum` WHERE `statys` = 0");
$osh_u = $sel_osh_u->fetch(PDO::FETCH_LAZY);
$col = new Base();
#-Участвует игрок в бою или нет-#
$sel_coliseum_me = $pdo->prepare("SELECT * FROM `coliseum` WHERE `user_id` = :user_id");
$sel_coliseum_me->execute([':user_id' => $user['id']]);
if ($sel_coliseum_me->rowCount() == 0) {

    echo '<img src="/style/images/location/coliseum.jpg" class="img" alt=""/>';
    echo '<div style="padding-top: 3px;"></div>';
    echo '<center><span class="whit">"Сражайся пока последний оппонент не будет повержен."</span></center>';
    echo '<div style="padding-top: 3px;"></div>';
    echo '<a href="/coliseum_start?act=start" class="button_green_a">Встать в очередь</a>';
    echo '<div style="padding-top: 3px;"></div>';

    echo '<div class="body_list">';
    echo '<div class="line_1"></div>';
    echo '<div class="info_list"><img src="/style/images/user/user.png" alt=""/>В очереди: ' . $osh_u[0] . '</div>';
    echo '</div>';
} else {
    $coliseum_me = $sel_coliseum_me->fetch(PDO::FETCH_LAZY);

    #-Если бой не начат-#
    if ($coliseum_me['statys'] < 2) {

        #-Очередь-#
        if ($coliseum_me['statys'] == 0) {
            #-Выборка сколько игроков участвует в сражении-#
            $sel_coliseum_a = $pdo->prepare("SELECT COUNT(*) FROM `coliseum` WHERE `battle_id` = :battle_id");
            $sel_coliseum_a->execute([':battle_id' => $coliseum_me['battle_id']]);
            $coliseum_a = $sel_coliseum_a->fetch(PDO::FETCH_LAZY);

            $check = $col->checkUserColiseum($coliseum_me['battle_id'], $user);
            echo '<img src="/style/images/location/coliseum.jpg" class="img" alt=""/>';
            echo '<div style="padding-top: 3px;"></div>';
            echo "<center><span class='gray'><img src='/style/images/user/user.png' alt=''/>Очередь: $coliseum_a[0]/5</span></center>";
            echo '<div style="padding-top: 3px;"></div>';
            echo '<a href="/coliseum" class="button_green_a">Обновить</a>';
            echo '<div style="padding-top: 2px;"></div>';
            echo '<a href="/coliseum_exit?act=exit_osh" class="button_red_a">Покинуть очередь</a>';
            echo '<div style="padding-top: 3px;"></div>';
            echo '<div class="body_list">';
            echo '<div class="line_1"></div>';
            echo '<div class="info_list"><img src="/style/images/user/user.png" alt=""/>В очереди: ' . $osh_u[0] . '</div>';
            echo '</div>';
        }

        #-Начало боя-#
        if ($coliseum_me['statys'] == 1) {
            #-Время ожидания-#
            $sel_coliseum_t = $pdo->prepare("SELECT `id`, `battle_id`, `time` FROM `coliseum` WHERE `battle_id` = :battle_id ORDER BY `id`");
            $sel_coliseum_t->execute([':battle_id' => $coliseum_me['battle_id']]);
            $coliseum_t = $sel_coliseum_t->fetch(PDO::FETCH_LAZY);
            $coliseum_ostatok = $coliseum_t['time'] - time();//Сколько времени осталось

            echo '<div class="body_list">';
            #-Выборка всех участников сражения-#
            $sel_coliseum_u = $pdo->prepare("SELECT * FROM `coliseum` WHERE `battle_id` = :battle_id ORDER BY `level` DESC, `id`");
            $sel_coliseum_u->execute([':battle_id' => $coliseum_me['battle_id']]);
            while ($coliseum_u = $sel_coliseum_u->fetch(PDO::FETCH_LAZY)) {
                #-Выборка данных игрока-#
                $sel_users = $pdo->prepare("SELECT `id`, `avatar`, `pol`, `nick`, `level`, `time_online`, `param` FROM `users` WHERE `id` = :user_id");
                $sel_users->execute([':user_id' => $coliseum_u['user_id']]);
                $all = $sel_users->fetch(PDO::FETCH_LAZY);
                if (empty($all)) {
                    $all = $col->infoBots($user);
                }
                echo '<div class="line_1"></div>';
                echo '<div class="menulitl">';
                echo "<li><a href='/hero/$all[id]'> <img src='" . avatar_img_min($all['avatar'], $all['pol']) . "' class='menulitl_img' width='30' height='30' alt=''/><div class='menulitl_block'>" . online($all['time_online']) . " <span class='menulitl_name'>" . ($all['id'] == $user['id'] ? "<span class='green'>$all[nick]</span>" : "$all[nick]") . "</span><br/><div class='menulitl_param'><img src='/style/images/user/level.png' alt=''/>$all[level] <img src='/style/images/body/all.png' alt=''/>$all[param]</div></div></a></li>";
                echo '</div>';
            }
            echo '</div>';
            echo '<div class="line_1"></div>';
            $ostatok = timers($coliseum_ostatok);
            echo '<div style="padding-top: 3px;"></div>';
            echo '<a href="/coliseum" class="button_green_a">' . ($ostatok == '' ? "Обновить" : "$ostatok") . '</a>';
            echo '<div style="padding-top: 3px;"></div>';
        }

    } else {

        #-Бой-#
        if ($coliseum_me['statys'] != 3) {
            #-Данные оппонента-#
            //

            $sel_users_op = $pdo->prepare("SELECT `id`, `avatar`, `pol`, `nick`, `level`, `sila`, `s_sila`, `sila_bonus`, `zashita`, `s_zashita`, `zashita_bonus`, `health`, `s_health`, `health_bonus` FROM `users` WHERE `id` = :id");
            $sel_users_op->execute([':id' => $coliseum_me['ank_id']]);
            $all = $sel_users_op->fetch(PDO::FETCH_LAZY);
            if (empty($all)) {
                $all = $col->infoBots($user);
            }


            $sel_coliseum_all = $pdo->prepare("SELECT `id`, `user_id`, `ank_id`, `user_health`, `user_t_health` FROM `coliseum` WHERE `user_id` = :ank_id");
            $sel_coliseum_all->execute([':ank_id' => $coliseum_me['ank_id']]);
            $coliseum_all = $sel_coliseum_all->fetch(PDO::FETCH_LAZY);
            debug($coliseum_all);
            echo '<div class="block_hunting">';
            #-Оппонент-#
            echo "<img src='" . avatar_img_min($all['avatar'], $all['pol']) . "' class='img_h_battle' width='50' height='50' alt=''/><div class='block_monsters'><img src='/style/images/user/user.png' alt=''/><b>$all[nick]</b> <span style='font-size: 13px;'>[$all[level] ур.]</span><br/><div class='param_monsters'><img src='/style/images/user/sila.png' alt=''/>" . ($all['sila'] + $all['s_sila'] + $all['sila_bonus']) . " <img src='/style/images/user/zashita.png' alt=''/>" . ($all['zashita'] + $all['s_zashita'] + $all['zashita_bonus']) . " <img src='/style/images/user/health.png' alt=''/>$coliseum_all[user_t_health]</div></div>";
            echo "<div class='hp_bar_monster'><div class='health2' style='width:" . round(100 / (($all['health'] + $all['s_health'] + $all['health_bonus']) / $coliseum_all['user_health'])) . "%'><div class='health' style='width:" . round(100 / ($coliseum_all['user_health'] / $coliseum_all['user_t_health'])) . "%'></div></div></div>";

            #-Оружие-#
            echo '<div style="padding-top: 10px;"></div>';
            echo '<div class="line_1"></div>';
            echo '<div class="body_list">';
            echo '<div style="padding-top: 3px;"></div>';
            #-Выборка оружия для боя которое сейчас надето-#
            $sel_weapon_me = $pdo->prepare("SELECT * FROM `weapon_me` WHERE `user_id` = :user_id AND `type` = :type AND `state` = :state");
            $sel_weapon_me->execute([':user_id' => $user['id'], ':type' => '5', ':state' => '1']);
            $weapon_me = $sel_weapon_me->fetch(PDO::FETCH_LAZY);
            #-Если есть оружие-#
            if ($sel_weapon_me->rowCount() != 0) {
                #-Выборка данных о оружие-#
                $sel_weapon = $pdo->prepare("SELECT * FROM `weapon` WHERE `id` = :id");
                $sel_weapon->execute([':id' => $weapon_me['weapon_id']]);
                $weapon = $sel_weapon->fetch(PDO::FETCH_LAZY);
                echo "<center><a href='/coliseum_attack?act=attc'><img src='$weapon[images]' class='" . ramka($weapon_me['max_level']) . "' alt=''/></a></center>";
            } else {
                echo "<center><a href='/coliseum_attack?act=attc'><img src='/style/images/weapon/arm/0_0.png' class='weapon_me' alt=''/></a></center>";
            }
            echo '<div style="padding-top: 3px;"></div>';
            echo '</div>';
            echo '<div class="line_1"></div>';

            #-Герой-#
            echo "<img src='" . avatar_img_min($user['avatar'], $user['pol']) . "' class='img_h_battle' width='50' height='50' alt=''/><div class='block_monsters'><img src='/style/images/user/user.png' alt=''/><span class='name_monsters'>$user[nick]</span><br/><div class='param_monsters'><img src='/style/images/user/sila.png' alt=''/>" . ($user['sila'] + $user['s_sila'] + $user['sila_bonus']) . " <img src='/style/images/user/zashita.png' alt=''/>" . ($user['zashita'] + $user['s_zashita'] + $user['zashita_bonus']) . " <img src='/style/images/user/health.png' alt=''/>$coliseum_me[user_t_health]</div></div>";
            echo "<div class='hp_bar_users'><div class='health2' style='width:" . round(100 / (($user['health'] + $user['s_health'] + $user['health_bonus']) / $coliseum_me['user_health'])) . "%'><div class='health' style='width:" . round(100 / ($coliseum_me['user_health'] / $coliseum_me['user_t_health'])) . "%'></div></div></div>";
            echo '<div style="padding-top: 10px;"></div>';
            echo '</div>';

            echo '<div class="body_list">';
            echo '<div class="menulist">';
            #-Игроки которые бьют текущего игрока-#
            $sel_atk_u = $pdo->prepare("SELECT COUNT(*) FROM `coliseum` WHERE `ank_id` = :user_id AND `battle_id` = :battle_id");
            $sel_atk_u->execute([':user_id' => $user['id'], ':battle_id' => $coliseum_me['battle_id']]);
            $amount_atk = $sel_atk_u->fetch(PDO::FETCH_LAZY);
            #-Игроки которые участвуют в бою-#
            $sel_ysh_u = $pdo->prepare("SELECT COUNT(*) FROM `coliseum` WHERE `battle_id` = :battle_id AND `statys` = 2 AND `user_t_health` > 0 AND `user_id` != :user_id");
            $sel_ysh_u->execute([':battle_id' => $coliseum_me['battle_id'], ':user_id' => $user['id']]);
            $amount_ysh = $sel_ysh_u->fetch(PDO::FETCH_LAZY);
            echo '<div class="line_1"></div>';
            echo "<li><a href='/coliseum_me?act=me'><img src='/style/images/body/league.png' alt=''/> Бьют меня <span class='white'>(" . $amount_atk[0] . ")</span></a></li>";
            echo '<div class="line_1"></div>';
            echo "<li><a href='/coliseum_next?act=next'><img src='/style/images/body/refresh.png' alt=''/> Другой <span class='white'>(" . $amount_ysh[0] . ")</span></a></li>";
            echo '</div>';
            echo '</div>';

            #-Лог событий-#
            $sel_coliseum_l = $pdo->prepare("SELECT * FROM `coliseum_log` WHERE `battle_id` = :battle_id ORDER BY `time` DESC LIMIT 5");
            $sel_coliseum_l->execute([':battle_id' => $coliseum_me['battle_id']]);
            if ($sel_coliseum_l->rowCount() != 0) {
                echo '<div class="line_1"></div>';
                while ($coliseum_l = $sel_coliseum_l->fetch(PDO::FETCH_LAZY)) {
                    echo '<div class="body_list"><div style="padding: 2px;padding-left: 5px;">';
                    if ($coliseum_l['user_id'] == $user['id']) {
                        echo "<span class='green'> $coliseum_l[log]</span>";
                    } else {
                        echo "<span class='red'> $coliseum_l[log]</span>";
                    }
                    echo '</div></div>';
                }
            }

        } else {
            #-Статистика боя-#
            echo '<div class="body_list">';
            #-Выборка всех участников сражения и их результаты-#
            $sel_coliseum_u = $pdo->prepare("SELECT * FROM `coliseum` WHERE `battle_id` = :battle_id ORDER BY `rang` DESC, `id`");
            $sel_coliseum_u->execute([':battle_id' => $coliseum_me['battle_id']]);
            while ($coliseum_u = $sel_coliseum_u->fetch(PDO::FETCH_LAZY)) {
                #-Выборка данных игрока-#
                $sel_users = $pdo->prepare("SELECT `id`, `avatar`, `pol`, `nick`, `level`, `time_online` FROM `users` WHERE `id` = :user_id");
                $sel_users->execute([':user_id' => $coliseum_u['user_id']]);
                $all = $sel_users->fetch(PDO::FETCH_LAZY);
                if (empty($coliseum_u['user_id'])) {
                    $all = $col->infoBots($user);
                }
                debug($all);
                echo '<div class="line_1"></div>';
                echo '<div class="menulitl">';
                echo "<li><a href='/hero/$all[id]'> <img src='" . avatar_img_min($all['avatar'], $all['pol']) . "' class='menulitl_img' width='30' height='30' alt=''/><div class='menulitl_block'>" . online($all['time_online']) . " <span class='menulitl_name'>" . ($all['id'] == $user['id'] ? "<span class='green'>$all[nick]</span>" : "$all[nick]") . "</span> " . ($coliseum_u['user_t_health'] == 0 ? '<span class="red">Мертв(а)</span>' : '') . "<br/><div class='menulitl_param'><img src='/style/images/body/coliseum.png' alt=''/>+$coliseum_u[rang] " . ($coliseum_u['user_t_health'] > 0 ? '<img src="/style/images/user/health.png" alt=""/>' . $coliseum_u['user_t_health'] . '' : '<img src="/style/images/user/health.png" alt=""/>0') . " <img src='/style/images/user/exp.png' alt=''/>$coliseum_u[exp] <img src='/style/images/many/silver.png' alt=''/>$coliseum_u[silver]</div></div></a></li>";
                echo '</div>';
            }
            echo '</div>';
            echo '<div class="line_1"></div>';
            echo '<div style="padding-top: 3px;"></div>';
            echo '<a href="/coliseum_exit?act=exit_stk" class="button_red_a">Покинуть</a>';
            echo '<div style="padding-top: 3px;"></div>';
        }
    }
}
echo '</div>';
require_once H . 'system/footer.php';
?>