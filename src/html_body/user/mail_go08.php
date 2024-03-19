<?php
require_once '../../system/system.php';
$head = 'Почта';
echo only_reg();
echo mail_level();
require_once H.'system/head.php';
#-СООБЩЕНИЯ-#
$sel_c_mail = $pdo->prepare("SELECT COUNT(*) FROM `mail_kont` WHERE `user_id` = :user_id");
$sel_c_mail->execute(array(':user_id' => $user['id']));
$amount = $sel_c_mail->fetch(PDO::FETCH_LAZY);
#-Действия постраничной навигации-#
$num = 10;  
$page = $_GET['page'];  
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;$page = intval($page);if(empty($page) or $page < 0) $page = 1;if($page > $total) $page = $total;$start = $page * $num - $num;  
$sel_mail_kont = $pdo->prepare("SELECT * FROM `mail_kont` WHERE `user_id` = :user_id ORDER BY `new` DESC, `time` DESC LIMIT $start, $num");
$sel_mail_kont->execute(array(':user_id' => $user['id']));
#-Если есть записи-#
if($sel_mail_kont-> rowCount() != 0){
echo'<div class="body_list">';
while($mail_kont = $sel_mail_kont->fetch(PDO::FETCH_LAZY))  
{	
$sel_users = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :kont_id LIMIT 1");
$sel_users->execute(array(':kont_id' => $mail_kont['kont_id']));
$all = $sel_users->fetch(PDO::FETCH_LAZY);
#-Выборка сообщения-#
$sel_mail = $pdo->prepare("SELECT * FROM `mail` WHERE (`send_id` = :user_id AND `recip_id` = :all_id OR `send_id` = :all_id AND `recip_id` = :user_id) AND `clear_1` != :user_id AND `clear_2` != :user_id ORDER BY `time` DESC LIMIT 1");
$sel_mail->execute(array(':user_id' => $user['id'], ':all_id' => $all['id']));
$mail = $sel_mail->fetch(PDO::FETCH_LAZY);
echo'<div class="line_3"></div>';
echo'<div class="line_1_m"></div>';	
if($mail_kont['new'] == 1){
$color = 'green';
$new = "<span class='green'>[Новое]</span>";
}else{
$color = 'gray';
$new = '';		
}
#-Онлайн-#
$time = time() - 1200;
if($all['time_online'] > $time){
$img_online = '/style/images/user/online.png';
}else{
$img_online = '/style/images/user/offline.png';
}
echo'<div class="menulitl">';
echo"<li><a href='/mail_write/$all[id]'><img src='".avatar_img_min($all['avatar'], $all['pol'])."' class='menulitl_img' width='30' height='30' alt=''/><div class='menulitl_block'><img src='$img_online'> <span class='menulitl_name'>$all[nick]</span> $new <div style='float: right; font-size: 12px; color: #666666;'>".vremja($mail['time'])."</div><br/><div class='menulitl_param'><div class='mail'><span class='$color'>$mail[msg]</span></div></div></a></li>";
echo'</div>';
}
echo'</div>';	
}else{
echo'<div class="line_1_m"></div>';	
echo'<div class="body_list">';
echo'<div class="error_list">';
echo'<img src="/style/images/body/error.png" alt=""/>Нет сообщений!';
echo'</div>';
echo'</div>';
}
#-Вывод постраничной навигации-#
if($posts > $num){
$action = '';
echo'<div class="body_list">';
echo'<div class="line_1"></div>';
$z = pages($posts,$total,$action);
echo'</div>';
}
#-Очистка всей почты-#
echo'<div class="body_list">';
echo'<div class="menulist">';
echo'<div class="line_1"></div>';
if($_GET['conf'] == 'clear'){
echo'<li><a href="/mail_act?act=clear_all"><img src="/style/images/body/basket.png" alt=""/> Подтверждаю</a></li>';
}else{
echo'<li><a href="/mail?conf=clear"><img src="/style/images/body/basket.png" alt=""/> Очистить почту</a></li>';
}
echo'</div>';
echo'</div>';
require_once H.'system/footer.php';
?>