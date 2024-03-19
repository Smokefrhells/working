<?php
require_once '../../system/system.php';
$head = 'Переписка';
echo only_reg();
echo mail_level();
$id = check($_GET['id']);
if(empty($_GET['id'])) $error = 'Ошибка!';
if(!isset($_GET['id'])) $error = 'Ошибка!';
if(!isset($error)){
$sel_users = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :id");
$sel_users->execute(array(':id' => $id));
if($sel_users-> rowCount() != 0){
$all = $sel_users->fetch(PDO::FETCH_LAZY);
}else{
header ("Location: /hero/$user[id]");
$_SESSION['err'] = 'Пользователь не найден!';
exit();
}
}else{
header("Location: /hero/$user[id]");
$_SESSION['err'] = $error;
exit();
}
require_once H.'system/head.php';
	
#-Сами себе не пишем-#
if($user['id'] != $all['id']){
$upd_mail_k = $pdo->prepare("UPDATE `mail_kont` SET `new` = :new WHERE `user_id` = :user_id AND `kont_id` = :kont_id");
$upd_mail_k->execute(array(':new' => '0', ':user_id' => $user['id'], ':kont_id' => $all['id']));

#-ВЕРХНЕЕ МЕНЮ-#
echo'<div class="body_list">';
echo'<div class="menulist">';
echo'<div class="line_1_m"></div>';
echo"<a href='/mail_write/$all[id]?panel_mail=on' id='arrow'><img src='/style/images/user/mail.png' alt=''/><span class='yellow'> $all[nick]</span></a>";
echo'<img src="/style/images/mail/arrow_bot.png" alt="" class="arrow"/>';

#-ПАРАМЕТРЫ-#
$sel_ignor = $pdo->prepare("SELECT * FROM `ignor` WHERE `kto` = :user_id AND `kogo` = :all_id");
$sel_ignor->execute(array(':user_id' => $user['id'], ':all_id' => $all['id']));
if($sel_ignor-> rowCount() == 0){
$black_list = '<li><a href="/mail_act?act=ignor&id='.$all['id'].'"><img src="/style/images/body/error.png" alt=""/> Добавить в чёрный список</a></li>';
}else{
$black_list = '<li><a href="/mail_act?act=ignor&id='.$all['id'].'"><img src="/style/images/body/error.png" alt=""/> Удалить из чёрного списка</a></li>';
}
$clear_msg = '<li><a href="/mail_act?act=clear&id='.$all['id'].'"><img src="/style/images/body/basket.png" alt=""/> Очистить историю сообщений</a></li>';
$enabled = "<li><a href='/mail_write/$all[id]'><img src='/style/images/body/error.png' alt=''/>Скрыть</a></li>";
if(isset($_GET['panel_mail'])){
echo'<div class="line_1"></div>';
echo $black_list;
echo'<div class="line_1"></div>';
echo $clear_msg;
echo'<div class="line_1"></div>';
echo $enabled;
}
#-Меню на javascript-#
echo'<div id="panel">';
echo'<div class="line_1"></div>';
echo $black_list;
echo'<div class="line_1"></div>';
echo $clear_msg;
echo'</div>';
echo'</div>';
echo'</div>';
echo'<div class="line_1_m"></div>';

#-Все могут писать сообщения-#
if($all['ev_mail'] == 0){
$ev_mail = 'OK';	
}	
#-Только друзья-#
if($all['ev_mail'] == 1){
$sel_friends = $pdo->prepare("SELECT * FROM `friends` WHERE (`friend_1` = :user_id AND `friend_2` = :all_id) OR (`friend_1` = :all_id AND `friend_2` = :user_id)");
$sel_friends->execute(array(':user_id' => $user['id'], ':all_id' => $all['id']));
if($sel_friends-> rowCount() != 0){
$ev_mail = 'OK';	
}
}
#-Проверка-#
if(isset($ev_mail)){
if($user['ban'] == 0){
#-ФОРМА ОТПРАВКИ-#
echo'<div class="page">';
echo'<div style="padding-top: 5px;"></div>';
echo'<center>';
echo'<form method="post" action="/mail_act?act=send&id='.$all['id'].'">';
echo"<textarea class='input_form' type='text' name='msg' id='mail'></textarea><br/>";
echo'</center>';
echo'<input class="button_i_mini" name="submit" type="submit"  value="Отправить"/>';
echo'</form>';
echo'<img src="/style/images/body/smiles.png" alt="" class="smiles_button"/>';
echo'<div style="padding-top: 5px;"></div>';
echo'</center>';
echo'</div>';

#-Смайлы-#
echo'<div id="panel_smiles">';
echo'<div class="line_1_m"></div>';
echo smiles_kolobok();
echo'</div>';
echo'</div>';
}else{
echo'<div class="body_list">';
echo'<div class="error_list">';
echo'<img src="/style/images/body/error.png" alt=""/>Наложена молчанка до: '.vremja($user['ban']).'';
echo'</div>';
echo'</div>';
}
}else{
echo'<div class="body_list">';
echo'<div class="error_list">';
echo'<img src="/style/images/body/error.png" alt=""/>Писать сообщения запрещено!';
echo'</div>';
echo'</div>';
}

echo'<div style="white-space: pre-wrap;">';
#-СООБЩЕНИЯ-#
$sel_c_mail = $pdo->prepare("SELECT COUNT(*) FROM `mail` WHERE (`send_id` = :user_id AND `recip_id` = :all_id OR `send_id` = :all_id AND `recip_id` = :user_id) AND `clear_1` != :user_id AND `clear_2` != :user_id");
$sel_c_mail->execute(array(':user_id' => $user['id'], ':all_id' => $all['id']));
$amount = $sel_c_mail->fetch(PDO::FETCH_LAZY);
#-Действия постраничной навигации-#
$num = 10;  
$page = $_GET['page'];  
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;$page = intval($page);if(empty($page) or $page < 0) $page = 1;if($page > $total) $page = $total;$start = $page * $num - $num;  
$sel_mail = $pdo->prepare("SELECT * FROM `mail` WHERE (`send_id` = :user_id AND `recip_id` = :all_id OR `send_id` = :all_id AND `recip_id` = :user_id) AND `clear_1` != :user_id AND `clear_2` != :user_id ORDER BY `time` DESC LIMIT $start, $num");
$sel_mail->execute(array(':user_id' => $user['id'], ':all_id' => $all['id']));
#-Если есть записи-#
if($sel_mail-> rowCount() != 0){
echo'<div class="body_list">';
while($mail = $sel_mail->fetch(PDO::FETCH_LAZY))  
{	
$sel_users = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :send_id");
$sel_users->execute(array(':send_id' => $mail['send_id']));
$all = $sel_users->fetch(PDO::FETCH_LAZY);
echo'<div class="line_1"></div>';
#-Показываем комментарии-#
echo"<a href='/hero/$all[id]'><img src='".avatar_img_min($all['avatar'], $all['pol'])."' class='menulitl_img' width='30' height='30' alt=''/><div class='menulitl_block'><span class='user_nick'>$all[nick]</span></a> ";
echo"<div style='float: right; font-size: 12px; color: #666666;'>".vremja($mail['time'])."</div><br/>";
if($all['premium'] != 0){$color = 'orange';}
if($all['prava'] == 2){$color = 'blue';}
if($all['prava'] == 3){$color = 'yellow';}
if($all['prava'] == 1){$color = 'red';}
if($all['prava'] == 0 and $all['premium'] == 0){$color = 'gray';}
echo"<span class='$color'>".msg($mail['msg'])."</span><br/>";
echo'</div>';
}
echo'</div>';	
}
echo'</div>';	
#-Вывод постраничной навигации-#
if($posts > $num){
$action = '';
echo'<div class="body_list">';
echo'<div class="line_1"></div>';
$z = pages($posts,$total,$action);
echo'</div>';
}
}else{
echo'<div class="body_list">';
echo'<div class="line_1_m"></div>';
echo'<div class="error_list">';
echo'<img src="/style/images/body/error.png" alt=""/>Вы не можете писать сами себе!';
echo'</div>';
echo'</div>';
}
echo'<div class="body_list">';
echo'<div class="menulist">';
echo'<div class="line_1"></div>';
echo"<li><a href='/mail'><img src='/style/images/body/back.png' alt=''/> Почта</a></li>";
echo'</div>';
echo'</div>';
require_once H.'system/footer.php';
?>