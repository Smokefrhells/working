<?php
require_once '../../system/system.php';
$head = 'Замки';
echo only_reg();
echo zamki_level();
require_once H . 'system/head.php';
echo '<div class="page">';
echo '<img src="/style/images/location/zamki.jpg" class="img" alt=""/>';
echo '<div style="padding-top: 3px;"></div>';
#-Выборка данных замка-#
$sel_zamki = $pdo->query("SELECT * FROM `zamki`");
if ($sel_zamki->rowCount() != 0) {
    $zamki = $sel_zamki->fetch(PDO::FETCH_LAZY);

    #-Здоровье замков-#
    $health_right = num_format($zamki['health_t_right']);
    $health_left = num_format($zamki['health_t_left']);
    #-Кол-во участников-#
    $sel_zamki_g = $pdo->query("SELECT COUNT(*) FROM `zamki_users`");
    $zamki_g = $sel_zamki_g->fetch(PDO::FETCH_LAZY);
} else {
    $health_right = 0;
    $health_left = 0;
}

#-Выборка игроков замка-#
$sel_zamki_u = $pdo->prepare("SELECT `id`, `storona`, `user_id` FROM `zamki_users` WHERE `user_id` = :user_id");
$sel_zamki_u->execute([':user_id' => $user['id']]);
$zamki_u = $sel_zamki_u->fetch(PDO::FETCH_LAZY);

echo '<center>';
echo '<span class="yellow">';
echo '<img src="/style/images/body/zamki.png" alt=""/>Замок Правых: <img src="/style/images/user/health.png" alt=""/>' . ($zamki_u['storona'] == 'right' ? "<u>$health_right</u>" : "$health_right") . '<br/>';
echo '<img src="/style/images/body/zamki.png" alt=""/>Замок Левых: <img src="/style/images/user/health.png" alt=""/>' . ($zamki_u['storona'] == 'left' ? "<u>$health_left</u>" : "$health_left") . '<br/>';
if ($sel_zamki->rowCount() != 0) {
    echo '<div style="padding-top: 3px;"></div>';
    echo '<a href="/zamki_users" class="button_green_a">Участники: ' . ($zamki_g[0]) . ' </a>';
}
echo '</span>';
echo '<div style="padding-top: 3px;"></div>';

#-Начало сражения-#
if ($sel_zamki->rowCount() == 0) {
    echo '<a href="/zamki_start?act=start" class="button_green_a">Начать сражение</a>';
} else {
    $zamki_ostatok = $zamki['time'] - time(); //время сражения

    #-Сражение еще не начато-#
    if ($zamki['statys'] == 0) {
        echo '<a href="/zamki_battle" class="button_red_a"><img src="/style/images/body/time.png" alt=""/>До сражения: ' . timers($zamki_ostatok) . '</a>';
    } else {
        echo '<div class="button_red_a"><img src="/style/images/body/time.png" alt=""/>До конца: ' . timers($zamki_ostatok) . '</div>';
        #-Участвуем в сражении или нет-#
        if ($sel_zamki_u->rowCount() != 0) {
            echo '<div style="padding-top: 3px;"></div>';
            echo '<a href="/zamki_battle" class="button_green_a">Атаковать</a>';
        }
    }
    echo '<div style="padding-top: 3px;"></div>';
    #-Участвуем в сражении или нет-#
    if ($zamki['statys'] == 0) {
        if ($sel_zamki_u->rowCount() == 0) {
            echo '<a href="/zamki_join?act=join" class="button_green_a">Участвовать</a>';
        }
    }
}
echo '<div style="padding-top: 3px;"></div>';
echo '</center>';

echo '<div class="body_list">';
#-Игрок с найбольшим уроном-#
echo '<div class="menulitl">';
echo '<div class="line_1"></div>';
#-Выборка игрока с найбольшим уроном-#
$sel_uron = $pdo->query("SELECT `id`, `nick`, `zamki_uron`, `avatar`, `pol` FROM `users` WHERE `zamki_uron` > 0 ORDER BY `zamki_uron` DESC");
if ($sel_uron->rowCount() != 0) {
    $uron = $sel_uron->fetch(PDO::FETCH_LAZY);
    echo "<li><a href='/hero/$uron[id]'><img src='" . avatar_img_min($uron['avatar'], $uron['pol']) . "' class='menulitl_img' width='30' height='30' alt=''/><div class='menulitl_block'><img src='/style/images/user/user.png'><span class='menulitl_name'>$uron[nick]</span><br/><div class='menulitl_param'><img src='/style/images/body/attack.png' alt=''/>" . num_format($uron['zamki_uron']) . "</div></div></a></li>";
} else {
    echo '<div class="info_list"><img src="/style/images/body/error.png" alt=""/>Лидер по урону отсутствует!</div>';
}
echo '</div>';

echo '<div class="line_1"></div>';
echo "<div class='info_list'><img src='/style/images/body/attack.png' alt=''/>Урон за прошлый бой: " . num_format($user['zamki_uron']) . "</div>";
echo '<div class="line_1"></div>';
echo "<div class='info_list'><img src='/style/images/body/ok.png' alt=''/><span class='green'>$user[zamki_pobeda]</span> <img src='/style/images/body/error.png' alt=''/><span class='red'>$user[zamki_progrash]</span></div>";
echo '</div>';
echo '</div>';
require_once H . 'system/footer.php';
?>