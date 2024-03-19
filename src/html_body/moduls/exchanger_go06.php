<?php
require_once '../../system/system.php';
$head = 'Обменник';
echo only_reg();
echo exchanger_level();
require_once H.'system/head.php';
#-Выгодное предложение обмена-#
$sel_stock = $pdo->query("SELECT * FROM `stock` WHERE `type` = 7");
if($sel_stock-> rowCount() != 0){
$stock = $sel_stock->fetch(PDO::FETCH_LAZY);
}
$silver = $user['level'] *1000;
$gold = $user['level'];
#-Обмен серебра на золото-#
echo'<div class="page">';
echo'<div style="padding-top: 5px;"></div>';
if($user['obmenik_time'] >= time()){
$obmenik_time = $user['obmenik_time']-time();
echo'<center><div class="button_green_a"><img src="/style/images/body/time.png" alt=""/>'.(int)($obmenik_time/3600).' час. '.($obmenik_time/60%60).' мин.</a></center>';
}else{
echo'<center><a href="/exchanger_act?act=gold" class="button_green_a">Обменять <img src="/style/images/many/silver.png" alt=""/>'.num_format($silver).' на <img src="/style/images/many/gold.png" alt=""/>'.$gold.'</a></center>';	
}
echo'<div style="padding-top: 5px;"></div>';
echo'</div>';
#-Обмен золота на серебро-#
echo'<div class="body_list">';
echo'<div class="menulist">';
echo'<div class="line_1"></div>';
echo'<li><a href="/exchanger_act?act=silver&obm=1"><img src="/style/images/body/obmenik.png" alt=""> Обменять <img src="/style/images/many/gold.png" alt=""/>10 на <img src="/style/images/many/silver.png" alt=""/>'.($sel_stock-> rowCount() == 0 ? "10'000" : "<span class='white'><del>10'000</del></span> ".round((10000 + ((10000 * $stock['prosent'])/100)), 0)."").'</a></li>';
echo'<div class="line_1"></div>';
echo'<li><a href="/exchanger_act?act=silver&obm=2"><img src="/style/images/body/obmenik.png" alt=""> Обменять <img src="/style/images/many/gold.png" alt="Золото">50 на <img src="/style/images/many/silver.png" alt=""/>'.($sel_stock-> rowCount() == 0 ? "50'000" : "<span class='white'><del>50'000</del></span> ".round((50000 + ((50000 * $stock['prosent'])/100)), 0)."").'</a></li>';
echo'<div class="line_1"></div>';
echo'<li><a href="/exchanger_act?act=silver&obm=3"><img src="/style/images/body/obmenik.png" alt=""> Обменять <img src="/style/images/many/gold.png" alt="Золото">100 на <img src="/style/images/many/silver.png" alt=""/>'.($sel_stock-> rowCount() == 0 ? "100'000" : "<span class='white'><del>100'000</del></span> ".round((100000 + ((100000 * $stock['prosent'])/100)), 0)."").'</a></li>';
echo'</div>';
echo'</div>';
require_once H.'system/footer.php';
?>