<?php
require_once '../../system/system.php';
$head = 'Служба поддержки';
echo only_reg();
require_once H.'system/head.php';
echo'<div class="page">';

#-Если выбран тикет-#
if(isset($_GET['support_id'])){
$support_id = check($_GET['support_id']);

#-Просматривает админ или игрок-#	
if($user['prava'] == 1 or $user['prava'] == 3){
$sel_support = $pdo->prepare("SELECT * FROM `support` WHERE `support_id` = :support_id ORDER BY `time`");
$sel_support->execute(array(':support_id' => $support_id));	
}else{
$sel_support = $pdo->prepare("SELECT * FROM `support` WHERE `support_id` = :support_id AND `user_id` = :user_id ORDER BY `time`");
$sel_support->execute(array(':support_id' => $support_id, ':user_id' => $user['id']));	
}

if($sel_support-> rowCount() != 0){
echo'<div class="body_list">';
echo'<div class="menulist">';
while($support = $sel_support->fetch(PDO::FETCH_LAZY)){
$close = $support['close'];
#-Выборка ника игрока-#
$sel_users = $pdo->prepare("SELECT `id`, `nick` FROM `users` WHERE `id` = :user_id");	
$sel_users->execute(array(':user_id' => $support['user_id']));	
$all = $sel_users->fetch(PDO::FETCH_LAZY);	
echo'<div class="line_1"></div>';	
echo"<li><a href='/hero/$all[id]'>[".vremja($support['time'])."] ".($support['system'] == 0 ? "<span class='green'>$all[nick]:</span>" : "<span class='red'>Система:</span>")." <span class='whit'>$support[msg]</span><br/>Категория: $support[category]<br/></a></li>";				
}
echo'</div>';	
echo'</div>';

#-Комментирование если тикет не закрыт-#
echo'<div class="line_1"></div>';	
if($close == 0){
echo'<center>';
echo'<form method="post" action="/support_comment?act=com&support_id='.$support_id.'">';
echo'<textarea class="input_form" type="text" name="msg" placeholder="Сообщение"></textarea><br/>';
echo'<input class="button_green_i" name="submit" type="submit" value="Написать"/>';
echo'</form>';
echo'<div style="padding-top: 5px;"></div>';
echo'</center>';
}else{
echo'<div class="error_list"><img src="/style/images/body/error.png" alt=""/>Тикет закрыт!</div>';
}
}

echo'<div class="body_list">';
echo'<div class="menulist">';
if($user['prava'] == 1 or $user['prava'] == 3){
echo'<div class="line_1"></div>';	
echo'<li><a href="/support_close?act=close&support_id='.$support_id.'">'.($close == 0 ? '<img src="/style/images/body/error.png" alt=""/> Закрыть тикет' : '<img src="/style/images/body/ok.png" alt=""/> Открыть тикет').'</a></li>';
}
echo'<div class="line_1"></div>';	
echo'<li><a href="/support"><img src="/style/images/body/back.png" alt=""/> Назад</a></li>';
echo'</div>';
echo'</div>';
}else{
	
#-Создание тикета-#
if($user['prava'] != 1 and $user['prava'] != 3){
echo'<center>';
echo'<form method="post" action="/support_add?act=add">';
echo'<textarea class="input_form" type="text" name="msg" placeholder="Сообщение"></textarea><br/>';
echo'<select name="category">';
echo'<option value="1">Ошибки и баги</option>';
echo'<option value="2">Вопросы по оплате</option>';
echo'<option value="3">Помощь по игре</option>';
echo'<option value="4">Обжалование Молчанки</option>';
echo'<option value="5">Другое</option>';
echo'</select>';
echo'<input class="button_green_i" name="submit" type="submit" value="Написать"/>';
echo'</form>';
echo'<div style="padding-top: 5px;"></div>';
echo'</center>';
}
echo'</div>';
echo'</div>';

#-Список открытых тикетов-#
echo'<div class="body_list">';
echo'<div class="line_1"></div>';

if($user['prava'] == 1 or $user['prava'] == 3){
$sel_support = $pdo->query("SELECT * FROM `support` WHERE `close` = 0 GROUP BY `support_id` ORDER BY `time` DESC");
}else{
$sel_support = $pdo->prepare("SELECT * FROM `support` WHERE `user_id` = :user_id AND `close` = 0 GROUP BY `support_id` ORDER BY `time` DESC");
$sel_support->execute(array(':user_id' => $user['id']));
}
if($sel_support-> rowCount() != 0){
echo'<div class="svg_list">Открытые тикеты: '.$sel_support-> rowCount().'</div>';
echo'<div class="menulist">';
while($support = $sel_support->fetch(PDO::FETCH_LAZY)){
#-Выборка ника игрока-#	
$sel_users = $pdo->prepare("SELECT `id`, `nick` FROM `users` WHERE `id` = :user_id");	
$sel_users->execute(array(':user_id' => $support['user_id']));	
$all = $sel_users->fetch(PDO::FETCH_LAZY);
echo'<div class="line_1"></div>';	
echo"<li><a href='/support?support_id=$support[support_id]'>[".vremja($support['time'])."] <span class='green'>$all[nick]:</span> <span class='whit'>$support[msg]</span><br/>Категория: $support[category]<br/>Статус: ".($support['new'] == 0 ? '<img src="/style/images/body/support_load.gif" alt=""/><span class="red">Ожидание ответа</span>' : '<img src="/style/images/body/ok.png" alt=""/><span class="green">Есть ответы</span>')."</a></li>";		
}	
}else{
echo'<div class="svg_list">Открытые тикеты: 0</div>';	
}
echo'</div>';
echo'</div>';
}
echo'</div>';
require_once H.'system/footer.php';
?>