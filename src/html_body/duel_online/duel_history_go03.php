<?php
require_once '../../system/system.php';
$head = 'История';
echo only_reg();
require_once H.'system/head.php';
echo'<div class="page">';
if($user['level'] >= 5){
#-Считаем общее кол-во записей-#
$sel_count_l = $pdo->prepare("SELECT COUNT(*) FROM `event_log` WHERE `user_id` = :user_id AND `type` = 4 ORDER BY `time` DESC");
$sel_count_l->execute(array(':user_id' => $user['id']));
$amount = $sel_count_l->fetch(PDO::FETCH_LAZY);
#-Постраничная навигация-#
$num = 10;  
$page = $_GET['page'];  
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;$page = intval($page);if(empty($page) or $page < 0) $page = 1;if($page > $total) $page = $total;$start = $page * $num - $num;  
#-Выводим историю сражений с боссами-#
$sel_event_b = $pdo->prepare("SELECT * FROM `event_log` WHERE `user_id` = :user_id AND `type` = 4 ORDER BY `time` DESC LIMIT $start, $num");
$sel_event_b->execute(array(':user_id' => $user['id']));
if($sel_event_b -> rowCount() != 0){
while($event_b = $sel_event_b->fetch(PDO::FETCH_LAZY)){
echo'<div class="body_list">';
echo'<div class="line_1_m"></div>';
echo"<div style='padding-left:2px;'><img src='/style/images/body/time.png' alt=''/><span class='green'>".vremja($event_b['time'])."</span><div class='not_exit'><a href='/event_act?act=del&event_id=$event_b[id]&redicet=$redicet'><img src='/style/images/body/cross.png' alt=''/></a></div></div>";
echo'<div class="line_1_m"></div>';
echo'</div>';
echo'<div style="padding: 2px;padding-left:5px;">';
echo'<span class="gray">';
echo"<img src='/style/images/body/bos.png' alt=''/>$event_b[log]<br/>";	
echo'</span>';	
echo'</div>';
}
}else{
echo'<div class="line_1_m"></div>';
echo'<div class="body_list">';
echo'<div class="error_list">';
echo'<img src="/style/images/body/error.png" alt=""/>Нет записей';
echo'</div>';
echo'</div>';
}

echo'<div class="body_list">';
#-Номирация страниц-#
if($posts > $num){
$action = "";
echo'<div class="line_1"></div>';
$z = pages($posts,$total,$action);
}

#-Только если больше одной записи-#
if($sel_event_b -> rowCount() >= 2){
echo'<div class="line_1"></div>';
echo'<div class="menulist">';
if($_GET['conf'] == 'clear'){
echo'<li><a href="/event_act?act=del_all&type=4&redicet='.$redicet.'"><img src="/style/images/body/basket.png" alt=""/> Подтверждаю</a></li>';
}else{
echo'<li><a href="/duel_history?conf=clear"><img src="/style/images/body/basket.png" alt=""/> Очистить историю</a></li>';
}
echo'</div>';
}
echo'</div>';
}
require_once H.'system/footer.php';
?>