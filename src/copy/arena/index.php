<?php
require_once '../../system/system.php';

only_reg();
require_once H . 'copy/copy_func.php';
include_once H . '/avenax/Events.php';

$arena = fch("SELECT * FROM `arena` WHERE `id_user` = ?", array($user['id']));
$new_pers = fch("SELECT * FROM `users` WHERE `id` != ? AND (`level` >= ? AND `level` <= ?) ORDER BY RAND() LIMIT 1", array($user['id'], $user['level'] - 10, $user['level'] + 5));
if (!$new_pers)
    $new_pers = fch("SELECT * FROM `users` WHERE `id` != ? ORDER BY RAND() DESC LIMIT 1", array($user['id']));
if (!$arena) {
    qry("INSERT INTO `arena` SET `id_user` = ?, `kolls` = ?, `id_opp` = ?", array($user['id'], 20, $new_pers['id']));
    header('Location: /arena/');
    exit();
}
if ($arena['id_opp'] == 0) {
    qry("UPDATE `arena` SET `id_opp` = ?", array($new_pers['id']));
    header('Location: /arena/');
    exit();
}



$user_clan = fch("SELECT * FROM `clan_users` WHERE `id_user` = ?", array($user['id']));
$clan = fch("SELECT * FROM `clans` WHERE `id` = ?", array($user_clan['id_clan']));
if (isset($_GET['attack']) and $arena['kolls'] >= 1) {
    $id = fl($_GET['attack']);
    $pers = fch("SELECT * FROM `users` WHERE `id` = ?", array($arena['id_opp']));
    if (!$pers) {
        header('Location: ?');
        exit();
    }
    if ($arena['kolls'] - 1 == 0) {
        qry("UPDATE `arena` SET `kolls` = ?, `last` = ? WHERE `id` = ?", array(0, (time() + 60 * 60 * 1), $arena['id']));
    } else {
        qry("UPDATE `arena` SET `kolls` = ? WHERE `id` = ?", array(($arena['kolls'] - 1), $arena['id']));
    }
    $str_ = round(get_power($user['id']) / 4);
    $str__ = round(get_power($user['id']) / 5);
    $str = rand($str_, $str__);
//    if (mt_rand(25, 100) < get_krit($user['id'])) {
//        $str *= 3;
//    }

    $def_opp_ = round(get_block($pers['id']) / 6);
    $def_opp__ = round(get_block($pers['id']) / 7);
    $def_opp = rand($def_opp_, $def_opp__);

    $str -= $def_opp;
    if ($str < 0)
        $str = 0;

    $str_opp_ = round(get_power($pers['id']) / 4);
    $str_opp__ = round(get_power($pers['id']) / 5);
    $str_opp = rand($str_opp_, $str_opp__);
//    if (mt_rand(25, 100) < get_krit($pers['id'])) {
//        $str_opp *= 2;
//    }

    $def_ = round(get_block($user['id']) / 6);
    $def__ = round(get_block($user['id']) / 7);
    $def = rand($def_, $def__);

    $str_opp -= $def;
    if ($str_opp < 0)
        $str_opp = 0;

    $health = get_health($user['id']);
    $health_opp = get_health($pers['id']);

    for ($round = 1; $round < 4; $round++) {
        $health -= $str_opp;
        $health_opp -= $str;
    }
    qry("UPDATE `arena` SET `id_opp` = ?", array($new_pers['id']));

    $exp_no = rand((($user['level'] + 50) * 20), (($user['level'] + 150) * 50)); //Победа
    if ($user['premium'] == 0) {
        $exp_pobeda = $exp_no;
    }
    if ($user['premium'] == 1) {
        $exp_pobeda = $exp_no * 2;
    }
    if ($user['premium'] == 2) {
        $exp_pobeda = $exp_no * 3;
    }
    $exp = bon_exp($exp_pobeda);

    $silver_no = rand(($user['level'] * 150) + 800, ($user['level'] * 250) + 1500);
    if ($user['premium'] == 0) {
        $silver_pobeda = $silver_no;
    }
    if ($user['premium'] == 1) {
        $silver_pobeda = round((($silver_no * 0.25) + $silver_no), 0);
    }
    if ($user['premium'] == 2) {
        $silver_pobeda = round((($silver_no * 0.50) + $silver_no), 0);
    }
    $silver = bon_mon($silver_pobeda);

    if ($health > $health_opp) {
        // задания
        $quest = fch("SELECT * FROM `quest` WHERE `type` = ?", array('arena_wins'));
        $user_quest = fch("SELECT * FROM `quest_user` WHERE `id_quest` = ? AND `id_user` = ?", array($quest['id'], $user['id']));
        if ($user_quest['ok'] == 0) {
            qry("UPDATE `quest_user` SET `kolls` = `kolls` + '1' WHERE `id` = ?", array($user_quest['id']));
        }


        /*Клановые задания арена*/

//        $questclan = fch("SELECT * FROM `quest_clan` WHERE `type` = ?", array('arena_wins'));
//        $clan_quest = fch("SELECT * FROM `quest_clans` WHERE `id_quest` = ? AND `id_clan` = ? AND `id_user` = ? or `id_pom` = ?", array($questclan['id'], $clan['id'], $user['id'], $user['id']));
//        if ($clan_quest['ok'] == 0) {
//            qry("UPDATE `quest_clans` SET `kolls` = `kolls` + '1' WHERE `id` = ?", array($clan_quest['id']));
//        }
        /*конец*/


        //Умение крит
        /*
        $skill = fch('SELECT * FROM `skill_user` WHERE `id_user` = ? LIMIT 1', array($user['id']));
        $shanse = $array['skill_stat_param'][$skill['krit']];
        if(mt_rand(1,20) > $shanse){
               $krit = fch("SELECT * FROM `skill_user` WHERE `krit` > ?ORDER BY RAND() LIMIT 1", array(0));
      if($krit){
        $_SESSION['wins_skill'] = '<img src="/images/icon/skill/krit.png" width="15px">';
        }
        }
        */
        //конец умения крит


//        $count_inv = cnt("SELECT * FROM `inv` WHERE `id_user` = ? AND `odeta` = ?", array($user['id'], 'net'));
//        $drop = 25;
//        if ($clan['level'] >= 15) {
//            $drop += 5;
//        }
//        if (mt_rand(0, 100) < $drop and $count_inv < 20) {
//            $item = fch("SELECT * FROM `complect_item` WHERE `cachestvo` = ? OR `cachestvo` = ? ORDER BY RAND() LIMIT 1", array(1, 2));
//            if ($item['cachestvo'] == 1) {
//                $item['level'] = 1;
//                $item['max_level'] = 5;
//            } else {
//                $item['level'] = 5;
//                $item['max_level'] = 10;
//            }
//            $my_item = fch("SELECT * FROM `inv` WHERE `id_user` = ? AND `type` = ? AND `odeta` = ?", array($user['id'], $item['type'], 'da'));
//            $luchshe = 0;
//            if ($my_item['level'] < $item['level']) {
//                $luchshe += ($array_item_level[$item['level']] - $array_item_level[$my_item['level']]);
//            }
//            qry("INSERT INTO `inv` SET `id_user` = ?, `id_item` = ?, `name` = ?, `level` = ?, `max_level` = ?, `cachestvo` = ?, `type` = ?", array($user['id'], $item['id'], $item['name'], $item['level'], $item['max_level'], $item['cachestvo'], $item['type']));
//            $query = $base->query("SELECT LAST_INSERT_ID()");
//            $item_inv['id'] = $query->fetchColumn();
//            $_SESSION['wins_item'] = '<td><img src="/images/items/' . $item['id'] . '.png" width="50px" class="bor-' . $item['cachestvo'] . ' bor-no-radius"></td><td valign="top">' . $item['name'] . '<br><font color="' . $array['item_cach_color'][$item['cachestvo']] . '">' . $array['item_cach'][$item['cachestvo']] . '</font> [' . $item['level'] . '/' . $item['max_level'] . ']' . ($luchshe > 0 ? '<br><a href="?item_ok=' . $item_inv['id'] . '">Одеть вещь</a>' : '') . '</td>
//        ' . ($count_inv >= 20 ? '<font color="red"><center>Вы не смогли поднять вещь, инвентарь переполнен</center></font>' : '') . '';
//
//
//        }


        qry("UPDATE `users` SET `exp` = ?, `silver` = ? WHERE `id` = ?", array(($user['exp'] + $exp), ($user['silver'] + $silver), $user['id']));
        $_SESSION['wins'] = '<div class="r-middle"><font color="green">Победа</font></div><font color="goldenrod">Награда:</font> <img src="/style/images/user/exp.png" width="13px"> ' . $exp . ' опыта <img src="/style/images/many/silver.png" width="13px"> ' . $silver . ' серебра';
        // event
        Events::randomItem('arena', $user['id']);
        header('Location: ?');
        exit();
    } else {

        qry("UPDATE `users` SET `exp` = ?, `silver` = ? WHERE `id` = ?", array(($user['exp'] + $exp), ($user['silver'] + $silver), $user['id']));

        $_SESSION['wins'] = '<div class="r-middle"><font color="red">Поражение</font></div><font color="goldenrod">Награда:</font> <img src="/style/images/user/exp.png" width="13px"> ' . $exp . ' опыта <img src="/style/images/many/silver.png" width="13px"> ' . $silver . ' серебра</br>';
        header('Location: ?');
        exit();
    }
}
$head = 'Арена (' . $arena['kolls'] . ')';
require_once H . 'system/head.php';
if (isset($_SESSION['wins'])) {
    echo '<div class="block center"><div class="r-top"></div>' . $_SESSION['wins'] . '<div class="r-bottom"></div></div>';
    unset($_SESSION['wins']);
}
if (isset($_SESSION['wins_item'])) {
    echo '<div class="block"><table cellspacing="5" cellpadding="5" align="center">' . $_SESSION['wins_item'] . '</table></div>';
    unset($_SESSION['wins_item']);
}

/*
if(isset($_SESSION['wins_skill'])){
    echo '<div class="block"><table cellspacing="5" cellpadding="5" align="center">'.$_SESSION['wins_skill'].'</table></div>';
    unset($_SESSION['wins_skill']);
}
*/
//debug(get_power($user['id']));
if ($arena['kolls'] >= 1) {
    $pers = fch("SELECT * FROM `users` WHERE `id` = ? LIMIT 1", array($arena['id_opp']));


    echo '<div style="background-repeat: no-repeat;background-image: url(/images/fon_boi.png); background-position: 50% 50% ; position:relative; z-index:2; padding: 1px 1px">';

    echo '<div class="block_zero">';
    echo '<div class="left">';
    echo '<img src="/avenax/Maneken.php?user=' . $pers['id'] . '" width="35px" style="border-bottom-right-radius: 40px 40px; border-bottom-left-radius: 40px 40px;">';
    echo '</div>';
    echo '<span class="right"><img src="/style/images/user/sila.png" width="16px"> ' . get_power($pers['id']) . ' <img src="/style/images/user/zashita.png" width="16px"> ' . get_block($pers['id']) . '<br> <img src="/style/images/user/health.png" width="16px"> ' . get_health($pers['id']) . '</span><img src="/style/images/user/' . ($pers['storona'] == 1 ? 'shine.png' : 'dark.png') . '" width="16px"> ' . $pers['nick'] . '<br> <img src="/style/images/user/level.png" width="16px"> ' . $pers['level'] . ' уровень';
    echo '<div style="clear:both;"></div>';
    echo '</div>';

    echo '<div class="center">';
    echo '<a href="?attack=' . md5($pers['id']) . '">';
    echo '<img src="/avenax/Maneken.php?user=' . $pers['id'] . '" width="180px">';
    echo '</a>';
    echo '</div>';


    echo '<div class="center" style="margin-top: -10px;"><a href="?attack" class="btn-attack">Атаковать</a></div>';
    echo '<div class="block_zero">';
    echo '<div class="left">';
    echo '<img src="/avenax/Maneken.php?user=' . $user['id'] . '" width="35px" style="border-bottom-right-radius: 40px 40px; border-bottom-left-radius: 40px 40px;">';
    echo '</div>';
    echo '<span class="right"><img src="/style/images/user/sila.png" width="16px"> ' . get_power($user['id']) . ' <img src="/style/images/user/zashita.png" width="16px"> ' . get_block($user['id']) . '<br> <img src="/style/images/user/health.png" width="16px"> ' . get_health($user['id']) . ' <img src="/style/images/user/' . ($pers['storona'] == 1 ? 'shine.png' : 'dark.png') . '" width="16px"></span> ' . $user['nick'] . '<br> <img src="/style/images/user/level.png" width="16px"> ' . $user['level'] . ' уровень';
    echo '<div style="clear:both;"></div>';
    echo '</div>';

    echo '</div>';
} else {
    echo '<div class="block center"><span class="r-middle">Бои закончились</span><br>Возвращайтесь через ' . tl($arena['last'] - time()) . '</div>';
}

require_once H . 'system/footer.php';