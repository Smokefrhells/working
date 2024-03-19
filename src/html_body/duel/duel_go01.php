<?php
require_once '../../system/system.php';
$head = 'Дуэли';
echo only_reg();
echo duel_level();
require_once H.'system/head.php';
echo'<div class="page">';
echo'<img src="/style/images/location/duel.jpg" class="img"/>';
#-В бою или нет-#
$sel_duel = $pdo->prepare("SELECT * FROM `duel_battle` WHERE `user_id` = :user_id");
$sel_duel->execute(array(':user_id' => $user['id']));
if($sel_duel-> rowCount() == 0){
#-В бою или нет-#
$sel_duel_o = $pdo->prepare("SELECT * FROM `duel_online` WHERE `user_id` = :user_id OR `ank_id` = :user_id");
$sel_duel_o->execute(array(':user_id' => $user['id']));
if($sel_duel_o-> rowCount() == 0){
#-Проверяем стоит время или нет-#
$sel_duel_t = $pdo->prepare("SELECT * FROM `duel_time` WHERE `user_id` = :user_id");
$sel_duel_t->execute(array(':user_id' => $user['id']));
echo'<div style="padding-top: 5px;"></div>';
if($sel_duel_t-> rowCount() == 0){
echo"<a href='/duel_act?act=start' class='button_red_a'>Оффлайн режим</a>";	
echo'<div style="padding-top: 3px;"></div>';
echo"<a href='/duel_act?act=start_online' class='button_green_a'>Онлайн режим</a>";	
echo'<div style="padding-top: 5px;"></div>';
}else{
$duel_time = $sel_duel_t->fetch(PDO::FETCH_LAZY);
#-Сколько времени осталось-#
$duel_ostatok = $duel_time['duel_time']-time();
$battle_d = floor($user['level']/2);
#-Золото за ускорение времени-#
$min = round(((($duel_ostatok/60%60) * 60) /85) + $battle_d, 0);
if($min < 1){
$gold_time = 1;
}else{
$gold_time = round($min, 0);
}

if($duel_time['duel_time'] >= time()){
if(!isset($_GET['accel'])){
echo'<center><a href="/duel?accel=1" class="button_green_a"><img src="/style/images/body/time.png" alt=""/>'.(int)($duel_ostatok/60%60).' мин. '.($duel_ostatok%60).' сек.</a></center>';
echo'<div style="padding-top: 5px;"></div>';
}else{
echo'<center><a href="/buy_duel?act=accel" class="button_green_a">Ускорить за <img src="/style/images/many/gold.png" alt=""/>'.$gold_time.'</a></center>';
echo'<div style="padding-top: 3px;"></div>';
echo'<center><a href="/duel" class="button_red_a">Отменить</a></center>';	
echo'<div style="padding-top: 5px;"></div>';
}	
}
}
}else{
$duel_online = $sel_duel_o->fetch(PDO::FETCH_LAZY);
if($duel_online['time'] >= time()){
$oshered_time = $duel_online['time']-time();
#-Проверяем какой статус-#
if($duel_online['statys'] == 0){
echo'<div style="padding-top: 5px;"></div>';
echo'<center><a href="" class="button_green_a"><img src="/style/images/body/time.png" alt=""/>Очередь: '.(int)($oshered_time/60%60).' мин. '.($oshered_time%60).' сек.</a></center>';
echo'<div style="padding-top: 3px;"></div>';
echo'<center><a href="/duel_act?act=exit_oshered" class="button_red_a">Покинуть очередь</a></center>';	
echo'<div style="padding-top: 5px;"></div>';
}else{
echo'<div style="padding-top: 5px;"></div>';
echo'<center><a href="/duel_online" class="button_green_a"><img src="/style/images/body/time.png" alt=""/>Начало через: '.($oshered_time%60).' сек.</a></center>';
echo'<div style="padding-top: 5px;"></div>';
}
}else{
echo'<div style="padding-top: 3px;"></div>';
echo'<center><a href="/duel_online" class="button_red_a">Продолжить бой</a></center>';	
echo'<div style="padding-top: 5px;"></div>';	
}
}
}else{
echo'<div style="padding-top: 3px;"></div>';
echo'<center><a href="/duel_battle" class="button_red_a">Продолжить бой</a></center>';	
echo'<div style="padding-top: 5px;"></div>';
}

//Если нет боя то показываем предыдущий бой
echo'<div class="line_1_m"></div>';
echo'<div class="body_list">';
echo'<div style="padding: 3px;">';
echo'<span class="gray">';
#-Выборка данных последнего боя-#
$sel_log = $pdo->prepare("SELECT * FROM `event_log` WHERE `user_id` = :user_id AND `type` = 4 ORDER BY `time` DESC LIMIT 1");
$sel_log->execute(array(':user_id' => $user['id']));
#-Если есть такой бой-#
if($sel_log->rowCount() != 0){
$event = $sel_log->fetch(PDO::FETCH_LAZY);
echo"<a href='/duel_history' style='text-decoration: none;'><img src='/style/images/body/league.png' alt=''/>$event[log]</a>";
}else{ //Если нет
echo'<div class="svg_list"><img src="/style/images/body/error.png" alt=""/>Данных о бое нет!</div>';
}
echo'</span>';
echo'</div>';
echo'</div>';


echo'<div class="line_1"></div>';
echo'<div class="body_list">';
echo'<div class="info_list">';
echo"<img src='/style/images/body/ok.png' alt=''/><span class='green'>$user[duel_pobeda]</span> <img src='/style/images/body/error.png' alt=''/><span class='red'>$user[duel_progrash]</span>";
echo'</div>';
echo'</div>';
echo'</div>';
require_once H.'system/footer.php';
?>