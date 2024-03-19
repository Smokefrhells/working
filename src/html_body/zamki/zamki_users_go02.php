<?php
require_once '../../system/system.php';
$head = 'Участники';
echo only_reg();
echo zamki_level();
require_once H.'system/head.php';
echo'<div class="body_list">';
#-Сортировка-#
$right = '<a href="/zamki_users?type=1" style="text-decoration:none;"><span class="body_sel">'.(($_GET['type'] == 1 or $_GET['type'] > 2 or !isset($_GET['type']))  ? "<b>Правые</b>" : "Правые").'</span></a>';
$left = '<a href="/zamki_users?type=2" style="text-decoration:none;"><span class="body_sel">'.($_GET['type'] == 2 ? "<b>Левые</b>" : "Левые").'</span></a>';
echo'<div class="line_1_m"></div>';
echo'<div style="padding: 5px;">';
echo''.$right.' '.$left.'';
echo'</div>';
echo'<div class="menulitl">';
if($_GET['type'] == 1 or $_GET['type'] > 2 or !isset($_GET['type'])){
$storona = 'right';
}else{
$storona = 'left';
}

#-Количество участников-#
$sel_zamki_c = $pdo->prepare("SELECT COUNT(*) FROM zamki_users, users WHERE users.storona = :storona AND zamki_users.user_id = users.id");
$sel_zamki_c->execute(array(':storona' => $storona));
$amount = $sel_zamki_c->fetch(PDO::FETCH_LAZY);

#-Действия постраничной навигации-#
$num = 10;  
$page = $_GET['page'];  
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;$page = intval($page);if(empty($page) or $page < 0) $page = 1;if($page > $total) $page = $total;$start = $page * $num - $num;  

if($_GET['type'] == 1 or $_GET['type'] > 2 or !isset($_GET['type'])){
$sel_zamki_u = $pdo->prepare("SELECT * FROM zamki_users, users WHERE zamki_users.storona = :storona AND zamki_users.user_id = users.id ORDER BY users.level DESC, users.id DESC LIMIT $start, $num");
$sel_zamki_u->execute(array(':storona' => 'right'));
}
if($_GET['type'] == 2){
$sel_zamki_u = $pdo->prepare("SELECT * FROM zamki_users, users WHERE zamki_users.storona = :storona AND zamki_users.user_id = users.id ORDER BY users.level DESC, users.id DESC LIMIT $start, $num");
$sel_zamki_u->execute(array(':storona' => 'left'));
}
if($sel_zamki_u-> rowCount() != 0){
while($zamki_u = $sel_zamki_u->fetch(PDO::FETCH_LAZY)){
#-Выборка даных игрока-#	
$sel_users = $pdo->prepare("SELECT `id`, `avatar`, `pol`, `nick`, `level`, `sila`, `s_sila`, `sila_bonus`, `zashita`, `s_zashita`, `zashita_bonus`, `health`, `s_health`, `health_bonus`, `time_online`, `clan_id` FROM `users` WHERE `id` = :all_id");
$sel_users->execute(array(':all_id' => $zamki_u['user_id']));
$all = $sel_users->fetch(PDO::FETCH_LAZY);
#-Онлайн или нет-#
$time = time() - 1200;
if($all['time_online'] > $time){
$img_online = '/style/images/user/online.png';
}else{
$img_online = '/style/images/user/offline.png';
}
echo'<div class="line_1"></div>';
echo"<li><a href='/hero/$all[id]'><img src='".avatar_img_min($all['avatar'], $all['pol'])."' class='menulitl_img' width='30' height='30' alt=''/><div class='menulitl_block'><img src='$img_online'> <span class='menulitl_name'>$all[nick]</span>".($user['clan_id'] == $all['clan_id'] ? '<div style="float: right; padding-right: 5px;"><img src="/style/images/body/clan.png" alt=""/></div>' : '')."<br/><div class='menulitl_param'><img src='/style/images/user/level.png' alt=''/>$all[level] <img src='/style/images/user/sila.png' alt=''/>".($all['sila']+$all['s_sila']+$all['sila_bonus'])." <img src='/style/images/user/zashita.png' alt=''/>".($all['zashita']+$all['s_zashita']+$all['zashita_bonus'])." <img src='/style/images/user/health.png' alt=''/>".($all['health']+$all['s_health']+$all['health_bonus'])."</div></div></a></li>";
}
}else{
echo'<div class="line_1"></div>';
echo'<div class="error_list">';	
echo'<img src="/style/images/body/error.png" alt=""/>Список пуст!';
echo'</div>';
}
echo'</div>';
#-Вывод страниц-#
if($posts > $num){
$action = "&storona=$_GET[storona]";
echo'<div class="body_list">';
echo'<div class="line_1"></div>';
$z = pages($posts,$total,$action);
echo'</div>';
}

echo'<div class="menulist">';
echo'<div class="line_1"></div>';
echo"<li><a href='/zamki'><img src='/style/images/body/back.png' alt=''/> Замки</span></a></li>";
echo'</div>';
echo'</div>';
require_once H.'system/footer.php';
?>