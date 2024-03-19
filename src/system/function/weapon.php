<?php
#-Цвет шрифта названия оружия-#
function color_w($max_level) {
    if ($max_level >= 0) {
        $style_kashestvo = '#bfbfbf';
    }
    if ($max_level >= 20) {
        $style_kashestvo = '#18b40c';
    }
    if ($max_level >= 40) {
        $style_kashestvo = '#e2b70b';
    }
    if ($max_level >= 60) {
        $style_kashestvo = '#2066ce';
    }
    if ($max_level >= 80) {
        $style_kashestvo = '#921ece';
    }
    if ($max_level >= 100) {
        $style_kashestvo = '#ff0000';
    }
    return $style_kashestvo;
}

function debug($str) {
    global $user;
    if ($user['id'] == 805) {
        echo '<pre>';
        print_r($str);
        echo '</pre>';
    }
}

function color_w_new($max_level) {
    if ($max_level == 1) {
        $style_kashestvo = '#bfbfbf';
    }
    if ($max_level == 2) {
        $style_kashestvo = '#18b40c';
    }
    if ($max_level == 3) {
        $style_kashestvo = '#2066ce';
    }
    if ($max_level == 4) {
        $style_kashestvo = '#921ece';
    }
    if ($max_level == 5) {
        $style_kashestvo = '#2066ce';
    }
    if ($max_level == 6) {
        $style_kashestvo = '#ff0000';
    }
    if ($max_level == 7) {
        $style_kashestvo = '#ff6600';
    }
    return $style_kashestvo;
}

#-Звездочка оружия-#
function star($max_level) {
    if ($max_level >= 0) {
        $style_kashestvo_star = '/style/images/quality/1.png';
    }
    if ($max_level >= 20) {
        $style_kashestvo_star = '/style/images/quality/2.png';
    }
    if ($max_level >= 40) {
        $style_kashestvo_star = '/style/images/quality/3.png';
    }
    if ($max_level >= 60) {
        $style_kashestvo_star = '/style/images/quality/4.png';
    }
    if ($max_level >= 80) {
        $style_kashestvo_star = '/style/images/quality/5.png';
    }
    if ($max_level >= 100) {
        $style_kashestvo_star = '/style/images/quality/7.png';
    }
    return $style_kashestvo_star;
}

#-Рамка оружия-#
function ramka($max_level) {
    if ($max_level == 1) {
        $ramka = 'weapon_1';
    }
    if ($max_level == 2) {
        $ramka = 'weapon_2';
    }
    if ($max_level == 3) {
        $ramka = 'weapon_3';
    }
    if ($max_level == 4) {
        $ramka = 'weapon_4';
    }
    if ($max_level == 5) {
        $ramka = 'weapon_5';
    }
    if ($max_level == 6) {
        $ramka = 'weapon_6';
    }
    return $ramka;
}

#-Руна оружия-#
function runa($num) {
    if ($num == 0) {
        $runa = 'Не установлена';
    }
#-Обычная-#
    if ($num == 250) {
        $runa = '<img src="/style/images/runa/1.png"/><span class="gray">Обычная</span>';
    }
#-Редкая-#
    if ($num == 1000) {
        $runa = '<img src="/style/images/runa/2.png"/><span class="blue">Редкая</span>';
    }
#-Эпическая-#
    if ($num == 3000) {
        $runa = '<img src="/style/images/runa/3.png"/><span class="green">Эпическая</span>';
    }
#-Легендарная-#
    if ($num == 5000) {
        $runa = '<img src="/style/images/runa/4.png"/><span class="red">Легендарная</span>';
    }
#-Божественная-#
    if ($num == 10000) {
        $runa = '<img src="/style/images/runa/5.png"/><span class="purple">Божественная</span>';
    }
    return $runa;
}

?>