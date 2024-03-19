<?php
require_once '../system/system.php';
$head = 'Покупка золота';
echo only_reg();
require_once H.'system/head.php';
echo'<div class="body_list">';
#-Акция на золото-#
$sel_stock = $pdo->query("SELECT * FROM `stock` ORDER BY `type`");
if($sel_stock-> rowCount() != 0){
while($stock = $sel_stock->fetch(PDO::FETCH_LAZY)){
#-Помощь клану-#
if($stock['type'] == 1){
echo'<div class="line_1"></div>';
echo'<div style="padding: 5px; color: #cb862c;">';
echo'<center><img src="/style/images/body/clan.png" alt=""/> <b>Помощь клану!</b> <img src="/style/images/body/clan.png" alt=""/></center>';
echo'Купи любое количество <img src="/style/images/many/gold.png" alt=""/>Золота и казна твоего клана будет пополнена на такое же количество.';	
echo'</div>';
}
#-В два раза больше-#
if($stock['type'] == 2){
echo'<div class="line_1"></div>';
echo'<div style="padding: 5px; color: #cb862c;">';
echo'<center><img src="/style/images/body/gift.png" alt=""/> <b>В 2 раза больше золота!</b> <img src="/style/images/body/gift.png" alt=""/></center>';
echo'Купи любое количество <img src="/style/images/many/gold.png" alt=""/>Золота и получи в 2 раза больше.';	
echo'</div>';
}
#-В три раза больше-#
if($stock['type'] == 3){
echo'<div class="line_1"></div>';
echo'<div style="padding: 5px; color: #cb862c;">';
echo'<center><img src="/style/images/body/gift.png" alt=""/> <b>В 3 раза больше золота!</b> <img src="/style/images/body/gift.png" alt=""/></center>';
echo'Купи любое количество <img src="/style/images/many/gold.png" alt=""/>Золота и получи в 3 раза больше.';	
echo'</div>';
}


#-Питомец в подарок-#
if($stock['type'] == 8){
echo'<div class="line_1"></div>';
echo'<div style="padding: 5px; color: #dc50ff;">';
echo'<center><img src="/style/images/body/gift.png" alt=""/> <b>Питомец в подарок!</b> <img src="/style/images/body/gift.png" alt=""/></center>';
echo'</div>';
echo'<div class="t_max">';
echo'<img src="/style/images/pets/zmeeglav.jpg" class="t_img" alt=""/>';
echo'<div class="t_name"><img src="/style/images/quality/1.png" alt=""/><b>Змееглав</b><br/> <span class="t_param"><img src="/style/images/user/sila.png" alt=""/>2200 <img src="/style/images/user/zashita.png" alt=""/>2200  <img src="/style/images/user/health.png" alt=""/>4000</span><br/>Купи более <img src="/style/images/many/gold.png" alt=""/><s>5000</s> 2000 золота одним платежем</div>';
echo'</div>';
//echo'<div class="t_name"><img src="/style/images/quality/1.png" alt=""/><b>Змееглав</b><br/> <span class="t_param"><img src="/style/images/user/sila.png" alt=""/>2200 <img src="/style/images/user/zashita.png" alt=""/>2200  <img src="/style/images/user/health.png" alt=""/>4000</span><br/>Купи более <img src="/style/images/many/gold.png" alt=""/>5000 золота одним платежем</div>';
//echo'</div>';
echo'<div class="t_max">';
echo'<img src="/style/images/pets/gelaus.jpg" class="t_img" alt=""/>';
echo'<div class="t_name"><img src="/style/images/quality/1.png" alt=""/><b>Гелаус</b><br/> <span class="t_param"><img src="/style/images/user/sila.png" alt=""/>4400 <img src="/style/images/user/zashita.png" alt=""/>4400  <img src="/style/images/user/health.png" alt=""/>5000</span><br/>Купи более <img src="/style/images/many/gold.png" alt=""/><s>10000</s> 5000 золота одним платежем</div>';
echo'</div>';
//echo'<div class="t_name"><img src="/style/images/quality/1.png" alt=""/><b>Гелаус</b><br/> <span class="t_param"><img src="/style/images/user/sila.png" alt=""/>4400 <img src="/style/images/user/zashita.png" alt=""/>4400  <img src="/style/images/user/health.png" alt=""/>5000</span><br/>Купи более <img src="/style/images/many/gold.png" alt=""/>10000 золота одним платежем</div>';
//echo'</div>';

}
}
}

/*echo'<div class="line_1"></div>';
echo'<div style="padding: 5px; color: #cb862c;">';
echo'<center><img src="/style/images/body/gift.png" alt=""/> <b>В честь открытия 3 раза больше золота! </b> <img src="/style/images/body/gift.png" alt=""/></center>';
echo'Купи любое количество <img src="/style/images/many/gold.png" alt=""/>Золота и получи в 3 раза больше.';
echo'</div>
<center><span class="red"><b>Cегодня последний день!</b></span></center>
';
*/
/*echo'<div class="line_1"></div>';
echo'<div style="padding: 5px; color: #cb862c;">';
?>
<br />
<div class="cntr"><img src="/style/images/body/gift.png" alt=""/> <span style="color: #7afe4e;">Комплект лорда в подарок!</span> <img src="/style/images/body/gift.png" alt=""/><span style="color: #eff0a1;">:<br /> - Купи 10000 золота получи полный комплект<br /> - Купи 2000 золота получи 1 вещь лорда <br /> -<span style="color: red;"> Количество покупок ограниченное</span><br />Максимум 3 вещи в день <br /></span></div>
<br />
<div class="cntr"><img class="icon" height="48" src="/style/images/weapon/head/24.png" /> <img class="icon" height="48" src="/style/images/weapon/body/24.png" /> <img class="icon" height="48" src="/style/images/weapon/gloves/24.png" /> <img class="icon" height="48" src="/style/images/weapon/shield/24.png" /> <img class="icon" height="48" src="/style/images/weapon/arm/24.png" /> <img class="icon" height="48" src="/style/images/weapon/legs/24.png" /></div><br />
<?

echo'</div>
<center><span class="red"><b>Акция действует до 18 августа включительно!</b></span></center>
';
*/
echo'<span class="green"><strong>Шаг 2 из 2.<br/>
 Выберите способ оплаты:</strong></span>

';
echo'<br>';
echo'<div class="menulist">';
echo'<div class="line_1"></div>';
echo'<li><a href="/wapkassa"><img src="/style/images/donat/wapkassa.png" width="13px" height="13px" alt=""/> <span class="gray">Купить через Wapkassa (Автоматически)</span></a></li>';
echo'<div class="line_1"></div>';
echo'<li><a href="/cards"><img src="/style/images/donat/wapkassa.png" width="13px" height="13px" alt=""/> <span class="gray">Перевод на карту (Россия)</span></a></li>';
echo'<div class="line_1"></div>';
echo'<li><a href="/privat24"><img src="/style/images/donat/privat24.png" width="13px" height="13px" alt=""/> <span class="gray">Перевод через Приват 24</span></a></li>';
echo'<div class="line_1"></div>';
echo'<li><a href="/webmoney"><img src="/style/images/donat/webmoney.png" width="13px" height="13px" alt=""/> <span class="gray">Перевод через Webmoney</span></a></li>';
echo'<div class="line_1"></div>';
echo'<li><a href="/qiwi"><img src="/style/images/donat/qiwi.png" width="13px" height="13px" alt=""/> <span class="gray">Перевод через Qiwi</span></a></li>';

echo'</div>';
require_once H.'system/footer.php';
?>