<?php
require_once '../../system/system.php';
if($_GET['type_chat'] == 'torg'){
$head = 'Торговый Чат';
}else{
$head = 'Чат';
}
echo only_reg();
require_once H.'system/head.php';
#-Ответ, если есть id-#
if(isset($_GET['id'])){
$id = check($_GET['id']);
if($id != $user['id']){
$sel_users = $pdo->prepare("SELECT `id`, `nick` FROM `users` WHERE `id` = :user_id");
$sel_users->execute(array(':user_id' => $id));
#-Если есть такой игрок-#
if($sel_users-> rowCount() != 0){
$all = $sel_users->fetch(PDO::FETCH_LAZY);
$otv_nick = "$all[nick], ";
$ank_id = $all['id'];
}
}	
}
#-Обычный чат-#
if($_GET['type_chat'] == 'obs' or !isset($_GET['type_chat'])){
$type_chat = 'obs';
$type = 1;
#-Делаем выборку самого последнего сообщения и записываем его в бд-#
$sel_end = $pdo->query("SELECT `id`, `type`, `time` FROM `chat` WHERE `type` = 1 ORDER BY `time` DESC LIMIT 1");
if($sel_end-> rowCount() != 0){ //Если есть вообще сообщения
$end = $sel_end->fetch(PDO::FETCH_LAZY);
if($end['id'] != $user['chat_id']){
$upd_users = $pdo->prepare("UPDATE `users` SET `chat_id` = :chat_id WHERE `id` = :user_id LIMIT 1");
$upd_users->execute(array(':chat_id' => $end['id'], ':user_id' => $user['id']));
}
}
}


#-Торговый чат-#
if($_GET['type_chat'] == 'torg'){
$type_chat = 'torg';
$type = 2;
#-Делаем выборку самого последнего сообщения и записываем его в бд-#
$sel_end = $pdo->query("SELECT `id`, `type`, `time` FROM `chat` WHERE `type` = 2 ORDER BY `time` DESC LIMIT 1");
if($sel_end-> rowCount() != 0){ //Если есть вообще сообщения
$end = $sel_end->fetch(PDO::FETCH_LAZY);
if($end['id'] != $user['chat_id']){
$upd_users = $pdo->prepare("UPDATE `users` SET `chat_war_id` = :chat_torg_id WHERE `id` = :user_id LIMIT 1");
$upd_users->execute(array(':chat_torg_id' => $end['id'], ':user_id' => $user['id']));
}
}
}

echo'<div class="page">';
#-Форма отправки сообщений-#
if($user['ban'] == 0 and $user['level'] >= 15 and $user['save'] == 1){
echo'<div style="padding-top: 5px;"></div>';
echo'<center>';
echo'<form method="post" action="/chat_act?act=send&chat='.$type.'">';
echo"<input class='input_form' type='text' name='msg' id='mail' value='$otv_nick'/><br/>";
echo"<input class='input_form' type='hidden' name='ank_id' value='$ank_id'/>";
echo'</center>';
echo'<input class="button_i_mini" name="submit" type="submit"  value="Отправить"/>';
echo'</form>';
if($_GET['type_chat'] == 'torg'){
echo'<a href="?type_chat=torg"><img src="/style/images/chat/update.png" alt="Обновить" class="update_button"/></a>'; 
}else{
echo'<a href="?"><img src="/style/images/chat/update.png" alt="Обновить" class="update_button"/></a>'; 
}
echo'<img src="/style/images/body/smiles.png" alt="" class="smiles_button"/>';
echo'<div style="padding-top: 5px;"></div>';
echo'</center>';
}else{
echo'<div class="line_1_m"></div>';
echo'<div class="body_list">';
echo'<div class="error_list">';
echo'<img src="/style/images/body/error.png" alt=""/>Чат доступен с 15 уровня!';
echo'</div>';
echo'</div>';
}

#-Смайлы-#
echo'<div id="panel_smiles">';
echo'<div class="line_1_m"></div>';
echo smiles_kolobok();
echo'</div>';
echo'<div class="body_list">';

/*Вывод сообщений из БД*/
#-Считаем количество записей-#
$sel_c_chat = $pdo->prepare("SELECT COUNT(*) FROM `chat` WHERE `type` = :type");
$sel_c_chat->execute(array(':type' => $type));
$amount = $sel_c_chat->fetch(PDO::FETCH_LAZY);
#-Действия постраничной навигации-#
$num = 10;  
$page = $_GET['page'];  
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;$page = intval($page);if(empty($page) or $page < 0) $page = 1;if($page > $total) $page = $total;$start = $page * $num - $num;  
$sel_chat = $pdo->prepare("SELECT * FROM `chat` WHERE `type` = :type ORDER BY `time` DESC LIMIT $start, $num");
$sel_chat->execute(array(':type' => $type));
#-Если есть записи-#
if($sel_chat-> rowCount() != 0){
while($chat = $sel_chat->fetch(PDO::FETCH_LAZY))  
{
#-Выборка игрока-#
$sel_users = $pdo->prepare("SELECT `id`, `nick`, `pol`, `prava`, `ban`, `premium`, `avatar` FROM `users` WHERE `id` = :user_id");
$sel_users->execute(array(':user_id' => $chat['user_id']));
#-Если есть такой игрок-#
if($sel_users-> rowCount() != 0){
$all = $sel_users->fetch(PDO::FETCH_LAZY);
$nick = $all['nick'];
}else{
$nick = 'Неизвестно';
}
echo'<div class="line_1"></div>';
echo"<a href='/hero/$all[id]'><img src='".avatar_img_min($all['avatar'], $all['pol'])."' class='menulitl_img' width='30' height='30' alt=''/><div class='menulitl_block'><span class='user_nick'>$nick</span></a> ";
if($all['id'] != $user['id']){
echo"<a href='/chat?id=$all[id]'><span id='otv' class='$all[nick]/$all[id]'>[Отв.]</span></a></a> ";
}

#-Если модератор или админ-#
if($user['prava'] == 1 OR $user['prava'] == 2){
if($all['id'] != $user['id'] and $all['prava'] != 1){
echo"<a href='/chat_mod?act=pred&user_id=$all[id]&chat=$type'><span style='color: #666666; font-size: 13px;'>[Пред.]</span></a> ";
if($all['ban'] == 0){
echo"<a href='/moder_ban?ank_id=$all[id]&redicet=$_SERVER[REQUEST_URI]'><span style='color: #666666; font-size: 13px;'>[Молч.]</span></a> ";
}else{
echo"<a href='/ban_act?act=ban&user_id=$all[id]&redicet=$_SERVER[REQUEST_URI]'><span style='color: #666666; font-size: 13px;'>[Не молч.]</span></a> ";
}
}
echo"<a href='/chat_mod?act=del_msg&msg_id=$chat[id]&user_id=$all[id]&chat=$type'><span style='color: #666666; font-size: 13px;'>[X]</span></a>";
}

echo"<div style='float: right; font-size: 12px; color: #666666;'>".vremja($chat['time'])."</div><br/>";
if($all['premium'] != 0){$color = 'lblue';}
if($all['prava'] == 2){$color = 'lyell';}
if($all['prava'] == 3){$color = 'yellow';}
if($all['prava'] == 1){$color = 'lyell';}
if($all['prava'] == 0 and $all['premium'] == 0){$color = 'lblue';}
if($chat['ank_id'] == $user['id']){$color = 'red';}
if($chat['close'] == 0){
echo"<span class='$color'>".msg($chat['msg'])."</span><br/>";
}else{
echo"<span class='$color'>Сообщение скрыто!</span><br/>";
}
echo'</div>';
}
}

#-Отображение постраничной навигации-#
if($posts > $num){
$action = "&type_chat=$type_chat";
echo'<div class="body_list">';
echo'<div class="line_1"></div>';
$z = pages($posts,$total,$action);
echo'</div>';
}
#-Нижнеее меню-#
echo'<div class="body_list">';
echo'<div class="menulist">';
echo'<div class="line_1"></div>';

if($_GET['type_chat'] == 'obs' or !isset($_GET['type_chat'])){
$sel_chat_c = $pdo->prepare("SELECT COUNT(*) FROM `chat` WHERE `type` = 2 AND `id` > :chat_war_id");
$sel_chat_c->execute(array(':chat_war_id' => $user['chat_war_id']));
$amount_c = $sel_chat_c->fetch(PDO::FETCH_LAZY);
if($amount_c[0] > 0){
echo'<li><a href="/chat?type_chat=torg"><img src="/style/images/body/attack.png" alt=""/> Торговый чат <span class="green">(+)</span></a></li>';
}else{
echo'<li><a href="/chat?type_chat=torg"><img src="/style/images/body/attack.png" alt=""/> Торговый чат</a></li>';
}
}else{
$sel_chat_c = $pdo->prepare("SELECT COUNT(*) FROM `chat` WHERE `type` = 1 AND `id` > :chat_id");
$sel_chat_c->execute(array(':chat_id' => $user['chat_id']));
$amount_c = $sel_chat_c->fetch(PDO::FETCH_LAZY);
if($amount_c[0] > 0){
echo'<li><a href="/chat?type_chat=obs"><img src="/style/images/body/back.png" alt=""/> Общий чат <span class="green">(+)</span></a></li>';
}else{
echo'<li><a href="/chat?type_chat=obs"><img src="/style/images/body/back.png" alt=""/> Общий чат</a></li>';	
}
}
echo'<div class="line_1"></div>';
echo'<li><a href="/smiles"><img src="/style/images/chat/smiles_b.png" alt=""/> Смайлы</a></li>';
echo'<div class="line_1"></div>';
echo'<li><a href="/moderator"><img src="/style/images/chat/moder.png" alt=""/> Модераторы</a></li>';
echo'</div>';
echo'</div>';
echo'</div>';

require_once H.'system/footer.php';
?>


