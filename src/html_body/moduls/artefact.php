<?php
require_once '../../system/system.php';
$head = 'Активные артефакты';
only_reg();
armor_campaign();
require_once H . 'system/head.php';
echo '<link rel="stylesheet" href="/default_x10.css" type="text/css" />';
require_once H . 'avenax/Art.php';
#-Выбор игрока-#
if (!empty($_GET['id'])) {
    $id = check($_GET['id']);
    $sel_users = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :id");
    $sel_users->execute([':id' => $id]);
    if ($sel_users->rowCount() != 0) {
        $all = $sel_users->fetch(PDO::FETCH_LAZY);
    } else {
        header('Location: /');
        exit();
    }
}

#-АМУЛЕТ-#
$sel_weapon_me_am = $pdo->prepare("SELECT * FROM `weapon_me` WHERE `user_id` = :user_id AND `state` = :state AND `type` = :type");
$sel_weapon_me_am->execute([':user_id' => $all['id'], ':state' => 1, ':type' => 7]);
if ($sel_weapon_me_am->rowCount() != 0) {
    $weapon_me_am = $sel_weapon_me_am->fetch(PDO::FETCH_LAZY);
    $sel_weapon_am = $pdo->prepare("SELECT * FROM `weapon` WHERE `id` = :id");
    $sel_weapon_am->execute([':id' => $weapon_me_am['weapon_id']]);
    $weapon_am = $sel_weapon_am->fetch(PDO::FETCH_LAZY);
}
#-КОЛЬЦО-#
$sel_weapon_me_r = $pdo->prepare("SELECT * FROM `weapon_me` WHERE `user_id` = :user_id AND `state` = :state AND `type` = :type");
$sel_weapon_me_r->execute([':user_id' => $all['id'], ':state' => 1, ':type' => 8]);
if ($sel_weapon_me_r->rowCount() != 0) {
    $weapon_me_r = $sel_weapon_me_r->fetch(PDO::FETCH_LAZY);
    $sel_weapon_r = $pdo->prepare("SELECT * FROM `weapon` WHERE `id` = :id");
    $sel_weapon_r->execute([':id' => $weapon_me_r['weapon_id']]);
    $weapon_r = $sel_weapon_r->fetch(PDO::FETCH_LAZY);
}


//$getArt = $pdo->prepare("SELECT `wm`.*, `w`.`images`, `w`.`level`, `w`.`name`, `w`.`sila`, `w`.`health`, `w`.`sila` FROM `weapon_me` as wm
// LEFT JOIN `weapon` as w
// ON `w`.`id` = `wm`.`weapon_id` WHERE `wm`.`user_id` = :user_id AND `wm`.`type` = :type AND `wm`.`weapon_id` IN (171, 172, 173) LIMIT 3");
//$getArt->execute([':user_id' => $all['id'], ':type' => 9]);
//$getListArt = $getArt->fetchAll(PDO::FETCH_ASSOC);
//

$artClass = new Art($all);


echo '<div class="razd">Амулеты и кольца (<a href="/artefact?go=faq_bog">?</a>):</div>';
#-Амулет-#
if ($weapon_me_am['state'] == true) {
    echo '<div class="post">
<table><tbody><tr><td style="width:1%;vertical-align:top;">
<img src="' . $weapon_am['images'] . '" width="48px" height="48px" class="' . ramka($weapon_am['level']) . '" alt=""/></td>
<td style="vertical-align:top;"> <a href="/weapon_me/' . $all['id'] . '/?type=7"> ' . $weapon_am['name'] . '</a>
<br/>Ат: <span class="green">' . ($weapon_am['sila'] + $weapon_me['b_sila'] + $weapon_me['runa']) . '</span> |
 ХП: <span class="green">' . ($weapon_am['health'] + $weapon_me['b_health'] + $weapon_me['runa']) . '</span> 
 Защ: <span class="green">' . ($weapon_am['sila'] + $weapon_me['b_sila'] + $weapon_me['runa']) . '</span></td> </tr></tbody></table></div>';
} else {
    echo "<div class='post'><table><tbody><tr><td style='width:1%;vertical-align:top;'><img src='/style/images/weapon/amulet/0.png' width='48px' height='48px' class='weapon_0' alt=''/></td>
        <td style='vertical-align:top;'> <a href='/weapon_me/" . $all['id'] . "/?type=7'>Пусто</a></tr></tbody></table></div>";
}
#-Кольцо-#
if ($weapon_me_r['state'] == '1') {
    echo '<div class="post">
<table><tbody><tr><td style="width:1%;vertical-align:top;">
<img src="' . $weapon_r['images'] . '" width="48px" height="48px" class"' . ramka($weapon_r['level']) . '" alt=""/></td><td style="vertical-align:top;"> <a href="/weapon_me/' . $all['id'] . '/?type=8"> ' . $weapon_r['name'] . '</a><br/>Ат: <span class="green">' . ($weapon_r['sila'] + $weapon_me['b_sila'] + $weapon_me['runa']) . '</span> | ХП: <span class="green">' . ($weapon_r['health'] + $weapon_me['b_health'] + $weapon_me['runa']) . '</span> Защ: <span class="green">' . ($weapon_r['sila'] + $weapon_me['b_sila'] + $weapon_me['runa']) . '</span></td></tr></tbody></table></div>';
} else {

    echo "<div class='post'><table><tbody><tr><td style='width:1%;vertical-align:top;'><img src='/style/images/weapon/amulet/0.png' width='48px' height='48px' class='weapon_0' alt=''/></td>
        <td style='vertical-align:top;'> <a href='/weapon_me/" . $all['id'] . "/?type=8'>Пусто</a></tr></tbody></table></div>";
}

echo '<div class="razd">Редкие артефакты (<a href="/artefact?go=faq_bog">?</a>):</div>';

// Редкие артефакты
foreach ($artClass->getListArt() as $art) {
    if (!empty($art['weapon_id'])) {
        echo '<div class="post"><table><tbody><tr><td style="width:1%;vertical-align:top;">
            <img src="' . $art['images'] . '"width="48px" height="48px" class="' . ramka($art['level']) . '" alt=""/></td>
            <td style="vertical-align:top;"> <a href="/art/' . $art['id'] . '"> ' . $art['name'] . '</a><br/>
            Ат: <span class="green">' . ($art['sila']) . '</span> |
             ХП: <span class="green">' . ($art['health']) . '</span> 
             Защ: <span class="green">' . ($art['sila']) . '</span></td> </tr></tbody></table></div>';
    } else {
        echo "<div class='post'><table><tbody><tr><td style='width:1%;vertical-align:top;'><img src='/style/images/weapon/amulet/0.png' width='48px' height='48px' class='weapon_0' alt=''/></td>
        <td style='vertical-align:top;'> " . ($all['id'] == $user['id'] ? '<a href="/art/' . $art['id'] . '">' : null) . "Пусто" . ($all['id'] == $user['id'] ? '</a>' : null) . "</tr></tbody></table></div>";
    }
}

if ($type == 8 or $type == 9) {
    echo '<div class="body_list">';
    echo '<div class="menulist">';
    if ($user['id'] != $all['id']) {
        echo '<div class="line_1"></div>';
        echo "<li><a href='/hero/$all[id]'><img src='/style/images/user/user.png' alt=''/> $all[nick]</a></li>";
    }

    if ($type == 8) {
        $t = $type - 1;
        echo '<div class="line_1"></div>';
        // echo "<li><a href='/weapon_me/$all[id]/?type=$t'><img src='/style/images/body/back.png' alt=''/> Предыдущее снаряжение</a></li>";
    }
    if ($type == 9) {
        $t = $type - 1;
        echo '<div class="line_1"></div>';
        // echo "<li><a href='/weapon_me/$all[id]/?type=$t'><img src='/style/images/body/back.png' alt=''/> Предыдущее снаряжение</a></li>";
    }
    echo '</div>';
    echo '</div>';
}
echo '</div>';

#-Отображение постраничной навигации-#
if ($posts > $num) {
    $action = '';
    echo '<div class="line_1"></div>';
    pages($posts, $total, $action);
}
echo '</div>';
require_once H . 'system/footer.php';