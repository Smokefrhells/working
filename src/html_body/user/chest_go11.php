<?php
require_once '../../system/system.php';
$head = 'Сундуки';
only_reg();
require_once H . 'system/head.php';
echo '<div class="page">';

if (isset($_GET['type'])) {
    $type_c = $_GET['type'];
} else {
    $type_c = 1;
}

#-Считаем все или по типу сундука-#
if ($_GET['type'] == 1 or $_GET['type'] > 5 or !isset($_GET['type'])) {
    $sel_c_all = $pdo->prepare("SELECT COUNT(*) FROM `chest` WHERE `user_id` = :user_id");
    $sel_c_all->execute(array(':user_id' => $user['id']));
    $amount_all = $sel_c_all->fetch(PDO::FETCH_LAZY);
} else {
    $type = $_GET['type'] - 1;
    $sel_c_all = $pdo->prepare("SELECT COUNT(*) FROM `chest` WHERE `user_id` = :user_id AND `type` = :type");
    $sel_c_all->execute(array(':user_id' => $user['id'], ':type' => $type));
    $amount_all = $sel_c_all->fetch(PDO::FETCH_LAZY);
}

#-Меню сортировка-#
$all = '<a href="/chest?type=1" style="text-decoration:none;"><span class="body_sel">' . (($_GET['type'] == 1 or $_GET['type'] > 4 or !isset($_GET['type'])) ? "<b>Все $amount_all[0]</b>" : "Все") . '</span></a>';
$ordinary = '<a href="/chest?type=2" style="text-decoration:none;"><span class="body_sel">' . ($_GET['type'] == 2 ? "<b>Обычный $amount_all[0]</b>" : "Обычный") . '</span></a>';
$ancient = '<a href="/chest?type=3" style="text-decoration:none;"><span class="body_sel">' . ($_GET['type'] == 3 ? "<b>Древний $amount_all[0]</b>" : "Древний") . '</span></a>';
$gold = '<a href="/chest?type=4" style="text-decoration:none;"><span class="body_sel">' . ($_GET['type'] == 4 ? "<b>Золотой $amount_all[0]</b>" : "Золотой") . '</span></a>';
$red = '<a href="/chest?type=5" style="text-decoration:none;"><span class="body_sel">' . ($_GET['type'] == 5 ? "<b>Редкие $amount_all[0]</b>" : "Редкие") . '</span></a>';

#-Вывод меню-#
echo '<div class="body_list">';
echo '<div style="padding: 5px;">';
echo '' . $all . ' ' . $ordinary . ' ' . $ancient . ' ' . $gold . '' . $red . '';
echo '</div>';
echo '<div class="line_1"></div>';
echo '</div>';

#-Вывод сундуков-#
$num = 10;
$page = $_GET['page'];
$posts = $amount_all[0];
$total = intval(($posts - 1) / $num) + 1;
$page = intval($page);
if (empty($page) or $page < 0)
    $page = 1;
if ($page > $total)
    $page = $total;
$start = $page * $num - $num;

if ($_GET['type'] == 1 or !isset($_GET['type']) or $_GET['type'] != 2) {
    $sel_chest = $pdo->prepare("SELECT * FROM `chest` WHERE `user_id` = :user_id ORDER BY `time` ASC LIMIT $start, $num");
    $sel_chest->execute(array(':user_id' => $user['id']));
}
if ($_GET['type'] == 2 or $_GET['type'] == 3 or $_GET['type'] == 4 or $_GET['type'] == 5) {
    $type = $_GET['type'] - 1;
    $sel_chest = $pdo->prepare("SELECT * FROM `chest` WHERE `user_id` = :user_id AND `type` = :type ORDER BY `time` ASC LIMIT $start, $num");
    $sel_chest->execute(array(':user_id' => $user['id'], ':type' => $type));
}
#-Если есть записи-#
if ($sel_chest->rowCount() != 0) {
    while ($chest = $sel_chest->fetch(PDO::FETCH_LAZY)) {
#-Обычный сундук-#
        if ($chest['type'] == 1) {
            $name = 'Обычный сундук';
            $color = '#bfbfbf';
            $style_kashestvo_star = '/style/images/quality/1.png';
            $ramka = 'weapon_1';
            $images = '/style/images/user/chest_1.jpg';
            $info = 'Получите серебро и опыт';
        }
#-Древний сундук-#
        if ($chest['type'] == 2) {
            $name = 'Древний сундук';
            $color = '#18b40c';
            $style_kashestvo_star = '/style/images/quality/2.png';
            $ramka = 'weapon_2';
            $images = '/style/images/user/chest_2.jpg';
            $info = 'Получите одну вещь из Торгового рынка';
        }
#-Золотой сундук-#
        if ($chest['type'] == 3) {
            $name = 'Золотой сундук';
            $color = '#e2b70b';
            $style_kashestvo_star = '/style/images/quality/3.png';
            $ramka = 'weapon_3';
            $images = '/style/images/user/chest_3.jpg';
            $info = 'Получите золото или одну из скрытых вещей';
        }
#-Сундук Ванху-#
        if ($chest['type'] == 4) {
            $name = 'Сундук Ванху';
            $color = '#e2b70b';
            $style_kashestvo_star = '/style/images/quality/3.png';
            $ramka = 'weapon_3';
            $images = 'http://piratgo.ru/img/shop/art/sunduk4.jpg';
            $info = 'Получите от 550 до 20000 Золота! Или Редкую вещь!';
        }
        $chest_ostatok = ($chest['time'] + 432000) - time();
        echo '<div style="min-height: 60px;">';
        echo '<div class="img_weapon"><img src="' . $images . '" class="' . $ramka . '"  alt=""></div>';
        echo '<div class="weapon_setting">';
        echo '<span style="color: ' . $color . ';"><img src="' . $style_kashestvo_star . '" alt=""/><b>' . $name . '</b> </span><br/><div style="font-size: 13px;"><span class="white">' . $info . '</span><br/><img src="/style/images/body/time.png" alt=""/><span class="red">' . timer($chest_ostatok) . '</span></div>';
        echo '</div>';
        echo '</div>';
        if ($user['key'] > 0) {
            echo "<a href='/chest_act?act=open&id=$chest[id]&type=key&type_c=$type_c' class='button_green_a'>Открыть</a>";
        } else {
            echo "<a href='/chest_act?act=open&id=$chest[id]&type=gold&type_c=$type_c' class='button_green_a'>Открыть за <img src='/style/images/many/gold.png' alt=''/>" . ($chest['type'] * 10) . "</a>";
        }
        echo '<div style="padding-top: 5px;"></div>';
    }
} else {
    echo '<div class="body_list">';
    echo '<div class="error_list">';
    echo '<img src="/style/images/body/error.png" alt=""/>Нет сундуков!';
    echo '</div>';
    echo '</div>';
}
echo '</div>';
#-Отображение постраничной навигации-#
if ($posts > $num) {
    $action = "&type=$type_c";
    echo '<div class="body_list">';
    echo '<div class="line_1"></div>';
    pages($posts, $total, $action);
}
echo '</div>';
echo '</div>';
echo '<div class="line_1"></div>';
echo '<div class="body_list">';
echo '<div class="info_list">';
echo "<span class='gray'>Ключей у вас: <img src='/style/images/body/key.png' alt=''/>$user[key]</span>";
echo '</div>';
echo '<div class="line_1"></div>';
echo '<div class="info_list">';
echo '<img src="/style/images/body/imp.png" alt=""/>Срок хранения сундуков 5 дней';
echo '</div>';
echo '</div>';
require_once H . 'system/footer.php';
?>