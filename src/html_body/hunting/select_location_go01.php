<?php
require_once '../../system/system.php';
$head = 'Выбор локации';
only_reg();
hunting_campaign();
require_once H . 'system/head.php';
echo '<div class="page">';
#-Проверяем в бою мы или нет-#
$sel_hunting_b = $pdo->prepare("SELECT * FROM `hunting_battle` WHERE `user_id` = :user_id");
$sel_hunting_b->execute([':user_id' => $user['id']]);
#-Если не в бою то продолжаем-#
if ($sel_hunting_b->rowCount() == 0) {
    #-Выборка локаций для охоты-#
    $sel_hunting = $pdo->query("SELECT * FROM `hunting`");
    while ($hunting = $sel_hunting->fetch(PDO::FETCH_LAZY)) {
        #-Только если уровень локации больше или равен нашему-#
        if ($user['level'] >= $hunting['level']) {
            #-Какая локация-#
            if ($hunting['id'] == 1) {
                $hunting_t = $user['hunting_1'];
                $hunting_b = $user['hunting_b_1'];
                $hunting_o = 10 - $user['hunting_1'];
                $hunting_g = round($hunting_o / 2, 0);
            }
            if ($hunting['id'] == 2) {
                $hunting_t = $user['hunting_2'];
                $hunting_b = $user['hunting_b_2'];
                $hunting_o = 10 - $user['hunting_2'];
                $hunting_g = round($hunting_o, 0);
            }
            if ($hunting['id'] == 3) {
                $hunting_t = $user['hunting_3'];
                $hunting_b = $user['hunting_b_3'];
                $hunting_o = 10 - $user['hunting_3'];
                $hunting_g = round($hunting_o + 2, 0);
            }
            if ($hunting['id'] == 4) {
                $hunting_t = $user['hunting_4'];
                $hunting_b = $user['hunting_b_4'];
                $hunting_o = 10 - $user['hunting_4'];
                $hunting_g = round($hunting_o + 4, 0);
            }
            if ($hunting['id'] == 5) {
                $hunting_t = $user['hunting_5'];
                $hunting_b = $user['hunting_b_5'];
                $hunting_o = 10 - $user['hunting_5'];
                $hunting_g = round($hunting_o + 8, 0);
            }
            if ($hunting['id'] == 6) {
                $hunting_t = $user['hunting_6'];
                $hunting_b = $user['hunting_b_6'];
                $hunting_o = 10 - $user['hunting_6'];
                $hunting_g = round($hunting_o + 14, 0);
            }
            if ($hunting['id'] == 7) {
                $hunting_t = $user['hunting_7'];
                $hunting_b = $user['hunting_b_7'];
                $hunting_o = 10 - $user['hunting_7'];
                $hunting_g = round($hunting_o + 20, 0);
            }
            echo "<img src='$hunting[images]' class='img'/>";
            echo "<div class='hunting_name'><img src='/style/images/quality/$hunting[quality].png' alt=''/><span style='color:$hunting[color];'><span style='font-size: 16px;'>$hunting[name]</span><br/><img src='/style/images/body/attack.png' alt=''/>Боев: $hunting_t из 10<br/><img src='/style/images/body/league.png' alt=''/>Всего боев: $hunting_b</span></div>";

            #-Принадлежит ли локация игроку-#
            if ($hunting['user_id'] != 0) {
                $sel_users = $pdo->prepare("SELECT `id`, `nick` FROM `users` WHERE `id` = :user_id");
                $sel_users->execute([':user_id' => $hunting['user_id']]);
                $all = $sel_users->fetch(PDO::FETCH_LAZY);
                $hunting_battle = $hunting['time_battle'] - time();
                echo "<center><img src='/style/images/body/error.png'/><a href='/hunting_battle_u?loc=$hunting[id]'><span style='color:$hunting[color];'>Локация захвачена:</span></a> <a href='/hero/$all[id]'>" . ($all['id'] == $user['id'] ? "<span class='green'>$all[nick]</span>" : "<span class='red'>$all[nick]</span>") . "</a><br/>";
                echo "<span style='color:$hunting[color];'>";
                #-Сколько времени осталось до сражения-#
                if ($hunting['statys_battle'] == 0) {
                    echo '<img src="/style/images/body/time.png"/>До сражения: ' . (int)($hunting_battle / 3600) . ' час. ' . (int)($hunting_battle / 60 % 60) . ' мин.<br/>';
                }
                #-Количество участников-#
                $sel_amount = $pdo->prepare("SELECT COUNT(*) FROM `hunting_battle_u` WHERE `location` = :location");
                $sel_amount->execute([':location' => $hunting['id']]);
                $amount = $sel_amount->fetch(PDO::FETCH_LAZY);
                echo "<a href='/hunting_users?loc=$hunting[id]'><img src='/style/images/user/user.png' alt=''/><span style='color:$hunting[color]'>Участников:</span></a> $amount[0]";

                echo '</span></center>';
                echo '<div style="padding-top: 3px;"></div>';
                #-Не учавствуем в бою за локацию-#
                $sel_hunting_u = $pdo->prepare("SELECT * FROM `hunting_battle_u` WHERE `user_id` = :user_id");
                $sel_hunting_u->execute([':user_id' => $user['id']]);
                if ($sel_hunting_u->rowCount() == 0) {
                    if ($hunting['user_id'] != $user['id'] and $hunting['statys_battle'] == 0) {
                        #-Не должно быть захвачено других локаций-#
                        $sel_hunting_z = $pdo->prepare("SELECT * FROM `hunting` WHERE `user_id` = :user_id");
                        $sel_hunting_z->execute([':user_id' => $user['id']]);
                        if ($sel_hunting_z->rowCount() == 0) {
                            if ($user['level'] < $hunting['level'] + 15) {
                                echo '<center><a href="/create_battle?act=battle&loc=' . $hunting['id'] . '" class="button_green_a">Сражаться</a></center>';
                                echo '<div style="padding-top: 3px;"></div>';
                            }
                        }
                    }
                } else {
                    if ($hunting['statys_battle'] == 0) {
                        $sel_hunting_u = $pdo->prepare("SELECT * FROM `hunting_battle_u` WHERE `user_id` = :user_id AND `location` = :location");
                        $sel_hunting_u->execute([':user_id' => $user['id'], ':location' => $hunting['id']]);
                        if ($sel_hunting_u->rowCount() != 0) {
                            echo '<center><div class="button_green_a">Участвуете в бою</a></div>';
                        }
                    }
                    echo '<div style="padding-top: 3px;"></div>';
                }
            } else {
                echo "<center><img src='/style/images/body/ok.png' alt=''/><a href='/hunting_battle_u?loc=$hunting[id]'><span style='color:$hunting[color];'>Локация не захвачена</span></a></center>";
                echo '<div style="padding-top: 3px;"></div>';
                #-Если нет захваченных локаций-#
                $sel_hunting_c = $pdo->prepare("SELECT * FROM `hunting` WHERE `user_id` = :user_id");
                $sel_hunting_c->execute([':user_id' => $user['id']]);
                if ($sel_hunting_c->rowCount() == 0) {
                    #-Если наш уровень не низкий-#
                    if ($user['level'] < $hunting['level'] + 15) {
                        echo '<a href="/buy_location?act=buy&loc=' . $hunting['id'] . '" class="button_green_a">Выкупить за <img src="/style/images/many/gold.png" alt="">' . ($hunting['id'] * 40) . '</a>';
                        echo '<div style="padding-top: 3px;"></div>';
                    }
                }
            }

            #-Проверяем стоит время или нет-#
            $sel_hunting_t = $pdo->prepare("SELECT * FROM `hunting_time` WHERE `user_id` = :user_id AND `hunting_id` = :hunting_id");
            $sel_hunting_t->execute([':user_id' => $user['id'], ':hunting_id' => $hunting['id']]);
            if ($sel_hunting_t->rowCount() == 0) {
                echo '<center><a href="/start_hunting?act=battle&loc=' . $hunting['id'] . '" class="button_green_a">Охотиться</a></center>';
                echo '<div style="padding-top: 3px;"></div>';
                echo '<center><a href="/hunting_attack_auto?act=attc&loc=' . $hunting['id'] . '" class="button_green_a">' . $hunting_o . ' боев за <img src="/style/images/many/gold.png" alt=""/>' . $hunting_g . '</a></center>';
                echo '<div style="padding-top: 5px;"></div>';
            } else {
                $hunting_time = $sel_hunting_t->fetch(PDO::FETCH_LAZY);
                #-Сколько времени осталось-#
                $hunting_ostatok = $hunting_time['hunting_time'] - time();

                #-Золото за ускорение времени-#
                $hour = floor($hunting_ostatok / 3600);
                if ($hour <= 0) {
                    $min = round(((($hunting_ostatok / 60 % 60) * 60) / 35), 0);
                    if ($min < 1) {
                        $gold_time = 1;
                    } else {
                        $gold_time = round($min, 0);
                    }
                } else {
                    $minut = ($hunting_ostatok / 60 % 60) * 60;
                    $hou = ($hour * 3600);
                    $summa = round($minut + $hou, 0);
                    $gold_time = round($summa / 35, 0);
                }

                if ($hunting_time['hunting_time'] >= time()) {
                    if ($_GET['hunt_id'] != $hunting['id']) {
                        if ($hunting['id'] > 5) {
                            echo '<center><a href="/select_location?accel=1&hunt_id=' . $hunting['id'] . '" class="button_green_a"><img src="/style/images/body/time.png" alt=""/>' . (int)($hunting_ostatok / 3600) . ' час. ' . (int)($hunting_ostatok / 60 % 60) . ' мин. ' . ($hunting_ostatok % 60) . ' сек.</a></center>';
                        } else {
                            echo '<center><a href="/select_location?accel=1&hunt_id=' . $hunting['id'] . '" class="button_green_a"><img src="/style/images/body/time.png" alt=""/>' . (int)($hunting_ostatok / 60 % 60) . ' мин. ' . ($hunting_ostatok % 60) . ' сек.</a></center>';
                        }
                        echo '<div style="padding-top: 5px;"></div>';
                    } else {
                        if ($_GET['hunt_id'] == $hunting['id']) {
                            echo '<center><a href="/buy_hunting?act=accel&hunting_id=' . $hunting['id'] . '" class="button_green_a">Ускорить за <img src="/style/images/many/gold.png" alt=""/>' . $gold_time . '</a></center>';
                            echo '<div style="padding-top: 3px;"></div>';
                            echo '<center><a href="/select_location" class="button_red_a">Отменить</a></center>';
                            echo '<div style="padding-top: 5px;"></div>';
                        }
                    }
                } else {
                    echo '<center><a href="/start_hunting?act=battle&loc=' . $hunting['id'] . '" class="button_green_a">Охотиться</a></center>';
                    echo '<div style="padding-top: 5px;"></div>';
                }
            }
        } else {
            echo '<div class="line_3"></div>';
            echo '<div style="background: #000000; opacity: .3; padding-bottom: 3px;">';
            echo "<img src='$hunting[images]' class='img'/><div class='hunting_name'><img src='/style/images/quality/$hunting[quality].png' alt=''/><span style='color:$hunting[color]'>$hunting[name]<br/>Доступно с <img src='/style/images/user/level.png' alt=''/>$hunting[level]</span></div>";
            echo '<center><div class="button_green_a">Охотиться</div></center>';
            echo '<div style="padding-top: 5px;"></div>';
            echo '</div>';
        }
    }
} else {
    echo '<div style="padding-top: 3px;"></div>';
    echo "<center><a href='/hunting_battle' class='button_green_a'>Продолжить бой</a></center>";
    echo '<div style="padding-top: 3px;"></div>';
}
echo '</div>';
require_once H . 'system/footer.php';
?>
