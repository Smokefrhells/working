<?php
require_once '../../../system/system.php';
echo only_reg();
echo clan_level();
echo save();
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
$head = 'Форум';
require_once H.'system/head.php';

#-Меню создание раздела-#
if($_GET['razdel_create'] == 'on'){
echo'<div class="page">';
echo'<center>';
echo'<form method="post" action="/clan_razdel_act?act=add&clan_id='.$clan['id'].'">';
echo'<input class="input_form" type="text" name="name" placeholder="Название раздела"/><br/>';
?>
<select name="prava">
<option value="0">для всех</option><option value="1">для клана</option></select>
<?
echo'<input class="button_green_i" name="submit" type="submit"  value="Создать"/>';
echo'</form>';
echo'<div style="padding-top: 5px;"></div>';
echo'</center>';
echo'</div>';
echo'<div class="body_list">';
echo'<div class="menulist">';
echo'<div class="line_1"></div>';
echo'<li><a href="/clan/razdel/'.$clan['id'].'"><img src="/style/images/body/back.png" alt=""/> Назад</a></li>';
echo'</div>';
echo'</div>';
	
}else{
echo'<div class="body_list">';
echo'<div class="menulist">';

#-Выборка разделов форума этого клана-#
if($clan['id']==$user['clan_id']){
$sel_razdel = $pdo->prepare("SELECT * FROM `clan_razdel` WHERE `clan_id` = :clan_id");
$sel_razdel->execute(array(':clan_id' => $clan['id']));
#-Если есть записи-#
if($sel_razdel-> rowCount() != 0){
while($razdel = $sel_razdel->fetch(PDO::FETCH_LAZY))  
{
#-Считаем топики данного раздела-#
$sel_c_topic = $pdo->prepare("SELECT COUNT(*) FROM `clan_topic` WHERE `razdel_id` = :razdel_id AND `clan_id` = :clan_id");
$sel_c_topic->execute(array(':razdel_id' => $razdel['id'], ':clan_id' => $clan['id']));
$c_topic = $sel_c_topic->fetch(PDO::FETCH_LAZY);
echo'<div class="line_1"></div>';
#-Удаление раздела-#
if($_GET['razdel_delete'] != 'on'){
echo"<li><a href='/clan/topic/$clan[id]?razdel_id=$razdel[id]'><img src='/style/images/forum/forum.png' alt=''/> <span class='gray'>$razdel[name]</span> <div style='float: right; color:#666666;'>$c_topic[0]</div></a></li>";
}else{
echo"<li><a href='/clan_razdel_act?act=del&clan_id=$clan[id]&razdel_id=$razdel[id]'><img src='/style/images/forum/forum.png' alt=''/> <span class='gray'>$razdel[name]</span> <div style='float: right; color:#666666;'><img src='/style/images/body/error.png' alt=''/></div></a></li>";
}
}
}else{
echo'<div class="line_1_m"></div>';
echo'<div class="body_list">';
echo'<div class="error_list">';
echo'<img src="/style/images/body/error.png" alt=""/>Разделы отсутствуют!';
echo'</div>';
echo'</div>';
}
}else{
$sel_razdel = $pdo->prepare("SELECT * FROM `clan_razdel` WHERE `clan_id` = :clan_id AND `prava` = 0");
$sel_razdel->execute(array(':clan_id' => $clan['id']));
#-Если есть записи-#
if($sel_razdel-> rowCount() != 0){
while($razdel = $sel_razdel->fetch(PDO::FETCH_LAZY))  
{
#-Считаем топики данного раздела-#
$sel_c_topic = $pdo->prepare("SELECT COUNT(*) FROM `clan_topic` WHERE `razdel_id` = :razdel_id AND `clan_id` = :clan_id");
$sel_c_topic->execute(array(':razdel_id' => $razdel['id'], ':clan_id' => $clan['id']));
$c_topic = $sel_c_topic->fetch(PDO::FETCH_LAZY);
echo'<div class="line_1"></div>';
#-Удаление раздела-#
if($_GET['razdel_delete'] != 'on'){
echo"<li><a href='/clan/topic/$clan[id]?razdel_id=$razdel[id]'><img src='/style/images/forum/forum.png' alt=''/> <span class='gray'>$razdel[name]</span> <div style='float: right; color:#666666;'>$c_topic[0]</div></a></li>";
}else{
echo"<li><a href='/clan_razdel_act?act=del&clan_id=$clan[id]&razdel_id=$razdel[id]'><img src='/style/images/forum/forum.png' alt=''/> <span class='gray'>$razdel[name]</span> <div style='float: right; color:#666666;'><img src='/style/images/body/error.png' alt=''/></div></a></li>";
}
}
}else{
echo'<div class="line_1_m"></div>';
echo'<div class="body_list">';
echo'<div class="error_list">';
echo'<img src="/style/images/body/error.png" alt=""/>Разделы отсутствуют!';
echo'</div>';
echo'</div>';
}
}
#-Создание разделов и удаление-#
$sel_clan_u = $pdo->prepare("SELECT `id`, `clan_id`, `user_id`, `prava` FROM `clan_users` WHERE `user_id` = :user_id AND `clan_id` = :clan_id AND (`prava` = 4 OR `prava` = 3)");
$sel_clan_u->execute(array(':user_id' => $user['id'], ':clan_id' => $clan['id']));
if($sel_clan_u->rowCount() != 0){
echo'<div class="line_1"></div>';	
echo'<li><a href="?razdel_create=on"><img src="/style/images/forum/topic_add.png" alt=""/> Создать раздел</a></li>';
echo'<div class="line_1"></div>';
if($_GET['razdel_delete'] != 'on'){	
echo'<li><a href="?razdel_delete=on"><img src="/style/images/body/error.png" alt=""/> Удалить разделы</a></li>';
}else{
echo'<li><a href="/clan/razdel/'.$clan['id'].'"><img src="/style/images/body/ok.png" alt=""/> Отменить удаление</a></li>';
}
}
}
echo'</div>';
echo'</div>';
require_once H.'system/footer.php';
?>