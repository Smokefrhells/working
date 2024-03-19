<?php
/**
 * Created by PhpStorm.
 * User: A.Gorbunov
 * Date: 28.10.2019
 * Time: 15:10
 */

require_once '../system/system.php';
require_once 'Events.php';
$events = Events::setEvents();
$head = 'Магазин ' . $events['name'];
only_reg();
require_once H . 'system/head.php';
echo '<div class="page">';

if (!empty($_GET['act']) && in_array($_GET['act'], ['item', 'gold'])) {
    $needUser = Events::setShop();

    if ($user['eventItem'] >= $needUser[$_GET['act']]['need']) {
        Events::getConvert($_GET['act'], $user['id']);
    } else {
        $soNeed = $needUser[$_GET['act']]['need'] - $user['eventItem'];
        $_SESSION['err'] = "Вам не хватает <img width='16' src='{$events['img']}'> {$soNeed}";
        header('Location: /shopEvent');
        exit();
    }
}
?>
    <div class="body_list">
        <div class="menulist">
            <div class="line_1"></div>
            <div class="center"><span>У Вас: <img src="<?= $events['img']; ?>" width="16"
                                                  alt="*"><?= $user['eventItem']; ?></span></div>
            <?php foreach (Events::printr() as $item): ?>
                <div class="line_1"></div>
                <?= $item; ?>
            <?php endforeach; ?>
        </div>
    </div>
<?php


echo '</div>';
require_once H . 'system/footer.php';