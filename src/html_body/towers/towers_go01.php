<?php
require_once '../../system/system.php';
$head = 'Башни';
echo only_reg();
echo towers_level();
require_once H.'system/head.php';
echo'<div class="page">';
#-Проверка что игрок не участвует в бою-#
$sel_towers_me = $pdo->prepare("SELECT * FROM `towers` WHERE `user_id` = :user_id");
$sel_towers_me->execute(array(':user_id' => $user['id']));
if($sel_towers_me->rowCount() == 0){
echo'<img src="/style/images/location/towers.jpg" class="img" alt=""/>';
echo'<div style="padding-top: 3px;"></div>';

#-Кол-во необходимого серебра и золота-#
if($user['level'] <= 100){$gold = 85;$silver = "25'000";}
if($user['level'] <= 75){$gold = 65;$silver = "15'000";}
if($user['level'] <= 50){$gold = 45;$silver = "10'000";}
if($user['level'] <= 25){$gold = 25;$silver = "5'000";}
echo'<a href="/towers_start?act=start&type=gold" class="button_green_a">Сражаться за <img src="/style/images/many/gold.png" alt=""/>'.$gold.'</a>';
echo'<div style="padding-top: 3px;"></div>';
echo'<a href="/towers_start?act=start&type=silver" class="button_green_a">Сражаться за <img src="/style/images/many/silver.png" alt=""/>'.$silver.'</a>';
echo'<div style="padding-top: 3px;"></div>';
echo'<div class="body_list">';
echo'<div class="line_1"></div>';
echo'<div class="info_list"><img src="/style/images/body/imp.png" alt=""/> При победе награда удваивается.</div>';
echo'</div>';
}else{
$towers_me = $sel_towers_me->fetch(PDO::FETCH_LAZY);	

#-Если бой не начат-#
if($towers_me['statys'] < 2){
?>
<script type="text/javascript">
function timer(){
 var obj=document.getElementById('timer_inp');
 obj.innerHTML--;
 if(obj.innerHTML==0){location.reload();setTimeout(function(){},1000);}
 else{setTimeout(timer, 1000);}
}
setTimeout(timer,1000);
</script>
<?
#-Очередь-#
if($towers_me['statys'] == 0){
#-Выборка сколько игроков участвует в сражении-#
$sel_towers_a = $pdo->prepare("SELECT COUNT(*) FROM `towers` WHERE `battle_id` = :battle_id ORDER BY `group`");
$sel_towers_a->execute(array(':battle_id' => $towers_me['battle_id']));
$towers_a = $sel_towers_a->fetch(PDO::FETCH_LAZY);
echo'<img src="/style/images/location/towers.jpg" class="img" alt=""/>';
echo'<div style="padding-top: 3px;"></div>';
echo'<center>';
echo"<span class='gray'><img src='/style/images/body/loader.gif' alt=''/>Ожидание других игроков: <img src='/style/images/user/user.png' alt=''/>$towers_a[0]/4 [<span id='timer_inp'>5</span>]</span><br/>";
if($towers_me['type'] == 'gold'){
echo'<span class="gray"><img src="/style/images/body/towers.png" alt=""/>Башня: <img src="/style/images/many/gold.png" alt=""/>Золотая</span>';
}else{
echo'<span class="gray"><img src="/style/images/body/towers.png" alt=""/>Башня: <img src="/style/images/many/silver.png" alt=""/>Серебряная</span>';
}
echo'</center>';
echo'<div style="padding-top: 3px;"></div>';
echo'<a href="/towers" class="button_green_a">Обновить</a>';
echo'<div style="padding-top: 2px;"></div>';
echo'<a href="/towers_exit?act=exit_osh" class="button_red_a">Выйти</a>';
echo'<div style="padding-top: 3px;"></div>';
}	

#-Начало боя-#
if($towers_me['statys'] == 1){
#-Время ожидания-#
$sel_towers_t = $pdo->prepare("SELECT `id`, `battle_id`, `time` FROM `towers` WHERE `battle_id` = :battle_id ORDER BY `id`");
$sel_towers_t->execute(array(':battle_id' => $towers_me['battle_id']));
$towers_t = $sel_towers_t->fetch(PDO::FETCH_LAZY);
$towers_ostatok = $towers_t['time']-time();//Сколько времени осталось	

echo'<div class="body_list">';
#-Выборка всех участников сражения-#
$sel_towers_u = $pdo->prepare("SELECT * FROM `towers` WHERE `battle_id` = :battle_id ORDER BY `group`");
$sel_towers_u->execute(array(':battle_id' => $towers_me['battle_id']));
while($towers_u = $sel_towers_u->fetch(PDO::FETCH_LAZY)){
#-Выборка данных игрока-#	
$sel_users = $pdo->prepare("SELECT `id`, `avatar`, `pol`, `nick`, `level`, `time_online`, `param` FROM `users` WHERE `id` = :user_id");
$sel_users->execute(array(':user_id' => $towers_u['user_id']));	
$all = $sel_users->fetch(PDO::FETCH_LAZY);
echo'<div class="line_1"></div>';
echo'<div class="menulitl">';
echo"<li><a href='/hero/$all[id]'> <img src='".avatar_img_min($all['avatar'], $all['pol'])."' class='menulitl_img' width='30' height='30' alt=''/><div class='menulitl_block'>".online($all['time_online'])." <span class='menulitl_name'>".($towers_me['group'] == $towers_u['group'] ? "<span class='green'>$all[nick]</span>" : "<span class='red'>$all[nick]</span>")."</span><br/><div class='menulitl_param'><img src='/style/images/user/level.png' alt=''/>$all[level] <img src='/style/images/body/all.png' alt=''/>$all[param]</div></div></a></li>";
echo'</div>';
}	
echo'</div>';
echo'<div class="line_1"></div>';
$ostatok = timers($towers_ostatok);
echo'<div style="padding-top: 3px;"></div>';
echo'<a href="/towers" class="button_green_a">'.($ostatok == '' ? "Обновить" : "$ostatok").'</a>';
echo'<div style="padding-top: 3px;"></div>';
}

}else{
	
#-Бой-#
if($towers_me['statys'] != 3){
	
#-Проверка наличия оппонента-#
$sel_towers_u = $pdo->prepare("SELECT `id`, `battle_id`, `user_id`, `user_t_health` FROM `towers` WHERE `battle_id` = :battle_id AND `user_id` = :ank_id AND `user_t_health` > 0");
$sel_towers_u->execute(array(':battle_id' => $towers_me['battle_id'], ':ank_id' => $towers_me['ank_id']));
if($sel_towers_u-> rowCount() == 0){
$sel_towers_a = $pdo->prepare("SELECT * FROM `towers` WHERE `battle_id` = :battle_id AND `user_t_health` > 0 AND `user_id` != :user_id AND `group` != :group");
$sel_towers_a->execute(array(':battle_id' => $towers_me['battle_id'], ':user_id' => $user['id'], ':group' => $towers_me['group']));
if($sel_towers_a-> rowCount() != 0){
$towers_a = $sel_towers_a->fetch(PDO::FETCH_LAZY);
#-Запись другого оппонента-#
$upd_towers_me = $pdo->prepare("UPDATE `towers` SET `ank_id` = :ank_id WHERE `user_id` = :user_id LIMIT 1");	
$upd_towers_me->execute(array(':ank_id' => $towers_a['user_id'], ':user_id' => $user['id']));	
}
}
	
#-Данные оппонента-#
$sel_users_op = $pdo->prepare("SELECT `id`, `avatar`, `pol`, `nick`, `level`, `sila`, `s_sila`, `sila_bonus`, `zashita`, `s_zashita`, `zashita_bonus`, `health`, `s_health`, `health_bonus` FROM `users` WHERE `id` = :id");
$sel_users_op->execute(array(':id' => $towers_me['ank_id']));
$all = $sel_users_op->fetch(PDO::FETCH_LAZY);
$sel_towers_all = $pdo->prepare("SELECT `id`, `user_id`, `ank_id`, `user_health`, `user_t_health` FROM `towers` WHERE `user_id` = :ank_id");
$sel_towers_all->execute(array(':ank_id' => $towers_me['ank_id']));
$towers_all = $sel_towers_all->fetch(PDO::FETCH_LAZY);

echo'<div class="block_hunting">';
#-Оппонент-#
echo"<img src='".avatar_img_min($all['avatar'], $all['pol'])."' class='img_h_battle' width='50' height='50' alt=''/><div class='block_monsters'><img src='/style/images/user/user.png' alt=''/><b>$all[nick]</b> <span style='font-size: 13px;'>[$all[level] ур.]</span><br/><div class='param_monsters'><img src='/style/images/user/sila.png' alt=''/>".($all['sila']+$all['s_sila']+$all['sila_bonus'])." <img src='/style/images/user/zashita.png' alt=''/>".($all['zashita']+$all['s_zashita']+$all['zashita_bonus'])." <img src='/style/images/user/health.png' alt=''/>$towers_all[user_t_health]</div></div>";
echo"<div class='hp_bar_monster'><div class='health2' style='width:".round(100/(($all['health']+$all['s_health']+$all['health_bonus'])/$towers_all['user_health']))."%'><div class='health' style='width:".round(100/($towers_all['user_health']/$towers_all['user_t_health']))."%'></div></div></div>";

#-Оружие-#
echo'<div style="padding-top: 10px;"></div>';
echo'<div class="line_1"></div>';
echo'<div class="body_list">';
echo'<div style="padding-top: 3px;"></div>';
#-Выборка оружия для боя которое сейчас надето-#
$sel_weapon_me = $pdo->prepare("SELECT * FROM `weapon_me` WHERE `user_id` = :user_id AND `type` = :type AND `state` = :state");
$sel_weapon_me->execute(array(':user_id' => $user['id'], ':type' => 5, ':state' => 1)); 
$weapon_me = $sel_weapon_me->fetch(PDO::FETCH_LAZY);
#-Если есть оружие-#
if($sel_weapon_me-> rowCount() != 0){
#-Выборка данных о оружие-#
$sel_weapon = $pdo->prepare("SELECT * FROM `weapon` WHERE `id` = :id");
$sel_weapon->execute(array(':id' => $weapon_me['weapon_id'])); 
$weapon = $sel_weapon->fetch(PDO::FETCH_LAZY);
echo"<center><a href='/towers_attack?act=attc'><img src='$weapon[images]' class='".ramka($weapon_me['max_level'])."' alt=''/></a></center>";
}else{
echo"<center><a href='/towers_attack?act=attc'><img src='/style/images/weapon/arm/0_0.png' class='weapon_me' alt=''/></a></center>";
}
echo'<div style="padding-top: 3px;"></div>';	
echo'</div>';
echo'<div class="line_1"></div>';

#-Герой-#
echo"<img src='".avatar_img_min($user['avatar'], $user['pol'])."' class='img_h_battle' width='50' height='50' alt=''/><div class='block_monsters'><img src='/style/images/user/user.png' alt=''/><span class='name_monsters'>$user[nick]</span><br/><div class='param_monsters'><img src='/style/images/user/sila.png' alt=''/>".($user['sila']+$user['s_sila']+$user['sila_bonus'])." <img src='/style/images/user/zashita.png' alt=''/>".($user['zashita']+$user['s_zashita']+$user['zashita_bonus'])." <img src='/style/images/user/health.png' alt=''/>$towers_me[user_t_health]</div></div>";
echo"<div class='hp_bar_users'><div class='health2' style='width:".round(100/(($user['health']+$user['s_health']+$user['health_bonus'])/$towers_me['user_health']))."%'><div class='health' style='width:".round(100/($towers_me['user_health']/$towers_me['user_t_health']))."%'></div></div></div>";
echo'<div style="padding-top: 10px;"></div>';
echo'</div>';

#-Лог событий-#
$sel_towers_l = $pdo->prepare("SELECT * FROM `towers_log` WHERE `battle_id` = :battle_id ORDER BY `time` DESC LIMIT 5");
$sel_towers_l->execute(array(':battle_id' => $towers_me['battle_id']));
if($sel_towers_l-> rowCount() != 0){
echo'<div class="line_1"></div>';
while($towers_l = $sel_towers_l->fetch(PDO::FETCH_LAZY))  
{
echo'<div class="body_list"><div style="padding: 2px;padding-left: 5px;">';
if($towers_l['group'] == $towers_me['group']){
echo"<span class='green'> $towers_l[log]</span>";
}else{
echo"<span class='red'> $towers_l[log]</span>";
}
echo'</div></div>';	
}
}	
	
}else{
#-Статистика боя-#
echo'<div class="body_list">';
#-Выборка всех участников сражения и их результаты-#
$sel_towers_u = $pdo->prepare("SELECT * FROM `towers` WHERE `battle_id` = :battle_id ORDER BY `group`");
$sel_towers_u->execute(array(':battle_id' => $towers_me['battle_id']));
while($towers_u = $sel_towers_u->fetch(PDO::FETCH_LAZY)){
#-Выборка данных игрока-#	
$sel_users = $pdo->prepare("SELECT `id`, `avatar`, `pol`, `nick`, `level`, `time_online` FROM `users` WHERE `id` = :user_id");
$sel_users->execute(array(':user_id' => $towers_u['user_id']));	
$all = $sel_users->fetch(PDO::FETCH_LAZY);
echo'<div class="line_1"></div>';
echo'<div class="menulitl">';
echo"<li><a href='/hero/$all[id]'> <img src='".avatar_img_min($all['avatar'], $all['pol'])."' class='menulitl_img' width='30' height='30' alt=''/><div class='menulitl_block'>".online($all['time_online'])." <span class='menulitl_name'>".($towers_me['group'] == $towers_u['group'] ? "<span class='green'>$all[nick]</span>" : "<span class='red'>$all[nick]</span>")."</span> ".($towers_u['user_t_health'] == 0 ? '<span class="red">Мертв(а)</span>' : '')."<br/><div class='menulitl_param'>".($towers_u['user_t_health'] > 0 ? '<img src="/style/images/user/health.png" alt=""/>'.$towers_u['user_t_health'].'' : '<img src="/style/images/user/health.png" alt=""/>0')." <img src='/style/images/user/exp.png' alt=''/>$towers_u[exp] ".($towers_u['type'] == 'gold' ? "<img src='/style/images/many/gold.png' alt=''/>+$towers_u[many]" : "<img src='/style/images/many/silver.png' alt=''/>+$towers_u[many]")."</div></div></a></li>";
echo'</div>';
}
echo'</div>';
echo'<div class="line_1"></div>';
echo'<div style="padding-top: 3px;"></div>';
#-Есть ли еще игроки которые в бою-#
$sel_towers_b = $pdo->prepare("SELECT * FROM `towers` WHERE `battle_id` = :battle_id AND `statys` = 2");
$sel_towers_b->execute(array(':battle_id' => $towers_me['battle_id']));
if($sel_towers_b-> rowCount() != 0){
echo'<a href="/towers" class="button_green_a">Обновить</a>';
}else{
echo'<a href="/towers_exit?act=exit_stk" class="button_red_a">Покинуть</a>';	
}
echo'<div style="padding-top: 3px;"></div>';

echo'<div class="body_list">';
echo'<div class="line_1"></div>';
echo'<div class="info_list"><img src="/style/images/body/imp.png" alt=""/> На сражание дается <img src="/style/images/body/time.png" alt=""/>10 минут</div>';
echo'</div>';
}
}
}
echo'</div>';
require_once H.'system/footer.php';
?>