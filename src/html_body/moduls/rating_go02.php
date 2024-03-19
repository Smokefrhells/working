<?php
require_once '../../system/system.php';
$head = 'Зал славы';
echo only_reg();
require_once H.'system/head.php';
$all_param = $user['param'];
$all_id = $user['id'];
$all_pobeda_h = $user['hunting_pobeda'];
$all_progrash_h = $user['hunting_progrash'];
$all_pobeda_d = $user['duel_pobeda'];
$all_progrash_d = $user['duel_progrash'];
$all_pobeda_b = $user['boss_pobeda'];
$all_progrash_b = $user['boss_progrash'];
$all_tasks = $user['tasks'];
require_once H.'system/game/rating.php';
echo'<div class="page">';
echo'<div class="body_list">';

#-Навигация-#
$param = '<a href="/rating?type=1" style="text-decoration:none;"><span class="body_sel">'.(($_GET['type'] == 1 or $_GET['type'] > 5 or !isset($_GET['type']))  ? "<b>Параметры</b> <img src='/style/images/body/rating.png' alt=''/>$mesto_param" : "Параметры").'</span></a>';
$hunting = '<a href="/rating?type=2" style="text-decoration:none;"><span class="body_sel">'.($_GET['type'] == 2 ? "<b>Охота</b> <img src='/style/images/body/rating.png' alt=''/>$mesto_hunting" : "Охота").'</span></a>';
$duel = '<a href="/rating?type=3" style="text-decoration:none;"><span class="body_sel">'.($_GET['type'] == 3 ? "<b>Дуэли</b> <img src='/style/images/body/rating.png' alt=''/>$mesto_duel" : "Дуэли").'</span></a>';
$boss = '<a href="/rating?type=4" style="text-decoration:none;"><span class="body_sel">'.($_GET['type'] == 4 ? "<b>Боссы</b> <img src='/style/images/body/rating.png' alt=''/>$mesto_boss" : "Боссы").'</span></a>';
$tasks = '<a href="/rating?type=5" style="text-decoration:none;"><span class="body_sel">'.($_GET['type'] == 5 ? "<b>Задания</b> <img src='/style/images/body/rating.png' alt=''/>$mesto_tasks" : "Задания").'</span></a>';

#-Тип рейтинга-#
$type = check($_GET['type']);
if(empty($type) or $type > 5){
$type = 1;
}

#-Вывод-#
echo'<div class="body_list">';	
echo'<div style="padding: 5px;">';
echo'<center>'.$param.''.$hunting.''.$duel.''.$boss.''.$tasks.'</center>';
echo'</div>';
echo'</div>';

echo'<div class="menulitl">';

#-По параметрам-#
if($_GET['type'] == 1 or !isset($_GET['type']) or ($_GET['type'] != 1 and $_GET['type'] != 2 and $_GET['type'] != 3 and $_GET['type'] != 4 and $_GET['type'] != 5)){
#-Кол-во записей-#
$sel_count_l = $pdo->query("SELECT COUNT(*) FROM `users` WHERE `param` > 0 AND `save` = 1");
$amount = $sel_count_l->fetch(PDO::FETCH_LAZY);
$num = 10;  
$page = $_GET['page'];  
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;$page = intval($page);if(empty($page) or $page < 0) $page = 1;if($page > $total) $page = $total;$start = $page * $num - $num;  
#-Вывод записей-#
$sel_users_l = $pdo->query("SELECT `id`, `param`, `save`, `avatar`, `time_online`, `pol`, `nick` FROM `users` WHERE `param` > 0 AND `save` = 1 ORDER BY `param` DESC, `id` LIMIT $start, $num");
#-Если есть записи-#
if($sel_users_l-> rowCount() != 0){
$t = $start+$amount[0];
for($i = $start; $i <= $t; $i++){
while($all = $sel_users_l->fetch(PDO::FETCH_LAZY))  
{
$i = $i+1;
#-Онлайн-#
$time = time() - 1200;
if($all['time_online'] > $time){
$img_online = '/style/images/user/online.png';
}else{
$img_online = '/style/images/user/offline.png';
}
echo'<div class="line_1"></div>';
echo"<li><a href='/hero/$all[id]'> <img src='".avatar_img_min($all['avatar'], $all['pol'])."' class='menulitl_img' width='30' height='30' alt=''/><div class='menulitl_block'><img src='$img_online'> <span class='menulitl_name'>".($all['id'] == $user['id'] ? "<span class='green'>$all[nick]</span>" : "$all[nick]")."</span><br/><div class='menulitl_param'><img src='/style/images/body/rating.png' alt=''/>$i место <img src='/style/images/body/all.png' alt=''/>$all[param]</div></div></a></li>";
}
}
}
}

#-Охота-#
if($_GET['type'] == 2){
$sel_count_l = $pdo->query("SELECT COUNT(*) FROM `users` WHERE `save` = 1 AND `hunting_pobeda` > 0");
$amount = $sel_count_l->fetch(PDO::FETCH_LAZY);
#-Действия постраничной навигации-#
$num = 10;  
$page = $_GET['page'];  
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;$page = intval($page);if(empty($page) or $page < 0) $page = 1;if($page > $total) $page = $total;$start = $page * $num - $num;  
$sel_users_l = $pdo->query("SELECT `id`, `hunting_pobeda`, `hunting_progrash`, `save`, `avatar`, `time_online`, `pol`, `nick` FROM `users` WHERE `save` = 1 AND `hunting_pobeda` > 0 ORDER BY `hunting_pobeda` DESC, `hunting_progrash` LIMIT $start, $num");
#-Если есть записи-#
if($sel_users_l-> rowCount() != 0){
$t = $start+$amount[0];
for($i = $start; $i <= $t; $i++){
while($all = $sel_users_l->fetch(PDO::FETCH_LAZY))  
{
#-Онлайн-#
$time = time() - 1200;
if($all['time_online'] > $time){
$img_online = '/style/images/user/online.png';
}else{
$img_online = '/style/images/user/offline.png';
}
$i = $i+1;
echo'<div class="line_1"></div>';
echo"<li><a href='/hero/$all[id]'> <img src='".avatar_img_min($all['avatar'], $all['pol'])."' class='menulitl_img' width='30' height='30' alt=''/><div class='menulitl_block'><img src='$img_online'> <span class='menulitl_name'>".($all['id'] == $user['id'] ? "<span class='green'>$all[nick]</span>" : "$all[nick]")."</span><br/><div class='menulitl_param'><img src='/style/images/body/rating.png' alt=''/>$i место <img src='/style/images/body/ok.png' alt=''/>$all[hunting_pobeda] <img src='/style/images/body/error.png' alt=''/>$all[hunting_progrash]</div></div></a></li>";
}
}
}
}

#-Дуэли-#
if($_GET['type'] == 3){
$sel_count_l = $pdo->query("SELECT COUNT(*) FROM `users` WHERE `save` = 1 AND `duel_pobeda` > 0");
$amount = $sel_count_l->fetch(PDO::FETCH_LAZY);
#-Действия постраничной навигации-#
$num = 10;  
$page = $_GET['page'];  
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;$page = intval($page);if(empty($page) or $page < 0) $page = 1;if($page > $total) $page = $total;$start = $page * $num - $num;  
$sel_users_l = $pdo->query("SELECT `id`, `duel_pobeda`, `duel_progrash`, `save`, `avatar`, `time_online`, `pol`, `nick` FROM `users` WHERE `save` = 1 AND `duel_pobeda` > 0 ORDER BY `duel_pobeda` DESC, `duel_progrash` LIMIT $start, $num");
#-Если есть записи-#
if($sel_users_l-> rowCount() != 0){
$t = $start+$amount[0];
for($i = $start; $i <= $t; $i++){
while($all = $sel_users_l->fetch(PDO::FETCH_LAZY))  
{
#-Онлайн-#
$time = time() - 1200;
if($all['time_online'] > $time){
$img_online = '/style/images/user/online.png';
}else{
$img_online = '/style/images/user/offline.png';
}
$i = $i+1;
echo'<div class="line_1"></div>';
echo"<li><a href='/hero/$all[id]'> <img src='".avatar_img_min($all['avatar'], $all['pol'])."' class='menulitl_img' width='30' height='30' alt=''/><div class='menulitl_block'><img src='$img_online'> <span class='menulitl_name'>".($all['id'] == $user['id'] ? "<span class='green'>$all[nick]</span>" : "$all[nick]")."</span><br/><div class='menulitl_param'><img src='/style/images/body/rating.png' alt=''/>$i место <img src='/style/images/body/ok.png' alt=''/>$all[duel_pobeda] <img src='/style/images/body/error.png' alt=''/>$all[duel_progrash]</div></div></a></li>";
}
}
}
}

#-Боссы-#
if($_GET['type'] == 4){
$sel_count_l = $pdo->query("SELECT COUNT(*) FROM `users` WHERE `save` = 1 AND `boss_pobeda` > 0");
$amount = $sel_count_l->fetch(PDO::FETCH_LAZY);
#-Действия постраничной навигации-#
$num = 10;  
$page = $_GET['page'];  
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;$page = intval($page);if(empty($page) or $page < 0) $page = 1;if($page > $total) $page = $total;$start = $page * $num - $num;  
$sel_users_l = $pdo->query("SELECT `id`, `boss_pobeda`, `boss_progrash`, `save`, `avatar`, `time_online`, `pol`, `nick` FROM `users` WHERE `save` = 1 AND `boss_pobeda` > 0 ORDER BY `boss_pobeda` DESC, `boss_progrash` LIMIT $start, $num");
#-Если есть записи-#
if($sel_users_l-> rowCount() != 0){
$t = $start+$amount[0];
for($i = $start; $i <= $t; $i++){
while($all = $sel_users_l->fetch(PDO::FETCH_LAZY))  
{
#-Онлайн-#
$time = time() - 1200;
if($all['time_online'] > $time){
$img_online = '/style/images/user/online.png';
}else{
$img_online = '/style/images/user/offline.png';
}
$i = $i+1;
echo'<div class="line_1"></div>';
echo"<li><a href='/hero/$all[id]'> <img src='".avatar_img_min($all['avatar'], $all['pol'])."' class='menulitl_img' width='30' height='30' alt=''/><div class='menulitl_block'><img src='$img_online'> <span class='menulitl_name'>".($all['id'] == $user['id'] ? "<span class='green'>$all[nick]</span>" : "$all[nick]")."</span><br/><div class='menulitl_param'><img src='/style/images/body/rating.png' alt=''/>$i место <img src='/style/images/body/ok.png' alt=''/>$all[boss_pobeda] <img src='/style/images/body/error.png' alt=''/>$all[boss_progrash]</div></div></a></li>";
}
}
}
}

#-Задания-#
if($_GET['type'] == 5){
$sel_count_l = $pdo->query("SELECT COUNT(*) FROM `users` WHERE `save` = 1 AND `tasks` > 0");
$amount = $sel_count_l->fetch(PDO::FETCH_LAZY);
#-Действия постраничной навигации-#
$num = 10;  
$page = $_GET['page'];  
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;$page = intval($page);if(empty($page) or $page < 0) $page = 1;if($page > $total) $page = $total;$start = $page * $num - $num;  
$sel_users_l = $pdo->query("SELECT `id`, `tasks`, `save`, `avatar`, `time_online`, `pol`, `nick` FROM `users` WHERE `save` = 1 AND `tasks` > 0 ORDER BY `tasks` DESC, `id` LIMIT $start, $num");
#-Если есть записи-#
if($sel_users_l-> rowCount() != 0){
$t = $start+$amount[0];
for($i = $start; $i <= $t; $i++){
while($all = $sel_users_l->fetch(PDO::FETCH_LAZY))  
{
#-Онлайн-#
$time = time() - 1200;
if($all['time_online'] > $time){
$img_online = '/style/images/user/online.png';
}else{
$img_online = '/style/images/user/offline.png';
}
$i = $i+1;
echo'<div class="line_1"></div>';
echo"<li><a href='/hero/$all[id]'> <img src='".avatar_img_min($all['avatar'], $all['pol'])."' class='menulitl_img' width='30' height='30' alt=''/><div class='menulitl_block'><img src='$img_online'> <span class='menulitl_name'>".($all['id'] == $user['id'] ? "<span class='green'>$all[nick]</span>" : "$all[nick]")."</span><br/><div class='menulitl_param'><img src='/style/images/body/rating.png' alt=''/>$i место <img src='/style/images/body/ok.png' alt=''/>$all[tasks]</div></div></a></li>";
}
}
}
}
echo'</div>';

#-Отображение постраничной навигации-#
if($posts > $num){
if(!isset($_GET['type'])){$type = 1;}else{$type = $_GET['type'];}
$action = "&type=$type";
echo'<div class="line_1"></div>';
$z = pages($posts,$total,$action);
}
echo'</div>';
require_once H.'system/footer.php';
?>