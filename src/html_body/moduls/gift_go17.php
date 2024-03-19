<?php
require_once '../../system/system.php';
$head = 'Подарки';
echo only_reg();
echo gift_level();
require_once H.'system/head.php';
#-Выбор игрока-#
if(isset($_GET['id']) and !empty($_GET['id'])){
$id = check($_GET['id']);
$sel_users = $pdo->prepare("SELECT `id`, `ev_gift`, `nick` FROM `users` WHERE `id` = :id");
$sel_users->execute(array(':id' => $id));
if($sel_users -> rowCount() != 0){
$all = $sel_users->fetch(PDO::FETCH_LAZY);
}else{
header('Location: /');
exit();
}
echo'<div class="body_list">';

#-Считаем кол-во подарков-#
$sel_c_gift_m = $pdo->prepare("SELECT COUNT(*) FROM `gift_users` WHERE `recip_id` = :recip_id");
$sel_c_gift_m->execute(array(':recip_id' => $all['id']));
$amount = $sel_c_gift_m->fetch(PDO::FETCH_LAZY);
#-Действия постраничной навигации-#
$num = 10;  
$page = $_GET['page'];  
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;$page = intval($page);if(empty($page) or $page < 0) $page = 1;if($page > $total) $page = $total;$start = $page * $num - $num;  
#-Выборка подарков игрока-#
$sel_gift_u = $pdo->prepare("SELECT * FROM `gift_users` WHERE `recip_id` = :recip_id LIMIT $start, $num");
$sel_gift_u->execute(array(':recip_id' => $all['id']));
if($sel_gift_u -> rowCount() != 0){
while($gift_u = $sel_gift_u->fetch(PDO::FETCH_LAZY)){
#-Выборка отправителя-#
$sel_send = $pdo->prepare("SELECT `id`, `nick`, `pol` FROM `users` WHERE `id` = :id");
$sel_send->execute(array(':id' => $gift_u['send_id']));
$send = $sel_send->fetch(PDO::FETCH_LAZY);
echo'<div class="line_1"></div>';
echo"<img src='/style/images/gift/$gift_u[gift_id].png' class='img_m_battle' width='50px' height='50px' alt=''/><div class='menubig_block'>".pol($send['pol'])."<a href='/hero/$send[id]'><span class='yellow'>$send[nick]</span></a><br/>$gift_u[description]<br/>".($gift_u['recip_id'] == $user['id'] ? '<a href="/gift_act?act=del&u_id='.$all['id'].'&g_id='.$gift_u['id'].'">Удалить</a>' : '')."</div>";	
echo'<div style="padding-top:3px;"></div>';
}
}else{
echo'<div class="line_1_m"></div>';
echo'<div class="error_list">';
echo'<img src="/style/images/body/error.png" alt=""/> У вас нет подарков!';
echo'</div>';
}
}

#-Отображение постраничной навигации-#
if($posts > $num){
$action = '';
echo'<div class="line_1"></div>';
$z = pages($posts,$total,$action);
}
echo'</div>';
require_once H.'system/footer.php';
?>