<?php
require_once '../../system/system.php';
echo only_reg();
echo save();
$head = 'История';
require_once H.'system/head.php';
echo'<div class="body_list">';

#-Считаем количество записей-#
$sel_ref_c = $pdo->prepare("SELECT COUNT(*) FROM `referal`");
$sel_ref_c->execute(array(':clan_id' => $clan['id']));
$amount = $sel_ref_c->fetch(PDO::FETCH_LAZY);
#-Действия постраничной навигации-#
$num = 10;  
$page = $_GET['page'];  
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;$page = intval($page);if(empty($page) or $page < 0) $page = 1;if($page > $total) $page = $total;$start = $page * $num - $num;  
$sel_ref_h = $pdo->query("SELECT * FROM `referal` ORDER BY `time` DESC LIMIT $start, $num");
if($sel_ref_h-> rowCount() != 0){
while($ref_h = $sel_ref_h->fetch(PDO::FETCH_LAZY))  
{
#-Выборка реферера-#
$sel_referer = $pdo->prepare("SELECT `id`, `nick`, `time_online` FROM `users` WHERE `id` = :ref_id");
$sel_referer->execute(array(':ref_id' => $ref_h['ref_id']));
if($sel_referer-> rowCount() != 0){
$referer = $sel_referer->fetch(PDO::FETCH_LAZY);
}
#-Выборка реферала-#
$sel_referal = $pdo->prepare("SELECT `id`, `nick`, `time_online` FROM `users` WHERE `id` = :user_id");
$sel_referal->execute(array(':user_id' => $ref_h['user_id']));
if($sel_referal-> rowCount() != 0){
$referal = $sel_referal->fetch(PDO::FETCH_LAZY);
}
echo'<div class="line_1"></div>';
echo'<div class="svg_list">';
echo"".online($referer['time_online'])." <a href='/hero/$referer[id]'>$referer[nick]</a> пригласил(а) ".online($referal['time_online'])." <a href='/hero/$referal[id]'>$referal[nick]</a>";
echo'</div>';
}
}else{
echo'<div class="error_list">';
echo'<center><img src="/style/images/body/error.png" alt=""/>Нет записей!</center>';
echo'</div>';
}

#-Отображение постраничной навигации-#
if($posts > $num){
$action = '';
echo'<div class="line_1"></div>';
$z = pages($posts,$total,$action);
}
echo'</div>';
require_once H . 'system/footer.php';
?>