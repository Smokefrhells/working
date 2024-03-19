<?php
require_once '../system/system.php';
only_reg();
require_once H . 'copy/copy_func.php';


$id = fl($_GET['user']);

$us = fch("SELECT * FROM `users` WHERE `id` = ?", [$id]);
if (!$us) {
    header('Location /hero/');
    exit();
}

$head = 'Трофеи';
require_once H . 'system/head.php';
//$u_clan = fch("SELECT * FROM `clan_users` WHERE `id_user` = ?", array($us['id']));
//$clan_u = fch("SELECT * FROM `clans` WHERE `id` = ?", array($u_clan['id_clan']));

$count = cnt("SELECT * FROM `trofeis_user` WHERE `id_user` = ?", [$us['id']]);
$q = acc("SELECT * FROM `trofeis_user` WHERE `id_user` = ? ORDER BY `id` ASC", [$us['id']]);
if ($count == 0) {
    echo '<div class="block center">У игрока нет трофеев</div>';
}
foreach ($q as $t) {
    $trofei = fch("SELECT * FROM `trofeis` WHERE `id` = ?", [$t['id_trofei']]);
    //    if($clan_u){
    //        $trofei['param'] += ceil($trofei['param']*$array['clan_stat_param'][$clan_u['trofeis']]/100);
    //    }
    echo '<div class="block">';
    echo '<div class="left"><img src="/images/trofeis/' . $trofei['id'] . '.png" width="90px"></div>';
    echo '<img src="/images/trofeis/' . $trofei['id'] . '.png" width="16px"> <span font class="bold"><font color="goldenrod">' . $trofei['name'] . '</font></span><br>';
    echo '<img src="/style/images/body/all.png" width="16px"> +' . $trofei['param'] . ' к параметрам<br>';
//    echo '<img src="/images/icon/krit.png" width="16px"> +' . $trofei['krit'] . '% к ярости<br>';
    echo '<img src="/style/images/user/exp.png" width="16px"> +' . $trofei['exp'] . '% к опыту<br>';
    echo '<img src="/style/images/many/silver.png" width="16px"> +' . $trofei['silver'] . '% к серебру<br>';
    echo '<div style="clear:both;"></div>';
    echo '</div>';
}

echo '<a href="/hero/' . $us['id'] . '/" class="link"><img src="/images/icon/arrow.png" width="16px"> Вернуться назад</a>';

require_once H . 'system/footer.php';