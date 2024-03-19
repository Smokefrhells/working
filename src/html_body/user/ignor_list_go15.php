<?php
require_once '../../system/system.php';
$head = 'Чёрный список';
echo only_reg();
echo mail_level();
require_once H.'system/head.php';
$redicet = $_SERVER['REQUEST_URI'];
echo'<div class="body_list">';
echo'<div class="menulist">';
#-Выводим игроков из черного списка-#
$sel_ignor_c = $pdo->prepare("SELECT COUNT(*) FROM `ignor` WHERE `kto` = :user_id");
$sel_ignor_c->execute(array(':user_id' => $user['id']));
$amount = $sel_ignor_c->fetch(PDO::FETCH_LAZY);
echo'<div class="line_1"></div>';
echo'<div style="padding: 5px; color: #00a800;">';
echo"<img src='/style/images/body/error.png' alt=''/>Количество игроков: $amount[0]";
echo'</div>';
#-Действия постраничной навигации-#
$num = 10;  
$page = $_GET['page'];  
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;$page = intval($page);if(empty($page) or $page < 0) $page = 1;if($page > $total) $page = $total;$start = $page * $num - $num;  
$sel_ignor = $pdo->prepare("SELECT * FROM `ignor` WHERE `kto` = :user_id ORDER BY `time` DESC LIMIT $start, $num");
$sel_ignor->execute(array(':user_id' => $user['id']));
#-Если есть записи-#
if($sel_ignor-> rowCount() != 0){
while($ignor = $sel_ignor->fetch(PDO::FETCH_LAZY))  
{
#-Выборка данных-#
$sel_users = $pdo->prepare("SELECT `id`, `time_online`, `nick`, `level`, `param` FROM `users` WHERE `id` = :id");
$sel_users->execute(array(':id' => $ignor['kogo']));
if($sel_users-> rowCount() != 0){
$all = $sel_users->fetch(PDO::FETCH_LAZY);
#-Онлайн или нет-#
$time = time() - 1200;
if($all['time_online'] > $time){
$img_online = '/style/images/user/online.png';
}else{
$img_online = '/style/images/user/offline.png';
}
echo'<div class="line_1"></div>';
echo"<li><a href='/hero/$all[id]'><img src='$img_online' alt=''/><span class='yellow'> $all[nick]</span>, <span class='gray'><img src='/style/images/user/level.png' alt=''/>$all[level] ур., <img src='/style/images/body/all.png' alt=''/> ".($all['param'])."</a></li>";
echo'<div class="line_1"></div>';
echo'<li><a href="/mail_act?act=ignor&id='.$all['id'].'&redicet='.$redicet.'"><img src="/style/images/body/error.png" alt=""/><span class="white">Удалить</span></a></li>';
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