<?php
require_once '../../system/system.php';
echo only_reg();
echo clan_level();
$head = 'Клановые боссы';
require_once H.'system/head.php';

#-Проверка что игрок состоит в клане-#
$sel_clan_u = $pdo->prepare("SELECT * FROM `clan_users` WHERE `user_id` = :user_id");
$sel_clan_u->execute(array(':user_id' => $user['id']));
if($sel_clan_u-> rowCount() != 0){
$clan_u = $sel_clan_u->fetch(PDO::FETCH_LAZY);
#-Проверка существования клана-#
$sel_clan = $pdo->prepare("SELECT * FROM `clan` WHERE `id` = :clan_id");
$sel_clan->execute(array(':clan_id' => $clan_u['clan_id']));
if($sel_clan-> rowCount() != 0){
$clan = $sel_clan->fetch(PDO::FETCH_LAZY);

#-Клановые боссы время-#
$sel_clan_boss_b = $pdo->prepare("SELECT `id`, `clan_id`, `boss_id`, `time` FROM `clan_boss_battle` WHERE `time` < :time AND `clan_id` = :clan_id");
$sel_clan_boss_b->execute(array(':time' => time(), ':clan_id' => $clan['id']));
if($sel_clan_boss_b-> rowCount() != 0){
while($clan_boss_b = $sel_clan_boss_b->fetch(PDO::FETCH_LAZY)){
#-Выборка данных босса-#	
$sel_clan_boss = $pdo->prepare("SELECT `id`, `name`, `time_otduh` FROM `clan_boss` WHERE `id` = :boss_id");
$sel_clan_boss->execute(array(':boss_id' => $clan_boss_b['boss_id']));
$clan_boss = $sel_clan_boss->fetch(PDO::FETCH_LAZY);
#-Установка времени-#
$ins_clan_boss_time = $pdo->prepare("INSERT INTO `clan_boss_time` SET `clan_id` = :clan_id, `boss_id` = :boss_id, `time` = :time");
$ins_clan_boss_time->execute(array(':clan_id' => $clan_boss_b['clan_id'], ':boss_id' => $clan_boss['id'], ':time' => time()+$clan_boss['time_otduh']));
#-Удаление боя-#
$del_c_boss_battle = $pdo->prepare("DELETE FROM `clan_boss_battle` WHERE `clan_id` = :clan_id");
$del_c_boss_battle->execute(array(':clan_id' => $clan_boss_b['clan_id']));
#-Удаление лога-#
$del_c_boss_battle_u = $pdo->prepare("DELETE FROM `clan_boss_battle_u` WHERE `clan_id` = :clan_id");
$del_c_boss_battle_u->execute(array(':clan_id' => $clan_boss_b['clan_id']));
#-История клана-#
$ins_clan_l = $pdo->prepare("INSERT INTO `clan_log` SET `type` = :type, `log` = :log, `clan_id` = :clan_id, `time` = :time");
$ins_clan_l ->execute(array(':type' => 11, ':log' => "Время сражения с боссом $clan_boss[name] вышло", ':clan_id' => $clan_boss_b['clan_id'], ':time' => time())); 
}
}
#-Удаление отката времени-#
$del_clan_boss_t = $pdo->prepare("DELETE FROM `clan_boss_time` WHERE `time` < :time AND `clan_id` = :clan_id");
$del_clan_boss_t->execute(array(':time' => time(), ':clan_id' => $clan['id']));

#-Боссы с 10 уровня клана-#
if($clan['level'] >= 10){
echo'<div class="page">';

#-Бой или нет-#
$sel_boss_battle = $pdo->prepare("SELECT * FROM `clan_boss_battle` WHERE `clan_id` = :clan_id");
$sel_boss_battle->execute(array(':clan_id' => $clan['id']));
if($sel_boss_battle-> rowCount() == 0){

#-Выборка боссов-#
$sel_clan_boss = $pdo->query("SELECT * FROM `clan_boss`");	
while($clan_boss = $sel_clan_boss->fetch(PDO::FETCH_LAZY)){
#-Ключи-#	
if($clan_boss['id'] == 1){$key = 'key_1';}
if($clan_boss['id'] == 2){$key = 'key_2';$key_p = 'key_1';}
if($clan_boss['id'] == 3){$key = 'key_3';$key_p = 'key_2';}
if($clan_boss['id'] == 4){$key = 'key_4';$key_p = 'key_3';}
if($clan_boss['id'] == 5){$key = 'key_5';$key_p = 'key_4';}
	
echo'<div class="t_max">';
echo'<img src="'.$clan_boss['images'].'" class="t_img" width="50" height="50" alt=""/>';
echo'<div class="t_name"><img src="/style/images/clan/boss.png" alt=""/><span class="yellow">'.$clan_boss['name'].'</span><br/><span class="t_param"><img src="/style/images/user/zashita.png" alt=""/>'.$clan_boss['zashita'].' <img src="/style/images/user/health.png" alt=""/>'.$clan_boss['health'].'<br/><img src="/style/images/clan/'.$key.'.png" alt=""/>Ключей: '.$clan[$key].'</span></div>';
echo'</div>';
#-Начало боя-#
$sel_clan_boss_t = $pdo->prepare("SELECT * FROM `clan_boss_time` WHERE `clan_id` = :clan_id AND `boss_id` = :boss_id");
$sel_clan_boss_t->execute(array(':clan_id' => $clan['id'], ':boss_id' => $clan_boss['id']));
if($sel_clan_boss_t-> rowCount() == 0){
if($clan_u['prava'] == 3 or $clan_u['prava'] == 4){
echo'<center><a href="/clan_boss_act?act=start&boss_id='.$clan_boss['id'].'&clan_id='.$clan['id'].'" class="button_green_a">Напасть '.($clan_boss['id'] == 1 ? "" : "за <img src='/style/images/clan/$key_p.png' alt=''/>2").'</a></center>';		
}
}else{
$clan_boss_t = $sel_clan_boss_t->fetch(PDO::FETCH_LAZY);
echo'<center><div class="button_red_a"><img src="/style/images/body/time.png" alt=""/>'.timers($clan_boss_t['time']-time()).'</div></center>';		
}
}
echo'<div style="padding-top: 3px;"></div>';
}else{
$boss_battle = $sel_boss_battle->fetch(PDO::FETCH_LAZY);	
#-Выборка босса-#
$sel_clan_boss = $pdo->prepare("SELECT * FROM `clan_boss` WHERE `id` = :boss_id");		
$sel_clan_boss->execute(array(':boss_id' => $boss_battle['boss_id']));
$clan_boss = $sel_clan_boss->fetch(PDO::FETCH_LAZY);
echo'<div class="t_max">';
echo'<img src="'.$clan_boss['images'].'" class="t_img" width="50" height="50" alt=""/>';
echo'<div class="t_name"><img src="/style/images/clan/boss.png" alt=""/><span class="yellow">'.$clan_boss['name'].'</span><br/><span class="t_param"><img src="/style/images/user/zashita.png" alt=""/>'.$clan_boss['zashita'].' <img src="/style/images/user/health.png" alt=""/>'.$boss_battle['boss_t_health'].'<br/></span></div>';
echo'</div>';

#-Атака-#
$sel_boss_battle_u = $pdo->prepare("SELECT * FROM `clan_boss_battle_u` WHERE `clan_id` = :clan_id AND `user_id` = :user_id AND `time` > :time ORDER BY `id` DESC LIMIT 1");
$sel_boss_battle_u->execute(array(':clan_id' => $clan['id'], ':user_id' => $user['id'], ':time' => time()));
if($sel_boss_battle_u-> rowCount() != 0){
$boss_battle_u = $sel_boss_battle_u->fetch(PDO::FETCH_LAZY);
echo'<center><a href="/clan_boss" class="button_red_a"><img src="/style/images/body/time.png" alt=""/>'.timers($boss_battle_u['time']-time()).'</a></center>';		
}else{
echo'<center><a href="/clan_boss_act?act=attc&boss_id='.$clan_boss['id'].'&clan_id='.$clan['id'].'" class="button_red_a">Атаковать</a></center>';		
}

echo'<div style="padding-top: 3px;"></div>';
echo'</div>';

echo'<div class="body_list">';
#-Лог событий-#
$sel_boss_l = $pdo->prepare("SELECT * FROM `clan_boss_battle_u` WHERE `clan_id` = :clan_id ORDER BY `id` DESC LIMIT 5");
$sel_boss_l->execute(array(':clan_id' => $clan['id']));
if($sel_boss_l-> rowCount() != 0){
echo'<div class="line_1"></div>';
while($boss_l = $sel_boss_l->fetch(PDO::FETCH_LAZY))  
{
echo'<div style="padding: 2px;padding-left: 5px;">';
echo"<span class='red'> $boss_l[log]</span>";
echo'</div>';	
}
}
echo'<div class="line_1"></div>';
echo'<div class="info_list"><img src="/style/images/body/time.png" alt=""/>До конца боя: '.timers($boss_battle['time']-time()).'</div>';
echo'</div>';
}
}else{
echo'<div class="error_list"><img src="/style/images/body/error.png" alt=""/>Клановые боссы с 10 уровня клана!</div>';	
}
}else{
echo'<div class="error_list"><img src="/style/images/body/error.png" alt=""/>Клан не найден!</div>';		
}
}else{
echo'<div class="error_list"><img src="/style/images/body/error.png" alt=""/>Вы не состоите в этом клане!</div>';	
}
require_once H.'system/footer.php';
?>