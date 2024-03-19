<?php
require_once '../../system/system.php';
require_once '../../copy/copy_func.php';
only_reg();
armor_campaign();
$head = 'Сумка';
require_once H . 'system/head.php';
require_once H . 'avenax/Maneken.php';
require_once H . 'avenax/Item.php';
echo '<div class="page">';


//$getListSetItem = Item::Items();


#-Снаряжение-#
if ($_GET['type'] == 1 or !isset($_GET['type'])) {

#-Навигация-#
    $all = '<a href="/bag" style="text-decoration:none;"><span class="body_sel">' . (!isset($_GET['type_s']) ? "<b>Все</b>" : "Все") . '</span></a>';
    $head = '<a href="/bag?type_s=head" style="text-decoration:none;"><span class="body_sel">' . (isset($_GET['type_s']) && $_GET['type_s'] == 'head' ? "<b>Голова</b>" : "Голова") . '</span></a>';
    $shoulder = '<a href="/bag?type_s=shoulder" style="text-decoration:none;"><span class="body_sel">' . (isset($_GET['type_s']) && $_GET['type_s'] == 'shoulder' ? "<b>Плечо</b>" : "Плечо") . '</span></a>';
    $armor = '<a href="/bag?type_s=armor" style="text-decoration:none;"><span class="body_sel">' . (isset($_GET['type_s']) && $_GET['type_s'] == 'armor' ? "<b>Броня</b>" : "Броня") . '</span></a>';
    $gloves = '<a href="/bag?type_s=gloves" style="text-decoration:none;"><span class="body_sel">' . (isset($_GET['type_s']) && $_GET['type_s'] == 'gloves' ? "<b>Руки</b>" : "Руки") . '</span></a>';
    $weapons_1 = '<a href="/bag?type_s=weapons_1" style="text-decoration:none;"><span class="body_sel">' . (isset($_GET['type_s']) && $_GET['type_s'] == 'weapons_1' ? "<b>Левая рука</b>" : "Левая рука") . '</span></a>';
    $weapons_2 = '<a href="/bag?type_s=weapons_2" style="text-decoration:none;"><span class="body_sel">' . (isset($_GET['type_s']) && $_GET['type_s'] == 'weapons_2' ? "<b>Правая рука</b>" : "Правая рука") . '</span></a>';
    $pants = '<a href="/bag?type_s=pants" style="text-decoration:none;"><span class="body_sel">' . (isset($_GET['type_s']) && $_GET['type_s'] == 'pants' ? "<b>Штаны</b>" : "Штаны") . '</span></a>';
    $boots = '<a href="/bag?type_s=boots" style="text-decoration:none;"><span class="body_sel">' . (isset($_GET['type_s']) && $_GET['type_s'] == 'boots' ? "<b>Ботинки</b>" : "Ботинки") . '</span></a>';


#-Вывод-#
    echo '<div class="body_list">';
    echo '<div style="padding: 5px;">';
    echo $all . ' ' . $head . ' ' . $shoulder . ' ' . $armor . ' ' . $gloves . ' ' . $weapons_1 . ' ' . $weapons_2 . ' ' . $pants . ' ' . $boots;
    echo '</div>';
    echo '<div class="line_1"></div>';
    echo '</div>';

    #-Все без сортировки-#
    if ($_GET['type_s'] == 1 or !isset($_GET['type_s'])) {
        $sel_weapon_me = $pdo->prepare("SELECT * FROM `item_user` WHERE `user_id` = :user_id AND `state` = 0 ORDER BY `weapon_id` ASC");
        $sel_weapon_me->execute(array(':user_id' => $user['id']));
    }

    $typeItem = Item::getTypeItem();
    if (isset($_GET['type_s']) && array_key_exists($_GET['type_s'], $typeItem)) {
        $in = str_repeat('?,', count($typeItem[$_GET['type_s']]) - 1) . '?';
        array_unshift($typeItem[$_GET['type_s']], $user['id']);
        $sel_weapon_me = $pdo->prepare("SELECT * FROM `item_user` WHERE `user_id` = ? AND `state` = 0 AND `weapon_id` IN (" . $in . ") ORDER BY `weapon_id` ASC");
        $sel_weapon_me->execute($typeItem[$_GET['type_s']]);
    }

    #-Если есть оружие-#
    if ($sel_weapon_me->rowCount() != 0) {
        $weapon_me = $sel_weapon_me->fetchAll(PDO::FETCH_ASSOC);

        $arr = [];
        foreach ($weapon_me as $item) {
            $arr[$item['id']] = $item;
        }

        $userStateItems = Maneken::getUserItems($user['id']); // список всех одетых вещей

        // продаём
        if (isset($_GET['sale']) && array_key_exists($_GET['sale'], $arr)) {
            $itemTypeSale = Maneken::itemMore($arr[$_GET['sale']]['weapon_id']);
            $silver = Item::priceSale($itemTypeSale->levelSet);
            $upd_users2 = $pdo->prepare("UPDATE `users` SET `silver` = `silver` + :silver WHERE `id` = :id LIMIT 1");
            $upd_users2->execute(array(':silver' => $silver, ':id' => $user['id']));

            $del_weapon_me = $pdo->prepare("DELETE FROM `item_user` WHERE `id` = :id");
            $del_weapon_me->execute(array(':id' => $arr[$_GET['sale']]['id']));
            $_SESSION['ok'] = 'Ваша прибыль: <img src="/style/images/many/silver.png" alt=""> ' . $silver;
            header('Location: /bag');
            exit();
        }

        // разбираем
        if (isset($_GET['diss']) && array_key_exists($_GET['diss'], $arr)) {
            $itemInfoDiss = Maneken::itemMore($arr[$_GET['diss']]['weapon_id'], $arr[$_GET['diss']]['runa']); // инфо о вещи, которую хотим разобрать
            $typeItemInfoDiss = Item::getTypeItem($arr[$_GET['diss']]['weapon_id']); // узнаём тип вещи, которую хотим одеть


            $itemStateUserDiff = []; // хранит данные о веще, которая одета или пусто
            foreach ($userStateItems as $userState) {
                // узнаём тип вещи, которая одета
                if ($typeItemInfoDiss == Item::getTypeItem($userState['weapon_id'])) {
                    $itemStateUserDiff = $userState;
                }
            }

            if (empty($itemStateUserDiff)) {
                $err = 'На Вас не одета вещь!';
            }

            $itemInfoDissUser = Maneken::itemMore($itemStateUserDiff['weapon_id']); // инфо о вещи, которая одета
            if (empty($itemInfoDissUser)) {
                $err = 'Такой вещи не существует!';
            }

            if ($itemInfoDiss->levelSet > $itemInfoDissUser->levelSet) {
                $err = 'На Вас одета вещь по качеству хуже!';
            }

            if ($itemStateUserDiff['quenching_level_min'] >= $itemInfoDissUser->max_quenching) {
                $err = 'У Вас максимальный уровень вещи!';
            }

            if (isset($err)) {
                $_SESSION['err'] = $err;
            } else {
                // quenching_rating column
                $ratingHardBase = Item::ratingHard($itemInfoDissUser->levelSet);
                $level = 0;
                $up = 0;

                $checkRating = $itemStateUserDiff['quenching_rating'] + $ratingHardBase;
                if ($checkRating >= $itemInfoDissUser->quenching_rating) {
                    $level++;
                    $ratingHard = 0;
                    $up = 2;
                } else {
                    $ratingHard = $checkRating;
                }

                $runa = $itemStateUserDiff['runa'];
                $params = 0;
                $item_str = $itemStateUserDiff['str'];
                $item_def = $itemStateUserDiff['def'];
                $item_hp = $itemStateUserDiff['hp'];
                $str = $user['s_sila'] + $up;
                $def = $user['s_zashita'] + $up;
                $hp = $user['s_health'] + $up;

                if (!empty($itemInfoDiss->runa)) {
                    $runa = $itemInfoDiss->runa->id;

                    $runaItem = 0;
                    if ($itemStateUserDiff['runa'] == true) {
                        $runaItem = Item::Runes($itemStateUserDiff['runa'])->params;
                    }
                    $item_str = $itemStateUserDiff['str'] + $itemInfoDiss->runa->params - $runaItem;
                    $item_def = $itemStateUserDiff['def'] + $itemInfoDiss->runa->params - $runaItem;
                    $item_hp = $itemStateUserDiff['hp'] + $itemInfoDiss->runa->params - $runaItem;
                    $str += $itemInfoDiss->runa->params - $runaItem;
                    $def += $itemInfoDiss->runa->params - $runaItem;
                    $hp += $itemInfoDiss->runa->params - $runaItem;
                }

                // задание
                $quest = fch("SELECT * FROM `quest` WHERE `type` = ?", array('item_razbor'));
                $user_quest = fch("SELECT * FROM `quest_user` WHERE `id_quest` = ? AND `id_user` = ?", array($quest['id'], $user['id']));
                if($user_quest['ok'] == 0){
                    qry("UPDATE `quest_user` SET `kolls` = `kolls` + '1' WHERE `id` = ?", array($user_quest['id']));
                }

                $upd_users2 = $pdo->prepare("UPDATE `users` SET `s_sila` = :s_sila, `s_zashita` = :s_zashita, `s_health` = :s_health WHERE `id` = :id LIMIT 1");
                $upd_users2->execute(array(':s_sila' => $str, ':s_zashita' => $def, ':s_health' => $hp, ':id' => $user['id']));

                $upd_weapon_me = $pdo->prepare("UPDATE `item_user` SET `hp` = :hp, `def` = :def, `str` = :str, `runa` = :runa, `quenching_level_min` = `quenching_level_min` + :level, `quenching_rating` = :hard, `str` = `str` + :up, `def` = `def` + :up, `hp` = `hp` + :up WHERE `id` = :id LIMIT 1");
                $upd_weapon_me->execute(array(':str' => $item_str, ':def' => $item_def, ':hp' => $item_hp, ':runa' => $runa, ':level' => $level, ':hard' => $ratingHard, ':up' => $up, ':id' => $itemStateUserDiff['id']));

                $del_weapon_me = $pdo->prepare("DELETE FROM `item_user` WHERE `id` = :id");
                $del_weapon_me->execute(array(':id' => $arr[$_GET['diss']]['id']));

                $_SESSION['ok'] = 'Успешно разобрали <img src="/style/images/user/level_2.png" alt="">+' . $ratingHardBase;

            }
            header('Location: /bag');
            exit();
        }

        // одеваем
        if (isset($_GET['get']) && array_key_exists($_GET['get'], $arr)) {
            if ($user['battle'] != 0) {
                $_SESSION['err'] = 'Во время боя действие невозможно!';
                header('Location: /bag');
                exit();
            }

            $weapon_id = $arr[$_GET['get']]; // ID вещи, которую хотим одеть
            $itemInfo = Maneken::itemMore($weapon_id['weapon_id']); // инфо о вещи, которую хотим одеть
            $typeItem = Item::getTypeItem($weapon_id['weapon_id']); // узнаём тип вещи, которую хотим одеть

            if ($user['level'] < $itemInfo->level) {
                $_SESSION['err'] = 'У вас маленький уровень!';
                header('Location: /bag');
                exit();
            }

            $itemStateUser = []; // хранит данные о веще, которая одета или пусто
            foreach ($userStateItems as $userState) {
                // узнаём тип вещи, которая одета
                if ($typeItem == Item::getTypeItem($userState['weapon_id'])) {
                    $itemStateUser = $userState;
                }
            }

            // если на персонаже нет вещи, такого типа, которую хотим одеть
            if (empty($itemStateUser)) {
                // сила оружии + параметры руны
                $str = $user['s_sila'] + $weapon_id['str'];
                $def = $user['s_zashita'] + $weapon_id['def'];
                $hp = $user['s_health'] + $weapon_id['hp'];
            } else {
                // забераем характеристика итема с игрока
                $str_m = $user['s_sila'] - $itemStateUser['str'];
                // прибовляем новые
                $str = $str_m + $weapon_id['str'];
                $zashita_m = $user['s_zashita'] - $itemStateUser['def'];
                $def = $zashita_m + $weapon_id['def'];
                $hp_m = $user['s_health'] - $itemStateUser['hp'];
                $hp = $hp_m + $weapon_id['hp'];

                $upd_weapon_me = $pdo->prepare("UPDATE `item_user` SET `state` = :state WHERE `id` = :id LIMIT 1");
                $upd_weapon_me->execute(array(':state' => 0, ':id' => $itemStateUser['id']));
            }

            // обучение
            if ($user['start'] == 4) {
                $upd_users = $pdo->prepare("UPDATE `users` SET `start` = 5 WHERE `id` = :user_id LIMIT 1");
                $upd_users->execute(array(':user_id' => $user['id']));
            }

            $upd_users2 = $pdo->prepare("UPDATE `users` SET `s_sila` = :s_sila, `s_zashita` = :s_zashita, `s_health` = :s_health WHERE `id` = :id LIMIT 1");
            $upd_users2->execute(array(':s_sila' => $str, ':s_zashita' => $def, ':s_health' => $hp, ':id' => $user['id']));
            $upd_weapon_me2 = $pdo->prepare("UPDATE `item_user` SET `state` = :state WHERE `id` = :id LIMIT 1");
            $upd_weapon_me2->execute(array(':state' => 1, ':id' => $weapon_id['id']));

            header('Location: /bag');
            exit();
        }

        $userState = Maneken::getUserItems($user['id']);


        foreach ($arr as $items) {
            $itemType = Maneken::itemMore($items['weapon_id'], $items['runa']);

            $typeItemBag = Item::getTypeItem($items['weapon_id']); // узнаём тип вещи, которая в сумке

            $itemStateUserBag = []; // хранит данные о веще, которая одета или пусто

            foreach ($userStateItems as $userStateBag) {
                // узнаём тип вещи, которая одета
                if ($typeItemBag == Item::getTypeItem($userStateBag['weapon_id'])) {
                    $itemStateUserBag = $userStateBag;
                }
            }
            $useFeature = $items['str'] + $items['def'] + $items['hp']; // сумма характеристик вещи, которая в сумке
            $bagFeature = 0; // которая на персонаже
            $itemUser = [];
            if (!empty($itemStateUserBag)) {
                $bagFeature = $itemStateUserBag['str'] + $itemStateUserBag['def'] + $itemStateUserBag['hp'];
                $itemUser = Maneken::itemMore($itemStateUserBag['weapon_id']); // инфо вещи, которая одета
            }
            $w_param = false;

            // если сумма вещи в сумке больше, чем на игроке
            if ($useFeature > $bagFeature) {
                $param = $useFeature - $bagFeature;
                $w_param = "<span class='green'>[+$param]</span>";
            }
            // если сумма вещи в сумке меньше, чем на игроке
            if ($useFeature < $bagFeature) {
                $param = $bagFeature - $useFeature;
                $w_param = "<span class='red'>[-$param]</span>";
            }


            echo '<div class="img_weapon"><img src="/images/items/' . $items['weapon_id'] . '.png" class=""  alt="" width="48" height="48"></div><div class="weapon_setting">

<div><img src="' . $itemType->type_img . '" width="13px"> <span style="color: ' . color_w_new($itemType->levelSet) . ';"><b>' . $itemType->nameItem . '</b> [' . $itemType->level . ' ур.]</span></div>

<div class="weapon_param"><img src="/style/images/user/sila.png" alt=""/> ' . ($items['str']) . ' <img src="/style/images/user/zashita.png" alt=""/> ' . ($items['def']) . ' <img src="/style/images/user/health.png" alt=""/> ' . ($items['hp']) . ' ' . $w_param . ' <span style="color: green">[' . $items['level_sharpening'] . '/' . $itemType->max_sharpening . ']</span><br/>Руна: ' . ($items['runa'] == 0 ? "Не установлена" : "<span style='color: #{$itemType->runa->color}'>{$itemType->runa->name} [+{$itemType->runa->params}]</span>") . '</div></div>';
            echo '<div style="padding-top: 10px;"></div>';

            #-Уровень больше или равен нашему-#
            if ($user['level'] >= $itemType->level && $items['state'] == 0) {
                echo "<a href='/bag/get/$items[id]' class='button_green_a'>Надеть " . ($user['start'] == 4 ? "<img src='/style/images/body/left_mini.gif' alt=''/>" : "") . "</a>";
                echo '<div style="padding-top: 3px;"></div>';
            }

            
            if ($items['quenching_level_min'] >= 100) {
                echo "<a href='/bag/sale/{$items['id']}' class='button_red_a'>Продать за " . Item::priceSale($itemType->levelSet) . " <img src='/style/images/many/silver.png' alt=''></a>";
            } else {
                if ($itemType->levelSet <= $itemUser->levelSet) {
                    echo "<a href='/bag/diss/{$items['id']}' class='button_red_a'>Разобрать</a>";
                }
            }
            echo '<div style="padding-top: 5px;"></div>';
        }
    } else {
        echo '<div class="error_list"><img src="/style/images/body/error.png" alt=""/>Сумка пуста!</div>';
    }
}


#-Зелье и эликсиры-#
if ($_GET['type'] == 2) {
#-Делаем выборку нашего зелья-#
    $sel_potions_me = $pdo->prepare("SELECT * FROM `potions_me` WHERE `user_id` = :user_id ORDER BY `potions_id`");
    $sel_potions_me->execute(array(':user_id' => $user['id']));
#-если есть зелье-#
    if ($sel_potions_me->rowCount() != 0) {
        while ($potions_me = $sel_potions_me->fetch(PDO::FETCH_LAZY)) {
#-Выборка данных зелья-#
            $sel_potions = $pdo->prepare("SELECT * FROM `potions` WHERE `id` = :potions_id");
            $sel_potions->execute(array(':potions_id' => $potions_me['potions_id']));
            $potions = $sel_potions->fetch(PDO::FETCH_LAZY);
#-Звезда и цвет в зависимости от типа-#
            if ($potions['type'] == 1) {
                $star = 2;
                $color = 'green';
            }
            if ($potions['id'] == 4) {
                $star = 7;
                $color = 'red';
            }
            if ($potions['id'] == 5) {
                $star = 3;
                $color = 'yellow';
            }
            if ($potions['id'] == 6) {
                $star = 2;
                $color = 'green';
            }
            echo '<div style="min-height: 60px;">';
            echo "<div class='potions'><img src='$potions[images]' class='potions_img'/><div class='potions_name'><img src='/style/images/quality/$star.png' alt=''/><span class='$color'>$potions[name] [$potions_me[quatity]]</span><br/><span class='potions_param'>$potions[text]<br/></span></div></div>";
            echo '</div>';
            if ($potions['id'] != 1 and $potions['id'] != 2 and $potions['id'] != 3) {
                echo "<a href='/potions_act?act=itv&id=$potions[id]' class='button_green_a'>Использовать</a>";
            }
            echo '<div style="padding-top: 5px;"></div>';
        }
    } else {
        echo '<div class="line_1_m"></div>';
        echo '<div class="body_list">';
        echo '<div class="error_list"><img src="/style/images/body/error.png" alt=""/>Сумка пуста!</div>';
        echo '</div>';
    }
}

echo '<div class="body_list">';
echo '<div class="menulist">';

#-Количество зелья-#
$sel_potions_c = $pdo->prepare("SELECT SUM(quatity) FROM `potions_me` WHERE `user_id` = :user_id");
$sel_potions_c->execute(array(':user_id' => $user['id']));
$amount_potions = $sel_potions_c->fetch(PDO::FETCH_LAZY);
echo '<div class="line_1"></div>';
if ($_GET['type'] == 1 or !isset($_GET['type'])) {
    echo "<li><a href='/bag?type=2'><img src='/style/images/body/potions.png' alt=''/> Зелье [" . ($amount_potions[0] > 0 ? "$amount_potions[0]" : "0") . " шт.]</a></li>";
}
if ($_GET['type'] == 2) {
    echo "<li><a href='/bag?type=1'><img src='/style/images/body/traing.png' alt=''/> Снаряжение</a></li>";
}
echo '<div class="line_1"></div>';
echo "<li><a href='/weapon_me/$user[id]/?type=1'><img src='/style/images/body/forward.png' alt=''/> Экипировка</a></li>";
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';
require_once H . 'system/footer.php';
