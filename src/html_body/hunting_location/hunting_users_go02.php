<?php
require_once '../../system/system.php';
$head = 'Участники';
echo only_reg();
require_once H.'system/head.php';
echo'<div class="body_list">';
echo'<div class="menulist">';
#-Наличие данных-#
if(isset($_GET['loc'])){
$location = check($_GET['loc']);
#-Количество участников-#
$sel_hunting_c = $pdo->prepare("SELECT COUNT(*) FROM `hunting_battle_u` WHERE `location` = :location");
$sel_hunting_c ->execute(array(':location' => $location));
$amount = $sel_hunting_c->fetch(PDO::FETCH_LAZY);

#-Действия постраничной навигации-#
$num = 10;  
$page = $_GET['page'];  
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;$page = intval($page);if(empty($page) or $page < 0) $page = 1;if($page > $total) $page = $total;$start = $page * $num - $num;  
$sel_hunting_b_u = $pdo->prepare("SELECT * FROM `hunting_battle_u` WHERE `location` = :location ORDER BY `time` DESC LIMIT $start, $num");
$sel_hunting_b_u->execute(array(':location' => $location));
if($sel_hunting_b_u-> rowCount() != 0){
while($battle_u = $sel_hunting_b_u->fetch(PDO::FETCH_LAZY)){
#-Выборка даных игрока-#	
$sel_users = $pdo->prepare("SELECT `id`, `avatar`, `pol`, `nick`, `level`, `sila`, `s_sila`, `sila_bonus`, `zashita`, `s_zashita`, `zashita_bonus`, `health`, `s_health`, `health_bonus`, `time_online` FROM `users` WHERE `id` = :all_id");
$sel_users->execute(array(':all_id' => $battle_u['user_id']));
$all = $sel_users->fetch(PDO::FETCH_LAZY);
#-Онлайн или нет-#
$time = time() - 1200;
if($all['time_online'] > $time){
$img_online = '/style/images/user/online.png';
}else{
$img_online = '/style/images/user/offline.png';
}
echo'<div class="line_1"></div>';
echo'<div class="menulitl">';
echo"<li><a href='/hero/$all[id]'><img src='".avatar_img_min($all['avatar'], $all['pol'])."' class='menulitl_img' width='30' height='30' alt=''/><div class='menulitl_block'><img src='$img_online'> <span class='menulitl_name'>$all[nick]</span><br/><div class='menulitl_param'><img src='/style/images/user/level.png' alt=''/>$all[level] <img src='/style/images/user/sila.png' alt=''/>".($all['sila']+$all['s_sila']+$all['sila_bonus'])." <img src='/style/images/user/zashita.png' alt=''/>".($all['zashita']+$all['s_zashita']+$all['zashita_bonus'])." <img src='/style/images/user/health.png' alt=''/>".($all['health']+$all['s_health']+$all['health_bonus'])."</div></div></a></li>";
echo'</div>';
}
}else{
echo'<div class="error_list"><img src="/style/images/body/error.png" alt=""/>Нет участников!</div>';
}
echo'</div>';
#-Вывод страниц-#
if($posts > $num){
$action = "&loc=$location";
echo'<div class="body_list">';
echo'<div class="line_1"></div>';
$z = pages($posts,$total,$action);
echo'</div>';
}
}
echo'<div class="menulist">';
echo'<div class="line_1"></div>';
echo"<li><a href='/select_location'><img src='/style/images/body/back.png' alt=''/> Охота</a></li>";
echo'</div>';
echo'</div>';
require_once H.'system/footer.php';
?>