<?php
require_once '../system/system.php';
$head = 'Выдать вещь';
only_reg();
admin();
require_once H . 'system/head.php';
require_once H . 'avenax/.settings.php';
require_once H . 'avenax/Item.php';
//require_once H . 'avenax/Maneken.php';
echo '<div class="page">';
if (!empty($_GET['userID'])) {
    $checkUser = $pdo->prepare("SELECT `id`, `nick` FROM `users` WHERE `id` = ?");
    $checkUser->execute([$_GET['userID']]);

    $row = $checkUser->fetch(PDO::FETCH_ASSOC);

    if (empty($row)) {

        $_SESSION['err'] = 'Такого игрока не существует!';
        header('Location: /admin_item');
        exit();
    } else {
        $type = 7;
        $itemInfo = Item::Items($type); // инфо о вещи
        echo '<div class="user_info">';
        echo '<span>Игрок: <a href="/hero/' . $row['id'] . '">' . htmlspecialchars($row['nick']) . '</a></span>';
        echo '</div>';

        if (!empty($_GET['set'])) {
            if (isset($_GET['ok'])) {
                $ins_weapon_me = $pdo->prepare("INSERT INTO `item_user` SET `weapon_id` = :weapon_id, `user_id` = :user_id, `str` = :str, `def` = :def, `hp` = :hp, `quenching_level_min` = :quenching_level_min");
                $ins_weapon_me->execute(array(':weapon_id' => $_GET['set'], ':user_id' => $row['id'], ':str' => $itemInfo->str, ':def' => $itemInfo->def, ':hp' => $itemInfo->hp, 'quenching_level_min' => $itemInfo->min_quenching));
                header('Location: /admin_item?userID=' . $row['id']);
                exit();
            }
            echo confirm('/admin_item?userID=' . $row['id'] . '&set=' . intval($_GET['set']) . '&ok', '/admin_item?userID=' . $row['id']);
        }

        echo '<div style="text-align: center" class="center">';
        foreach ($itemInfo->output_set as $set) {
            foreach ($set['output_item'] as $id => $item) {

                echo '<div class="img_weapon"><img src="/images/items/' . $id . '.png" class=""  alt="" width="48" height="48"></div><div class="weapon_setting">

<div><img src="' . $itemInfo->type_img . '" width="13px"> <span style="color: ' . color_w_new($type) . '"><b><a href="/admin_item?userID=' . $row['id'] . '&set=' . $id . '" class="">' . $item . '</a></b> [' . $itemInfo->level . ' ур.]</span></div>

<div class="weapon_param"><img src="/style/images/user/sila.png" alt=""/> ' . ($itemInfo->str) . ' <img src="/style/images/user/zashita.png" alt=""/> ' . ($itemInfo->def) . ' <img src="/style/images/user/health.png" alt=""/> ' . ($itemInfo->hp) . ' <span style="color: green">[' . $itemInfo->max_sharpening . ']</span></div></div>';
                echo '<div style="padding-top: 25px;"></div>';
            }


        }

        echo '</div>';
    }




} else {

    echo '<form method="get" action=""><input class="input_form" type="number" name="userID" placeholder="ID игрока"/><br/>';
    echo '<input class="button_i_mini" name="" type="submit" value="Поиск"></form>';
}

echo '</div>';



require_once H . 'system/footer.php';