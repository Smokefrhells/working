<?php
require_once '../../system/system.php';
$sel_duel = $pdo->prepare("SELECT * FROM `duel_online` WHERE `user_id` = :user_id OR `ank_id` = :user_id");
$sel_duel->execute(array(':user_id' => $user['id']));
if($sel_duel-> rowCount() != 0){
$head = 'Бой';
}else{
$head = 'Итог боя';
}
echo only_reg();
require_once H.'system/head.php';
echo'<div class="page">';
#-Выборка данных о бое-#
$sel_duel = $pdo->prepare("SELECT * FROM `duel_online` WHERE `user_id` = :user_id OR `ank_id` = :user_id");
$sel_duel->execute(array(':user_id' => $user['id']));
if($sel_duel-> rowCount() != 0){
$duel = $sel_duel->fetch(PDO::FETCH_LAZY);
#-Только если статус больше 1-#
if($duel['statys'] >= 1){
#-Определяем id врага-#
if($duel['ank_id'] == $user['id']){
$user_id = $duel['ank_id'];
$ank_id = $duel['user_id'];
$ank_health = $duel['user_health'];
$ank_t_health = $duel['user_t_health'];
$user_health = $duel['ank_health'];
$user_t_health = $duel['ank_t_health'];
$user_isp = $duel['ank_isp'];
}else{
$user_id = $duel['user_id'];
$user_health = $duel['user_health'];
$user_t_health = $duel['user_t_health'];
$ank_health = $duel['ank_health'];
$ank_t_health = $duel['ank_t_health'];
$ank_id = $duel['ank_id'];
$user_isp = $duel['user_isp'];
}
#-Наш герой-#
$sel_users_me = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :id");
$sel_users_me->execute(array(':id' => $user_id));
$me = $sel_users_me->fetch(PDO::FETCH_LAZY);
#-Выборка данных оппонента-#
$sel_users_op = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :id");
$sel_users_op->execute(array(':id' => $ank_id));
$all = $sel_users_op->fetch(PDO::FETCH_LAZY);
echo'<div class="block_hunting">';
#-Враг-#
echo"<img src='".avatar_img_min($all['avatar'], $all['pol'])."' class='img_h_battle' width='50' height='50' alt=''/><div class='block_monsters'><img src='/style/images/user/user.png' alt=''/><b>$all[nick]</b> <span style='font-size: 13px;'>[$all[level] ур.]</span><br/><div class='param_monsters'><img src='/style/images/user/sila.png' alt=''/>".($all['sila']+$all['s_sila']+$all['sila_bonus'])." <img src='/style/images/user/zashita.png' alt=''/>".($all['zashita']+$all['s_zashita']+$all['zashita_bonus'])." <img src='/style/images/user/health.png' alt=''/>$ank_t_health</div></div>";
echo"<div class='hp_bar_monster'><div class='health2' style='width:".round(100/(($all['health']+$all['s_health']+$all['health_bonus'])/$ank_health))."%'><div class='health' style='width:".round(100/($ank_health/$ank_t_health))."%'></div></div></div>";
#-Оружие-#
echo'<div style="padding-top: 10px;"></div>';
echo'<div class="line_1"></div>';
echo'<div class="body_list">';
echo'<div style="padding-top: 3px;"></div>';
#-Показываем оружие или нет-#
if($duel['statys'] == 2){
#-Выборка оружия для боя которое сейчас надето-#
$sel_weapon_me = $pdo->prepare("SELECT * FROM `weapon_me` WHERE `user_id` = :user_id AND `type` = :type AND `state` = :state");
$sel_weapon_me->execute(array(':user_id' => $user['id'], ':type' => '5', ':state' => '1')); 
$weapon_me = $sel_weapon_me->fetch(PDO::FETCH_LAZY);
#-Если есть оружие-#
if($sel_weapon_me-> rowCount() != 0){
#-Выборка данных о оружие-#
$sel_weapon = $pdo->prepare("SELECT * FROM `weapon` WHERE `id` = :id");
$sel_weapon->execute(array(':id' => $weapon_me['weapon_id'])); 
$weapon = $sel_weapon->fetch(PDO::FETCH_LAZY);
echo"<center><a href='/duel_online_atk?act=attc'><img src='$weapon[images]' class='".ramka($weapon_me['max_level'])."' alt=''/></a></center>";
}else{
echo"<center><a href='/duel_online_atk?act=attc'><img src='/style/images/weapon/arm/0_0.png' class='weapon_me' alt=''/></a></center>";
}
}else{
echo'<center><a href="/duel_online" class="button_red_a">Готовься к бою</a></center>';
echo'<div style="padding-top: 3px;"></div>';
}
echo'<div style="padding-top: 3px;"></div>';	
echo'</div>';
echo'<div class="line_1"></div>';
#-Выборка данных о нас-#
$sel_users_me = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :id");
$sel_users_me->execute(array(':id' => $user_id));
$me = $sel_users_me->fetch(PDO::FETCH_LAZY);
#-Герой-#
echo"<img src='".avatar_img_min($me['avatar'], $me['pol'])."' class='img_h_battle' width='50' height='50' alt=''/><div class='block_monsters'><img src='/style/images/user/user.png' alt=''/><span class='name_monsters'>$me[nick]</span><br/><div class='param_monsters'><img src='/style/images/user/sila.png' alt=''/>".($me['sila']+$me['s_sila']+$me['sila_bonus'])." <img src='/style/images/user/zashita.png' alt=''/>".($me['zashita']+$me['s_zashita']+$me['zashita_bonus'])." <img src='/style/images/user/health.png' alt=''/>$user_t_health</div></div>";
echo"<div class='hp_bar_users'><div class='health2' style='width:".round(100/(($me['health']+$me['s_health']+$me['health_bonus'])/$user_health))."%'><div class='health' style='width:".round(100/($user_health/$user_t_health))."%'></div></div></div>";
echo'<div style="padding-top: 10px;"></div>';
echo'</div>';

#-Только во время боя-#
if($duel['statys'] == 2){
#-Зелье которое есть-#
$sel_potions_me = $pdo->prepare("SELECT * FROM `potions_me` WHERE `user_id` = :user_id AND (`potions_id` = 1 OR `potions_id` = 2 OR `potions_id` = 3) ORDER BY `potions_id`");
$sel_potions_me->execute(array(':user_id' => $user['id']));
#-Если есть зелье-#
if($sel_potions_me-> rowCount() != 0){
while($potions_me = $sel_potions_me->fetch(PDO::FETCH_LAZY))  
{
$sel_potions = $pdo->prepare("SELECT * FROM `potions` WHERE `id` = :id");
$sel_potions->execute(array(':id' => $potions_me['potions_id']));
$potions = $sel_potions->fetch(PDO::FETCH_LAZY);
echo'<div class="body_list">';
echo'<div class="menulist">';
echo'<div class="line_1"></div>';
#-Если время восстановления здоровья еще не прошло-#
if($user_isp >= time()){
$ostatok = $user_isp - time();
echo"<li><a href='/duel_online'><span class='white'><img src='$potions[images]' width='13' height='13'/> $potions[name] [$potions_me[quatity] шт.] (".(int)($ostatok%60)." сек.)</span></a></li>";
}else{
echo"<li><a href='/potions_duel_online?act=isp&id=$potions_me[potions_id]'><img src='$potions[images]' width='13' height='13'/> $potions[name] [$potions_me[quatity] шт.]</a></li>";
}
echo'</div>';
echo'</div>';
}
}	
	
#-Лог событий-#
$sel_duel_l = $pdo->prepare("SELECT * FROM `duel_log` WHERE `duel_id` = :duel_id ORDER BY `time` DESC LIMIT 3");
$sel_duel_l->execute(array(':duel_id' => $duel['id']));
if($sel_duel_l-> rowCount() != 0){
echo'<div class="line_1"></div>';
while($duel_l = $sel_duel_l->fetch(PDO::FETCH_LAZY))  
{
echo'<div class="body_list"><div style="padding: 2px;padding-left: 5px;">';
if($duel_l['user_id'] == $me['id']){
echo"<span class='green'> $duel_l[log]</span>";
}else{
echo"<span class='red'> $duel_l[log]</span>";
}
echo'</div></div>';	
}
}
}
}else{
echo'<div class="line_1_m"></div>';
echo'<div class="body_list">';
echo'<div class="error_list">';
echo'<img src="/style/images/body/error.png" alt=""/> Данные о бое не найдены!';
echo'</div>';
echo'</div>';
}
}else{ //Если нет боя то показываем предыдущий бой
echo'<div class="line_1_m"></div>';
echo'<div class="body_list">';
echo'<div class="error_list">';
#-Выборка данных последнего боя-#
$sel_log = $pdo->prepare("SELECT * FROM `event_log` WHERE `user_id` = :user_id AND `type` = 4 ORDER BY `time` DESC LIMIT 1");
$sel_log->execute(array(':user_id' => $user['id']));
#-Если есть такой бой-#
if($sel_log->rowCount() != 0){
$event = $sel_log->fetch(PDO::FETCH_LAZY);
echo"<img src='/style/images/body/league.png' alt=''/>$event[log]";
}else{ //Если нет
echo'<img src="/style/images/body/error.png" alt=""/> Данные о бое не найдены!';
}
echo'</div>';
echo'<div class="menulist">';
echo'<div class="line_1"></div>';
echo"<li><a href='/duel'><img src='/style/images/body/league.png' alt=''/> Дуэли</a></li>";
echo'</div>';
echo'</div>';
}
echo'</div>';
require_once H.'system/footer.php';
?>