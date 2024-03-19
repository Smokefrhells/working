<?php
require_once '../system/system.php';
$head = 'Экипировка';
only_reg();
require_once H . 'system/head.php';
require_once H . 'avenax/Art.php';

if (empty($_GET['id'])) {
    header('Location: /artefact/' . $user['id'] . '/?type=1');
    exit();
}

$artClass = new Art($user);
$item = $artClass->getItemById($_GET['id']);


echo '<div class="page">';

// если пустой слот
if (empty($item['weapon_id']) && $item['user_id'] == $user['id']) {
    // надеть
    if (!empty($_GET['use'])) {
        $artClass->useItem($_GET['id'], $_GET['use']);
        header('Location: /artefact/' . $user['id'] . '/');
        exit();
    }

    foreach ($artClass->getNotState() as $itemNotState) {
        $weapon = $itemNotState;
        #-Высчитываем-#
        $weapon_p = $weapon['sila'] + $weapon['zashita'] + $weapon['health'];
        if ($weapon_p > $nadeto_p) {
            $param = $weapon_p - $nadeto_p;
            $w_param = "<span class='green'>[+$param]</span>";
        }
        if ($weapon_p < $nadeto_p) {
            $param = $nadeto_p - $weapon_p;
            $w_param = "<span class='red'>[-$param]</span>";
        }
        if ($weapon_p == $nadeto_p) {
            $w_param = "";
        }
        echo '<div class="img_weapon"><img src="' . $weapon['images'] . '" class="' . ramka($weapon['level']) . '"  alt=""/></div><div class="weapon_setting"><span style="color: ' . color_w($weapon['level']) . ';"><img src="' . star($weapon['level']) . '" alt=""/><b>' . $weapon['name'] . '</b> [' . $weapon['level'] . ' ур.]</span><br/><div class="weapon_param"><img src="/style/images/user/sila.png" alt=""/>' . ($weapon['sila']) . ' <img src="/style/images/user/zashita.png" alt=""/>' . ($weapon['zashita']) . ' <img src="/style/images/user/health.png" alt=""/>' . ($weapon['health']) . ' ' . $w_param . '</div></div>';
        echo '<div style="padding-top: 20px;"></div>';
        #-Уровень больше или равен нашему-#
        if ($user['level'] >= $weapon['level']) {
            echo "<a href='/art/$item[id]/use/$itemNotState[id]' class='button_green_a'>Надеть " . ($user['start'] == 4 ? "<img src='/style/images/body/left_mini.gif' alt=''/>" : "") . "</a>";
            echo '<div style="padding-top: 5px;"></div>';
        }
    }
} else {

    if (isset($_GET['take']) && $item['user_id'] == $user['id']) {
        $artClass->takeItem($_GET['id'], $_GET['take']);
        header('Location: /artefact/' . $user['id'] . '/');
        exit();
    }

    $weapon = $item;
    #-Высчитываем-#
    $weapon_p = $weapon['sila'] + $weapon['zashita'] + $weapon['health'];

    echo '<div class="img_weapon"><img src="' . $weapon['images'] . '" class="' . ramka($weapon['level']) . '"  alt=""/></div>
<div class="weapon_setting"><span style="color: ' . color_w($weapon['level']) . ';"><img src="' . star($weapon['level']) . '" alt=""/><b>' . $weapon['name'] . '</b> [' . $weapon['level'] . ' ур.]</span><br/>
<div class="weapon_param"><img src="/style/images/user/sila.png" alt=""/>' . ($weapon['sila']) . ' <img src="/style/images/user/zashita.png" alt=""/>' . ($weapon['zashita']) . ' <img src="/style/images/user/health.png" alt=""/>' . ($weapon['health']) . ' </div></div>';
    echo '<div style="padding-top: 20px;"></div>';
    if ($item['user_id'] == $user['id']) {
        echo "<a href='/art/$item[id]/take/$weapon[weapon_id]' class='button_green_a'>Снять</a>";
    }
    echo '<div style="padding-top: 5px;"></div>';

}
//
//    $sel_weapon_me = $pdo->prepare("SELECT * FROM `weapon_me` WHERE `type` = :type AND `user_id` = :user_id AND `state` = 1 ORDER BY `state` DESC");
//$sel_weapon_me->execute(array(':type' => $type, ':user_id' => $all['id']));
echo '</div>';
require_once H . 'system/footer.php';