<?php
require_once '../../system/system.php';
$head = 'Зелье';
echo only_reg();
require_once H.'system/head.php';
echo'<div class="page">';
#-Только если не в бою-#
if($user['battle'] == 0){
#-Делаем выборку всех зельей и элексиров-#
$sel_potions = $pdo->query("SELECT * FROM `potions`");
#-Только если не равно 0-#
if($sel_potions-> rowCount() != 0){
while($potions = $sel_potions->fetch(PDO::FETCH_LAZY))
{
#-Звезда и цвет в зависимости от типа-#
if($potions['type'] == 1){
$star = 2;
$color = 'green';
}
if($potions['id'] == 4){
$star = 7;
$color = 'red';
}
if($potions['id'] == 5){
$star = 3;
$color = 'yellow';
}
if($potions['id'] == 6){
$star = 2;
$color = 'green';
}
if($potions['level'] > $user['level']){
$lvl = " <span class='green'>[$potions[level] ур.]</span>";
}
echo"<div class='potions'><img src='$potions[images]' class='potions_img'/><div class='potions_name'><img src='/style/images/quality/$star.png' alt=''/><span class='$color'>$potions[name]</span>$lvl<br/><span class='potions_param'>$potions[text]<br/><img src='/style/images/many/gold.png' alt=''/><span id='pot_$potions[id]'>$potions[gold]</span></span></div></div>";	
if($potions['level'] <= $user['level']){
?>

<form method="post" action="/potions_buy?act=buy&id=<?=$potions['id']?>">
<center><input class="input_form" type="number" name="num" value="1" oninput="many_p(this.value,<?=$potions['gold']?>,<?=$potions['id']?>)"/><br/>
<input class="button_green_i" name="submit" type="submit" value="Купить"/></center>
</form>
<script>
function many_p(amount,many,id){
var summa = amount*many;
if(amount > 100){
var summa = 'Не более 100 шт. за раз';
}
document.getElementById("pot_"+id).innerHTML = summa;
}
</script>
<div style="padding-top: 5px;"></div>

<?
}	
}
}else{
echo'<div class="line_1_m"></div>';
echo'<div class="body_list">';
echo'<div class="error_list"><img src="/style/images/body/error.png" alt=""/>Нету ни одного зелья или эликсира</div>';	
echo'</div>';
}
}else{
echo'<div class="line_1_m"></div>';
echo'<div class="body_list">';	
echo'<div class="error_list">';	
echo'<img src="/style/images/body/error.png" alt=""/>Недоступно во время боя!';
echo'</div>';
echo'</div>';	
}
echo'<div class="body_list">';
echo'<div class="line_1"></div>';
echo'<div class="info_list">';
echo'<img src="/style/images/body/imp.png" alt=""/> Во время боя покупка недоступна';
echo'</div>';
echo'<div class="menulist">';
echo'<div class="line_1"></div>';
echo'<li><a href="/trade_shop"><img src="/style/images/body/back.png" alt="Стрелка"/> Торговая лавка</a></li>';
echo'</div>';
echo'</div>';
echo'</div>';
require_once H.'system/footer.php';
?>