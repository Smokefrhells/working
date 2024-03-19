<?php
require_once '../../system/system.php';
only_reg();
training_campaign();
$head = 'Тренировка';
require_once H . 'system/head.php';
require_once H . 'avenax/.settings.php';


#-Скидка на тренировку-#
$sel_stock = $pdo->query("SELECT * FROM `stock` WHERE `type` = 4");
$stock = $sel_stock->fetch(PDO::FETCH_LAZY);

#-Параметры тренировки-#

#-Сила-#
$sila = COUNT_TRAINING;
$strPrice = priceTraining($user['level_sila']);
if (isset($strPrice->s)) {
    $many_sila = $strPrice->s;
    $sila_img = 'silver';
} else {
    $many_sila = $strPrice->g;
    $sila_img = 'gold';
}

#-Защита-#
$zashita = COUNT_TRAINING;
$defPrice = priceTraining($user['level_zashita']);
if (isset($defPrice->s)) {
    $many_zashita = $defPrice->s;
    $zashita_img = 'silver';
} else {
    $many_zashita = $defPrice->g;
    $zashita_img = 'gold';
}

#-Здоровье-#
$health = COUNT_TRAINING;
$hpPrice = priceTraining($user['level_health']);
if (isset($hpPrice->s)) {
    $many_health = $hpPrice->s;
    $health_img = 'silver';
} else {
    $many_health = $hpPrice->g;
    $health_img = 'gold';
}

echo '<div class="page">';
echo '<img src="/style/images/location/training.jpg" class="img"/>';
#-Сила-#
if ($user['level_sila'] < 200) {
    echo '<div class="img_height">';
    echo '<img src="/style/images/training/sila.png" class="img_weapon" alt=""/><div class="weapon_setting"><span class="yellow">Сила</span> <span class="green">[' . $user['sila'] . ']</span><br/><div style="font-size: 12px; color: #bfbfbf;">Уровень: ' . $user['level_sila'] . ' из 200 <br/>След. уровень: <span class="green">+' . $sila . '</span></div></div>';
    echo '</div>';
    echo "<a href='/training_act?act=sila' class='button_green_a'>Тренировать за <img src='/style/images/many/$sila_img.png' alt=''/>" . ($sel_stock->rowCount() == 0 ? '' . num_format($many_sila) . '' : '<span class="white"><del>' . num_format($many_sila) . '</del></span> ' . num_format(round(($many_sila - (($many_sila * $stock['prosent']) / 100)), 0)) . '') . "   " . ($user['level_sila'] == 0 ? "<img src='/style/images/body/left_mini.gif' alt=''/>" : "") . "</a>";
    echo '<div style="padding-top: 5px;"></div>';
} else {
    echo '<div class="img_height">';
    echo '<img src="/style/images/training/sila.png" class="img_weapon" alt=""/><div class="weapon_setting"><span class="yellow">Сила</span> <span class="green">[' . $user['sila'] . ']</span><br/><div style="font-size: 12px; color: #bfbfbf;">Уровень: <span class="green">200</span> из 200</div></div>';
    echo '</div>';
}
#-Защита-#
if ($user['level_zashita'] < 200) {
    echo '<div class="img_height">';
    echo '<img src="/style/images/training/zashita.png" class="img_weapon" alt=""/><div class="weapon_setting"><span class="yellow">Защита</span> <span class="green">[' . $user['zashita'] . ']</span><br/><div style="font-size: 12px; color: #bfbfbf;">Уровень ' . $user['level_zashita'] . ' из 200 <br/>След. уровень: <span class="green">+' . $zashita . '</span></div></div>';
    echo '</div>';
    echo "<a href='/training_act?act=zashita' class='button_green_a'>Тренировать за <img src='/style/images/many/$zashita_img.png' alt=''/>" . ($sel_stock->rowCount() == 0 ? '' . num_format($many_zashita) . '' : '<span class="white"><del>' . num_format($many_zashita) . '</del></span> ' . num_format(round(($many_zashita - (($many_zashita * $stock['prosent']) / 100)), 0)) . '') . " " . ($user['level_zashita'] == 0 ? "<img src='/style/images/body/left_mini.gif' alt=''/>" : "") . "</a>";
    echo '<div style="padding-top: 5px;"></div>';
} else {
    echo '<div class="img_height">';
    echo '<img src="/style/images/training/zashita.png" class="img_weapon" alt=""/><div class="weapon_setting"><span class="yellow">Защита</span> <span class="green">[' . $user['zashita'] . ']</span><br/><div style="font-size: 12px; color: #bfbfbf;">Уровень: <span class="green">200</span> из 200</div></div>';
    echo '</div>';
}
#-Здоровье-#
if ($user['level_health'] < 200) {
    echo '<div class="img_height">';
    echo '<img src="/style/images/training/health.png" class="img_weapon" alt=""/><div class="weapon_setting"><span class="yellow">Здоровье</span> <span class="green">[' . $user['health'] . ']</span><br/><div style="font-size: 12px; color: #bfbfbf;">Уровень ' . $user['level_health'] . ' из 200 <br/>След. уровень: <span class="green">+' . $health . '</span></div></div>';
    echo '</div>';
    echo "<a href='/training_act?act=health' class='button_green_a'>Тренировать за <img src='/style/images/many/$health_img.png' alt=''/>" . ($sel_stock->rowCount() == 0 ? '' . num_format($many_health) . '' : '<span class="white"><del>' . num_format($many_health) . '</del></span> ' . num_format(round(($many_health - (($many_health * $stock['prosent']) / 100)), 0)) . '') . " " . ($user['level_health'] == 0 ? "<img src='/style/images/body/left_mini.gif' alt=''/>" : "") . "</a>";
    echo '<div style="padding-top: 5px;"></div>';
} else {
    echo '<div class="img_height">';
    echo '<img src="/style/images/training/health.png" class="img_weapon" alt=""/><div class="weapon_setting"><span class="yellow">Здоровье</span> <span class="green">[' . $user['health'] . ']</span><br/><div style="font-size: 12px; color: #bfbfbf;">Уровень: <span class="green">200</span> из 200</div></div>';
    echo '</div>';
}
echo '</div>';
require_once H . 'system/footer.php';
?>