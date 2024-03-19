<?php
/**
 * Created by PhpStorm.
 * User: Avenax
 * Date: 26.08.2019
 * Time: 21:38
 */


require_once '../../system/system.php';
$head = 'Магазин снаряжения';
if (isset($_GET['type']) && $_GET['type'] == 'runes') {
    $head = 'Торговец рунами';
}
only_reg();
trade_shop_campaign();
require_once H . 'system/head.php';
require_once H . 'avenax/Item.php';
require_once H . 'avenax/Maneken.php';
require_once H . 'avenax/.settings.php';


$getListSetItem = Item::Items();
$sel_stock = $pdo->query("SELECT * FROM `stock` WHERE `type` = 30");
$stock = $sel_stock->fetch(PDO::FETCH_ASSOC);


echo '<div class="page">';
echo '<img src="/style/images/location/trade_shop/armor.jpg" class="img"/>';

$case = isset($_GET['type']) ? $_GET['type'] : null;

switch ($case) {
    case 'runes' :

        $itemUserList = Maneken::getUserItems($user['id']);

        foreach ($itemUserList as $list) {
            $itemType = Maneken::itemMore($list['weapon_id'], $list['runa']);
            $runaParams = !empty($itemType->runa) ? $itemType->runa->params : 0;
            echo '<div class="img_weapon"><img width="48px" src="/images/items/' . $list['weapon_id'] . '.png" class=""  alt=""/></div>
<div class="weapon_setting"><span style="color: ' . color_w_new($itemType->levelSet) . ';"><img src="' . $itemType->type_img . '" alt=""/> <b>' . htmlspecialchars($itemType->nameItem) . '</b> [' . $itemType->level . ' ур.]</span><br/>
<div class="weapon_param"><img src="/style/images/user/sila.png" alt=""/> ' . ($list['str'] + $runaParams) . ' <img src="/style/images/user/zashita.png" alt=""/> ' . ($list['def'] + $runaParams) . ' <img src="/style/images/user/health.png" alt=""/> ' . ($list['hp'] + $runaParams) . ' <span style="color: green">[' . $list['level_sharpening'] . '/' . $itemType->max_sharpening . ']</span><br/>
Руна: ' . ($runaParams == 0 ? "Не установлена" : "Установлена <span class='green'>[+$runaParams]</span>") . '</div></div>';
            echo '<div style="padding-top: 10px;"></div>';
            echo "<a href='/item/rune/{$list['weapon_id']}' class='button_green_a'>Установить руну</a>";
            echo '<div style="padding-top: 3px;"></div>';
        }

        echo '<div class="menulist"><div class="line_1"></div><li><a href="/trade_shop"><img src="/style/images/body/back.png" alt=""> Торговая лавка</a></li></div>';
        break;
    case 'list':
        echo '<div class="case_shop background_shop" style="text-align: center;">';

        for ($i = 1; $i <= 6; $i++) {
            if ($user['level'] >= $getListSetItem->$i->level) {
                echo '<a href="/armors/set/' . $i . '" class="inline"><img src="/images/cachestvo/' . $i . '.png" class="bor-' . $i . '"></a>';
            } else {
                echo '<img src="/images/cachestvo/' . $i . '.png" class="bor-' . $i . ' opacity">';
            }
        }
        echo '</div>';
        break;
    case 'set':

        checkSets();

        $l = intval($_GET['sets']);
        $items = $getListSetItem->$l;

        checkLevelItem($user['level'], $items->level);

        echo '<div style="text-align: center" class="center">';
        foreach ($items->output_set as $id => $set) {
            echo '<a href="/armors/set/complect/' . $l . '/' . $id . '" class="link_for"><p class="size"><img src="' . $set['img'] . '" style="max-width:120px;height:auto;" class="bor"><br>' . $set['name'] . '</p></a>';
        }
        echo '</div>';
        echo '<div class="body_list"><div class="menulist"><div class="line_1"></div><li><a href="/armors"><img src="/style/images/body/back.png" alt=""> Магазин снаряжения</a></li></div></div>';


        break;
    case 'item':
        checkSets();

        if (empty($_GET['part']) || !in_array($_GET['part'], range(1, 4))) {
            header('Location: /armors');
            exit();
        }
        $l = intval($_GET['sets']);
        $part = intval($_GET['part']);
        $items = $getListSetItem->$l;
        checkLevelItem($user['level'], $items->level);

        if (!empty($stock)) {
            $items->gold -= floor($items->gold/100 * $stock['prosent']);
            $items->silver -= floor($items->silver/100 * $stock['prosent']);
            if (!empty($items->diamond)) {
                $items->diamond -= floor($items->diamond/100 * $stock['prosent']);
            }
        }
        echo '<div class="block center"><img src="' . $items->output_set[$part]['img'] . '" style="max-width:160px;height:auto;" class="bor"></div>';

        $dataLists = array_chunk($items->id, 8, false);
        $arr = $items->output_set[$part]['output_item'];


        $userStateItems = Maneken::getUserItems($user['id']); // список всех одетых вещей
        $itemStateUser = []; // хранит данные о веще, которая одета или пусто
        $typeItem = Item::getTypeItem($_GET['buy']); // узнаём тип вещи, которую хотим одеть

        foreach ( $userStateItems as $userState) {
            // узнаём тип вещи, которая одета
            if ($typeItem == Item::getTypeItem($userState['weapon_id'])) {
                $itemStateUser = $userState;
            }
        }

        if (isset($_GET['buy']) && array_key_exists($_GET['buy'], $arr)) {
            if ($user['battle'] != 0) {
                $_SESSION['err'] = 'Во время боя действие невозможно!';
                header('Location: /armors');
                exit();
            }

            if (Maneken::countBag($user['id']) == false) {
                $_SESSION['err'] = 'В сумке нет места!';
                header('Location: /armors');
                exit();
            }

            $currUser = Item::getPriceSetType($items->gold, $items->silver); // обычные цены

            if (isset($items->private)) {

                $currUser = Item::getPriceSetType($items->gold, $items->silver, $items->diamond); // цены на дроконье качество

                // если не надет итем бузепречного качества и закалка меньше максимальной
                if (empty($itemStateUser) || $itemStateUser['quenching_level_min'] < 75) {
                    $_SESSION['err'] = 'Требуется вещь <span style="color: #5db1ce;">Безупречное</span> и закалка 75 уровня';
                    header('Location: /armors');
                    exit();
                }

            }

            if (isset($_GET['ok'])) {

                if ($user[$currUser[1]['table']] >= $currUser[1]['price'] && $user[$currUser[2]['table']] >= $currUser[2]['price']) {

                    // обучение
                    if ($user['start'] == 3) {
                        $upd_users = $pdo->prepare("UPDATE `users` SET `start` = 4 WHERE `id` = :user_id LIMIT 1");
                        $upd_users->execute(array(':user_id' => $user['id']));
                    }

                    $ins_weapon_me = $pdo->prepare("INSERT INTO `item_user` SET `weapon_id` = :weapon_id, `user_id` = :user_id, `str` = :str, `def` = :def, `hp` = :hp, `quenching_level_min` = :quenching_level_min");
                    $ins_weapon_me->execute(array(':weapon_id' => $_GET['buy'], ':user_id' => $user['id'], ':str' => $items->str, ':def' => $items->def, ':hp' => $items->hp, 'quenching_level_min' => $items->min_quenching));

                    $upd_users = $pdo->prepare("UPDATE `users` SET `{$currUser[1]['table']}` = `{$currUser[1]['table']}` - :gold, `{$currUser[2]['table']}` = `{$currUser[2]['table']}` - :silver WHERE `id` = :id LIMIT 1");
                    $upd_users->execute(array(':gold' => $currUser[1]['price'], ':silver' => $currUser[2]['price'], ':id' => $user['id']));
                    $_SESSION['ok'] = 'Успешная покупка';
                } else {
                    $_SESSION['err'] = 'Недостаточно средств';
                }
                header('Location: /armors/set/complect/' . $l . '/' . $part);
                exit();
            }

            echo confirm('/armors/set/complect/item/ok/' . $l . '/' . $part . '/' . intval($_GET['buy']), '/armors/set/complect/' . $l . '/' . $part);
        }

        echo '<div style="text-align: center" class="">';

        foreach ($arr as $k => $v) {

            echo '<div class="block"><div class="left"><img src="/images/items/' . $k . '.png" width="50px" class="bor-1 bor-no-radius"></div>' . (empty($items->private) ? '<a href="/armors/set/complect/item/' . $l . '/' . $part . '/' . $k . '">' : null) . $v  . (empty($items->private) ? '</a>' : null) . '
 <div><span>Качество:</span> <img src="' . $items->type_img . '" width="13px"> ' . $items->type . '</div>
 <div><span style="color: #ceb591; ">Закалка</span> [' . $items->min_quenching . '/' . $items->max_quenching . ']</div>

<div class="weapon_param"><img src="/style/images/user/sila.png" alt="">' . $items->str . ' <img src="/style/images/user/zashita.png" alt="">' . $items->def . ' <img src="/style/images/user/health.png" alt="">' . $items->hp . ', <img src="/style/images/many/gold.png" alt=""><span style="color: ' . ($user['gold'] >= $items->gold ? '#e6c4ad' : '#ff0000') . ';">' . $items->gold . '</span> ' . (!empty($items->diamond) ? '<img src="/style/images/many/crystal.png" alt=""><span style="color: ' . ($user['crystal'] >= $items->diamond ? '#e6c4ad' : '#ff0000') . ';">' . $items->diamond . '</span>' : '<img src="/style/images/many/silver.png" alt=""><span style="color: ' . ($user['silver'] >= $items->silver ? '#e6c4ad' : '#ff0000') . ';">' . $items->silver . '</span>') . '</div>
<div style="clear:both;"></div></div>';

            $typeItemStore = Item::getTypeItem($k); // узнаём тип вещи, которую хотим собрать

            foreach ($userStateItems as $itemStore) {
                $typeItemStoreUs = Item::getTypeItem($itemStore['weapon_id']);

                if ($typeItemStoreUs == $typeItemStore && $items->min_quenching >= 75) {
                    echo '<a href="/armors/set/complect/item/' . $l . '/' . $part . '/' . $k . '" class="button_green_a">Создать</a>';
                }
            }

        }


        echo '</div>';


        echo '<div class="body_list"><div class="menulist"><div class="line_1"></div><li><a href="/armors/set/' . $l . '"><img src="/style/images/body/back.png" alt=""> К комплектам</a></li></div></div>';
        break;
}

echo '</div>';
require_once H . 'system/footer.php';
