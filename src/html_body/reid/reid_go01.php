<?php
require_once '../../system/system.php';
$head = 'Рейд';
echo only_reg();
echo reid_level();
require_once H.'system/head.php';
echo'<div class="page">';
#-Проверяем есть ли босс-#
$sel_reid = $pdo->query("SELECT * FROM `reid_boss`");
if($sel_reid->rowCount() != 0){
$reid = $sel_reid->fetch(PDO::FETCH_LAZY);

#-Данные босса-#
if($reid['statys'] == 0){$img = "/style/images/monstru/reid/$reid[images]_$reid[images].jpg";}else{$img = "/style/images/monstru/reid/$reid[images].jpg";}
echo"<img src='$img' class='img_m_battle' alt=''/><div class='block_monsters'><img src='/style/images/body/reid.png' alt=''/><span class='red'><b>$reid[name]</b></span><br/><div class='param_monsters'><img src='/style/images/user/sila.png' alt=''/>$reid[sila] <img src='/style/images/user/zashita.png' alt=''/>$reid[zashita] <img src='/style/images/user/health.png' alt=''/>$reid[t_health]</div></div>";
echo"<div class='hp_bar_monster'><div class='health2' style='width:".round(100/($reid['max_health']/$reid['health']))."%'><div class='health' style='width:".round(100/($reid['health']/$reid['t_health']))."%'></div></div></div>";

#-Участвуем в сражении-#
$sel_reid_u = $pdo->prepare("SELECT * FROM `reid_users` WHERE `user_id` = :user_id");
$sel_reid_u->execute(array(':user_id' => $user['id']));

#-Считаем участников сражения-#
if($reid['statys'] == 0 or $sel_reid_u -> rowCount() == 0){
$sel_c_reid_u = $pdo->query("SELECT COUNT(*) FROM `reid_users`");
$c_reid_u = $sel_c_reid_u->fetch(PDO::FETCH_LAZY);	
echo'<div style="padding-top: 5px;"></div>';	
echo'<a href="/reid_users" class="button_green_a">Участников: '.$c_reid_u[0].'</a>';
echo'<div style="padding-top: 3px;"></div>';
if($reid['statys'] == 0){
echo'<a href="/reid_log" class="button_green_a">Лог предыдущего боя</a>';
echo'<div style="padding-top: 3px;"></div>';
}
}

#-Если не участвуем-#
if($sel_reid_u -> rowCount() == 0){
echo'<a href="/reid_join?act=join" class="button_red_a">Сражаться</a>';
echo'<div style="padding-top: 5px;"></div>';	
}else{
#-Начат бой или нет-#
if($reid['statys'] == 0){
$reid_ost = $reid['time']-time();
echo'<div class="button_red_a">'.timers($reid_ost).'</div>';
echo'<div style="padding-top: 5px;"></div>';		

}else{
$reid_u = $sel_reid_u->fetch(PDO::FETCH_LAZY);

echo'<div style="padding-top: 10px;"></div>';

if($reid_u['user_t_health'] > 0){
echo'<div class="line_1"></div>';
echo'<div class="body_list">';
echo'<div style="padding-top: 3px;"></div>';
#-Выборка оружия для боя которое сейчас надето-#
$sel_weapon_me = $pdo->prepare("SELECT * FROM `weapon_me` WHERE `user_id` = :user_id AND `type` = :type AND `state` = :state");
$sel_weapon_me->execute(array(':user_id' => $user['id'],':type' => 5, ':state' => 1)); 
$weapon_me = $sel_weapon_me->fetch(PDO::FETCH_LAZY);
#-Если есть оружие-#
if($sel_weapon_me-> rowCount() != 0){
#-Выборка данных о оружие-#
$sel_weapon = $pdo->prepare("SELECT * FROM `weapon` WHERE `id` = :id");
$sel_weapon->execute(array(':id' => $weapon_me['weapon_id'])); 
$weapon = $sel_weapon->fetch(PDO::FETCH_LAZY);
echo"<center><a href='/reid_attack?act=attc'><img src='$weapon[images]' class='".ramka($weapon_me['max_level'])."' alt='' /></a></center>";
}else{
echo"<center><a href='/reid_attack?act=attc'><img src='/style/images/weapon/arm/0_0.png' class='weapon_me' alt=''/></a></center>";
}
echo'</div>';
echo'<div class="line_1"></div>';
}

#-Данные игрока-#
if($reid_u['user_t_health'] != 0){
echo"<img src='".avatar_img_min($user['avatar'], $user['pol'])."' class='img_h_battle' width='50' height='50' alt=''/><div class='block_monsters'><img src='/style/images/user/user.png' alt=''/><span class='name_monsters'>$user[nick]</span><br/><div class='param_monsters'><img src='/style/images/user/sila.png' alt=''/>".($user['sila']+$user['s_sila']+$user['sila_bonus'])." <img src='/style/images/user/zashita.png' alt=''/>".($user['zashita']+$user['s_zashita']+$user['zashita_bonus'])." <img src='/style/images/user/health.png' alt=''/>$reid_u[user_t_health]</div></div>";
echo"<div class='hp_bar_users'><div class='health2' style='width:".round(100/(($user['health']+$user['s_health']+$user['health_bonus'])/$reid_u['user_health']))."%'><div class='health' style='width:".round(100/($reid_u['user_health']/$reid_u['user_t_health']))."%'></div></div></div>";
echo'<div style="padding-top: 10px;"></div>';

#-Зелье которое есть-#
$sel_potions_me = $pdo->prepare("SELECT * FROM `potions_me` WHERE `user_id` = :user_id AND (`potions_id` = 1 OR `potions_id` = 2 OR `potions_id` = 3) ORDER BY `potions_id`");
$sel_potions_me->execute(array(':user_id' => $user['id']));
#-Если есть зелье-#
if($sel_potions_me-> rowCount() != 0){
echo'<div class="line_4"></div>';
echo'<div class="body_list">';
echo'<div class="menulist">';
while($potions_me = $sel_potions_me->fetch(PDO::FETCH_LAZY))  
{
$sel_potions = $pdo->prepare("SELECT * FROM `potions` WHERE `id` = :id");
$sel_potions->execute(array(':id' => $potions_me['potions_id']));
$potions = $sel_potions->fetch(PDO::FETCH_LAZY);
echo'<div class="line_1"></div>';
#-Если время восстановления здоровья еще не прошло-#
if($reid_u['user_isp'] >= time()){
$ostatok = $reid_u['user_isp'] - time();
echo"<li><a href='/reid'><span class='white'><img src='$potions[images]' width='13' height='13'/> $potions[name] [$potions_me[quatity] шт.] (".(int)($ostatok%60)." сек.)</span></a></li>";
}else{
echo"<li><a href='/potions_reid?act=isp&id=$potions_me[potions_id]'><img src='$potions[images]' width='13' height='13'/> $potions[name] [$potions_me[quatity] шт.]</a></li>";
}
}
echo'</div>';
echo'</div>';
}
}else{
#-Воскрешение-#
if($reid_u['user_vosk'] < 4){
echo'<div class="line_1"></div>';
echo'<div class="body_list">';
echo'<div style="padding-top: 3px;"></div>';
if($_GET['inv'] == 'res'){
echo"<a href='/reid_res?act=res' class='button_green_a'>Да воскреснуть</a>";
}else{
$gold_v = ($reid['type']*15)*$reid_u['user_vosk'];
echo"<a href='/reid?inv=res' class='button_green_a'>Воскреснуть за <img src='/style/images/many/gold.png' alt=''/>".($user['gold'] >= $gold_v  ? "$gold_v" : "<span class='red'>$gold_v</span>")."</a>";
}
echo'<div style="padding-top: 3px;"></div>';
echo'<div class="line_1"></div>';
echo'</div>';
}
echo"<div style='opacity: .5;'><img src='".avatar_img_min($user['avatar'], $user['pol'])."' class='img_h_battle' width='50' height='50' alt=''/><div class='block_monsters'><img src='/style/images/user/user.png' alt=''/><span class='name_monsters'>$user[nick]</span><br/><div class='param_monsters'><img src='/style/images/user/sila.png' alt=''/>$user[sila] <img src='/style/images/user/zashita.png' alt=''/>$user[zashita] <img src='/style/images/user/health.png' alt=''/>$reid_u[user_t_health]</div></div></div>";
}

#-Участники-#
#-Выборка живых игроков-#
$sel_reid_g = $pdo->query("SELECT COUNT(*) FROM `reid_users` WHERE `user_t_health` > 0");
$reid_g = $sel_reid_g->fetch(PDO::FETCH_LAZY);
#-Выборка всех игроков-#
$sel_reid_a = $pdo->query("SELECT COUNT(*) FROM `reid_users`");
$reid_a = $sel_reid_a->fetch(PDO::FETCH_LAZY);
echo'<div class="body_list">';
echo'<div class="menulist">';
echo'<div class="line_1"></div>';
echo'<div class="svg_list"><img src="/style/images/many/silver.png" alt=""/> Серебро: '.$reid_u['silver'].'</div>';
echo'<div class="line_1"></div>';
echo'<li><a href="/reid_users"><img src="/style/images/user/user.png" alt=""/> Участники: <span class="green">'.$reid_g[0].'</span> из <span class="white">'.$reid_a[0].'</span></a></li>';
echo'</div>';
echo'</div>';

#-Лог боя-#
$sel_reid_log = $pdo->query("SELECT * FROM `reid_log` ORDER BY `time` DESC LIMIT 5");
if($sel_reid_log -> rowCount() != 0){
echo'<div class="line_1"></div>';
while($reid_log = $sel_reid_log->fetch(PDO::FETCH_LAZY)){
echo'<div class="body_list"><div style="padding: 2px; padding-left: 5px;">';
echo"$reid_log[log]";
echo'</div>';
echo'</div>';
}
}
}
}
}else{
echo'<div class="body_list">';	
echo'<div class="error_list">';	
echo'<img src="/style/images/body/error.png" alt=""/>Обновите страницу!';
echo'</div>';
echo'</div>';	
}
echo'</div>';
require_once H.'system/footer.php';
?>