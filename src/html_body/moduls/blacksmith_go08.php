<?php
require_once '../../system/system.php';
$head = 'Кузнец';
only_reg();
blacksmith_level();
echo admin();

require_once H . 'system/head.php';
include_once H . 'avenax/Item.php';
require_once H . 'avenax/Maneken.php';
echo '<img src="/style/images/location/blacksmith.jpg" class="img"/>';
#-Скидка на заточку-#
//$sel_stock = $pdo->query("SELECT * FROM `stock` WHERE `type` = 5");
//if ($sel_stock->rowCount() != 0) {
//    $stock = $sel_stock->fetch(PDO::FETCH_LAZY);
//}

#-ЗАТОЧКА-#

echo '<div class="page">';
#-Кол-во доступного снаряжения-#
$sel_count = $pdo->prepare("SELECT COUNT(*) FROM `item_user` WHERE `user_id` = :user_id AND `state` = 0");
$sel_count->execute(array(':user_id' => $user['id']));
$amount = $sel_count->fetch(PDO::FETCH_LAZY);
#-Выборка снаряжения-#
$num = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;
$page = intval($page);
if (empty($page) or $page < 0)
    $page = 1;
if ($page > $total)
    $page = $total;
$start = $page * $num - $num;
$sel_weapon_me = $pdo->prepare("SELECT * FROM `item_user` WHERE `user_id` = :user_id AND `state` = 0 ORDER BY `weapon_id` ASC LIMIT $start, $num");
$sel_weapon_me->execute(array(':user_id' => $user['id']));


#-Скидка на заточку-#
$sel_stock = $pdo->query("SELECT * FROM `stock` WHERE `type` = 5");
if ($sel_stock->rowCount() != 0) {
    $stock = $sel_stock->fetch(PDO::FETCH_LAZY);
}

$numUpdate = 1;
$levelUp = 2; // + к статам
$priceAllSharp = Item::priceZatochka();

if (!empty($_GET['set_item'])) {
    $itemUpdate = Maneken::getByUserId($user['id'], $_GET['set_item'], 0);
    $itemTypeUpdate = Maneken::itemMore($itemUpdate['weapon_id'], $itemUpdate['runa']);


    $err = false;

    if ($itemUpdate['level_sharpening'] >= $itemTypeUpdate->max_sharpening) {
        $err = 'Максимальный уровень заточки';
    }

    if ($user['battle'] != 0) {
        $err = 'Во время боя действие невозможно!';
    }

    $many = $priceAllSharp[$itemUpdate['level_sharpening'] + 1];

    // скидка
    if ($sel_stock->rowCount() != 0) {
        $many['price'] = round(($many['price'] - (($many['price'] * $stock['prosent']) / 100)));
    }

    if ($user[$many['table']] < $many['price']) {
        $err = 'Недостаточно ресурсов!';
    }

    if ($err == false) {

        // обновляем игрока
        $upd_users = $pdo->prepare("UPDATE `users` SET `" . $many['table'] . "` = `" . $many['table'] . "` - :crystal, `s_sila` = `s_sila` + :up, `s_zashita` = `s_zashita` + :up, `s_health` = `s_health` + :up WHERE `id` = :id LIMIT 1");
        $upd_users->execute(array(':crystal' => $many['price'], ':id' => $user['id'], ':up' => $levelUp));
        #-Записываем параметры-#
        $upd_weapon_me = $pdo->prepare("UPDATE `item_user` SET `str` = `str` + :up, `def` = `def` + :up, `hp` = `hp` + :up, `level_sharpening` = `level_sharpening` + 1 WHERE `id` = :id LIMIT 1");
        $upd_weapon_me->execute(array(':up' => $levelUp, ':id' => $itemUpdate['id']));
        $_SESSION['ok'] = 'Успешная заточка!';
    } else {
        $_SESSION['err'] = $err;
    }
    header('Location: /blacksmith');
    exit();
} else if (!empty($_GET['make_out'])) {
    $itemMake = Maneken::getByUserId($user['id'], $_GET['make_out'], 0);
    $itemTypeMake = Maneken::itemMore($itemMake['weapon_id'], $itemMake['runa']); // вещь, которую разбераем


    $itemUserId = Maneken::getUserState($user['id'], $_GET['make_out']); // id вещь, которая одета
    $itemUser = Maneken::getByUserId($user['id'], $itemUserId, 1, 'На Вас не одета вещь!');
    $itemUserMake = Maneken::itemMore($itemUserId, $itemUser['runa']); // вещь на которую будем переносить

    if (!empty($itemTypeMake)) {

        $err = false;
        if ($itemTypeMake->level > $itemUserMake->level) {
            $err = 'На Вас одета вещь, хуже по качеству!';
        }

        if ($itemUser['level_sharpening'] >= $itemUserMake->max_sharpening) {
            $err = 'Вещь, которая одета на Вас, достигла максимального уровня заточки!';
        }

        if (empty($err)) {
            $make = $levelUp * $itemMake['level_sharpening']; // + к статам
            $level_sharpening = $itemUser['level_sharpening'] + $itemMake['level_sharpening']; // уровень заточки

            // если сумма заточек двух вещей, больше чем максимум, присваеваем максимум
            if ($level_sharpening > $itemUserMake->max_sharpening) {
                $level_sharpening = $itemUserMake->max_sharpening;
            }

            $runa = 0; // ID руны по дефолту
            $paramsRune = 0; // параметры руны по дефолту

            // если в итеме, который одет, стоит руна, то записываем параметры
            if (!empty($itemUserMake->runa)) {
                $runa = $itemUser['runa']; // ID руны
//                $paramsRune = $itemUserMake->runa->params; // параметры руны
            }

            // если в итеме, который разбераем, стоит руна, то переносим
            if (!empty($itemTypeMake->runa)) {
                $runa = $itemMake['runa']; // ID руны
//                $paramsRune = $itemTypeMake->runa->params; // параметры руны
            }


            $upd_weapon_me = $pdo->prepare("UPDATE `item_user` SET `runa` = :runa, `str` = `str` + :make, `def` = `def` + :make, `hp` = `hp` + :make, `level_sharpening` = :level_sharpening WHERE `weapon_id` = :weapon_id AND `state` = 1 AND `id` = :id LIMIT 1");
            $upd_weapon_me->execute(array(':runa' => $runa, ':make' => $make, ':level_sharpening' => $level_sharpening, ':weapon_id' => $itemUserId, ':id' => $itemUser['id']));

            $upd_users2 = $pdo->prepare("UPDATE `users` SET `s_sila` = `s_sila` + :s_sila, `s_zashita` = `s_zashita` + :s_sila, `s_health` = `s_health` + :s_sila WHERE `id` = :id LIMIT 1");
            $upd_users2->execute(array(':s_sila' => $make, ':id' => $user['id']));

            $del_weapon_ru = $pdo->prepare("DELETE FROM `item_user` WHERE `user_id` = :id_user AND `id` = :id");
            $del_weapon_ru->execute(array(':id_user' => $user['id'], ':id' => $itemMake['id']));
            $_SESSION['ok'] = 'Вы успешно перенесли руну!';
        } else {
            $_SESSION['err'] = $err;

        }
        header('Location: /blacksmith');
        exit();
    }
}


#-Если есть такое оружие-#
if ($sel_weapon_me->rowCount() != 0) {
    while ($weapon_me = $sel_weapon_me->fetch(PDO::FETCH_LAZY)) {
        $itemType = Maneken::itemMore($weapon_me->weapon_id, $weapon_me->runa);

        #-Вывод снаряжения-#
        $runaParams = !empty($itemType->runa) ? $itemType->runa->params : 0;
        echo '<div class="img_weapon"><img width="48px" src="/images/items/' . $weapon_me->weapon_id . '.png" class=""  alt=""/></div>
<div class="weapon_setting"><span style="color: ' . color_w_new($itemType->levelSet) . ';"><img src="' . $itemType->type_img . '" alt=""/> <b>' . htmlspecialchars($itemType->nameItem) . '</b> [' . $itemType->level . ' ур.]</span><br/>
<div class="weapon_param"><img src="/style/images/user/sila.png" alt=""/> ' . ($weapon_me->str + $runaParams) . ' <img src="/style/images/user/zashita.png" alt=""/> ' . ($weapon_me->def + $runaParams) . ' <img src="/style/images/user/health.png" alt=""/> ' . ($weapon_me->hp + $runaParams) . ' <span style="color: green">[' . $weapon_me->level_sharpening . '/' . $itemType->max_sharpening . ']</span><br/>
Руна: ' . ($runaParams == 0 ? "Не установлена" : "Установлена <span class='green'>[+$runaParams]</span>") . '</div></div>';
        echo '<div style="padding-top: 10px;"></div>';


        #-ЗАТОЧКА ОРУЖИЯ-#
        if ($weapon_me->level_sharpening < $itemType->max_sharpening) {
            #-Цена за заточку-#
            $many = $priceAllSharp[$weapon_me->level_sharpening + 1];
            // скидка
            if ($sel_stock->rowCount() != 0) {
                $many['price'] = round(($many['price'] - (($many['price'] * $stock['prosent']) / 100)));
            }
            echo "<a href='/blacksmith/update/{$weapon_me->weapon_id}' class='button_green_a'>Заточить +{$numUpdate} за <img src='/style/images/many/{$many['table']}.png' alt=''/> {$many['price']}</a>";
        } else {
            echo "<div class='button_red_a'>Максимальная заточка</div>";
        }

        if($weapon_me->level_sharpening == true) {
            echo "<a href='/blacksmith/make_out/{$weapon_me->weapon_id}' class='button_green_a'>Перенести заточку</a>";
        }
        echo '<div style="padding-top: 3px;"></div>';
    }
} else {
    echo '<div class="line_1_m"></div>';
    echo '<div class="body_list">';
    echo '<div class="error_list">';
    echo '<img src="/style/images/body/error.png" alt=""/> Нет доступного снаряжения!';
    echo '</div>';
    echo '</div>';
}
echo '</div>';
echo '<div class="line_1"></div>';


#-Отображение постраничной навигации-#
if ($posts > $num) {
    $action = '';
    echo '<div class="line_1"></div>';
    echo '<div class="body_list">';
    pages($posts, $total, $action);
    echo '</div>';
}

require_once H . 'system/footer.php';
