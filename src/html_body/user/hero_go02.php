<?php
require_once '../../system/system.php';
require_once '../../copy/copy_func.php';
only_reg();
$id = check($_GET['id']);
if (empty($_GET['id']))
    $error = 'Ошибка!';
if (!isset($_GET['id']))
    $error = 'Ошибка!';
if (!isset($error)) {
    $sel_users = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :id");
    $sel_users->execute(array(':id' => $id));
    if ($sel_users->rowCount() != 0) {
        $all = $sel_users->fetch(PDO::FETCH_LAZY);
    } else {
        header("Location: /hero/$user[id]");
        $_SESSION['err'] = 'Пользователь не найден!';
        exit();
    }
} else {
    header("Location: /hero/$user[id]");
    $_SESSION['err'] = $error;
    exit();
}
$head = $all['nick'];
if ($user['prava'] > 0) {
    if (isset($_GET['ip'])) {

        $ip = $all['ip']; // IP, который будем проверять

// формируем URL для запроса
        $url = "http://ip-api.com/json/$ip";
// делаем запрос к API
        $data = @file_get_contents($url);
// если получили данные
        if ($data) {
            // декодируем полученные данные
            $dataDecode = json_decode($data);

            // выводим данные
            echo "Страна: " . $dataDecode->country . "<br/>";
            echo "Город: " . $dataDecode->city . "<br/>";
            echo "Провайдер: " . $dataDecode->isp . "<br/>";
            echo "Область: " . $dataDecode->regionName . "<br/>";
            echo "Часовой пояс: " . $dataDecode->timezone . "<br/>";
            echo "Почтовый индекс: " . $dataDecode->zip . "<br/>";

        } else {
            echo "Сервер не доступен!";
        }
        die();
    }
}

require_once H . 'system/head.php';
require_once H . 'avenax/Item.php';
require_once H . 'avenax/Maneken.php';

$all_param = $all['param'];
$all_id = $all['id'];
$all_pobeda_h = $all['hunting_pobeda'];
$all_progrash_h = $all['hunting_progrash'];
$all_pobeda_d = $all['duel_pobeda'];
$all_progrash_d = $all['duel_progrash'];
$all_pobeda_b = $all['boss_pobeda'];
$all_progrash_b = $all['boss_progrash'];
$all_tasks = $all['tasks'];
require_once H . 'system/game/rating.php';
echo '<div class="body_info">';


#-АМУЛЕТ-#
$sel_weapon_me_am = $pdo->prepare("SELECT * FROM `weapon_me` WHERE `user_id` = :user_id AND `state` = :state AND `type` = :type");
$sel_weapon_me_am->execute(array(':user_id' => $all['id'], ':state' => 1, ':type' => 7));
if ($sel_weapon_me_am->rowCount() != 0) {
    $weapon_me_am = $sel_weapon_me_am->fetch(PDO::FETCH_LAZY);
    $sel_weapon_am = $pdo->prepare("SELECT * FROM `weapon` WHERE `id` = :id");
    $sel_weapon_am->execute(array(':id' => $weapon_me_am['weapon_id']));
    $weapon_am = $sel_weapon_am->fetch(PDO::FETCH_LAZY);
}
#-КОЛЬЦО-#
$sel_weapon_me_r = $pdo->prepare("SELECT * FROM `weapon_me` WHERE `user_id` = :user_id AND `state` = :state AND `type` = :type");
$sel_weapon_me_r->execute(array(':user_id' => $all['id'], ':state' => 1, ':type' => 8));
if ($sel_weapon_me_r->rowCount() != 0) {
    $weapon_me_r = $sel_weapon_me_r->fetch(PDO::FETCH_LAZY);
    $sel_weapon_r = $pdo->prepare("SELECT * FROM `weapon` WHERE `id` = :id");
    $sel_weapon_r->execute(array(':id' => $weapon_me_r['weapon_id']));
    $weapon_r = $sel_weapon_r->fetch(PDO::FETCH_LAZY);
}


$resultObj = Maneken::getUserItems($all['id']);

$itemType = Item::getTypeItem();

$headBase = ['class' => 'bor'];
$shoulderBase = ['class' => 'bor']; // плечо
$armorBase = ['class' => 'bor']; // броня
$glovesBase = ['class' => 'bor']; // перчатки
$weapons_1Base = ['class' => 'bor']; // 1 рука
$weapons_2Base = ['class' => 'bor']; // 2 рука
$pantsBase = ['class' => 'bor']; // штаны
$bootsBase = ['class' => 'bor']; // боты


foreach ($resultObj as $obj) {

    if (in_array($obj['weapon_id'], $itemType['head'])) {
        $headBase['id'] = $obj['weapon_id'];
        $headBase['level_sharpening'] = $obj['level_sharpening'];
        $headBase['class'] = Item::getColor($obj['weapon_id']);
        if (!empty($obj['runa'])) {
            $headBase['runa'] = Item::Runes($obj['runa']);
        }
    }
    // плечо
    if (in_array($obj['weapon_id'], $itemType['shoulder'])) {
        $shoulderBase['id'] = $obj['weapon_id'];
        $shoulderBase['level_sharpening'] = $obj['level_sharpening'];
        $shoulderBase['class'] = Item::getColor($obj['weapon_id']);
        if (!empty($obj['runa'])) {
            $shoulderBase['runa'] = Item::Runes($obj['runa']);
        }
    }
    // броня
    if (in_array($obj['weapon_id'], $itemType['armor'])) {
        $armorBase['id'] = $obj['weapon_id'];
        $armorBase['level_sharpening'] = $obj['level_sharpening'];
        $armorBase['class'] = Item::getColor($obj['weapon_id']);
        if (!empty($obj['runa'])) {
            $armorBase['runa'] = Item::Runes($obj['runa']);
        }
    }
    // перчатки
    if (in_array($obj['weapon_id'], $itemType['gloves'])) {
        $glovesBase['id'] = $obj['weapon_id'];
        $glovesBase['level_sharpening'] = $obj['level_sharpening'];
        $glovesBase['class'] = Item::getColor($obj['weapon_id']);
        if (!empty($obj['runa'])) {
            $glovesBase['runa'] = Item::Runes($obj['runa']);
        }
    }
    // 1 рука
    if (in_array($obj['weapon_id'], $itemType['weapons_1'])) {
        $weapons_1Base['id'] = $obj['weapon_id'];
        $weapons_1Base['level_sharpening'] = $obj['level_sharpening'];
        $weapons_1Base['class'] = Item::getColor($obj['weapon_id']);
        if (!empty($obj['runa'])) {
            $weapons_1Base['runa'] = Item::Runes($obj['runa']);
        }
    }
    // 2 рука
    if (in_array($obj['weapon_id'], $itemType['weapons_2'])) {
        $weapons_2Base['id'] = $obj['weapon_id'];
        $weapons_2Base['level_sharpening'] = $obj['level_sharpening'];
        $weapons_2Base['class'] = Item::getColor($obj['weapon_id']);
        if (!empty($obj['runa'])) {
            $weapons_2Base['runa'] = Item::Runes($obj['runa']);
        }
    }
    // штаны
    if (in_array($obj['weapon_id'], $itemType['pants'])) {
        $pantsBase['id'] = $obj['weapon_id'];
        $pantsBase['level_sharpening'] = $obj['level_sharpening'];
        $pantsBase['class'] = Item::getColor($obj['weapon_id']);
        if (!empty($obj['runa'])) {
            $pantsBase['runa'] = Item::Runes($obj['runa']);
        }
    }
    // штаны
    if (in_array($obj['weapon_id'], $itemType['boots'])) {
        $bootsBase['id'] = $obj['weapon_id'];
        $bootsBase['level_sharpening'] = $obj['level_sharpening'];
        $bootsBase['class'] = Item::getColor($obj['weapon_id']);
        if (!empty($obj['runa'])) {
            $bootsBase['runa'] = Item::Runes($obj['runa']);
        }
    }
}
?>

    <div class="main-hero_p">

        <div class="hero-wrapper_p"></div>
        <div class="middle-maneken_p"><img src="/avenax/Maneken.php?user=<?= $all['id']; ?>"
                                           style="max-width:250px;height:auto;" alt="maneken"></div>
        <div class="b-gradient-line_p"></div>
        <table align="center" cellspacing="5" cellpadding="5" width="90%">
            <tbody>
            <tr>
                <td class="center" style="padding: 0px 0px 0px 10px;"><a
                            href="<?= (!empty($headBase['id']) ? '/item/' . $headBase['id'] : '/armors'); ?>">
                        <div class="<?= $headBase['class']; ?> bor-no-radius"
                             style="background-image:url('/images/items/<?= (!empty($headBase['id']) ? $headBase['id'] : 'null'); ?>.png');background-repeat:no-repeat;background-size:cover;height:55px;width:55px;">
                            <div style="float:left;margin-top:-5px;"><?= (!empty($headBase['level_sharpening']) ? '<font color="#ffd166">+' . $headBase['level_sharpening'] . '</font>' : ''); ?></div>
                            <div style="float:right;margin-top:-5px;"><?= (!empty($headBase['runa']) ? '<img src="' . $headBase['runa']->img . '" width="20px">' : ''); ?>
                            </div>
                        </div>
                    </a><br> <a
                            href="<?= (!empty($shoulderBase['id']) ? '/item/' . $shoulderBase['id'] : '/armors'); ?>">
                        <div class="<?= $shoulderBase['class']; ?> bor-no-radius"
                             style="background-image:url('/images/items/<?= (!empty($shoulderBase['id']) ? $shoulderBase['id'] : 'null'); ?>.png');background-repeat:no-repeat;background-size:cover;height:55px;width:55px;">
                            <div style="float:left;margin-top:-5px;"><?= (!empty($shoulderBase['level_sharpening']) ? '<font color="#ffd166">+' . $shoulderBase['level_sharpening'] . '</font>' : ''); ?></div>
                            <div style="float:right;margin-top:-5px;"><?= (!empty($shoulderBase['runa']) ? '<img src="' . $shoulderBase['runa']->img . '" width="20px">' : ''); ?>
                            </div>
                        </div>
                    </a><br> <a href="<?= (!empty($armorBase['id']) ? '/item/' . $armorBase['id'] : '/armors'); ?>">
                        <div class="<?= $armorBase['class']; ?> bor-no-radius"
                             style="background-image:url('/images/items/<?= (!empty($armorBase['id']) ? $armorBase['id'] : 'null'); ?>.png');background-repeat:no-repeat;background-size:cover;height:55px;width:55px;">
                            <div style="float:left;margin-top:-5px;"><?= (!empty($armorBase['level_sharpening']) ? '<font color="#ffd166">+' . $armorBase['level_sharpening'] . '</font>' : ''); ?></div>
                            <div style="float:right;margin-top:-5px;"><?= (!empty($armorBase['runa']) ? '<img src="' . $armorBase['runa']->img . '" width="20px">' : ''); ?>
                            </div>
                        </div>
                    </a><br> <a href="<?= (!empty($glovesBase['id']) ? '/item/' . $glovesBase['id'] : '/armors'); ?>">
                        <div class="<?= $glovesBase['class']; ?> bor-no-radius"
                             style="background-image:url('/images/items/<?= (!empty($glovesBase['id']) ? $glovesBase['id'] : 'null'); ?>.png');background-repeat:no-repeat;background-size:cover;height:55px;width:55px;">
                            <div style="float:left;margin-top:-5px;"><?= (!empty($glovesBase['level_sharpening']) ? '<font color="#ffd166">+' . $glovesBase['level_sharpening'] . '</font>' : ''); ?></div>
                            <div style="float:right;margin-top:-5px;"><?= (!empty($glovesBase['runa']) ? '<img src="' . $glovesBase['runa']->img . '" width="20px">' : ''); ?>
                            </div>
                        </div>
                    </a><br></td>
                <td class="right"><a
                            href="<?= (!empty($weapons_1Base['id']) ? '/item/' . $weapons_1Base['id'] : '/armors'); ?>">
                        <div class="<?= $weapons_1Base['class']; ?> bor-no-radius"
                             style="background-image:url('/images/items/<?= (!empty($weapons_1Base['id']) ? $weapons_1Base['id'] : 'null'); ?>.png');background-repeat:no-repeat;background-size:cover;height:55px;width:55px;">
                            <div style="float:left;margin-top:-5px;"><?= (!empty($weapons_1Base['level_sharpening']) ? '<font color="#ffd166">+' . $weapons_1Base['level_sharpening'] . '</font>' : ''); ?></div>
                            <div style="float:right;margin-top:-5px;"><?= (!empty($weapons_1Base['runa']) ? '<img src="' . $weapons_1Base['runa']->img . '" width="20px">' : ''); ?>
                            </div>
                        </div>
                    </a><br> <a
                            href="<?= (!empty($weapons_2Base['id']) ? '/item/' . $weapons_2Base['id'] : '/armors'); ?>">
                        <div class="<?= $weapons_2Base['class']; ?> bor-no-radius"
                             style="background-image:url('/images/items/<?= (!empty($weapons_2Base['id']) ? $weapons_2Base['id'] : 'null'); ?>.png');background-repeat:no-repeat;background-size:cover;height:55px;width:55px;">
                            <div style="float:left;margin-top:-5px;"><?= (!empty($weapons_2Base['level_sharpening']) ? '<font color="#ffd166">+' . $weapons_2Base['level_sharpening'] . '</font>' : ''); ?></div>
                            <div style="float:right;margin-top:-5px;"><?= (!empty($weapons_2Base['runa']) ? '<img src="' . $weapons_2Base['runa']->img . '" width="20px">' : ''); ?>
                            </div>
                        </div>
                    </a><br> <a href="<?= (!empty($pantsBase['id']) ? '/item/' . $pantsBase['id'] : '/armors'); ?>">
                        <div class="<?= $pantsBase['class']; ?> bor-no-radius"
                             style="background-image:url('/images/items/<?= (!empty($pantsBase['id']) ? $pantsBase['id'] : 'null'); ?>.png');background-repeat:no-repeat;background-size:cover;height:55px;width:55px;">
                            <div style="float:left;margin-top:-5px;"><?= (!empty($pantsBase['level_sharpening']) ? '<font color="#ffd166">+' . $pantsBase['level_sharpening'] . '</font>' : ''); ?></div>
                            <div style="float:right;margin-top:-5px;"><?= (!empty($pantsBase['runa']) ? '<img src="' . $pantsBase['runa']->img . '" width="20px">' : ''); ?>
                            </div>
                        </div>
                    </a><br> <a href="<?= (!empty($bootsBase['id']) ? '/item/' . $bootsBase['id'] : '/armors'); ?>">
                        <div class="<?= $bootsBase['class']; ?> bor-no-radius"
                             style="background-image:url('/images/items/<?= (!empty($bootsBase['id']) ? $bootsBase['id'] : 'null'); ?>.png');background-repeat:no-repeat;background-size:cover;height:55px;width:55px;">
                            <div style="float:left;margin-top:-5px;"><?= (!empty($bootsBase['level_sharpening']) ? '<font color="#ffd166">+' . $bootsBase['level_sharpening'] . '</font>' : ''); ?></div>
                            <div style="float:right;margin-top:-5px;"><?= (!empty($bootsBase['runa']) ? '<img src="' . $bootsBase['runa']->img . '" width="20px">' : ''); ?>
                            </div>
                        </div>
                    </a><br></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="center" style="background: #000;">




        <?php
        $count_trofeis = cnt("SELECT * FROM `trofeis_user` WHERE `id_user` = ?", [$all['id']]);
        if($count_trofeis >= 1){
            $trofeis = acc("SELECT * FROM `trofeis_user` WHERE `id_user` = ? ORDER BY `id` ASC LIMIT 10", [$all['id']]);
            echo '<div class="wrapper center"><a href="/trofeis/'.$all['id'].'/">';
            foreach($trofeis as $t){
                echo '<img src="/images/trofeis/'.$t['id_trofei'].'.png" width="55px">';
            }
            echo'</div></a>';
        }
 
        ?>

    </div>

<div style="background-image:url(/images/invasion/bg2.jpg);background-repeat:no-repeat;background-size:cover;padding:5px;">


<div style="padding-top: 3px;"></div>


<?php
#-ПОДАРКИ ИГРОКА-#
$sel_gift_u = $pdo->prepare("SELECT COUNT(*) FROM `gift_users` WHERE `recip_id` = :all_id");
$sel_gift_u->execute(array(':all_id' => $all['id']));
$countGift = $sel_gift_u->fetchColumn();


#-ПЕРВЫЙ БЛОК-#
#-Опыт-#

$sel_clan = $pdo->prepare("SELECT * FROM clan_users, clan WHERE clan_users.user_id = :all_id AND clan_users.clan_id = clan.id");
$sel_clan->execute(array(':all_id' => $all['id']));
$clan = false;
if ($sel_clan->rowCount() != 0) {
    $clan = $sel_clan->fetch(PDO::FETCH_LAZY);
}

#-Бонус от зелья-#
if ($all['sila_bonus'] > 0) {
    $ost_sila = $all['sila_time'] - time();
    $sila_bonus = " <span class='blue'>+$all[sila_bonus] (" . timer_u($ost_sila) . ")</span>";
}
if ($all['zashita_bonus'] > 0) {
    $ost_zashita = $all['zashita_time'] - time();
    $zashita_bonus = " <span class='blue'>+$all[zashita_bonus] (" . timer_u($ost_zashita) . ")</span>";
}
if ($all['health_bonus'] > 0) {
    $ost_health = $all['health_time'] - time();
    $health_bonus = " <span class='blue'>+$all[health_bonus] (" . timer_u($ost_health) . ")</span>";
}


#-Захваченная локация в охоте-#
$sel_hunting = $pdo->prepare("SELECT `id`, `user_id`, `name` FROM `hunting` WHERE `user_id` = :user_id");
$sel_hunting->execute(array(':user_id' => $all['id']));
$hunting = false;
if ($sel_hunting->rowCount() != 0) {
    $hunting = $sel_hunting->fetch(PDO::FETCH_LAZY);
}

if ($_GET['inf'] == 'all') {
    $hide = '.enabled';
}
?>



        <table width="100%">
            <tbody>
            <tr>
                <td valign="top" style="text-align:left;"><img src="/style/images/user/level.png" width="16px"> <font
                            color="#ceb591">Уровень:</font> <?= $all['level']; ?>
                    <div class="mt5"></div>
                    <img src="/style/images/body/static.png" width="16px"> Статус: <?= (!empty($all['status']) ? htmlspecialchars($all['status']) : 'Не указан'); ?>
                    <?php if ($all['id'] == $user['id']): ?>
                        <?php if(!empty($user['status'])): ?>
                            <a href="/setting?change=status">[Ред.]</a> <a href="/setting_act?act=status_del">[X]</a>
                        <?php else: ?>
                            <a href="/setting?change=status">[Изменить статус]</a>
                        <?php endif; ?>
                    <?php endif; ?>

                    <div class="mt5"></div>
                    <img src="/style/images/user/sila.png" width="16px"> Сила: <span
                            class='yellow'><?= $all['s_sila']; ?></span> <?= (($all['sila'] > 0 ? "<span class='green'>+$all[sila]</span>" : "")) . $sila_bonus; ?>
                    <div class="mt5"></div>
                    <img src="/style/images/user/zashita.png" width="16px"> Защита: <span
                            class='yellow'><?= $all['s_zashita']; ?></span> <?= ($all['zashita'] > 0 ? "<span class='green'>+$all[zashita]</span>" : "") . $zashita_bonus; ?>
                    <div class="mt5"></div>
                    <img src="/style/images/user/health.png" width="16px"> Здоровье: <span
                            class='yellow'><?= $all['s_health']; ?></span> <?= ($all['health'] > 0 ? "<span class='green'>+$all[health]</span>" : "") . $health_bonus; ?>
                    <div class="mt5"></div>
                    <img src="/style/images/body/all.png" width="16px"> Всего: <?= $all['param']; ?>
                    <div class="mt5"></div>
                    <?php
                    if ($all['level'] != 100) {
                        $level = file(H . "/system/exp.txt");
                        $exp = trim($level[$all['level'] + 1]);
                        echo '<img src="/style/images/user/exp.png" width="16px" alt=""/> Опыт: <span class="yellow">' . num_format($all['exp']) . '/' . num_format($exp) . '</span>';
                        echo '<div class="mt5"></div>';
                        echo '<img src="/style/images/user/exp_2.png" width="16px" alt=""/> Осталось: <span class="yellow">' . num_format($exp - $all['exp']) . '</span><br/>';
                    } else {
                        echo '<img src="/style/images/user/exp.png" width="16px" alt=""/> Опыт: <span class="yellow">' . num_format($all['exp']) . '/500\'000 [<img src="/style/images/user/figur.png" alt=""/>' . $all['figur'] . ']</span><br/>';
                    }
                    ?>
                    <div class="mt5"></div>
                    <img src="/style/images/user/<?= ($all['storona'] == 1 ? 'shine.png' : 'dark.png'); ?>"
                         width="16px"> Сторона: <span
                            class="yellow"><?= ($all['storona'] == 1 ? 'Свет' : 'Тьма'); ?></span>
                    <div class="mt5"></div>
                    <img src="/style/images/body/ohota.png" width="16px"> Захваченная локация: <?= (!empty($hunting) ? '<a href="/select_location"><span class="yellow">' . htmlspecialchars($hunting['name']) . '</span></a>' : 'Отсутствует'); ?>
                    <div class="mt5"></div>

                    <?php if (!empty($hunting)): ?>
                        <img src="/style/images/body/traing.png" width="16px"> Бонус к параметрам: <span class='yellow'><?= params($hunting['id']); ?></span> <?= $sila_bonus; ?>
                        <div class="mt5"></div>
                    <?php endif; ?>

                </td>
                <td style="text-align:right;"><img src="/images/invasion/info.png" width="65px"></td>
            </tr>
            </tbody>
        </table>

        <?php if(!empty($clan)): ?>
            <hr>
            <table width="100%">
                <tbody>
                <tr>
                    <td valign="top" style="text-align:left;"><img src="/style/images/body/clan.png" width="16px">
                        <a href="/clan/view/<?= $clan['clan_id']; ?>"><?= htmlspecialchars($clan['name']); ?></a>
                        <div class="mt5"></div>
                            <img src="/style/images/user/exp.png" width="16px" alt=""> Звание: <?= Item::getUserRang($clan['prava']); ?>
                        <div class="mt5"></div>
                    </td>
                    <td style="text-align:right;"><img src="<?= avatar_clan($clan['avatar']); ?>" width="65px"></td>
                </tr>
                </tbody>
            </table>
        <?php endif; ?>



<hr>
        <?php if (!isset($_GET['inf'])): ?>
        <a href='/hero/<?= $all['id']; ?>?inf=all' id='arrow'>Показать полностью</a> <img src="/style/images/mail/arrow_bot.png" alt="" class="arrow"/>
        <?php else: ?>
        <a href='/hero/<?= $all['id']; ?>' id='arrow'>Показать полностью</a> <img src="/style/images/mail/arrow_up.png" alt="" class="arrow"/>
        <?php endif; ?>
        <div class="mt5"></div>

        <div id="panel<?= $hide; ?>">
<?php





#-Статистика-#
echo '<img src="/style/images/body/static.png" alt=""/> Статистика: ';
if ($all['id'] == $user['id']) {
    echo '<a href="/setting_act?act=statik">' . ($user['statik'] == 0 ? 'Открыта' : 'Скрыта') . '</a>';
}
if ($all['statik'] == 0 or $all['id'] == $user['id'] or ($user['prava'] == 1 or $user['prava'] == 3)) {
#-Охота-#
    echo '<br/><img src="/style/images/body/ohota.png"/> Охота - <img src="/style/images/body/ok.png" alt=""/><span class="green">' . $all['hunting_pobeda'] . '</span> <img src="/style/images/body/error.png" alt=""/><span class="red">' . $all['hunting_progrash'] . '</span> <span class="white">(' . ($all['hunting_pobeda'] > 0 ? '' . round((($all['hunting_pobeda'] / ($all['hunting_pobeda'] + $all['hunting_progrash'])) * 100), 2) . '' : '0') . '%)</span><br/>';
#-Дуэли-#
    echo '<img src="/style/images/body/league.png"/> Дуэли - <img src="/style/images/body/ok.png" alt=""/><span class="green">' . $all['duel_pobeda'] . '</span> <img src="/style/images/body/error.png" alt=""/><span class="red">' . $all['duel_progrash'] . '</span> <span class="white">(' . ($all['duel_pobeda'] > 0 ? '' . round((($all['duel_pobeda'] / ($all['duel_pobeda'] + $all['duel_progrash'])) * 100), 2) . '' : '0') . '%)</span><br/>';
#-Боссы-#
    echo '<img src="/style/images/body/bos.png"/> Боссы - <img src="/style/images/body/ok.png" alt=""/><span class="green">' . $all['boss_pobeda'] . '</span> <img src="/style/images/body/error.png" alt=""/><span class="red">' . $all['boss_progrash'] . '</span> <span class="white">(' . ($all['boss_pobeda'] > 0 ? '' . round((($all['boss_pobeda'] / ($all['boss_pobeda'] + $all['boss_progrash'])) * 100), 2) . '' : '0') . '%)</span><br/>';
#-Замки-#
    echo '<img src="/style/images/body/zamki.png"/> Замки - <img src="/style/images/body/ok.png" alt=""/><span class="green">' . $all['zamki_pobeda'] . '</span> <img src="/style/images/body/error.png" alt=""/><span class="red">' . $all['zamki_progrash'] . '</span> <span class="white">(' . ($all['zamki_pobeda'] > 0 ? '' . round((($all['zamki_pobeda'] / ($all['zamki_pobeda'] + $all['zamki_progrash'])) * 100), 2) . '' : '0') . '%)</span><br/>';
#-Рейд-#
    echo '<img src="/style/images/body/reid.png"/> Рейд - <img src="/style/images/body/ok.png" alt=""/><span class="green">' . $all['reid_pobeda'] . '</span> <img src="/style/images/body/error.png" alt=""/><span class="red">' . $all['reid_progrash'] . '</span> <span class="white">(' . ($all['reid_pobeda'] > 0 ? '' . round((($all['reid_pobeda'] / ($all['reid_pobeda'] + $all['reid_progrash'])) * 100), 2) . '' : '0') . '%)</span><br/>';
#-Колизей-#
    echo '<img src="/style/images/body/coliseum.png"/> Колизей - <img src="/style/images/body/ok.png" alt=""/><span class="green">' . $all['coliseum_pobeda'] . '</span> <img src="/style/images/body/error.png" alt=""/><span class="red">' . $all['coliseum_progrash'] . '</span> <span class="white">(' . ($all['coliseum_pobeda'] > 0 ? '' . round((($all['coliseum_pobeda'] / ($all['coliseum_pobeda'] + $all['coliseum_progrash'])) * 100), 2) . '' : '0') . '%)</span><br/>';
#-Башни-#
    echo '<img src="/style/images/body/towers.png"/> Башни - <img src="/style/images/body/ok.png" alt=""/><span class="green">' . $all['towers_pobeda'] . '</span> <img src="/style/images/body/error.png" alt=""/><span class="red">' . $all['towers_progrash'] . '</span> <span class="white">(' . ($all['towers_pobeda'] > 0 ? '' . round((($all['towers_pobeda'] / ($all['towers_pobeda'] + $all['towers_progrash'])) * 100), 2) . '' : '0') . '%)</span><br/>';
#-Питомцы-#
    echo '<img src="/style/images/body/pets.png"/> Питомцы - <img src="/style/images/body/ok.png" alt=""/><span class="green">' . $all['pets_pobeda'] . '</span> <img src="/style/images/body/error.png" alt=""/><span class="red">' . $all['pets_progrash'] . '</span> <span class="white">(' . ($all['pets_pobeda'] > 0 ? '' . round((($all['pets_pobeda'] / ($all['pets_pobeda'] + $all['pets_progrash'])) * 100), 2) . '' : '0') . '%)</span><br/>';
#-Сундуки-#
    echo '<img src="/style/images/body/chest.png" alt=""/> Сундуки - <img src="/style/images/body/ok.png" alt=""/><span class="green">' . $all['chest'] . '</span><br/>';
#-Задания-#
    echo '<img src="/style/images/body/daily_tasks.png" alt=""/> Задания - <img src="/style/images/body/ok.png" alt=""/><span class="green">' . $all['tasks'] . '</span><br/>';
} else {
    echo ' <span class="red">Скрыта</span>';
}
echo '</div>';

#-Рейтинг-# 																

echo "<img src='/style/images/body/rating.png' alt=''/> Место в рейтинге:<br/>";
echo "<img src='/style/images/body/all.png' alt=''/> По параметрам: <img src='/style/images/body/rating.png' alt=''/><span class='yellow'>$mesto_param</span><br/>";
echo "<img src='/style/images/body/ohota.png' alt=''/> Охота: <img src='/style/images/body/rating.png' alt=''/><span class='yellow'>$mesto_hunting</span><br/>";
echo "<img src='/style/images/body/league.png' alt=''/> Дуэли: <img src='/style/images/body/rating.png' alt=''/><span class='yellow'>$mesto_duel</span><br/>";
echo "<img src='/style/images/body/bos.png' alt=''/> Боссы: <img src='/style/images/body/rating.png' alt=''/><span class='yellow'>$mesto_boss</span><br/>";
echo "<img src='/style/images/body/daily_tasks.png' alt=''/> Задания: <img src='/style/images/body/rating.png' alt=''/><span class='yellow'>$mesto_tasks</span><div class=\"mt5\"></div>";


#-ЧЕТВЕРТЫЙ БЛОК-#

$today = time();
$time_o = floor(($today - $all['time_online']));
#-Онлайн или нет-#
$time = time() - 1200;
if ($all['time_online'] > $time) {
    $online = '<span class="green"> Онлайн</span>';
    $img_online = '/style/images/user/online.png';
} else {
    $online = '<span class="red"> Оффлайн</span>';
    $img_online = '/style/images/user/offline.png';
}
#-Роль игрока-#
if ($all['prava'] == 1) {
    $rol = 'Администратор';
    $color = 'red';
    $img_r = '/style/images/chat/admin.png';
}
if ($all['prava'] == 2) {
    $rol = 'Модератор';
    $color = 'blue';
    $img_r = '/style/images/chat/moder.png';
}
if ($all['prava'] == 3) {
    $rol = 'Служба поддержки';
    $color = 'blue';
    $img_r = '/style/images/chat/moder.png';
}
if ($all['prava'] == 4) {
    $rol = 'Модератор';
    $color = 'blue';
    $img_r = '/style/images/chat/moder.png';
}

#-Роль игрока-#
if ($all['prava'] != 0) {
    echo '<img src="' . $img_r . '"/> Роль: <span class="' . $color . '">' . $rol . '</span><br/>';
}
#-Заблокирован или нет-#
if ($all['block'] != 0) {
    echo '<img src="/style/images/body/error.png" alt=""/><span class="red"> Заблокирован до: ' . vremja($all['block']) . '</span><br/>';
    echo '<span class="red">Причина: ' . $all['cause'] . '</span><br/>';
}
#-Молчанка-#
if ($all['ban'] != 0) {
    echo '<img src="/style/images/body/error.png" alt=""/><span class="red"> Наложена молчанка до: ' . vremja($all['ban']) . '</span><br/>';
    echo '<span class="red">Причина: ' . $all['cause'] . '</span><br/>';
}
#-Кол-во нарушений-#
echo '<img src="/style/images/body/violation.png" alt=""/> Нарушений: <span class="yellow"> ' . $all['violation'] . '</span><br/>';
#-Премиум аккаунт-#
if ($all['premium'] != 0) {
    echo '<img src="/style/images/body/premium.png" alt=""/> Премиум: <span class="yellow"> ' . ($all['premium'] == 1 ? 'Активирован Серебряный до ' . vremja($all['premium_time']) . '' : 'Активирован Золотой до ' . vremja($all['premium_time']) . '') . '</span><br/>';
} else {
    echo '<img src="/style/images/body/premium.png"/> Премиум: <span class="yellow"> Не активирован</span><br/>';
}
#-Пол-#
echo '<img src="' . ($all['pol'] == 1 ? "/style/images/user/pol_1.png" : "/style/images/user/pol_2.png") . '"/> Пол: <span class="yellow">' . ($all['pol'] == 1 ? "Мужской" : "Женский") . '</span><br/>';
#-Количество друзей-#
$sel_friends_c = $pdo->prepare("SELECT COUNT(*) FROM `friends` WHERE `friend_1` = :all_id OR `friend_2` = :all_id");
$sel_friends_c->execute(array(':all_id' => $all['id']));
$amount_friends = $sel_friends_c->fetch(PDO::FETCH_LAZY);
if ($all['id'] == $user['id']) {
    echo '<img src="/style/images/body/friends.png" alt=""/> <a href="/friends/' . $all['id'] . '"><span class="gray">Друзей:</span></a> <span class="yellow">' . $amount_friends[0] . '</span><br/>';
}
if ($user['prava'] > 0) {
echo '<img src="/style/images/body/time_reg.png" alt=""/> Регистрация: <span class="yellow">' . vremja($all['time']) . '</span><br/>';
echo '<img src="/style/images/body/calendar.png" alt=""/> С момента регистрации: <span class="yellow">' . vremja_p($all['time']) . '</span><br/>';
echo '<img src="/style/images/body/refresh.png" alt=""/> Посл. действие: <span class="yellow"> ' . timers($time_o) . ' назад</span><br/>';
}
echo '<img src="' . $img_online . '"/> Сейчас: <span class="yellow">' . $online . '</span><br/>';

#-ПИТОМЕЦ-#
$sel_pets_me = $pdo->prepare("SELECT * FROM `pets_me` WHERE `user_id` = :all_id AND `active` = 1");
$sel_pets_me->execute(array(':all_id' => $all['id']));
if ($sel_pets_me->rowCount() != 0) {
    $pets_me = $sel_pets_me->fetch(PDO::FETCH_LAZY);

    $sel_pets = $pdo->prepare("SELECT `id`, `name`, `sila`, `zashita`, `health`, `images` FROM `pets` WHERE `id` = :pets_id");
    $sel_pets->execute(array(':pets_id' => $pets_me['pets_id']));
    $pets = $sel_pets->fetch(PDO::FETCH_LAZY);
    echo '<div class="line_1_v"></div>';
    echo '<div class="body_list">';
    echo '<div class="t_max">';
    echo '<img src="' . $pets['images'] . '" class="t_img" width="50" height="50" alt=""/>';
    echo '<div class="t_name"><img src="/style/images/quality/1.png" alt=""/><b>' . $pets['name'] . '</b><br/> <span class="menulitl_param"><img src="/style/images/user/sila.png" alt=""/>' . ($pets['sila'] + $pets_me['b_param']) . ' <img src="/style/images/user/zashita.png" alt=""/>' . ($pets['zashita'] + $pets_me['b_param']) . '  <img src="/style/images/user/health.png" alt=""/>' . ($pets['health'] + $pets_me['b_param']) . '</span><br/>' . pets_ability($pets_me['pets_id'], $pets_me['abi_prosent']) . '</div>';
    echo '</div>';
    echo '</div>';
}

echo '</div>';

#-ССЫЛКИ-#
echo '<div class="line"></div>';


echo '<div class="body_list">';
#-ID не нашего игрока-#
echo '<div class="menulist">';
if ($all['id'] != $user['id']) {
#-Почта-#
    if ($all['level'] >= 20 and $user['level'] >= 20) {
        if ($all['ev_mail'] == 0) {
            $ev_mail = 'OK';
        }
#-Только друзья-#
        if ($all['ev_mail'] == 1) {
            $sel_friends = $pdo->prepare("SELECT * FROM `friends` WHERE (`friend_1` = :user_id AND `friend_2` = :all_id) OR (`friend_1` = :all_id AND `friend_2` = :user_id)");
            $sel_friends->execute(array(':user_id' => $user['id'], ':all_id' => $all['id']));
            if ($sel_friends->rowCount() != 0) {
                $ev_mail = 'OK';
            }
        }
#-Проверка-#
        if (isset($ev_mail)) {
            echo '<li><a class="link" href="/mail_write/' . $all['id'] . '"><img src="/style/images/user/mail.png" alt=""/> Написать</a></li>';
        } else {
            echo '<li><a class="link" href="/hero/' . $all['id'] . '"><img src="/style/images/user/mail.png" alt=""/> <span class="red">Написать</span></a></li>';
        }

    } else {
        echo '<li><a class="link" href="/hero/' . $all['id'] . '"><img src="/style/images/user/mail.png" alt=""/> <span class="white">Почта с <img src="/style/images/user/level.png" alt=""/>20 ур.</span></a></li>';
    }
echo '<div class="line"></div>';
#-Активные артефакты-#
    echo '<div class="line_1"></div>';
    echo '<li><a class="link"  href="/artefact/' . $all['id'] . '/?type=1"><img src="/style/images/body/achive.png" alt=""/> Активные артефакты</a></li>';

#-Подарки-#
    if ($all['level'] >= 10 and $user['level'] >= 10) {
        echo '<div class="line"></div>';
        echo '<li><a class="link" href="/gift/' . $all['id'] . '"><img src="/style/images/body/gift.png" alt=""/> Подарки (' . $countGift . ')</a></li>';
        echo '<div class="line"></div>';
        echo '<li><a class="link" href="/gift_give?id=' . $all['id'] . '"><img src="/style/images/body/gift.png" alt=""/> Подарить подарок</a></li>';
    }

#-Добавляем в друзья-#
#-Проверяем что нет в друзьях-#
    $sel_friends = $pdo->prepare("SELECT * FROM `friends` WHERE (`friend_1` = :user_id AND `friend_2` = :all_id) OR (`friend_1` = :all_id AND `friend_2` = :user_id)");
    $sel_friends->execute(array(':user_id' => $user['id'], ':all_id' => $all['id']));
    if ($sel_friends->rowCount() == 0) {
#-Отправлена заявка или нет-#
        $sel_event_f = $pdo->prepare("SELECT * FROM `event_log` WHERE `user_id` = :ank_id AND `ank_id` = :user_id AND `type` = 6");
        $sel_event_f->execute(array(':ank_id' => $all['id'], ':user_id' => $user['id']));
        if ($sel_event_f->rowCount() == 0) {
            echo '<div class="line"></div>';
            echo '<li><a class="link" href="/friends_act?act=send&id=' . $all['id'] . '"><img src="/style/images/body/friends_add.png" alt=""/> Добавить в друзья</a></li>';
        } else {
            echo '<div class="line"></div>';
            echo '<li><a class="link" href="/hero/' . $all['id'] . '"><img src="/style/images/body/friends.png" alt=""/> Заявка отправлена</a></li>';
        }
    } else {
        echo '<div class="line"></div>';
        echo '<li><a class="link" href="/friends_act?act=del&id=' . $all['id'] . '&redicet=' . $redicet . '"><img src="/style/images/body/friends_del.png" alt=""/> Удалить из друзей</a></li>';
    }

#-Черный список-#
    echo '<div class="line"></div>';
    $sel_ignor = $pdo->prepare("SELECT * FROM `ignor` WHERE `kto` = :user_id AND `kogo` = :all_id");
    $sel_ignor->execute(array(':user_id' => $user['id'], ':all_id' => $all['id']));
    if ($sel_ignor->rowCount() == 0) {
        echo '<li><a class="link" href="/mail_act?act=ignor&id=' . $all['id'] . '&redicet=' . $_SERVER['REQUEST_URI'] . '"><img src="/style/images/body/error.png" alt=""/> Добавить в чёрный список</a></li>';
    } else {
        echo '<li><a class="link" href="/mail_act?act=ignor&id=' . $all['id'] . '&redicet=' . $_SERVER['REQUEST_URI'] . '"><img src="/style/images/body/error.png" alt=""/> Удалить из чёрного списка</a></li>';
    }

#-Дуэли-#
    if ($all['level'] >= 5 and $user['level'] >= 5) {
        echo '<div class="line"></div>';
        echo '<li><a class="link" href="/call_duel?ank_id=' . $all['id'] . '"><img src="/style/images/body/league.png" alt=""/> Вызвать на дуэль</a></li>';
    }


#-Приглашение в клан-#
#-Проверяем что мы состоим в клане и есть права-#
    $sel_clan_me = $pdo->prepare("SELECT * FROM `clan_users` WHERE `user_id` = :user_id AND `prava` != 0");
    $sel_clan_me->execute(array(':user_id' => $user['id']));
    if ($sel_clan_me->rowCount() != 0) {
        $clan_me = $sel_clan_me->fetch(PDO::FETCH_LAZY);
#-Проверяем что игрок не состоит в клане-#
        $sel_clan_ank = $pdo->prepare("SELECT * FROM `clan_users` WHERE `user_id` = :all_id");
        $sel_clan_ank->execute(array(':all_id' => $all['id']));
        if ($sel_clan_ank->rowCount() == 0) {
#-Уровень игрока 15-#
            if ($all['level'] >= 15) {
                echo '<div class="line"></div>';
                if ($all['ev_clan'] == 0) {
                    echo '<li><a class="link" href="/clan_invite?act=inv&all_id=' . $all['id'] . '&clan_id=' . $clan_me['clan_id'] . '"><img src="/style/images/body/clan.png" alt=""/> Пригласить в клан</a></li>';
                } else {
                    echo '<li><a class="link" href="/hero/' . $all['id'] . '"><img src="/style/images/body/clan.png" alt=""/> <span class="red">Пригласить в клан</span></a></li>';
                }
            }
        }
    }

#-Передача снаряжения-#
    if ($user['level'] >= 60 and $all['level'] >= 10) {
        echo '<div class="line"></div>';
        echo '<li><a class="link" href="/armor_transfer?ank_id=' . $all['id'] . '"><img src="/style/images/body/snarag.png" alt=""/> Передать снаряжение</a></li>';
    }

#-ДЛЯ АДМИНА И МОДЕРАТОРА-#
    if ($user['prava'] == 1 or $user['prava'] == 2 or $user['prava'] == 3) {
        echo '<div class="line_1"></div>';
        echo '<div class="mini-line"></div>';
        echo '<div class="line_1_v"></div>';
#-Информация о игроке-#
        echo '<li><a href="/user_info?user_id=' . $all['id'] . '"><img src="/style/images/body/imp.png" alt=""/> Информация о игроке</a></li>';
        echo '<div class="line_1"></div>';
        if ($user['prava'] == 1) {
#-Зачисление ресурсов-#	
            echo '<li><a href="/admin_gold?id=' . $all['id'] . '"><img src="/style/images/many/gold.png" alt=""/> Зачислить золото</a></li>';
            echo '<div class="line_1"></div>';
            echo '<li><a href="/admin_res?id=' . $all['id'] . '"><img src="/style/images/many/silver.png" alt=""/> Зачислить ресурсы</a></li>';
            echo '<div class="line_1"></div>';
            echo '<li><a href="/admin_armor?id=' . $all['id'] . '"><img src="/style/images/body/snarag.png" alt=""/> Выдать Комплект</a></li>';
            echo '<div class="line_1"></div>';
            echo '<li><a href="/admin_pets?id=' . $all['id'] . '"><img src="/style/images/body/pets.png" alt=""/> Выдать Питомца</a></li>';
            echo '<div class="line_1"></div>';
        }
#-Скрытие игрока-#
        echo '<li><a href="/hidden_act?act=hidden&ank_id=' . $all['id'] . '"><img src="/style/images/body/hide.png" alt=""/> Скрыть игрока</a></li>';
        echo '<div class="line_1"></div>';
#-Блок-#
        if ($all['block'] == 0) {
            echo '<li><a href="/admin_block?ank_id=' . $all['id'] . '"><img src="/style/images/body/error.png" alt=""/> Заблокировать</a></li>';
        } else {
            echo '<li><a href="/block_act?act=go&ank_id=' . $all['id'] . '"><img src="/style/images/body/ok.png" alt=""/> Разблокировать</a></li>';
        }
        echo '<div class="line_1"></div>';
#-Молчанка-#
        if ($all['ban'] == 0) {
            echo '<li><a href="/moder_ban?ank_id=' . $all['id'] . '&redicet=' . $_SERVER['REQUEST_URI'] . '"><img src="/style/images/body/silent.png" alt=""/> Наложить молчанку</a></li>';
        } else {
            echo '<li><a href="/ban_act?act=ban&user_id=' . $all['id'] . '&redicet=' . $_SERVER['REQUEST_URI'] . '"><img src="/style/images/body/silent.png" alt=""/> Убрать молчанку</a></li>';
        }
#-Блок IP-#
        if ($user['prava'] == 1) {
            echo '<div class="line_1"></div>';
            $sel_ip_block = $pdo->prepare("SELECT * FROM `ip_block` WHERE `ip` = :ip");
            $sel_ip_block->execute(array(':ip' => $all['ip']));
            if ($sel_ip_block->rowCount() == 0) {
                echo '<li><a href="/block_ip_act?act=ip&ank_id=' . $all['id'] . '"><img src="/style/images/body/ip.png" alt=""/> Блок IP</a></li>';
            } else {
                echo '<li><a href="/block_ip_act?act=ip&ank_id=' . $all['id'] . '"><img src="/style/images/body/ip.png" alt=""/> Разблок IP</a></li>';
            }
        }
        if ($user['prava'] > 0) {
            ?>
            <center><input type="submit" onclick="show_ip();" class="btni" value="Местоположение"></center>
            <center>
                <div class="ajax feedback"></div>
            </center>
            <script>
                function show_ip() {
                    $.ajax({
                        url: "/hero/<?=$all['id']?>?ip",
                        cache: false,
                        success: function (html) {
                            $(".ajax").html(html);
                        }
                    });
                }
            </script>
            <?
        }
#-Лишение прав-#
        if (($user['prava'] == 1 or $user['prava'] == 3) and ($all['prava'] == 2 or $all['prava'] == 3)) {
            echo '<div class="line"></div>';
            echo '<li><a href="/deprivation_prava?act=depr&all_id=' . $all['id'] . '"><img src="/style/images/body/error.png" alt=""/> Лишить прав</a></li>';
        }
    }
}

#-ID нашего игрока-#
if ($all['id'] == $user['id']) {
#-Почта-#
    if ($user['level'] >= 20) {
        echo '<li><a class="link" href="/mail"><img src="/style/images/user/mail.png" alt=""/> Почта</a></li>';
        echo '<div class="line"></div>';
    }

        echo '<li><a class="link" href="/gift/' . $all['id'] . '"><img src="/style/images/body/gift.png" alt=""/> Подарки (' . $countGift . ')</a></li>';
#-Питомцы-#
    if ($user['level'] >= 20) {
        $sel_pets_c = $pdo->prepare("SELECT COUNT(*) FROM `pets_me` WHERE `user_id` = :user_id");
        $sel_pets_c->execute(array(':user_id' => $user['id']));
        $amount_pets = $sel_pets_c->fetch(PDO::FETCH_LAZY);
        echo '<li><a class="link"  href="/pets"><img src="/style/images/body/pets.png" alt=""/> Питомцы <span class="white">(' . $amount_pets[0] . ')</span></a></li>';
        echo '<div class="line_1"></div>';
    }

#-Сундук-#
    $sel_chest_c = $pdo->prepare("SELECT COUNT(*) FROM `chest` WHERE `user_id` = :user_id");
    $sel_chest_c->execute(array(':user_id' => $user['id']));
    $amount_chest = $sel_chest_c->fetch(PDO::FETCH_LAZY);
    echo '<li><a class="link"  href="/chest"><img src="/style/images/body/chest.png" alt=""/> Сундуки <span class="white">(' . $amount_chest[0] . ')</span></a></li>';

#-Сумка-#
#-Считаем снаряжение-#
    $sel_weapon_c = $pdo->prepare("SELECT COUNT(*) as c FROM `item_user` WHERE `user_id` = :user_id AND `state` = 0");
    $sel_weapon_c->execute(array(':user_id' => $user['id']));
    $amount_weapon = $sel_weapon_c->fetch(PDO::FETCH_LAZY);

#-Количество зелья-#
    $sel_potions_c = $pdo->prepare("SELECT SUM(quatity) FROM `potions_me` WHERE `user_id` = :user_id");
    $sel_potions_c->execute(array(':user_id' => $user['id']));
    $amount_potions = $sel_potions_c->fetch(PDO::FETCH_LAZY);
    $amount_bag = $amount_potions[0] + $amount_weapon['c'];
    echo '<div class="line_1"></div>';
    echo '<li><a class="link"  href="/bag"><img src="/style/images/body/sumka.png" alt=""/> Сумка <span class="white">(' . $amount_bag . ')</span></a></li>';

#-Активные артефакты-#
    echo '<div class="line_1"></div>';
    echo '<li><a class="link"  href="/artefact/' . $all['id'] . '/?type=1"><img src="/style/images/body/achive.png" alt=""/> Активные артефакты</a></li>';

#-Тренировка-#
    echo '<div class="line_1"></div>';
    echo '<li><a class="link"  href="/training"><img src="/style/images/body/traing.png" alt=""/> Тренировка</a></li>';

#-Достижения-#
    echo '<div class="line_1"></div>';
    echo '<li><a class="link"  href="/achive"><img src="/style/images/body/achive.png" alt=""/> Достижения</a></li>';

#-Чёрный список-#
    $sel_ignor_c = $pdo->prepare("SELECT COUNT(*) FROM `ignor` WHERE `kto` = :user_id");
    $sel_ignor_c->execute(array(':user_id' => $user['id']));
    $amount = $sel_ignor_c->fetch(PDO::FETCH_LAZY);
    echo '<div class="line_1"></div>';
    echo '<li><a class="link"  href="/ignor_list"><img src="/style/images/body/error.png" alt=""/> Чёрный список <span class="white">(' . $amount[0] . ')</span></a></li>';

#-Реферальная программа-#
    echo '<div class="line_1"></div>';
    echo '<li><a class="link"  href="/ref"><img src="/style/images/body/ref.png" alt=""/> Реферальная программа</a></li>';

#-Настройки-#
    echo '<div class="line_1"></div>';
    echo '<li><a class="link"  href="/setting"><img src="/style/images/body/set.png" alt=""/> Настройки</a></li>';
}
echo '</div>';
echo '</div>';
echo '</div>';
require_once H . 'system/footer.php';
?>