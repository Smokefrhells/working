<?php
require_once '../../system/system.php';
$head = 'Турнир';
echo only_reg();
require_once H.'system/head.php';
$all_id = $user['id'];
$all_figur = $user['figur'];
$all_duel_t = $user['duel_t'];
$all_coliseum_t = $user['coliseum_t'];
$all_towers_t = $user['towers_t'];
require_once H.'system/game/tournament.php';
echo'<div class="page">';
echo'<div class="body_list">';

#-Навигация-#
$figur = '<a href="/tournament_users?type=1" style="text-decoration:none;"><span class="body_sel">'.(($_GET['type'] == 1 or $_GET['type'] > 4 or !isset($_GET['type']))  ? "<b>Статуэтки</b> <img src='/style/images/body/rating.png' alt=''/>$mesto_figur" : "Статуэтки").'</span></a>';
$duel = '<a href="/tournament_users?type=2" style="text-decoration:none;"><span class="body_sel">'.($_GET['type'] == 2 ? "<b>Дуэли</b> <img src='/style/images/body/rating.png' alt=''/>$mesto_duel" : "Дуэли").'</span></a>';
$coliseum = '<a href="/tournament_users?type=3" style="text-decoration:none;"><span class="body_sel">'.($_GET['type'] == 3 ? "<b>Колизей</b> <img src='/style/images/body/rating.png' alt=''/>$mesto_coliseum" : "Колизей").'</span></a>';
$towers = '<a href="/tournament_users?type=4" style="text-decoration:none;"><span class="body_sel">'.($_GET['type'] == 4 ? "<b>Башни</b> <img src='/style/images/body/rating.png' alt=''/>$mesto_towers" : "Башни").'</span></a>';


#-Тип рейтинга-#
$type = check($_GET['type']);
if(empty($type) or $type > 4){
$type = 1;
}

#-Вывод-#
echo'<div class="body_list">';	
echo'<div style="padding: 5px;">';
echo''.$figur.' '.$duel.' '.$coliseum.' '.$towers.'';
echo'</div>';
echo'</div>';

echo'<div class="menulitl">';

#-Статуэтки-#
if(($_GET['type'] == 1 or !isset($_GET['type'])) and $_GET['type'] != 2 and $_GET['type'] != 3 and $_GET['type'] != 4){
#-Кол-во записей-#
$sel_count_t = $pdo->query("SELECT COUNT(*) FROM `users` WHERE `figur` > 0 AND `level` = 100");
$amount = $sel_count_t->fetch(PDO::FETCH_LAZY);
$num = 10;  
$page = $_GET['page'];  
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;$page = intval($page);if(empty($page) or $page < 0) $page = 1;if($page > $total) $page = $total;$start = $page * $num - $num;  
#-Вывод записей-#
$sel_users_t = $pdo->query("SELECT `id`, `figur`, `level`, `avatar`, `time_online`, `pol`, `nick` FROM `users` WHERE `figur` > 0 AND `level` = 100 ORDER BY `figur` DESC, `id` LIMIT $start, $num");
#-Если есть записи-#
if($sel_users_t-> rowCount() != 0){
$t = $start+$amount[0];
for($i = $start; $i <= $t; $i++){
while($all = $sel_users_t->fetch(PDO::FETCH_LAZY))  
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
echo"<li><a href='/hero/$all[id]'> <img src='".avatar_img_min($all['avatar'], $all['pol'])."' class='menulitl_img' width='30' height='30' alt=''/><div class='menulitl_block'><img src='$img_online'> <span class='menulitl_name'>".($all['id'] == $user['id'] ? "<span class='green'>$all[nick]</span>" : "$all[nick]")."</span><br/><div class='menulitl_param'><img src='/style/images/body/rating.png' alt=''/>$i место <img src='/style/images/user/figur.png' alt=''/>$all[figur]</div></div></a></li>";
}
}
}
}

#-Побед в дуэлях-#
if($_GET['type'] == 2){
#-Кол-во записей-#
$sel_count_t = $pdo->query("SELECT COUNT(*) FROM `users` WHERE `duel_t` > 0");
$amount = $sel_count_t->fetch(PDO::FETCH_LAZY);
$num = 10;  
$page = $_GET['page'];  
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;$page = intval($page);if(empty($page) or $page < 0) $page = 1;if($page > $total) $page = $total;$start = $page * $num - $num;  
#-Вывод записей-#
$sel_users_t = $pdo->query("SELECT `id`, `duel_t`, `avatar`, `time_online`, `pol`, `nick` FROM `users` WHERE `duel_t` > 0 ORDER BY `duel_t` DESC, `id` LIMIT $start, $num");
#-Если есть записи-#
if($sel_users_t-> rowCount() != 0){
$t = $start+$amount[0];
for($i = $start; $i <= $t; $i++){
while($all = $sel_users_t->fetch(PDO::FETCH_LAZY))  
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
echo"<li><a href='/hero/$all[id]'> <img src='".avatar_img_min($all['avatar'], $all['pol'])."' class='menulitl_img' width='30' height='30' alt=''/><div class='menulitl_block'><img src='$img_online'> <span class='menulitl_name'>".($all['id'] == $user['id'] ? "<span class='green'>$all[nick]</span>" : "$all[nick]")."</span><br/><div class='menulitl_param'><img src='/style/images/body/rating.png' alt=''/>$i место <img src='/style/images/body/ok.png' alt=''/>$all[duel_t]</div></div></a></li>";
}
}
}
}

#-Убийств в колизее-#
if($_GET['type'] == 3){
#-Кол-во записей-#
$sel_count_t = $pdo->query("SELECT COUNT(*) FROM `users` WHERE `coliseum_t` > 0");
$amount = $sel_count_t->fetch(PDO::FETCH_LAZY);
$num = 10;  
$page = $_GET['page'];  
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;$page = intval($page);if(empty($page) or $page < 0) $page = 1;if($page > $total) $page = $total;$start = $page * $num - $num;  
#-Вывод записей-#
$sel_users_t = $pdo->query("SELECT `id`, `coliseum_t`, `avatar`, `time_online`, `pol`, `nick` FROM `users` WHERE `coliseum_t` > 0 ORDER BY `coliseum_t` DESC, `id` LIMIT $start, $num");
#-Если есть записи-#
if($sel_users_t-> rowCount() != 0){
$t = $start+$amount[0];
for($i = $start; $i <= $t; $i++){
while($all = $sel_users_t->fetch(PDO::FETCH_LAZY))  
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
echo"<li><a href='/hero/$all[id]'> <img src='".avatar_img_min($all['avatar'], $all['pol'])."' class='menulitl_img' width='30' height='30' alt=''/><div class='menulitl_block'><img src='$img_online'> <span class='menulitl_name'>".($all['id'] == $user['id'] ? "<span class='green'>$all[nick]</span>" : "$all[nick]")."</span><br/><div class='menulitl_param'><img src='/style/images/body/rating.png' alt=''/>$i место <img src='/style/images/body/ok.png' alt=''/>$all[coliseum_t]</div></div></a></li>";
}
}
}
}

#-Убийств в башнях-#
if($_GET['type'] == 4){
#-Кол-во записей-#
$sel_count_t = $pdo->query("SELECT COUNT(*) FROM `users` WHERE `towers_t` > 0");
$amount = $sel_count_t->fetch(PDO::FETCH_LAZY);
$num = 10;  
$page = $_GET['page'];  
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;$page = intval($page);if(empty($page) or $page < 0) $page = 1;if($page > $total) $page = $total;$start = $page * $num - $num;  
#-Вывод записей-#
$sel_users_t = $pdo->query("SELECT `id`, `towers_t`, `avatar`, `time_online`, `pol`, `nick` FROM `users` WHERE `towers_t` > 0 ORDER BY `towers_t` DESC, `id` LIMIT $start, $num");
#-Если есть записи-#
if($sel_users_t-> rowCount() != 0){
$t = $start+$amount[0];
for($i = $start; $i <= $t; $i++){
while($all = $sel_users_t->fetch(PDO::FETCH_LAZY))  
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
echo"<li><a href='/hero/$all[id]'> <img src='".avatar_img_min($all['avatar'], $all['pol'])."' class='menulitl_img' width='30' height='30' alt=''/><div class='menulitl_block'><img src='$img_online'> <span class='menulitl_name'>".($all['id'] == $user['id'] ? "<span class='green'>$all[nick]</span>" : "$all[nick]")."</span><br/><div class='menulitl_param'><img src='/style/images/body/rating.png' alt=''/>$i место <img src='/style/images/body/ok.png' alt=''/>$all[towers_t]</div></div></a></li>";
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