<?php
require_once '../../system/system.php';
$head = 'Аукцион';
echo only_reg();
echo auction_level();
require_once H.'system/head.php';

echo'<div class="page">';
echo'<img src="/style/images/location/trade_shop/auction.jpg" class="img"/>';

#-Голова-#
$sel_count_h = $pdo->prepare("SELECT COUNT(*) FROM `weapon_me` WHERE `type` = 1 AND `auction` = 1 AND `gold` > 0 AND `silver` > 0 AND `user_id` != :user_id");
$sel_count_h->execute(array(':user_id' => $user['id']));
$amount_h = $sel_count_h->fetch(PDO::FETCH_LAZY);
#-Торс-#
$sel_count_b = $pdo->prepare("SELECT COUNT(*) FROM `weapon_me` WHERE `type` = 2 AND `auction` = 1 AND `gold` > 0 AND `silver` > 0 AND `user_id` != :user_id");
$sel_count_b->execute(array(':user_id' => $user['id']));
$amount_b = $sel_count_b->fetch(PDO::FETCH_LAZY);
#-Руки-#
$sel_count_g = $pdo->prepare("SELECT COUNT(*) FROM `weapon_me` WHERE `type` = 3 AND `auction` = 1 AND `gold` > 0 AND `silver` > 0 AND `user_id` != :user_id");
$sel_count_g->execute(array(':user_id' => $user['id']));
$amount_g = $sel_count_g->fetch(PDO::FETCH_LAZY);
#-Защита-#
$sel_count_s = $pdo->prepare("SELECT COUNT(*) FROM `weapon_me` WHERE `type` = 4 AND `auction` = 1 AND `gold` > 0 AND `silver` > 0 AND `user_id` != :user_id");
$sel_count_s->execute(array(':user_id' => $user['id']));
$amount_s = $sel_count_s->fetch(PDO::FETCH_LAZY);
#-Оружие-#
$sel_count_a = $pdo->prepare("SELECT COUNT(*) FROM `weapon_me` WHERE `type` = 5 AND `auction` = 1 AND `gold` > 0 AND `silver` > 0 AND `user_id` != :user_id");
$sel_count_a->execute(array(':user_id' => $user['id']));
$amount_a = $sel_count_a->fetch(PDO::FETCH_LAZY);
#-Ноги-#
$sel_count_l = $pdo->prepare("SELECT COUNT(*) FROM `weapon_me` WHERE `type` = 6 AND `auction` = 1 AND `gold` > 0 AND `silver` > 0 AND `user_id` != :user_id");
$sel_count_l->execute(array(':user_id' => $user['id']));
$amount_l = $sel_count_l->fetch(PDO::FETCH_LAZY);
#-Амулет-#
$sel_count_am = $pdo->prepare("SELECT COUNT(*) FROM `weapon_me` WHERE `type` = 7 AND `auction` = 1 AND `gold` > 0 AND `silver` > 0 AND `user_id` != :user_id");
$sel_count_am->execute(array(':user_id' => $user['id']));
$amount_am = $sel_count_am->fetch(PDO::FETCH_LAZY);
#-Кольцо-#
$sel_count_r = $pdo->prepare("SELECT COUNT(*) FROM `weapon_me` WHERE `type` = 8 AND `auction` = 1 AND `gold` > 0 AND `silver` > 0 AND `user_id` != :user_id");
$sel_count_r->execute(array(':user_id' => $user['id']));
$amount_r = $sel_count_r->fetch(PDO::FETCH_LAZY);


#-Навигация-#
$head = '<a href="/auction?type=1" style="text-decoration:none;"><span class="body_sel">'.(($_GET['type'] == 1 or $_GET['type'] > 8 or !isset($_GET['type'])) ? "<b>Голова</b> <img src='/style/images/body/auction.png' alt=''/>$amount_h[0]" : "Голова <img src='/style/images/body/auction.png' alt=''/>$amount_h[0]").'</span></a>';
$body = '<a href="/auction?type=2" style="text-decoration:none;"><span class="body_sel">'.($_GET['type'] == 2 ? "<b>Торс</b> <img src='/style/images/body/auction.png' alt=''/>$amount_b[0]" : "Торс <img src='/style/images/body/auction.png' alt=''/>$amount_b[0]").'</span></a>';
$gloves = '<a href="/auction?type=3" style="text-decoration:none;"><span class="body_sel">'.($_GET['type'] == 3 ? "<b>Руки</b> <img src='/style/images/body/auction.png' alt=''/>$amount_g[0]" : "Руки <img src='/style/images/body/auction.png' alt=''/>$amount_g[0]").'</span></a>';
$shield = '<a href="/auction?type=4" style="text-decoration:none;"><span class="body_sel">'.($_GET['type'] == 4 ? "<b>Защита</b> <img src='/style/images/body/auction.png' alt=''/>$amount_s[0]" : "Защита <img src='/style/images/body/auction.png' alt=''/>$amount_s[0]").'</span></a>';
$arm = '<a href="/auction?type=5" style="text-decoration:none;"><span class="body_sel">'.($_GET['type'] == 5 ? "<b>Оружие</b> <img src='/style/images/body/auction.png' alt=''/>$amount_a[0]" : "Оружие <img src='/style/images/body/auction.png' alt=''/>$amount_a[0]").'</span></a>';
$legs = '<a href="/auction?type=6" style="text-decoration:none;"><span class="body_sel">'.($_GET['type'] == 6 ? "<b>Ноги</b> <img src='/style/images/body/auction.png' alt=''/>$amount_l[0]" : "Ноги <img src='/style/images/body/auction.png' alt=''/>$amount_l[0]").'</span></a>';
$amulet = '<a href="/auction?type=7" style="text-decoration:none;"><span class="body_sel">'.($_GET['type'] == 7 ? "<b>Амулет</b> <img src='/style/images/body/auction.png' alt=''/>$amount_am[0]" : "Амулет <img src='/style/images/body/auction.png' alt=''/>$amount_am[0]").'</span></a>';
$ring = '<a href="/auction?type=8" style="text-decoration:none;"><span class="body_sel">'.($_GET['type'] == 8 ? "<b>Кольцо</b> <img src='/style/images/body/auction.png' alt=''/>$amount_r[0]" : "Кольцо <img src='/style/images/body/auction.png' alt=''/>$amount_r[0]").'</span></a>';

#-Вывод-#
echo'<div class="body_list">';	
echo'<div style="padding: 5px;">';
echo''.$all.' '.$head.' '.$body.' '.$gloves.' '.$shield.' '.$arm.' '.$legs.' '.$amulet.' '.$ring.'';
echo'</div>';
echo'<div class="line_1"></div>';
echo'</div>';	

#-Тип снаряжения-#
if($_GET['type'] > 8 or !isset($_GET['type'])){
$type = 1;
}else{
$type = check($_GET['type']);
}

#-Кол-во записей-#
$sel_count_t = $pdo->prepare("SELECT COUNT(*) FROM `weapon_me` WHERE `type` = :type AND `auction` = 1 AND `gold` > 0 AND `silver` > 0 AND `user_id` != :user_id");
$sel_count_t->execute(array(':type' => $type, ':user_id' => $user['id']));
$amount = $sel_count_t->fetch(PDO::FETCH_LAZY);
$num = 10;  
$page = $_GET['page'];  
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;$page = intval($page);if(empty($page) or $page < 0) $page = 1;if($page > $total) $page = $total;$start = $page * $num - $num;  
#-Выборка данных снаряжения-#
$sel_weapon_me = $pdo->prepare("SELECT * FROM `weapon_me` WHERE `type` = :type AND `auction` = 1 AND `gold` > 0 AND `silver` > 0 AND `user_id` != :user_id  ORDER BY `weapon_id` LIMIT $start, $num");
$sel_weapon_me->execute(array(':type' => $type, ':user_id' => $user['id']));
if($sel_weapon_me-> rowCount() != 0){
while($weapon_me = $sel_weapon_me->fetch(PDO::FETCH_LAZY))  
{
#-Выборка данных-#
$sel_weapon = $pdo->prepare("SELECT * FROM `weapon` WHERE `id` = :weapon_id");
$sel_weapon->execute(array(':weapon_id' => $weapon_me['weapon_id']));
$weapon = $sel_weapon->fetch(PDO::FETCH_LAZY);	
#-Продавец-#
$sel_users = $pdo->prepare("SELECT `id`, `nick` FROM `users` WHERE `id` = :user_id");
$sel_users->execute(array(':user_id' => $weapon_me['user_id']));
$all = $sel_users->fetch(PDO::FETCH_LAZY);
$time_ost = $weapon_me['time']-time(); //Время аукциона

#-Вывод снаряжения-#
echo'<div class="img_weapon"><img src="'.$weapon['images'].'" class="'.ramka($weapon_me['max_level']).'"  alt=""/></div>';
echo'<div class="weapon_setting">';
echo'<span style="color: '.color_w($weapon_me['max_level']).';"><img src="'.star($weapon_me['max_level']).'" alt=""/><b>'.$weapon['name'].'</b></span> '.($user['level'] >= $weapon['level'] ? "<span class='green'>[$weapon[level] ур.]</span>" : "<span class='red'>[$weapon[level] ур.]</span>").'<br/>';
echo'<div class="weapon_param"><img src="/style/images/user/sila.png" alt=""/>'.($weapon['sila']+$weapon_me['b_sila']).' <img src="/style/images/user/zashita.png" alt=""/>'.($weapon['zashita']+$weapon_me['b_zashita']).' <img src="/style/images/user/health.png" alt=""/>'.($weapon['health']+$weapon_me['b_health']).' <img src="/style/images/body/blacksmith.png" alt=""/>'.($weapon_me['b_sila']+$weapon_me['b_zashita']+$weapon_me['b_health']).'<br/><img src="/style/images/user/level.png" alt=""/>Уровень заточки: '.$weapon_me['max_level'].'<br/><img src="/style/images/user/user.png" alt=""/>Продавец: <a href="/hero/'.$all['id'].'">'.$all['nick'].'</a><br/><img src="/style/images/body/time.png" alt=""/>'.timers($time_ost).'<br/><img src="/style/images/many/gold.png" alt=""/>'.($user['gold'] >= $weapon_me['gold'] ? '<span class="yellow">' : '<span class="red">').''.$weapon_me['gold'].'</span> <img src="/style/images/many/silver.png" alt=""/>'.($user['silver'] >= $weapon_me['silver'] ? '<span class="yellow">' : '<span class="red">').''.$weapon_me['silver'].'</span></div>';
echo'</div>';

#-Покупка снаряжения-#
echo'<div style="padding-top: 3px;"></div>';

if($_GET['conf'] == 'buy' and $weapon_me['id'] == $_GET['weapon_me']){
echo"<a href='/auction_buy_act?act=buy&weapon_id=$weapon_me[id]&page=".(isset($_GET['page']) ? "$_GET[page]" : "1")."' class='button_green_a'>Подтверждаю</a>";
}else{
if($user['gold'] >= $weapon_me['gold'] and $user['silver'] >= $weapon_me['silver']){
echo"<a href='/auction?conf=buy&weapon_me=$weapon_me[id]&page=".(isset($_GET['page']) ? "$_GET[page]" : "1")."&type=$type' class='button_green_a'>Купить</a>";
}else{
echo"<div class='button_red_a'>Купить</div>";
}	
}
echo'<div style="padding-top: 3px;"></div>';	
}
}else{
echo'<div class="error_list"><img src="/style/images/body/error.png" alt=""/>Нет снаряжения!</div>';
}

#-Отображение постраничной навигации-#
if($posts > $num){
if(!isset($_GET['type'])){$type = 1;}else{$type = $_GET['type'];}
$action = "&type=$type";
echo'<div class="body_list">';
echo'<div class="line_1"></div>';
$z = pages($posts,$total,$action);
echo'</div>';
}

echo'<div class="body_list">';
echo'<div class="menulist">';
#-Лоты игрока-#
if($user['level'] >= 60){
echo'<div class="line_1"></div>';
echo"<li><a href='/lot'><img src='/style/images/body/lot.png' alt=''/> Мои лоты [$user[lot]]</a></li>";
}
echo'<div class="line_1"></div>';
echo"<li><a href='/trade_shop'><img src='/style/images/body/back.png' alt=''/> Торговая лавка</a></li>";
echo'</div>';
echo'</div>';
echo'</div>';
require_once H.'system/footer.php';
?>