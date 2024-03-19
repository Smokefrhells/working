<?php
require_once '../../system/system.php';
$head = 'Пирамида Тэпа';
only_reg();
require_once H . 'system/head.php';
echo '<div class="page">';

#-Есть бой или нет-#
$sel_pyramid_b = $pdo->query("SELECT * FROM `pyramid_battle_b`");
if ($sel_pyramid_b->rowCount() == 0) {
    echo '<img src="/style/images/location/pyramid.jpg" class="img" alt=""/>';
    echo '<div style="padding-top: 3px;"></div>';
    echo '<div class="button_red_a">Запись в 18:00</div>';
    echo '<div style="padding-top: 3px;"></div>';
    echo '<div class="line_1"></div>';
    echo '<div class="body_list">';
    echo '<div class="menulist">';
    echo '<li><a href="/pyramid_log"><img src="/style/images/body/ok.png" alt=""/>Лог предыдущего боя</a></li>';
    echo '</div>';
    echo '<div class="line_1"></div>';
    echo '<div class="info_list"><img src="/style/images/body/attack.png" alt=""/>Сражение проводится ежедневно в <img src="/style/images/body/time.png" alt=""/>18:00 по игровому времени</div>';
    echo '</div>';
} else {
    $pyramid_b = $sel_pyramid_b->fetch(PDO::FETCH_LAZY);

#-Данные опонента-#
    echo '<div class="t_max">';
    echo '<img src="' . $pyramid_b['images'] . '" class="t_img" width="50px" height="50px" alt=""/>';
    echo '<div class="t_name"><img src="/style/images/body/pyramid.png" width="13px" height="13px" alt=""/><b>' . $pyramid_b['name'] . '</b><br/><span class="t_param"><img src="/style/images/user/sila.png" alt=""/>' . $pyramid_b['sila'] . ' <img src="/style/images/user/zashita.png" alt=""/>' . $pyramid_b['zashita'] . ' <img src="/style/images/user/health.png" alt=""/>' . $pyramid_b['health'] . '</div>';
    echo '</div>';

    echo '<div class="line_1"></div>';
    echo '<div class="mini-line"></div>';
    echo '<div class="line_1_v"></div>';

#-Участвуем в бою или нет-#	
    $sel_pyramid_u = $pdo->prepare("SELECT * FROM `pyramid_battle_u` WHERE `user_id` = :user_id");
    $sel_pyramid_u->execute(array(':user_id' => $user['id']));
    if ($sel_pyramid_u->rowCount() == 0) {
        echo '<div class="body_list">';
        echo '<div style="padding-top: 3px;"></div>';
        echo '<a href="/pyramid_join?act=join" class="button_green_a">Принять участие</a>';
        echo '<div style="padding-top: 3px;"></div>';
        echo '</div>';
    } else {
        $pyramid_u = $sel_pyramid_u->fetch(PDO::FETCH_LAZY);

        echo '<div class="body_list">';
        echo '<div style="padding-top: 3px;"></div>';

#-Атака или воскрешение-#
        if ($pyramid_u['zamor'] == 0) {
            if ($pyramid_u['health'] > 0) {
                echo '<a href="/pyramid_attack?act=attc" class="button_red_a">Атаковать</a>';
            } else {
                if ($pyramid_u['vosk'] < 10) {
                    echo '<a href="/pyramid_vosk?act=vosk" class="button_green_a">Воскреснуть за <img src="/style/images/many/gold.png" alt=""/>150</a>';
                } else {
                    echo '<a href="/pyramid" class="button_red_a">Бой для вас закончен!</a>';
                }
            }
        } else {
            echo '<a href="/pyramid" class="button_red_a">' . timers($pyramid_u['zamor'] - time()) . '</a>';
        }
        echo '<div style="padding-top: 3px;"></div>';
        echo '<div class="line_1"></div>';
        echo '<div class="mini-line"></div>';
        echo '<div class="line_1_v"></div>';
        echo '</div>';

#-Данные игрока-#
        echo '<div class="t_max">';
        echo '<img src="' . $pyramid_u['images'] . '" class="t_img" width="50px" height="50px" alt=""/>';
        echo '<div class="t_name"><img src="/style/images/user/user.png" width="13px" height="13px" alt=""/><b>' . $pyramid_u['name'] . '</b><br/><span class="t_param"><img src="/style/images/user/sila.png" alt=""/>' . $pyramid_u['sila'] . ' <img src="/style/images/user/zashita.png" alt=""/>' . $pyramid_u['zashita'] . ' <img src="/style/images/user/health.png" alt=""/>' . $pyramid_u['health'] . '</div>';
        echo '</div>';

#-Лечение-#
        if ($pyramid_u['lesh'] < 10 and $pyramid_u['health'] > 0) {
            echo '<div class="line_1"></div>';
            echo '<div class="body_list">';
            echo '<div class="menulist">';
            echo '<li><a href="/pyramid_lesh?act=lesh">Лечить <img src="/style/images/user/health.png" alt=""/>50% за <img src="/style/images/many/gold.png" alt=""/>100</a></li>';
            echo '</div>';
            echo '</div>';
        }

#-Лог боя-#
        $sel_pyramid_l = $pdo->query("SELECT * FROM `pyramid_battle_l` ORDER BY `id` DESC LIMIT 3");
        if ($sel_pyramid_l->rowCount() != 0) {
            echo '<div class="line_1"></div>';
            while ($pyramid_l = $sel_pyramid_l->fetch(PDO::FETCH_LAZY)) {
                echo '<div class="body_list"><div style="padding: 2px;padding-left: 5px;">';
                echo "<span class='red'> $pyramid_l[log]</span>";
                echo '</div></div>';
            }
        }

    }
    echo '</div>';

#-Время до конца боя-#
    echo '<div class="line_1"></div>';
    echo '<div class="body_list">';
    echo '<div class="info_list"><img src="/style/images/body/time.png" alt=""/>До конца боя осталось: ' . timers($pyramid_b['time'] - time()) . '</div>';
    echo '</div>';
    echo '</div>';
}
require_once H . 'system/footer.php';
?>