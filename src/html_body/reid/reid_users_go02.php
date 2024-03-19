<?php
require_once '../../system/system.php';
$head = 'Участники';
echo only_reg();
echo reid_level();
require_once H.'system/head.php';
echo'<div class="body_list">';

#-Проверяем есть ли босс-#
$sel_reid = $pdo->query("SELECT * FROM `reid_boss`");
if($sel_reid->rowCount() != 0){
	
#-Количество игроков которые участвуют-#
$sel_reid_u_c = $pdo->query("SELECT COUNT(*) FROM `reid_users`");
$amount = $sel_reid_u_c->fetch(PDO::FETCH_LAZY);
#-Действия постраничной навигации-#
$num = 10;  
$page = $_GET['page'];  
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;$page = intval($page);if(empty($page) or $page < 0) $page = 1;if($page > $total) $page = $total;$start = $page * $num - $num;  

#-Игроки которые участвуют-#
$sel_reid_u = $pdo->query("SELECT * FROM `reid_users` ORDER BY `uron` DESC, `user_t_health` DESC LIMIT $start, $num");
if($sel_reid_u->rowCount() != 0){
while($reid_u = $sel_reid_u->fetch(PDO::FETCH_LAZY)){
$sel_users = $pdo->prepare("SELECT `id`, `avatar`, `pol`, `nick`, `level`, `health`, `s_health`, `health_bonus`, `time_online` FROM `users` WHERE `id` = :id");
$sel_users->execute(array(':id' => $reid_u['user_id']));
$all = $sel_users->fetch(PDO::FETCH_LAZY);
$time = time() - 1200;
if($all['time_online'] > $time){
$img_online = '/style/images/user/online.png';
}else{
$img_online = '/style/images/user/offline.png';
}

#-Данные игроков-#
echo'<div class="line_1"></div>';
echo'<div class="menulitl">';
echo"<li><a href='/hero/$all[id]'>";
if($reid_u['user_t_health'] > 0){
echo"<img src='".avatar_img_min($all['avatar'], $all['pol'])."' class='menulitl_img' width='30' height='30' alt=''/><div class='menulitl_block'><img src='$img_online'> <span class='menulitl_name'>$all[nick]</span><br/><div class='menulitl_param'><img src='/style/images/user/level.png' alt=''/>".$all['level']." <img src='/style/images/body/attack.png' alt=''/>".num_format($reid_u['uron'])." <img src='/style/images/user/health.png' alt=''/>$reid_u[user_t_health]</div></div>";
}else{
echo"<div style='opacity: .5;'><img src='".avatar_img_min($all['avatar'], $all['pol'])."' class='menulitl_img' width='30' height='30' alt=''/><div class='menulitl_block'><img src='$img_online'> <span class='menulitl_name'>$all[nick]</span> <span class='red'>Мертв</span><br/><div class='menulitl_param'><img src='/style/images/user/level.png' alt=''/>".$all['level']." <img src='/style/images/body/attack.png' alt=''/>".num_format($reid_u['uron'])." <img src='/style/images/user/health.png' alt=''/>$reid_u[user_t_health]</div></div></div>";
}
echo'</a></li>';
echo'</div>';
}
}else{
echo'<div class="error_list">';	
echo'<img src="/style/images/body/error.png" alt=""/>Нет участников!';
echo'</div>';
}
#-Отображение постраничной навигации-#
if($posts > $num){
$action = '';
echo'<div class="line_1"></div>';
$z = pages($posts,$total,$action);
}
}

echo'<div class="menulist">';
echo'<div class="line_1"></div>';
echo"<li><a href='/reid'><img src='/style/images/body/back.png' alt=''/> Рейд</a></li>";
echo'</div>';
echo'</div>';
require_once H.'system/footer.php';
?>