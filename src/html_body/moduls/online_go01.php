<?php
require_once '../../system/system.php';
$head = 'Онлайн';
echo only_reg();
require_once H.'system/head.php';
echo'<div class="page">';
echo'<div class="body_list">';
$time = time() - 3600;
if($_GET['type'] == 1 or $_GET['type'] > 2 or !isset($_GET['type'])){
$sel_count = $pdo->prepare("SELECT COUNT(*) FROM `users` WHERE `time_online` > :time");
$sel_count->execute(array(':time' => $time));
$amount = $sel_count->fetch(PDO::FETCH_LAZY);
}
if($_GET['type'] == 2){
$sel_count = $pdo->prepare("SELECT COUNT(*) FROM `users` WHERE `time_online` > :time AND `clan_id` = 0");
$sel_count->execute(array(':time' => $time));
$amount = $sel_count->fetch(PDO::FETCH_LAZY);
}
#-Все игроки-#
$sel_all_u = $pdo->prepare("SELECT COUNT(*) FROM `users` WHERE `time_online` > :time");
$sel_all_u->execute(array(':time' => $time));
$all_u = $sel_all_u->fetch(PDO::FETCH_LAZY);
#-Без клана-#
$sel_no_clan_u = $pdo->prepare("SELECT COUNT(*) FROM `users` WHERE `time_online` > :time AND `clan_id` = 0");
$sel_no_clan_u->execute(array(':time' => $time));
$no_clan_u = $sel_no_clan_u->fetch(PDO::FETCH_LAZY);

#-Сортировка-#
$all = '<a href="/online?type=1" style="text-decoration:none;"><span class="body_sel">'.(($_GET['type'] == 1 or $_GET['type'] > 2 or !isset($_GET['type']))  ? "<b>Все</b> <img src='/style/images/user/user.png' alt=''/>$all_u[0]" : "Все <img src='/style/images/user/user.png' alt=''/>$all_u[0]").'</span></a>';
$no_clan = '<a href="/online?type=2" style="text-decoration:none;"><span class="body_sel">'.($_GET['type'] == 2 ? "<b>Без клана</b> <img src='/style/images/user/user.png' alt=''/>$no_clan_u[0]" : "Без клана <img src='/style/images/user/user.png' alt=''/>$no_clan_u[0]").'</span></a>';
echo'<div class="line_1_m"></div>';
echo'<div style="padding: 5px;">';
echo''.$all.''.$no_clan.'';
echo'</div>'; 
		
echo'<div class="menulitl">';
$num = 10;  
$page = $_GET['page'];  
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;$page = intval($page);if(empty($page) or $page < 0) $page = 1;if($page > $total) $page = $total;$start = $page * $num - $num;  
if($_GET['type'] == 1 or $_GET['type'] > 2 or !isset($_GET['type'])){
$sel_users = $pdo->prepare("SELECT `id`, `avatar`, `pol`, `nick`, `level`, `param`, `sila`, `s_sila`, `sila_bonus`, `zashita`, `s_zashita`, `zashita_bonus`, `health`, `s_health`, `health_bonus`, `time_online` FROM `users` WHERE `time_online` > :time ORDER BY `level` DESC, `param` DESC LIMIT $start, $num");
$sel_users->execute(array(':time' => $time));
}
if($_GET['type'] == 2){
$sel_users = $pdo->prepare("SELECT `id`, `avatar`, `pol`, `nick`, `level`, `param`, `sila`, `s_sila`, `sila_bonus`, `zashita`, `s_zashita`, `zashita_bonus`, `health`, `s_health`, `health_bonus`, `time_online`, `clan_id` FROM `users` WHERE `time_online` > :time AND `clan_id` = 0 ORDER BY `level` DESC, `param` DESC LIMIT $start, $num");
$sel_users->execute(array(':time' => $time));
}
#-Если есть записи-#
if($sel_users-> rowCount() != 0){
while($all = $sel_users->fetch(PDO::FETCH_LAZY))  
{
echo'<div class="line_1"></div>';
echo"<li><a href='/hero/$all[id]'><img src='".avatar_img_min($all['avatar'], $all['pol'])."' class='menulitl_img' width='30' height='30' alt=''/><div class='menulitl_block'><img src='/style/images/user/user.png'><span class='menulitl_name'>$all[nick]</span><br/><div class='menulitl_param'><img src='/style/images/user/level.png' alt=''/>$all[level] <img src='/style/images/user/sila.png' alt=''/>".($all['sila']+$all['s_sila']+$all['sila_bonus'])." <img src='/style/images/user/zashita.png' alt=''/>".($all['zashita']+$all['s_zashita']+$all['zashita_bonus'])." <img src='/style/images/user/health.png' alt=''/>".($all['health']+$all['s_health']+$all['health_bonus'])."</div></div></a></li>";
}
}else{
echo'<div class="line_1"></div>';
echo'<div class="error_list">';	
echo'<img src="/style/images/body/error.png" alt=""/>Список пуст!';
echo'</div>';
}
echo'</div>';

#-Отображение постраничной навигации-#
if($posts > $num){
$action = '';
echo'<div class="line_1"></div>';
$z = pages($posts,$total,$action);
}

echo'<div class="menulist">';
echo'<div class="line_1"></div>';
echo"<li><a href='/search_gamers'><img src='/style/images/body/search.png' alt=''/> Найти игрока</a></li>";
echo'</div>';
echo'</div>';
require_once H.'system/footer.php';
?>