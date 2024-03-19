<?php
require_once '../../system/system.php';
$head = 'Торговая лавка';
echo only_reg();
require_once H . 'system/head.php';
require_once H . 'avenax/Item.php';


//var_dump(Item::nameItem());
echo '<div class="page">';
echo '<img src="/style/images/location/trade_shop/trade_shop.jpg" class="img"/>';
echo '<div class="body_list">';
echo '<div class="menulist">';
echo '<div class="line_3"></div>';
#-Магазин снаряжения-#
echo '<li><a href="/armors"><img src="/style/images/body/snarag.png" alt=""/> Магазин снаряжения</a></li>';
echo '<li><a href="/armors/runes"><img src="/style/images/body/runa.png" alt=""/> Торговец рунами</a></li>';

#-Магазин питомцев-#
echo '<div class="line_1"></div>';
if ($user['level'] >= 20) {
    echo '<li><a href="/magazin_pets"><img src="/style/images/body/pets.png" alt=""/> Магазин питомцев</a></li>';
} else {
    echo '<li><a href="/magazin_pets"><img src="/style/images/body/pets.png" alt=""/> <span class="white">Магазин питомцев (Требуется <img src="/style/images/user/level.png" alt=""/>20)</span></a></li>';
}

#-Аукцион-#
//echo '<div class="line_1"></div>';
//if ($user['level'] >= 10) {
//    echo '<li><a href="/auction"><img src="/style/images/body/auction.png" alt=""/> Аукцион</a></li>';
//} else {
//    echo '<li><a href="/auction"><img src="/style/images/body/auction.png" alt=""/> <span class="white">Аукцион (Требуется <img src="/style/images/user/level.png" alt=""/>10)</span></a></li>';
//}

#-Зелья-#
if ($user['battle'] == 0) {
    echo '<div class="line_1"></div>';
    echo '<li><a href="/potions"><img src="/style/images/body/potions.png" alt=""/> Зелье</a></li>';
} else {
    echo '<div class="line_1"></div>';
    echo '<li><a href="/trade_shop"><img src="/style/images/body/potions.png" alt=""/> <span class="white">Зелье (Вы в бою)</span></a></li>';
}

#-Премиум-#
echo '<div class="line_1"></div>';
echo '<li><a href="/premium"><img src="/style/images/body/premium.png" alt=""/> Премиум</a></li>';

#-Колесо фортуны-#
echo '<div class="line_1"></div>';
echo '<li><a href="/fortuna"><img src="/style/images/body/lottery.png" alt=""/> Колесо фортуны</a></li>';
echo '</div>';
echo '</div>';
echo '</div>';
require_once H . 'system/footer.php';
?>
