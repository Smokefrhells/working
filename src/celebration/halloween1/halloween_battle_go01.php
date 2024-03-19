<?php
require_once '../../system/system.php';
$head = 'Бой';
only_reg();
require_once H . 'system/head.php';
echo '<div class="page">';
#-Враг-#
echo '<div class="block_hunting">';
echo "<img src='/style/images/monstru/halloween/knaz_tukva.png' class='img_m_battle' width='50' height='50'  alt=''/><div class='block_monsters'><img src='/style/images/user/user.png' alt=''/><b><span class='orange'>Князь тыква</span></b> <span style='font-size: 13px;'>[100 ур.]</span></div>";
#-Оружие-#
echo '<div class="line_1"></div>';
echo '<div class="body_list">';
echo '<div style="padding-top: 3px;"></div>';
if ($user['celebration_time'] == 0) {
    echo "<a href='/halloween_battle_act?act=attc' class='button_green_a'>Атаковать</a>";
} else {
    $time = $user['celebration_time'] - time();
    echo '<div class="button_red_a">' . (int)($time / 3600) . ' час. ' . (int)($time / 60 % 60) . ' мин.</div>';
}
echo '<div style="padding-top: 3px;"></div>';
echo '</div>';
echo '<div class="line_1"></div>';
echo "<a href='/shopEvent' class='button_green_a'>Магазин</a>";
echo '<div class="line_1"></div>';
#-Герой-#
echo "<img src='" . avatar_img_min($user['avatar'], $user['pol']) . "' class='img_h_battle'  width='50' height='50' alt=''/><div class='block_monsters'><img src='/style/images/user/user.png' alt=''/><span class='name_monsters'>$user[nick]</span><br/><div class='param_monsters'><img src='/style/images/user/sila.png' alt=''/>" . ($user['sila'] + $user['s_sila'] + $user['sila_bonus']) . " <img src='/style/images/user/zashita.png' alt=''/>" . ($user['zashita'] + $user['s_zashita'] + $user['zashita_bonus']) . " <img src='/style/images/user/health.png' alt=''/>$user[health] $us_p</div></div>";
echo '</div>';
echo '</div>';
require_once H . 'system/footer.php';
?>