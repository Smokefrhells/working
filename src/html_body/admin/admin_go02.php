<?php
require_once '../../system/system.php';
$head = 'Админка';
only_reg();
admin();
require_once H . 'system/head.php';
echo '<div class="body_list">';
echo '<div class="menulist">';
#-Платежи в игре-#
echo '<div class="line_1"></div>';
echo '<center><div class="svg_list"><img src="/style/images/body/payment.png" alt=""/> <span class="yellow"><b>Платежи</b></span> <img src="/style/images/body/payment.png" alt=""/></div></center>';
#-Успешные платежи-#
$sel_ok_pay = $pdo->query("SELECT COUNT(*) FROM `donate` WHERE `statys` = 1");
$ok_pay = $sel_ok_pay->fetch(PDO::FETCH_LAZY);
echo '<div class="line_1"></div>';
echo '<div class="svg_list"><img src="/style/images/body/ok.png" alt=""/>Успешных платежей: ' . $ok_pay[0] . '</div>';
#-Неоплачиваемые платежи-#
$sel_err_pay = $pdo->query("SELECT COUNT(*) FROM `donate` WHERE `statys` = 0");
$err_pay = $sel_err_pay->fetch(PDO::FETCH_LAZY);
echo '<div class="line_1"></div>';
echo '<div class="svg_list"><img src="/style/images/body/error.png" alt=""/>Неоплаченных платежей: ' . $err_pay[0] . '</div>';
echo '<div class="line_1"></div>';
echo '<li><a href="/admin_payment"><img src="/style/images/body/payment.png" alt=""/> <span class="gray">Осмотр платежей</span></a></li>';


#-Настройки игры-#
echo '<div class="line_1"></div>';
echo '<center><div class="svg_list"><img src="/style/images/body/set.png" alt=""/> <span class="yellow"><b>Настройки игры</b></span> <img src="/style/images/body/set.png" alt=""/></div></center>';

echo '<div class="line_1"></div>';
echo '<li><a href="/news_add"><img src="/style/images/news/news.png" alt=""/> <span class="gray">Добавить новость</span></a></li>';

echo '<div class="line_1"></div>';
echo '<li><a href="/admin_gold"><img src="/style/images/many/gold.png" alt=""/> <span class="gray">Зачислить золото</span></a></li>';

echo '<div class="line_1"></div>';
echo '<li><a href="/admin_podarok"><img src="/style/images/body/gift.png" alt=""/> <span class="gray">Сделать подарок</span></a></li>';

echo '<div class="line_1"></div>';
echo '<li><a href="/admin_act?act=delete_podarok"><img src="/style/images/body/gift.png" alt=""/> <span class="gray">Удалить подарок</span></a></li>';

#-Закрытие и открытие игры-#
echo '<div class="line_1"></div>';
$sel_close = $pdo->query("SELECT * FROM `close` WHERE `close` = 1");
if ($sel_close->rowCount() == 0) {
    echo '<li><a href="/admin_act?act=close"><img src="/style/images/body/error.png" alt=""/> <span class="gray">Закрыть игру</span></a></li>';
} else {
    echo '<li><a href="/admin_act?act=close"><img src="/style/images/body/ok.png" alt=""/> <span class="gray">Открыть игру</span></a></li>';
}

#-АКЦИИ-#
echo '<div class="line_1"></div>';
echo '<center><div class="svg_list"><img src="/style/images/many/gold.png" alt=""/> <span class="yellow"><b>Акции</b></span> <img src="/style/images/many/gold.png" alt=""/></div></center>';

#-Акция золото клану-#
echo '<div class="line_1"></div>';
$sel_stock_1 = $pdo->query("SELECT * FROM `stock` WHERE `type` = 1");
if ($sel_stock_1->rowCount() == 0) {
    echo '<li><a href="/admin_stock_act?act=clan"><img src="/style/images/body/clan.png" alt=""/> <span class="gray">Акция золото клану</span></a></li>';
} else {
    if ($_GET['conf'] == 'clan') {
        echo '<li><a href="/admin_stock_act?act=clan"><img src="/style/images/body/clan.png" alt=""/> <span class="gray">Подтверждаю</span></a></li>';
    } else {
        echo '<li><a href="/admin?conf=clan"><img src="/style/images/body/clan.png" alt=""/> <span class="gray">Удалить акцию</span></a></li>';
    }
}

#-Акция x2 золото-#
echo '<div class="line_1"></div>';
$sel_stock_2 = $pdo->query("SELECT * FROM `stock` WHERE `type` = 2");
if ($sel_stock_2->rowCount() == 0) {
    echo '<li><a href="/admin_stock_act?act=goldx2"><img src="/style/images/many/gold.png" alt=""/> <span class="gray">Акция x2 золото</span></a></li>';
} else {
    if ($_GET['conf'] == 'goldx2') {
        echo '<li><a href="/admin_stock_act?act=goldx2"><img src="/style/images/many/gold.png" alt=""/> <span class="gray">Подтверждаю</span></a></li>';
    } else {
        echo '<li><a href="/admin?conf=goldx2"><img src="/style/images/many/gold.png" alt=""/> <span class="gray">Удалить акцию</span></a></li>';
    }
}

#-Акция x3 золото-#
echo '<div class="line_1"></div>';
$sel_stock_3 = $pdo->query("SELECT * FROM `stock` WHERE `type` = 3");
if ($sel_stock_3->rowCount() == 0) {
    echo '<li><a href="/admin_stock_act?act=goldx3"><img src="/style/images/many/gold.png" alt=""/> <span class="gray">Акция x3 золото</span></a></li>';
} else {
    if ($_GET['conf'] == 'goldx3') {
        echo '<li><a href="/admin_stock_act?act=goldx3"><img src="/style/images/many/gold.png" alt=""/> <span class="gray">Подтверждаю</span></a></li>';
    } else {
        echo '<li><a href="/admin?conf=goldx3"><img src="/style/images/many/gold.png" alt=""/> <span class="gray">Удалить акцию</span></a></li>';
    }
}

#-СКИДКИ-#
echo '<div class="line_1"></div>';
echo '<center><div class="svg_list"><img src="/style/images/body/ok.png" alt=""/> <span class="yellow"><b>Скидки</b></span> <img src="/style/images/body/ok.png" alt=""/></div></center>';


#-Скидка на Снаряжения-#
echo '<div class="line_1"></div>';
$sel_stock_30 = $pdo->query("SELECT * FROM `stock` WHERE `type` = 30");
if ($sel_stock_30->rowCount() == 0) {
    echo '<li><a href="/admin_stock?type=30"><img src="/style/images/body/torg.png" alt=""/> <span class="gray">Снаряжения</span></a></li>';
} else {
    if ($_GET['conf'] == 'items') {
        echo '<li><a href="/admin_stock_act?act=delete&type=30"><img src="/style/images/body/torg.png" alt=""/> <span class="gray">Подтверждаю</span></a></li>';
    } else {
        echo '<li><a href="/admin?conf=items"><img src="/style/images/body/torg.png" alt=""/> <span class="gray">Удалить скидку</span></a></li>';
    }
}

#-Скидка на руны-#
echo '<div class="line_1"></div>';
$sel_stock_30 = $pdo->query("SELECT * FROM `stock` WHERE `type` = 31");
if ($sel_stock_30->rowCount() == 0) {
    echo '<li><a href="/admin_stock?type=31"><img src="/style/images/body/runa.png" alt=""/> <span class="gray">Руны</span></a></li>';
} else {
    if ($_GET['conf'] == 'runes') {
        echo '<li><a href="/admin_stock_act?act=delete&type=31"><img src="/style/images/body/runa.png" alt=""/> <span class="gray">Подтверждаю</span></a></li>';
    } else {
        echo '<li><a href="/admin?conf=runes"><img src="/style/images/body/runa.png" alt=""/> <span class="gray">Удалить скидку</span></a></li>';
    }
}

#-Скидка на тренировку-#
echo '<div class="line_1"></div>';
$sel_stock_4 = $pdo->query("SELECT * FROM `stock` WHERE `type` = 4");
if ($sel_stock_4->rowCount() == 0) {
    echo '<li><a href="/admin_stock?type=4"><img src="/style/images/body/traing.png" alt=""/> <span class="gray">Тренировка</span></a></li>';
} else {
    if ($_GET['conf'] == 'traing') {
        echo '<li><a href="/admin_stock_act?act=delete&type=4"><img src="/style/images/body/traing.png" alt=""/> <span class="gray">Подтверждаю</span></a></li>';
    } else {
        echo '<li><a href="/admin?conf=traing"><img src="/style/images/body/traing.png" alt=""/> <span class="gray">Удалить скидку</span></a></li>';
    }
}

#-Скидка на заточку-#
echo '<div class="line_1"></div>';
$sel_stock_5 = $pdo->query("SELECT * FROM `stock` WHERE `type` = 5");
if ($sel_stock_5->rowCount() == 0) {
    echo '<li><a href="/admin_stock?type=5"><img src="/style/images/body/blacksmith.png" alt=""/> <span class="gray">Заточка</span></a></li>';
} else {
    if ($_GET['conf'] == 'blacksmith') {
        echo '<li><a href="/admin_stock_act?act=delete&type=5"><img src="/style/images/body/blacksmith.png" alt=""/> <span class="gray">Подтверждаю</span></a></li>';
    } else {
        echo '<li><a href="/admin?conf=blacksmith"><img src="/style/images/body/blacksmith.png" alt=""/> <span class="gray">Удалить скидку</span></a></li>';
    }
}

#-Скидка на премиум-#
echo '<div class="line_1"></div>';
$sel_stock_6 = $pdo->query("SELECT * FROM `stock` WHERE `type` = 6");
if ($sel_stock_6->rowCount() == 0) {
    echo '<li><a href="/admin_stock?type=6"><img src="/style/images/body/premium.png" alt=""/> <span class="gray">Премиум аккаунт</span></a></li>';
} else {
    if ($_GET['conf'] == 'premium') {
        echo '<li><a href="/admin_stock_act?act=delete&type=6"><img src="/style/images/body/premium.png" alt=""/> <span class="gray">Подтверждаю</span></a></li>';
    } else {
        echo '<li><a href="/admin?conf=premium"><img src="/style/images/body/premium.png" alt=""/> <span class="gray">Удалить скидку</span></a></li>';
    }
}
#-ВЫГОДНЫЕ ПРЕДЛОЖЕНИЯ-#
echo '<div class="line_1"></div>';
echo '<center><div class="svg_list"><img src="/style/images/body/ok.png" alt=""/> <span class="yellow"><b>Выгодные предложения</b></span> <img src="/style/images/body/ok.png" alt=""/></div></center>';

#-Обменник-#
echo '<div class="line_1"></div>';
$sel_stock_7 = $pdo->query("SELECT * FROM `stock` WHERE `type` = 7");
if ($sel_stock_7->rowCount() == 0) {
    echo '<li><a href="/admin_stock?type=7"><img src="/style/images/body/obmenik.png" alt=""/> <span class="gray">Обменник</span></a></li>';
} else {
    if ($_GET['conf'] == 'obmenik') {
        echo '<li><a href="/admin_stock_act?act=delete&type=7"><img src="/style/images/body/obmenik.png" alt=""/> <span class="gray">Подтверждаю</span></a></li>';
    } else {
        echo '<li><a href="/admin?conf=obmenik"><img src="/style/images/body/obmenik.png" alt=""/> <span class="gray">Удалить предложение</span></a></li>';
    }
}

#-Комплект в подарок-#
echo '<div class="line_1"></div>';
$sel_stock_8 = $pdo->query("SELECT * FROM `stock` WHERE `type` = 8");
if ($sel_stock_8->rowCount() == 0) {
    echo '<li><a href="/admin_stock_act?act=pets"><img src="/style/images/body/pets.png" alt=""/> <span class="gray">Питомец в подарок</span></a></li>';
} else {
    if ($_GET['conf'] == 'pets') {
        echo '<li><a href="/admin_stock_act?act=pets"><img src="/style/images/body/pets.png" alt=""/> <span class="gray">Подтверждаю</span></a></li>';
    } else {
        echo '<li><a href="/admin?conf=pets"><img src="/style/images/body/pets.png" alt=""/> <span class="gray">Удалить предложение</span></a></li>';
    }
}
echo '<li><a href="/admin_item"><img src="/style/images/body/pets.png" alt=""/> <span class="gray">Комплект в подарок</span></a></li>';
echo '<li><a href="/admin_chest"><img src="/style/images/body/pets.png" alt=""/> <span class="gray">Выдать сундук</span></a></li>';

echo '</div>';
echo '</div>';
require_once H . 'system/footer.php';
?>