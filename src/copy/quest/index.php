<?php
require_once '../../system/system.php';

only_reg();
require_once H . 'copy/copy_func.php';
require_once H . 'copy/array.php';

$kvest = fch("SELECT * FROM `kvest` WHERE `id_user` = ?", array($user['id']));
if (!$kvest) {
    qry("INSERT INTO `kvest` SET `id_user` = ?, `id_monster` = ?, `kolls` = ?", array($user['id'], 1, 2));
    header('Location: ?');
    exit();
}

if ($kvest['gold_last'] < time()) {
    $gold_last = strtotime('23:59:59');
    qry("UPDATE `kvest` SET `gold` = ?, `gold_last` = ? WHERE `id` = ?", array(0, $gold_last, $kvest['id']));
    header('Location: ?');
    exit();
}
$monster = fch("SELECT * FROM `monsters_quest` WHERE `id` = ?", array($kvest['id_monster']));
if (!$monster) {
    $head = 'Квест';
    require_once H . 'system/head.php';
    echo '<div class="block center">';
    echo 'Вы прошли квест ожидайте когда добавлятся новые боссы, приносим извинения за доставленные неудобства';
    echo '</div>';
    require_once H . 'system/footer.php';
    die();
}
$battle = fch("SELECT * FROM `kvest_battle` WHERE `id_kvest` = ? AND `start` != ?", array($kvest['id'], 3));
$gold_max = ($user['level'] + 6);
$gold_battle = 7;
if ($kvest['gold'] + 7 > $gold_max) {
    $gold_battle = ($gold_max - $kvest['gold']);
} else {
    $gold_max = 0;
}
if ($kvest['gold'] == 0 and $kvest['gold_last'] < time()) {
    qry("UPDATE `kvest` SET `kolls` = ? WHERE `id` = ?", array(2, $kvest['id']));
}
$head = $array['kvest_name'][$monster['id']];
if ($battle['start'] == 1) {

    require_once H . 'system/head.php';
    echo '<script type="text/javascript" src="/js/battle.js?sec=' . time() . '"></script>';
    echo '<div style="background-repeat: no-repeat;background-image: url(/images/monsters/backgrounds/' . $monster['id'] . '.jpg); background-position: 50% 50% ; position:relative; z-index:2; padding: 0 1px">';

    echo '<table class="block_zero" width="100%">';
    echo '<td width="40px" class="center">';
    echo '<img src="/images/monsters/' . $monster['id'] . '.jpg" width="40px" class="bor bor-no-radius">';
    echo '</td>';
    echo '<td valign="top">';
    echo '' . $monster['name'] . '<br><img src="/style/images/user/sila.png" width="16px"> ' . $monster['power'] . ' <img src="/style/images/user/zashita.png" width="16px"> ' . $monster['block'] . ' <img src="/style/images/user/health.png" width="16px"> <span id="hp_small_v">' . $battle['monster_hp'] . '</span> <span id="hp_del_v" style="color:red;">' . (isset($_SESSION['str']) ? '<font color="red">-' . $_SESSION['str'] . '</font>' : '') . '</span><br>';
    $progress = round($battle['monster_hp'] / $monster['health'] * 100);
    $hp_red = round(isset($_SESSION['str']) ? $_SESSION['str'] : null / $monster['health'] * 100);
    if ($progress >= 25) {
        $hp_color = 'url(/template/images/bar_green.png)';
    } elseif ($progress <= 25) {
        $hp_color = 'url(/template/images/bar_red2.png)';
    }
    echo '<div style="padding:3px 2px 4px 2px"><div style="height:6px;background:url(/template/images/bar_gray.png) left center;width:100%;">
    <div id="hp_proc_block_v" style="transition-property:width,background;transition-duration:0.5s;display:inline-block;height:6px;background:' . $hp_color . ' left top;width:' . $progress . '%;float:left"></div>
    <div id="hp_proc_del_block_v" style="transition-property:width;transition-duration:0.5s;display:inline-block;height:6px;background:url(/template/images/bar_red.png) left center;width:' . $hp_red . '%;float:left"></div></div>';
    echo '</td>';
    echo '</table>';
    echo '<div class="center" style="position:relative;">';
    echo '<img src="/images/monsters/fons/' . $monster['id'] . '.png" width="50%">';

    echo '<div id="attack" style="position:absolute;top:81%;left:1%;right:1%;"><a onclick="attack(\'/copy/quest/func.php\');return false;" href="?attack"><div style="display:inline-block;background-image:url(/images/attack_on.png);background-repeat:no-repeat;background-size:cover;height:50px;width:50px;margin: 0 auto;"></div></a></div>';
    echo '</div>';

    echo '<div class="mt10"></div>';
    echo '<table class="block_zero" width="100%">';
    echo '<td width="40px" class="center">';
    echo '<img src="/avenax/Maneken.php?user=' . $user['id'] . '" width="40px" class="bor bor-no-radius">';
    echo '</td>';
    echo '<td valign="top">';
    echo '' . $user['nick'] . ' <br><img src="/style/images/user/sila.png" width="16px"> ' . get_power($user['id']) . ' <img src="/style/images/user/zashita.png" width="16px"> ' . get_block($user['id']) . ' <img src="/style/images/user/health.png" width="16px"> <span id="hp_small_u">' . $battle['user_hp'] . '</span> <span id="hp_del_u" style="color:red;">' . (isset($_SESSION['str_opp']) ? '<font color="red">-' . $_SESSION['str_opp'] . '</font>' : '') . '</span><br>';
    $progress = round($battle['user_hp'] / get_health($user['id']) * 100);
    $hp_red = round(isset($_SESSION['str_opp']) ? $_SESSION['str_opp'] : null / get_health($user['id']) * 100);
    if ($progress >= 25) {
        $hp_color = 'url(/template/images/bar_green.png)';
    } elseif ($progress <= 25) {
        $hp_color = 'url(/template/images/bar_red2.png)';
    }
    echo '<div style="padding:3px 2px 4px 2px"><div style="height:6px;background:url(/template/images/bar_gray.png) left center;width:100%;">
    <div id="hp_proc_block_u" style="transition-property:width,background;transition-duration:0.5s;display:inline-block;height:6px;background:' . $hp_color . ' left top;width:' . $progress . '%;float:left"></div>
    <div id="hp_proc_del_block_u" style="transition-property:width;transition-duration:0.5s;display:inline-block;height:6px;background:url(/template/images/bar_red.png) left center;width:' . $hp_red . '%;float:left"></div></div>';
    echo '</td>';
    echo '</table>';
    ?>
    <script type="text/javascript" language="javascript">var url = '/copy/quest/func.php';
        var hp_user =<?=$battle['user_hp']?>;
        var hp_vrag =<?=$battle['monster_hp']?>;
        var hp_user_max =<?=get_health($user['id'])?>;
        var hp_vrag_max =<?=$monster['health']?>;</script> <?
    echo '</div>';
    /* Покидаем бой */
    if (isset($_GET['pok'])) {
        qry("UPDATE `kvest_battle` SET `start` = ? WHERE `id` = ?", array(3, $battle['id']));
        header('Location: /');
        exit();
    }
    echo '<div class="line"></div>';
    echo '<a href="?pok" class="link center">Покинуть бой</a>';
    echo '<div class="line"></div>';
    echo '<div class="fire"></div>';
    echo '</body></html>';

    if (isset($_SESSION['str']) or isset($_SESSION['str_opp'])) {
        unset($_SESSION['str']);
        unset($_SESSION['str_opp']);
    }
    die();
} elseif ($battle['start'] == 2) {
    if (isset($_SESSION['str']) or isset($_SESSION['str_opp'])) {
        unset($_SESSION['str']);
        unset($_SESSION['str_opp']);
    }
    require_once H . 'system/head.php';
    if ($battle['wins'] == 1) {
        qry("UPDATE `kvest` SET `id_monster` = ? WHERE `id` = ?", array($monster['id'] + 1, $kvest['id']));
        $exp = bon_exp(rand($monster['id'] * 24, $monster['id'] * 28));
        $silver = bon_mon(rand($monster['id'] * 26, $monster['id'] * 30));

        echo '<div class="block center">';
        echo '<div class="r-top"></div>';
        echo '<div class="r-middle"><font color=green><b>Победа</b></font></div>';
        echo '<font color="goldenrod">Награда:</font> 
        ' . ($gold_battle > 0 ? '<img src="/style/images/many/gold.png" width="16px"> ' . $gold_battle . ' золота<br>' : '') . '
        <img src="/style/images/many/silver.png" width="16px"> ' . $silver . ' серебра и <img src="/style/images/user/exp.png" width="16px"> ' . $exp . ' опыта';
        echo '<div class="r-bottom"></div>';
        echo '</div>';
        echo '<div class="line"></div>';

//        if ($kvest['id_monster'] <= 5) {
//            $cachestvo = 2;
//        } elseif ($kvest['id_monster'] <= 10) {
//            $cachestvo = 3;
//        } elseif ($kvest['id_monster'] <= 15) {
//            $cachestvo = 4;
//        } elseif ($kvest['id_monster'] <= 30) {
//            $cachestvo = 4;
//        }
//        $array_cach = array(1 => 'Обычное', 2 => 'Не обычное', 3 => 'Редкое', 4 => 'Эпическое', 5 => 'Легендарное');
//        $item = fch("SELECT * FROM `complect_item` WHERE `cachestvo` = ? ORDER BY RAND() LIMIT 1", array($cachestvo));
//        $my_item = fch("SELECT * FROM `inv` WHERE `id_user` = ? AND `type` = ? AND `odeta` = ?", array($user['id'], $item['type'], 'da'));
//        $luchshe = 0;
//        if ($my_item['level'] < $item['level']) {
//            $luchshe += ($array_item_level[$item['level']] - $array_item_level[$my_item['level']]);
//        }
//        qry("INSERT INTO `inv` SET `id_user` = ?, `id_item` = ?, `name` = ?, `cachestvo` = ?, `type` = ?, `level` = ?, `max_level` = ?", array($user['id'], $item['id'], $item['name'], $item['cachestvo'], $item['type'], $item['level'], $item['max_level']));
//        $query = $base->query("SELECT LAST_INSERT_ID()");
//        $item_inv['id'] = $query->fetchColumn();
//        echo '<div class="block"><table align="center">';
//        echo '<td><img src="/images/items/' . $item['id'] . '.png" width="50px" class="bor-' . $item['cachestvo'] . ' bor-no-radius"></td>';
//        echo '<td valign="top">' . $item['name'] . '<br>' . $array_cach[$item['cachestvo']] . ' [' . $item['level'] . '/' . $item['max_level'] . ']' . ($luchshe > 0 ? '<br><a href="?item_ok=' . $item_inv['id'] . '">Одеть вещь</a>' : '') . '</td>';
//        echo '</table></div>';
    } elseif ($battle['wins'] == 2) {
        $exp = bon_exp(rand($monster['id'] * 22, $monster['id'] * 24));
        $silver = bon_mon(rand($monster['id'] * 24, $monster['id'] * 28));

        echo '<div class="block center">';
        echo '<div class="r-top"></div>';
        echo '<div class="r-middle"><font color=red><b>Поражение</b></font></div>';
        echo '<font color="goldenrod">Награда:</font> 
        ' . ($gold_battle > 0 ? '<img src="/style/images/many/gold.png" width="16px"> ' . $gold_battle . ' золота<br>' : '') . '
        <img src="/style/images/many/silver.png" width="16px"> ' . $silver . ' серебра и <img src="/style/images/user/exp.png" width="16px"> ' . $exp . ' опыта';
        echo '<div class="r-bottom"></div>';
        echo '</div>';
        echo '<div class="line"></div>';
//        if ($kvest['id_monster'] <= 5) {
//            $cachestvo = 1;
//        } elseif ($kvest['id_monster'] <= 10) {
//            $cachestvo = rand(1, 2);
//        } elseif ($kvest['id_monster'] <= 15) {
//            $cachestvo = rand(2, 3);
//        } elseif ($kvest['id_monster'] <= 30) {
//            $cachestvo = 3;
//        }
//        $inv_count = cnt("SELECT * FROM `inv` WHERE `id_user` = ? AND `odeta` = ?", array($user['id'], 'net'));
//        $array_cach = array(1 => 'Обычное', 2 => 'Необычное', 3 => 'Редкое', 4 => 'Эпическое', 5 => 'Легендарное');
//        $item = fch("SELECT * FROM `complect_item` WHERE `cachestvo` = ? ORDER BY RAND() LIMIT 1", array($cachestvo));
//        if ($inv_count < 20)
//            qry("INSERT INTO `inv` SET `id_user` = ?, `id_item` = ?, `name` = ?, `cachestvo` = ?, `type` = ?, `level` = ?, `max_level` = ?", array($user['id'], $item['id'], $item['name'], $item['cachestvo'], $item['type'], $item['level'], $item['max_level']));
//        echo '<div class="block"><table align="center">';
//        echo '<td><img src="/images/items/' . $item['id'] . '.png" width="50px" class="bor-' . $item['cachestvo'] . ' bor-no-radius"></td>';
//        echo '<td valign="top">' . $item['name'] . '<br>' . $array_cach[$item['cachestvo']] . ' [' . $item['level'] . '/' . $item['max_level'] . ']</td>';
//        echo '</table>' . ($inv_count == 20 ? '<font color="red"><center>Вы не смогли поднять вещь, инвентарь переполнен</center></font>' : '') . '</div>';
    }
    qry("UPDATE `users` SET `gold` = ?, `exp` = ?, `silver` = ? WHERE `id` = ?", array($user['gold'] + $gold_battle, $user['exp'] + $exp, $user['silver'] + $silver, $user['id']));
//    $user_clan = fch("SELECT * FROM `clan_users` WHERE `id_user` = ?", array($user['id']));
//    if ($user_clan) {
//        $clan = fch("SELECT * FROM `clans` WHERE `id` = ?", array($user_clan['id_clan']));
//        $clan_exp = get_exp_clan($exp);
//        qry("UPDATE `clan_users` SET `exp` = ?, `exp_24` = ? WHERE `id` = ?", array($user_clan['exp'] + $clan_exp, $user_clan['exp_24'] + $clan_exp, $user_clan['id']));
//        qry("UPDATE `clans` SET `exp` = ? WHERE `id` = ?", array($clan['exp'] + $clan_exp, $clan['id']));
//    }
    qry("UPDATE `kvest` SET `gold` = ? WHERE `id` = ?", array($kvest['gold'] + $gold_battle, $kvest['id']));
    qry("UPDATE `kvest_battle` SET `start` = ? WHERE `id` = ?", array(3, $battle['id']));
    echo '<div class="line"></div>';
    echo '<a href="?" class="link center">Продолжить</a>';
    echo '<div class="line"></div>';
    echo '<div class="fire"></div>';
    echo '</body></html>';
    die();
}
$head = $array['kvest_name'][$monster['id']];
require_once H . 'system/head.php';
echo '<div class="block center">';
echo '<div class="r-middle"><font color="goldenrod">' . $monster['name'] . '</font></div>';
echo '<hr>';
echo '<img src="/images/monsters/avatars/' . $monster['id'] . '.png" width="150px"></br>';
echo '<img src="/style/images/user/sila.png" width="16px"> ' . $monster['power'] . ' <img src="/style/images/user/zashita.png" width="16px"> ' . $monster['block'] . ' <img src="/style/images/user/health.png" width="16px"> ' . $monster['health'] . '</span>';
echo '<hr>';
if ($kvest['kolls'] >= 1) {
    if (isset($_GET['vboi']) and $kvest['kolls'] >= 1) {
        if ($kvest['kolls'] - 1 == 0) {
            qry("UPDATE `kvest` SET `last` = ?, `kolls` = ? WHERE `id` = ?", array(time() + 60 * 60, $kvest['kolls'] - 1, $kvest['id']));
        } else {
            qry("UPDATE `kvest` SET `kolls` = ? WHERE `id` = ?", array($kvest['kolls'] - 1, $kvest['id']));
        }
        qry("INSERT INTO `kvest_battle` SET `id_kvest` = ?, `user_hp` = ?, `monster_hp` = ?, `start` = ?", array($kvest['id'], get_health($user['id']), $monster['health'], 1));

        // задание
        $quest = fch("SELECT * FROM `quest` WHERE `type` = ?", array('kvest_battle'));
        $user_quest = fch("SELECT * FROM `quest_user` WHERE `id_quest` = ? AND `id_user` = ?", array($quest['id'], $user['id']));
        if ($user_quest['ok'] == 0) {
            qry("UPDATE `quest_user` SET `kolls` = `kolls` + '1' WHERE `id` = ?", array($user_quest['id']));
        }

//        $questclan = fch("SELECT * FROM `quest_clan` WHERE `type` = ?", array('kvest_battle'));
//        $clan_quest = fch("SELECT * FROM `quest_clans` WHERE `id_quest` = ? AND `id_clan` = ? AND `id_user` = ? or `id_pom` = ?", array($questclan['id'], $my_clan['id'], $user['id'], $user['id']));
//        if ($clan_quest['ok'] == 0) {
//            qry("UPDATE `quest_clans` SET `kolls` = `kolls` + '1' WHERE `id` = ?", array($clan_quest['id']));
//        }

        header('Location: ?');
        exit();
    }
    echo '<a href="?vboi" class="btn-attack">В бой</a><hr>';
} else {
    echo 'Ваш герой устал возвращайтесь через ' . tl($kvest['last'] - time()) . '<br>';
}
echo 'Сегодня найдено <img src="/style/images/many/gold.png" width="16px"> ' . $kvest['gold'] . ' из ' . ($user['level'] + 6) . ' золота';
echo '</div>';

require_once H . 'system/footer.php';