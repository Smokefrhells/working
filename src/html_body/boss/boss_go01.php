<?php
require_once '../../system/system.php';
$head = 'Боссы';
echo only_reg();
echo boss_level();
echo boss_campaign();
require_once H.'system/head.php';
echo'<div class="page">';
echo'<img src="/style/images/location/boss.jpg" class="img"/>';
#-Делаем проверку в бою сейчас или нет-#
$sel_boss_u = $pdo->prepare("SELECT * FROM `boss_users` WHERE `user_id` = :user_id");
$sel_boss_u->execute(array(':user_id' => $user['id']));
if($sel_boss_u-> rowCount() == 0){

#-Верхнеее меню-#
$normal = '<a href="/boss?type=1" style="text-decoration:none;"><span class="body_sel">'.(($_GET['type'] == 1 or $_GET['type'] > 3 or !isset($_GET['type']))  ? "<b>Обычные</b>" : "Обычные").'</span></a>';
$elite = '<a href="/boss?type=2" style="text-decoration:none;"><span class="body_sel">'.($_GET['type'] == 2 ? "<b>Элитные</b>" : "Элитные").'</span></a>';
$legendar = '<a href="/boss?type=3" style="text-decoration:none;"><span class="body_sel">'.($_GET['type'] == 3 ? "<b>Легендарные</b>" : "Легендарные").'</span></a>';
//$celebration = '<a href="/boss?type=4" style="text-decoration:none;"><span class="body_sel">'.($_GET['type'] == 4 ? "<b>Праздничные</b>" : "Праздничные").'</span></a>';
#-Тип боссов-#
$type = check($_GET['type']);
if(empty($type) or $type > 4){
$type = 1;
}
#-Вывод-#
echo'<div class="body_list">';	
echo'<div style="padding: 5px;">';
echo''.$normal.''.$elite.''.$legendar.''.$celebration.'';
echo'</div>';
echo'</div>';

echo'<div class="line_1_m"></div>';
#-Делаем выборку боссов-#
$sel_boss = $pdo->prepare("SELECT * FROM `boss` WHERE `type` = :type");
$sel_boss->execute(array(':type' => $type));
while($boss = $sel_boss->fetch(PDO::FETCH_LAZY)){
#-Цвет и иконка-#
if($boss['type'] == 1){$color = '#d1d1d1';$img = 1;}
if($boss['type'] == 2){$color = '#9b71b1';$img = 2;}
if($boss['type'] == 3){$color = '#ff0000';$img = 3;}
if($boss['type'] == 4){$color = '#048bbd';$img = 4;}
#-Уровень Босса и наш-#
if($user['level'] >= $boss['level']){
echo"<img src='$boss[images]' class='img_m_battle' alt=''/><div class='block_monsters'><img src='/style/images/monstru/quality/$img.png' alt=''/><span style='color: $color'><b>$boss[name]</b> [$boss[level]]</span><br/><div class='param_monsters'><img src='/style/images/user/sila.png' alt=''/>$boss[sila] <img src='/style/images/user/zashita.png' alt=''/>$boss[zashita] <img src='/style/images/user/health.png' alt=''/>$boss[health]</div></div><br/>";
echo'<div style="margin-top: -5px;"></div>';

#-Проверяем на отдыхе или нет-#
$sel_boss_t = $pdo->prepare("SELECT * FROM `boss_time` WHERE `boss_id` = :boss_id AND `user_id` = :user_id  AND `type` = :type");
$sel_boss_t->execute(array(':boss_id' => $boss['id'], ':user_id' => $user['id'], ':type' => 2));
#-Если есть время-#
if($sel_boss_t-> rowCount() != 0){
$boss_t = $sel_boss_t->fetch(PDO::FETCH_LAZY);
$boss_ostatok = $boss_t['time']-time(); //Высчитываем сколько осталось времени

#-Золото за ускорение времени-#
$hour = floor($boss_ostatok/3600);
if($hour <= 0){
$min = round((($boss_ostatok/60%60) * 60) /85, 0);
if($min < 1){
$gold_time = 1;
}else{
$gold_time = round($min, 0);
}
}else{
$minut = ($boss_ostatok/60%60) * 60;
$hou = ($hour * 3600);
$summa = round($minut+$hou, 0);
$gold_time = round($summa / 85, 0);
}
#-Если время отдыха больше чем время сервера-#
if($boss_t['time'] >= time()){
if(isset($_GET['accel']) and $_GET['id'] == $boss['id']){
echo'<center><a href="/buy_boss?act=accel&id='.$boss['id'].'" class="button_green_a">Ускорить за <img src="/style/images/many/gold.png"/>'.$gold_time.'</a></center>';
echo'<div style="padding-top: 3px;"></div>';
echo'<center><a href="/boss?type='.$boss['type'].'" class="button_red_a">Отменить</a></center>';	
echo'<div style="padding-top: 3px;"></div>';	
}else{
echo'<center><a href="/boss?type='.$boss['type'].'&accel=1&id='.$boss['id'].'" class="button_red_a"><img src="/style/images/body/time.png"/>'.timers($boss_ostatok).'</a></center>';
echo'<div style="padding-top: 5px;"></div>';
}
}
}else{
echo"<a href='/start_boss?act=atk&id=$boss[id]' class='button_green_a'>Напасть ".(($user['start'] == 6 and $boss['id'] == 1) ? "<img src='/style/images/body/left_mini.gif' alt=''/>" : "")."</a>";	
}

}else{ //Если наш уровень меньше чем Босса
echo"<span style='opacity: .5;'><img src='$boss[images]' class='img_h_battle' alt=''/><div class='block_monsters'><img src='/style/images/monstru/quality/$img.png' alt=''/><span style='color: $color'><b>$boss[name]</b> [$boss[level]]</span><br/><div class='param_monsters'><img src='/style/images/user/sila.png' alt=''/>$boss[sila] <img src='/style/images/user/zashita.png' alt=''/>$boss[zashita] <img src='/style/images/user/health.png' alt=''/>$boss[health]</div></div></span><br/>";
echo'<div style="margin-top: -5px;"></div>';	
}
}
#-Если сейчас в бою с Боссом-#
}else{
echo'<div style="padding-top: 5px;"></div>';
echo"<a href='/boss_battle' class='button_red_a'>Продолжить бой</a>";
}
echo'<div style="padding-top: 5px;"></div>';
//Если нет боя то показываем предыдущий бой
echo'<div class="line_1_m"></div>';
echo'<div class="body_list">';
echo'<div class="menulist">';
echo'<span class="gray">';
#-Выборка данных последнего боя-#
$sel_log = $pdo->prepare("SELECT * FROM `event_log` WHERE `user_id` = :user_id AND `type` = 3 ORDER BY `time` DESC LIMIT 1");
$sel_log->execute(array(':user_id' => $user['id']));
#-Если есть такой бой-#
if($sel_log->rowCount() != 0){
$event = $sel_log->fetch(PDO::FETCH_LAZY);
echo"<li><a href='/boss_history' style='text-decoration: none;color:#bfbfbf;'><img src='/style/images/body/bos.png' alt=''/>$event[log]</a></li>";
}else{ //Если нет
echo'<div class="svg_list"><img src="/style/images/body/error.png" alt=""/>Данных о бое нет!</div>';
}
echo'</span>';
echo'</div>';
echo'</div>';

echo'<div class="line_1"></div>';
echo'<div class="body_list">';
echo'<div class="info_list">';
echo"<img src='/style/images/body/ok.png' alt=''/><span class='green'>$user[boss_pobeda]</span> <img src='/style/images/body/error.png' alt=''/><span class='red'>$user[boss_progrash]</span>";
echo'</div>';
echo'</div>';

echo'</div>';
require_once H.'system/footer.php';
?>