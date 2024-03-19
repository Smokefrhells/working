<?php
require_once '../../system/system.php';
echo only_reg();
echo clan_level();
$head = 'Клановый чат';
require_once H.'system/head.php';
$id = check($_GET['id']);
if(empty($_GET['id'])) $error = 'Ошибка!';
if(!isset($_GET['id'])) $error = 'Ошибка!';
if(!isset($error)){
$sel_clan = $pdo->prepare("SELECT * FROM `clan` WHERE `id` = :id");
$sel_clan->execute(array(':id' => $id));
if($sel_clan-> rowCount() != 0){
$clan = $sel_clan->fetch(PDO::FETCH_LAZY);
}else{
header('Location: /clan');
$_SESSION['err'] = 'Клан не найден!';
exit();
}
}else{
header('Location: /clan');
$_SESSION['err'] = $error;
exit();
}
#-Ответ, если есть id-#
if(isset($_GET['ank_id'])){
$ank_id = check($_GET['ank_id']);
if($ank_id != $user['id']){
$sel_ank = $pdo->prepare("SELECT `id`, `nick` FROM `users` WHERE `id` = :user_id");
$sel_ank->execute(array(':user_id' => $ank_id));
#-Если есть такой игрок-#
if($sel_ank-> rowCount() != 0){
$ank = $sel_ank->fetch(PDO::FETCH_LAZY);
$otv_nick = "$ank[nick], ";
$ank_id = $ank['id'];
}
}	
}
#-Проверяем что мы состоим в клане-#
$sel_clan_u = $pdo->prepare("SELECT * FROM `clan_users` WHERE `clan_id` = :clan_id AND `user_id` = :user_id");
$sel_clan_u->execute(array(':clan_id' => $clan['id'], ':user_id' => $user['id']));
if($sel_clan_u-> rowCount() != 0){
$clan_u = $sel_clan_u->fetch(PDO::FETCH_LAZY); 
#-Делаем выборку самого последнего сообщения и записываем его в бд-#
$sel_end = $pdo->prepare("SELECT `id`, `clan_id` FROM `clan_chat` WHERE `clan_id` = :clan_id ORDER BY `time` DESC LIMIT 1");
$sel_end->execute(array(':clan_id' => $clan['id']));
if($sel_end-> rowCount() != 0){ //Если есть вообще сообщения
$end = $sel_end->fetch(PDO::FETCH_LAZY);
#-Запысываем игроку последнее сообщение которое он прочитал-#
$upd_clan_u = $pdo->prepare("UPDATE `clan_users` SET `chat_id` = :chat_id WHERE `clan_id` = :clan_id AND `user_id` = :user_id LIMIT 1");
$upd_clan_u->execute(array(':clan_id' => $clan['id'], ':chat_id' => $end['id'], ':user_id' => $user['id']));
}
#-форма отправки сообщений-#
echo'<div class="page">';
echo'<div style="padding-top: 5px;"></div>';
echo'<center>';
echo'<form method="post" action="/clan_chat_act?act=send&clan_id='.$clan['id'].'">';
echo"<input class='input_form' type='text' name='msg' id='mail' value='$otv_nick'/><br/>";
echo"<input class='input_form' type='hidden' name='ank_id' value='$ank_id'/>";
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

/*Вывод сообщений из БД*/
echo'<div class="body_list">';
#-Считаем количество записей-#
$sel_c_chat = $pdo->prepare("SELECT COUNT(*) FROM `clan_chat` WHERE `clan_id` = :clan_id");
$sel_c_chat->execute(array(':clan_id' => $clan['id']));
$amount = $sel_c_chat->fetch(PDO::FETCH_LAZY);
#-Действия постраничной навигации-#
$num = 10;  
$page = $_GET['page'];  
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;$page = intval($page);if(empty($page) or $page < 0) $page = 1;if($page > $total) $page = $total;$start = $page * $num - $num;  
$sel_chat = $pdo->prepare("SELECT * FROM `clan_chat` WHERE `clan_id` = :clan_id ORDER BY `time` DESC LIMIT $start, $num");
$sel_chat->execute(array(':clan_id' => $clan['id']));
#-Если есть записи-#
if($sel_chat-> rowCount() != 0){
while($chat = $sel_chat->fetch(PDO::FETCH_LAZY))  
{
#-Выборка игрока клана который написал сообщение-#
$sel_clan_u2 = $pdo->prepare("SELECT * FROM `clan_users` WHERE `clan_id` = :clan_id AND `user_id` = :user_id");
$sel_clan_u2->execute(array(':clan_id' => $clan['id'], ':user_id' => $chat['user_id']));
$clan_u2 = $sel_clan_u2->fetch(PDO::FETCH_LAZY); 
#-Выборка игрока-#
$sel_users = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :user_id");
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
echo"<a href='/clan/chat/$clan[id]?ank_id=$all[id]'><span id='otv' class='$all[nick]/$all[id]'>[Отв.]</span></a></a> ";
#-Если старейшина или создатель-#
if($clan_u['prava'] == 3 OR $clan_u['prava'] == 4){
echo"<a href='/clan_chat_act?act=del&msg_id=$chat[id]&clan_id=$clan[id]'><span style='color: #666666; font-size: 13px;'>[X]</span></a>";
}
echo"<div style='float: right; font-size: 12px; color: #666666;'>".vremja($chat['time'])."</div><br/>";
#-Цвет сообщения в зависимости от звания-#
if($clan_u2['prava'] == 0){$color = 'gray';}
if($clan_u2['prava'] == 1){$color = 'gray';}
if($clan_u2['prava'] == 2){$color = 'gray';}
if($all['premium'] != 0){$color = 'orange';}
if($clan_u2['prava'] == 3){$color = 'blue';}
if($clan_u2['prava'] == 4){$color = 'red';}
if($chat['ank_id'] == $user['id']){$color = 'green';}
echo"<span class='$color'>".msg($chat['msg'])."</span><br/>";
echo'</div>';
}
}
#-Отображение постраничной навигации-#
if($posts > $num){
$action = '';
echo'<div class="body_list">';
echo'<div class="line_1"></div>';
$z = pages($posts,$total,$action);
echo'</div>';
}
}
require_once H.'system/footer.php';
?>