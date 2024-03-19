<?php
require_once '../../system/system.php';
echo only_reg();
echo save();
$id = check($_GET['id']);
if($id!=$user['id']) $error = 'Ошибка!';
if(empty($_GET['id'])) $error = 'Ошибка!';
if(!isset($_GET['id'])) $error = 'Ошибка!';
if(!isset($error)){
$sel_users = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :id");
$sel_users->execute(array(':id' => $id));
if($sel_users-> rowCount() != 0){
$all = $sel_users->fetch(PDO::FETCH_LAZY);
}else{
header("Location: /hero/".$user[id]);
$_SESSION['err'] = 'Пользователь не найден!';
exit();
}
}else{
header("Location: /hero/".$user[id]);
$_SESSION['err'] = $error;
exit();
}
$head = 'Друзья';
require_once H.'system/head.php';
$redicet = $_SERVER['REQUEST_URI'];
echo'<div class="body_list">';
echo'<div class="menulist">';
#-Выводим игроков-#
$sel_friends_c = $pdo->prepare("SELECT COUNT(*) FROM `friends` WHERE `friend_1` = :id OR `friend_2` = :id");
$sel_friends_c->execute(array(':id' => $all['id']));
$amount = $sel_friends_c->fetch(PDO::FETCH_LAZY);
echo'<div class="line_1"></div>';
echo'<div style="padding: 5px; color: #00a800;">';
echo"<img src='/style/images/body/friends.png' alt=''/> Количество друзей: ".$amount[0];
echo'</div>';
#-Действия постраничной навигации-#
$num = 10;  
$page = $_GET['page'];  
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;$page = intval($page);if(empty($page) or $page < 0) $page = 1;if($page > $total) $page = $total;$start = $page * $num - $num;  
$sel_friends = $pdo->prepare("SELECT * FROM `friends` WHERE `friend_1` = :id OR `friend_2` = :id ORDER BY `time` DESC LIMIT $start, $num");
$sel_friends->execute(array(':id' => $id));
#-Если есть записи-#
if($sel_friends-> rowCount() != 0){
while($friends = $sel_friends->fetch(PDO::FETCH_LAZY))  
{
if($friends['friend_1'] == $all['id']){
$all_id = $friends['friend_2'];
}else{
$all_id = $friends['friend_1'];	
}
#-Выборка данных-#
$sel_users_f = $pdo->prepare("SELECT `id`, `avatar`, `pol`, `nick`, `level`, `sila`, `s_sila`, `sila_bonus`, `zashita`, `s_zashita`, `zashita_bonus`, `health`, `s_health`, `health_bonus`, `time_online`, `clan_id` FROM `users` WHERE `id` = :all_id");
$sel_users_f->execute(array(':all_id' => $all_id));
if($sel_users_f-> rowCount() != 0){
$all_f = $sel_users_f->fetch(PDO::FETCH_LAZY);
#-Онлайн или нет-#
$time = time() - 1200;
if($all_f['time_online'] > $time){
$img_online = '/style/images/user/online.png';
}else{
$img_online = '/style/images/user/offline.png';
}
echo'<div class="menulitl">';
echo'<div class="line_1"></div>';
echo"<li><a href='/hero/".$all_f[id]."'><img src='".avatar_img_min($all_f['avatar'], $all_f['pol'])."' class='menulitl_img' width='30' height='30' alt=''/><div class='menulitl_block'><img src='$img_online'> <span class='menulitl_name'>$all_f[nick]</span>".($all_f['clan_id'] == $all['clan_id'] ? '<div style="float: right; padding-right: 5px;"><img src="/style/images/body/clan.png" alt=""/></div>' : '')."<br/><div class='menulitl_param'><img src='/style/images/user/level.png' alt=''/>$all_f[level] <img src='/style/images/user/sila.png' alt=''/>".($all_f['sila']+$all_f['s_sila']+$all_f['sila_bonus'])." <img src='/style/images/user/zashita.png' alt=''/>".($all_f['zashita']+$all_f['s_zashita']+$all_f['zashita_bonus'])." <img src='/style/images/user/health.png' alt=''/>".($all_f['health']+$all_f['s_health']+$all_f['health_bonus'])."</div></div></a></li>";
echo'</div>';

#-Если наш id-#
if($all['id'] == $user['id']){
echo'<div class="line_1"></div>';
if($_GET['conf'] == $all_f['id']){
echo'<li><a href="/friends_act?act=del&id='.$all_f['id'].'&redicet='.$redicet.'"><img src="/style/images/body/error.png" alt=""/><span class="white">Подтверждаю</span></a></li>';
}else{
echo'<li><a href="/friends/'.$all['id'].'/?conf='.$all_f['id'].'&page='.$_GET['page'].'"><img src="/style/images/body/error.png" alt=""/><span class="white">Удалить из друзей</span></a></li>';
}
}
}
}
}
echo'</div>';
echo'</div>';
#-Вывод постраничной навигации-#
if($posts > $num){
$action = '';
echo'<div class="body_list">';
echo'<div class="line_1"></div>';
$z = pages($posts,$total,$action);
echo'</div>';
}
require_once H.'system/footer.php';
?>