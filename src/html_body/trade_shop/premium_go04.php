<?php
require_once '../../system/system.php';
$head = 'Премиум';
echo only_reg();
require_once H.'system/head.php';
#-Скидка на премиум-#
$sel_stock = $pdo->query("SELECT * FROM `stock` WHERE `type` = 6");
if($sel_stock-> rowCount() != 0){
$stock = $sel_stock->fetch(PDO::FETCH_LAZY);
$prem_silver = round((150 - ((150 * $stock['prosent'])/100)), 0);
$prem_gold = round((250 - ((250 * $stock['prosent'])/100)), 0);
}else{
$prem_silver = 150;
$prem_gold = 250;
}

echo'<div class="page">';
#-Серебряный премиум-#
echo'<div class="t_max">';
echo'<div class="t_img"><img src="/style/images/body/prem_silver.png" alt=""/></div>';
echo'<div class="t_name"><img src="/style/images/body/premium.png" alt=""/> <span class="gray"><b>Серебряный премиум</b></span> '.($sel_stock-> rowCount() != 0 ? "<span class='green'>[-$stock[prosent]%]</span>" : "").'<br/>';
echo'<div class="t_param"><img src="/style/images/user/exp.png" alt=""/>x2 опыт <img src="/style/images/many/silver.png" alt=""/>+25% серебра<br/>';
echo'Цвет: <span class="orange">Оранжевый</span><br/>';
echo'</div>';
echo'</div>';

echo'<div style="padding-top: 5px;"></div>';
#-Если активирован этот премиум-#
if($user['premium'] == 1){
$ostatok = $user['premium_time']-time();
echo'<center><div class="button_red_a"><img src="/style/images/body/time.png" alt=""/>'.timer($ostatok).'</div></center>';
echo'<div style="padding-top: 3px;"></div>';
}
if($user['premium'] == 0 or $user['premium'] == 1){
echo"<div class='button_green_a'><span id='day_silver'>1</span> дн. за <img src='/style/images/many/gold.png' alt=''/><span id='summ_silver'>$prem_silver</span></div>";
?>
<form method="post" action="/premium_buy?act=buy&type=1">
<center><input class="input_form" type="number" name="num" value="1" oninput="many_p(this.value, <?=$prem_silver?>, 1)"/><br/>
<input class="button_green_i" name="submit" type="submit" value="Купить"/></center>
</form>
<div style="padding-top: 5px;"></div>
<?
}

#-Золотой премиум-#
echo'<div style="padding-top: 3px;"></div>';
echo'<div class="t_max">';
echo'<div class="t_img"><img src="/style/images/body/prem_gold.png" alt=""/></div>';
echo'<div class="t_name"><img src="/style/images/body/premium.png" alt=""/> <b>Золотой премиум</b> '.($sel_stock-> rowCount() != 0 ? "<span class='green'>[-$stock[prosent]%]</span>" : "").'<br/>';
echo'<div class="t_param"><img src="/style/images/user/exp.png" alt=""/>x3 опыт <img src="/style/images/many/silver.png" alt=""/>+50% серебра<br/>';
echo'Цвет: <span class="orange">Оранжевый</span><br/>';
echo'</div>';
echo'</div>';

echo'<div style="padding-top: 5px;"></div>';
#-Если активирован этот премиум-#
if($user['premium'] == 2){
$ostatok = $user['premium_time']-time();
echo'<center><div class="button_red_a"><img src="/style/images/body/time.png" alt=""/>'.timer($ostatok).'</div></center>';
echo'<div style="padding-top: 3px;"></div>';
}
if($user['premium'] == 0 or $user['premium'] == 2){
echo"<div class='button_green_a'><span id='day_gold'>1</span> дн. за <img src='/style/images/many/gold.png' alt=''/><span id='summ_gold'>$prem_gold</span></div>";
?>
<form method="post" action="/premium_buy?act=buy&type=2">
<center><input class="input_form" type="number" name="num" value="1" oninput="many_p(this.value, <?=$prem_gold?>, 2)"/><br/>
<input class="button_green_i" name="submit" type="submit" value="Купить"/></center>
</form>
<?}?>

<script>
function many_p(amount, price, type){
var summa = amount*price;
if(amount > 90){
var summa = 'Не более 90 дней за раз!';
}
if(type == 1){
document.getElementById("summ_silver").innerHTML = summa;
document.getElementById("day_silver").innerHTML = amount;
}else{
document.getElementById("summ_gold").innerHTML = summa;
document.getElementById("day_gold").innerHTML = amount;
}
}
</script>
<?
echo'<div style="padding-top: 3px;"></div>';
echo'<div class="body_list">';
echo'<div class="menulist">';
echo'<div class="line_1"></div>';
echo'<li><a href="/trade_shop"><img src="/style/images/body/back.png" alt=""/> Торговая лавка</a></li>';
echo'</div>';
echo'</div>';
echo'</div>';
echo'</div>';
require_once H.'system/footer.php';
?>