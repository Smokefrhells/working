<?php
require_once '../../system/system.php';
$head = 'Мои лоты';
echo only_reg();
echo auction_level();
require_once H.'system/head.php';
if($user['level'] >= 60){

echo'<div class="page">';
echo'<img src="/style/images/location/trade_shop/auction.jpg" class="img"/>';

if(isset($_GET['expose'])){
$weapon_id = check($_GET['expose']);
$sel_weapon_me = $pdo->prepare("SELECT * FROM `weapon_me` WHERE `id` = :weapon_id AND `user_id` = :user_id AND `runa` = 0 AND `state` = 0 AND `auction` = 0");
$sel_weapon_me->execute(array(':weapon_id' => $weapon_id, ':user_id' => $user['id']));
if($sel_weapon_me-> rowCount() != 0){
$weapon_me = $sel_weapon_me->fetch(PDO::FETCH_LAZY);
#-Выборка данных-#
$sel_weapon = $pdo->prepare("SELECT * FROM `weapon` WHERE `id` = :weapon_id");
$sel_weapon->execute(array(':weapon_id' => $weapon_me['weapon_id']));
$weapon = $sel_weapon->fetch(PDO::FETCH_LAZY);		
#-Вывод снаряжения-#
echo'<div class="img_weapon"><img src="'.$weapon['images'].'" class="'.ramka($weapon_me['max_level']).'"  alt=""/></div>';
echo'<div class="weapon_setting">';
echo'<span style="color: '.color_w($weapon_me['max_level']).';"><img src="'.star($weapon_me['max_level']).'" alt=""/><b>'.$weapon['name'].'</b></span> <span class="yellow">['.$weapon['level'].' ур.]</span><br/>';
echo'<div class="weapon_param"><img src="/style/images/user/sila.png" alt=""/>'.($weapon['sila']+$weapon_me['b_sila']).' <img src="/style/images/user/zashita.png" alt=""/>'.($weapon['zashita']+$weapon_me['b_zashita']).' <img src="/style/images/user/health.png" alt=""/>'.($weapon['health']+$weapon_me['b_health']).' <img src="/style/images/body/blacksmith.png" alt=""/>'.($weapon_me['b_sila']+$weapon_me['b_zashita']+$weapon_me['b_health']).'<br/><img src="/style/images/user/level.png" alt=""/>Уровень заточки: '.$weapon_me['max_level'].'</span></div>';
echo'</div>';
echo'<div style="padding-top: 5px;"></div>';
echo'<center>';
echo'<form method="post" action="/auction_expose_act?act=expose&weapon_id='.$weapon_me['id'].'">';
echo'<span class="yellow">Золото:</span><br/>';
echo'<input class="input_form" type="number" name="gold" placeholder="От 1 до '.$weapon['gold'].'"/><br/>';
echo'<span class="yellow">Серебро:</span><br/>';
echo'<input class="input_form" type="number" name="silver" placeholder="От 1 до '.$weapon['silver'].'"/><br/>';
echo'<input class="button_green_i" name="submit" type="submit"  value="Выставить"/>';
echo'</form>';
echo'</center>';
}	
}else{
#-Выборка данных снаряжения-#
$sel_weapon_me = $pdo->prepare("SELECT * FROM `weapon_me` WHERE `auction` = 1 AND `user_id` = :user_id  ORDER BY `time` LIMIT 5");
$sel_weapon_me->execute(array(':user_id' => $user['id']));
if($sel_weapon_me-> rowCount() != 0){
while($weapon_me = $sel_weapon_me->fetch(PDO::FETCH_LAZY))  
{
#-Выборка данных-#
$sel_weapon = $pdo->prepare("SELECT * FROM `weapon` WHERE `id` = :weapon_id");
$sel_weapon->execute(array(':weapon_id' => $weapon_me['weapon_id']));
$weapon = $sel_weapon->fetch(PDO::FETCH_LAZY);	
$time_ost = $weapon_me['time']-time(); //Время аукциона	
#-Вывод снаряжения-#
echo'<div class="img_weapon"><img src="'.$weapon['images'].'" class="'.ramka($weapon_me['max_level']).'"  alt=""/></div>';
echo'<div class="weapon_setting">';
echo'<span style="color: '.color_w($weapon_me['max_level']).';"><img src="'.star($weapon_me['max_level']).'" alt=""/><b>'.$weapon['name'].'</b></span> <span class="yellow">['.$weapon['level'].' ур.]</span><br/>';
echo'<div class="weapon_param"><img src="/style/images/user/sila.png" alt=""/>'.($weapon['sila']+$weapon_me['b_sila']).' <img src="/style/images/user/zashita.png" alt=""/>'.($weapon['zashita']+$weapon_me['b_zashita']).' <img src="/style/images/user/health.png" alt=""/>'.($weapon['health']+$weapon_me['b_health']).' <img src="/style/images/body/blacksmith.png" alt=""/>'.($weapon_me['b_sila']+$weapon_me['b_zashita']+$weapon_me['b_health']).'<br/><img src="/style/images/user/level.png" alt=""/>Уровень заточки: '.$weapon_me['max_level'].'<br/><img src="/style/images/body/time.png" alt=""/>'.timers($time_ost).'<br/><img src="/style/images/many/gold.png" alt=""/><span class="yellow">'.$weapon_me['gold'].'</span> <img src="/style/images/many/silver.png" alt=""/><span class="yellow">'.$weapon_me['silver'].'</span></div>';
echo'</div>';
echo"<a href='/auction_remove_act?act=remove&weapon_id=$weapon_me[id]' class='button_red_a'>Убрать с продажи</a>";
}
}

#-Кол-во занятых лотов-#
$sel_count_w = $pdo->prepare("SELECT COUNT(*) FROM `weapon_me` WHERE `auction` = 1 AND `user_id` = :user_id");
$sel_count_w->execute(array(':user_id' => $user['id']));
$count_w  = $sel_count_w->fetch(PDO::FETCH_LAZY);
#-Покупка лота или выставить на продажу-#
if($count_w[0] < 5){
if($user['lot'] <= $count_w[0]){
echo'<div style="padding-top: 3px;"></div>';
echo"<a href='/auction_lot_act?act=buy' class='button_green_a'>Купить лот за <img src='/style/images/many/gold.png' alt=''/>".($user['lot'] == 0 ? '300' : ''.($user['lot']*700).'')."</a>";	
}else{
for($x=$count_w[0]; $x<$user['lot']; $x++){
echo'<div style="padding-top: 3px;"></div>';
echo"<a href='/bag' class='button_green_a'>Выставить на продажу</a>";	
}
}
}
}

echo'<div style="padding-top: 3px;"></div>';
echo'<div class="body_list">';
echo'<div class="line_1"></div>';
echo'<div class="info_list"><img src="/style/images/body/imp.png" alt=""/> Время на продажу 24 часа</div>';
echo'<div class="menulist">';
echo'<div class="line_1"></div>';
echo"<li><a href='/auction'><img src='/style/images/body/back.png' alt=''/> Аукцион</a></li>";
echo'</div>';
echo'</div>';
echo'</div>';
}
require_once H.'system/footer.php';
?>