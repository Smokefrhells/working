<?php
require_once '../../system/system.php';
only_reg();
ob_start();
require_once H . 'avenax/Item.php';
require_once H . 'avenax/Maneken.php';
require_once H . 'avenax/.settings.php';

if (empty($_GET['id'])) {
    header('Location: /');
    exit();
}
$weapon_id = intval($_GET['id']);

$item = Maneken::getByUserId($user['id'], $weapon_id);
$itemType = Maneken::itemMore($item['weapon_id'], $item['runa']);

$head = $itemType->nameItem;

require_once H . 'system/head.php';
$sel_stock = $pdo->query("SELECT * FROM `stock` WHERE `type` = 31");
$stock = $sel_stock->fetch(PDO::FETCH_ASSOC);

// снять итем
if (filter_has_var(INPUT_GET, 'take')) {

    if (Maneken::countBag($user['id']) == false) {
        $_SESSION['err'] = 'В сумке нет места!';
        header('Location: /item/' . $item['id']);
        exit();
    }

    $upd_weapon_me = $pdo->prepare("UPDATE `item_user` SET `state` = :state WHERE `id` = :id LIMIT 1");
    $upd_weapon_me->execute(array(':state' => 0, ':id' => $item['id']));

    $upd_users2 = $pdo->prepare("UPDATE `users` SET `s_sila` = `s_sila` - :str, `s_zashita` = `s_zashita` - :def, `s_health` = `s_health` - :hp WHERE `id` = :id LIMIT 1");
    $upd_users2->execute(array(':str' => $item['str'], ':def' => $item['def'], ':hp' => $item['hp'], ':id' => $user['id']));
    header('Location: /bag');
    exit();
}

$progressItem = round($item['quenching_rating'] / ($itemType->quenching_rating / 100));
if ($progressItem > 100) {
    $progressItem = 100;
}

?>
    <div class="page">
        <div class="img_weapon"><img src="/images/items/<?= $weapon_id; ?>.png" class="weapon_0" width="48px" alt="">
        </div>
        <div class="weapon_height"><span
                    style="color: <?= color_w_new($itemType->levelSet); ?>"><b><?= htmlspecialchars($itemType->nameItem); ?></b> [<?= $item['level_sharpening']; ?>/<?= $itemType->max_sharpening; ?>]</span>

            <div>
                 <span style="color: <?= color_w_new($itemType->levelSet); ?>">Закалка: [<?= $item['quenching_level_min']; ?>/<?= $itemType->max_quenching; ?>]</span><br>

            </div>
        </div>
    </div>

    <div style="background-image: url(/style/images/body/notif.jpg);padding:3px 2px 4px 2px">
        <div><span style="color: #c2965c;font-size: small">Прогресс: <?= $item['quenching_rating']; ?> из <?= $itemType->quenching_rating; ?></span></div>
        <div style="height:4px;background:url(/images/bar_gray.png) left center;width:75%;">
            <div style="transition-property:width,background;transition-duration:0.5s;display:inline-block;height:4px;background:url(/images/bar_green.png) left top;width:<?=$progressItem; ?>%;float:left"></div></div></div>



    <div class="user_info">
        <div class="weapon_param"><img src="/style/images/user/sila.png" alt=""> <?= $item['str']; ?> <img
                    src="/style/images/user/zashita.png" alt=""> <?= $item['def']; ?> <img
                    src="/style/images/user/health.png" alt="">
            <?= $item['hp']; ?>
            <br>Руна: <?= (!empty($itemType->runa) ? '<span style="color: #' . $itemType->runa->color . '">' . $itemType->runa->name . ' <img src="' . $itemType->runa->img . '" alt="" width="18"> +' . $itemType->runa->params . '</span>' : 'Не установлена'); ?>
        </div>
        <br>
        <a href='/item/take/<?= $weapon_id; ?>' class='button_green_a'>Снять</a>
    </div>




<?php
if (isset($_GET['rune'])) {
    if (!empty($_GET['id_rune'])) {
        $idRune = $_GET['id_rune'];
        $runeInfo = Item::Runes($idRune);

        if (!empty($runeInfo)) {
            if (isset($_GET['ok'])) {
                $runeInfo->price -= floor($runeInfo->price/100 * $stock['prosent']);
                if ($user['gold'] >= $runeInfo->price) {
                    $upd_users = $pdo->prepare("UPDATE `users` SET `s_sila` = `s_sila` + :up, `s_zashita` = `s_zashita` + :up, `s_health` = `s_health` + :up, `gold` = `gold` - :gold WHERE `id` = :id LIMIT 1");
                    $upd_users->execute(array(':gold' => $runeInfo->price, ':id' => $user['id'], ':up' => $runeInfo->params));

                    $upd_weapon_me = $pdo->prepare("UPDATE `item_user` SET `str` = `str` + :up, `def` = `def` + :up, `hp` = `hp` + :up, `runa` = :id_rune WHERE `id` = :id LIMIT 1");
                    $upd_weapon_me->execute(array(':up' => $runeInfo->params, ':id_rune' => $idRune, ':id' => $item['id']));
                    $_SESSION['ok'] = 'Успешная покупка';
                } else {
                    $_SESSION['err'] = 'Недостаточно средств';
                }
                header('Location: /item/rune/' . $weapon_id);
                exit();
            }
            echo '<div class="block">';
            echo confirm('/item/rune/' . $weapon_id . '/' . $idRune . '/ok', '/item/rune/' . $weapon_id);
            echo '</div>';
        }
    }

    $lastRune = 0;
    ?>

    <?php foreach (Item::Runes() as $k => $rune): ?>
        <?php if(!empty($stock)): ?>
            <?php $rune->price -= floor($rune->price/100 * $stock['prosent']); ?>
        <?php endif; ?>


        <?php if ($item['runa'] < $k): ?>
            <div class="block">
                <div class="left"><img src="<?= $rune->img; ?>" width="40px"></div>
                <span style="color: #<?= $rune->color; ?>"><?= $rune->name; ?></span><br><?= $rune->params; ?> к
                параметрам<br>
                <div style="padding-top: 10px;"></div>
                <a href='/item/rune/<?= $weapon_id; ?>/<?= $k; ?>' class='button_green_a'>Установить за <img
                            src="/style/images/many/gold.png" alt=""> <?= $rune->price; ?></a>
                <div style="clear:both;"></div>
            </div>
        <?php else: ?>
            <?php $lastRune = true; ?>
        <?php endif; ?>
    <?php endforeach; ?>

    <?php if ($lastRune): ?>
        <div class="error_list"><img src="/style/images/body/error.png" alt=""> Установлена максимальная руна!</div>
    <?php endif; ?>
    <?php
}
require_once H . 'system/footer.php';
