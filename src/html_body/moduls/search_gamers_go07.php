<?php
require_once '../../system/system.php';
$head = 'Поиск игроков';
echo only_reg();
require_once H.'system/head.php';
echo'<div class="page">';
echo'<center>';
echo'<form method="get" action="/search_gamers">';
echo'<input class="input_form" type="text" name="search" placeholder="Ник игрока или ID" value="'.$_GET['search'].'"/><br/>';
echo'<input class="button_green_i" name="submit" type="submit"  value="Поиск"/>';
echo'</form>';
echo'<div style="padding-top: 5px;"></div>';
echo'</center>';
echo'</div>';
#-Поиск игроков-#
if(isset($_GET['search'])){
$search = check($_GET['search']);
if(!empty($search)){
echo'<div class="body_list">';
echo'<div class="menulitl">';
$sel_users_c = $pdo->prepare("SELECT COUNT(*) FROM `users` WHERE `nick` LIKE :search OR `id` LIKE :search");
$sel_users_c->execute(array(':search' => "%$search%"));
$amount = $sel_users_c->fetch(PDO::FETCH_LAZY);
echo'<div class="line_1"></div>';
echo'<div class="svg_list">';
echo"<img src='/style/images/body/search.png' alt='Поиск'/> Найдено игроков: $amount[0]";
echo'</div>';
#-Действия постраничной навигации-#
$num = 10;  
$page = $_GET['page'];  
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;$page = intval($page);if(empty($page) or $page < 0) $page = 1;if($page > $total) $page = $total;$start = $page * $num - $num;  
$sel_users = $pdo->prepare("SELECT * FROM `users` WHERE `nick` LIKE :search OR `id` LIKE :search ORDER BY `level` DESC LIMIT $start, $num");
$sel_users->execute(array(':search' => "%$search%"));
#-Если есть записи-#
if($sel_users-> rowCount() != 0){
while($all = $sel_users->fetch(PDO::FETCH_LAZY))  
{
#-Онлайн или нет-#
$time = time() - 1200;
if($all['time_online'] > $time){
$img_online = '/style/images/user/online.png';
}else{
$img_online = '/style/images/user/offline.png';
}
echo'<div class="line_1"></div>';
echo"<li><a href='/hero/$all[id]'><img src='".avatar_img_min($all['avatar'], $all['pol'])."' class='menulitl_img' width='30' height='30' alt=''/><div class='menulitl_block'><img src='$img_online'> <span class='menulitl_name'>$all[nick]</span><br/><div class='menulitl_param'><img src='/style/images/user/level.png' alt=''/>$all[level] <img src='/style/images/user/sila.png' alt=''/>".($all['sila']+$all['s_sila']+$all['sila_bonus'])." <img src='/style/images/user/zashita.png' alt=''/>".($all['zashita']+$all['s_zashita']+$all['zashita_bonus'])." <img src='/style/images/user/health.png' alt=''/>".($all['health']+$all['s_health']+$all['health_bonus'])."</div></div></a></li>";
}
}
echo'</div>';
echo'</div>';	
}
if($posts > $num){
$action = "&search=$search";
echo'<div class="body_list">';
echo'<div class="line_1"></div>';
$z = pages($posts,$total,$action);
echo'</div>';
}
}
require_once H.'system/footer.php';
?>