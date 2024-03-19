<?php
require_once '../../../system/system.php';
echo only_reg();
echo clan_level();
echo save();

#-Создание топика-#
switch($act){
case 'add':
if(isset($_POST['title']) and isset($_POST['msg']) and isset($_GET['razdel']) and isset($_GET['clan_id'])){
$title = check($_POST['title']); //Заголовок
$msg = check($_POST['msg']); //Сообщение
$razdel_id = check($_GET['razdel']); //Раздел
$clan_id = check($_GET['clan_id']); //ID клана

#-Проверяем существует ли клан-#
$sel_clan = $pdo->prepare("SELECT `id` FROM `clan` WHERE `id` = :clan_id");
$sel_clan->execute(array(':clan_id' => $clan_id));
if($sel_clan-> rowCount() == 0) $error = 'Клан не найден!';
#-Проверяем существует ли раздел-#
$sel_clan_r = $pdo->prepare("SELECT * FROM `clan_razdel` WHERE `id` = :razdel_id AND `clan_id` = :clan_id");
$sel_clan_r->execute(array(':razdel_id' => $razdel_id, ':clan_id' => $clan_id));
if($sel_clan_r-> rowCount() == 0) $error = 'Раздел не найден!';
#-Проверяем что в клане-#
$sel_clan_u = $pdo->prepare("SELECT `id`, `clan_id`, `user_id` FROM `clan_users` WHERE `user_id` = :user_id AND `clan_id` = :clan_id");
$sel_clan_u->execute(array(':user_id' => $user['id'], ':clan_id' => $clan_id));
if($sel_clan_u->rowCount() == 0) $error = 'Вы не состоите в клане!';

#-Заголовок-#
if(preg_match('/[\^\<\>\&\`\$]/',$_POST['title'])) $error = 'Некорректное сообщение!';
if(mb_strlen($title) < 1) $error = 'Пустой заголовок!';
if(mb_strlen($title) > 50) $error = 'Слишком длинный заголовок!';
#-Сообщение-#
if(preg_match('/[\^\<\>\&\`\$]/',$_POST['msg'])) $error = 'Некорректное сообщение!';
if(mb_strlen($msg) < 1) $error = 'Пустое сообщение!';
if(mb_strlen($msg) > 5000) $error = 'Слишком длинное сообщение!';
#-Время обращения-#
if(isset($_SESSION["topic_create"])){
$t = ((int)(time()-$_SESSION["topic_create"]));
if ($t < 30) $error = 'Доступно через 30 сек!';}
$_SESSION["topic_create"]=time();

#-Если нет ошибок-#
if(!isset($error)){
$ins_clan_t = $pdo->prepare("INSERT INTO `clan_topic` SET `title` = :title, `msg` = :msg, `user_id` = :user_id, `razdel_id` = :razdel_id, `clan_id` = :clan_id, `time` = :time");
$ins_clan_t->execute(array(':title' => $title, ':msg' => $msg, ':user_id' => $user['id'], ':razdel_id' => $razdel_id, ':clan_id' => $clan_id, ':time' => time())); 
$topic_id = $pdo->lastInsertId();
$ins_clan_l = $pdo->prepare("INSERT INTO `clan_log` SET `type` = :type, `log` = :log, `clan_id` = :clan_id, `time` = :time");
$ins_clan_l ->execute(array(':type' => 9, ':log' => "<a href='/hero/$user[id]' style='display:inline;text-decoration:underline;padding:0px;'>$user[nick]</a> создал(а) топик <a href='/clan/topic_read/$clan_id?topic_id=$topic_id' style='display:inline;text-decoration:underline;padding:0px;'>$title</a>", ':clan_id' => $clan_id, ':time' => time())); 
header("Location: /clan/topic_read/$clan_id?topic_id=$topic_id");
}else{
header("Location: /clan/topic/$clan_id?razdel_id=$razdel_id&topic_create=on");
$_SESSION['err'] = $error;
exit();
}
}else{
header('Location: /clan');
$_SESSION['err'] = 'Данные не переданы!';
exit();
}
}

#-Редактирование топика-#
switch($act){
case 'edit':
if(isset($_POST['title']) and isset($_POST['msg']) and isset($_GET['topic_id']) and isset($_GET['clan_id'])){
$title = check($_POST['title']); //Заголовок
$msg = check($_POST['msg']); //Сообщение
$topic_id = check($_GET['topic_id']); //ID топика
$clan_id = check($_GET['clan_id']); //ID клана

#-Проверяем существует ли клан-#
$sel_clan = $pdo->prepare("SELECT `id` FROM `clan` WHERE `id` = :clan_id");
$sel_clan->execute(array(':clan_id' => $clan_id));
if($sel_clan-> rowCount() == 0) $error = 'Клан не найден!';
#-Проверяем существует ли топик-#
$sel_clan_t = $pdo->prepare("SELECT `id`, `close`, `clan_id`, `user_id` FROM `clan_topic` WHERE `id` = :topic_id AND `clan_id` = :clan_id");
$sel_clan_t->execute(array(':topic_id' => $topic_id, ':clan_id' => $clan_id));
if($sel_clan_t-> rowCount() == 0) $error = 'Топик не найден!';
#-Проверяем что в клане-#
$sel_clan_u = $pdo->prepare("SELECT `id`, `clan_id`, `user_id`, `prava` FROM `clan_users` WHERE `user_id` = :user_id AND `clan_id` = :clan_id");
$sel_clan_u->execute(array(':user_id' => $user['id'], ':clan_id' => $clan_id));
if($sel_clan_u->rowCount() == 0) $error = 'Вы не состоите в клане!';

#-Заголовок-#
if(preg_match('/[\^\<\>\&\`\$]/',$_POST['title'])) $error = 'Некорректное сообщение!';
if(mb_strlen($title) < 1) $error = 'Пустой заголовок!';
if(mb_strlen($title) > 50) $error = 'Слишком длинный заголовок!';
#-Сообщение-#
if(preg_match('/[\^\<\>\&\`\$]/',$_POST['msg'])) $error = 'Некорректное сообщение!';
if(mb_strlen($msg) < 1) $error = 'Пустое сообщение!';
if(mb_strlen($msg) > 5000) $error = 'Слишком длинное сообщение!';

#-Если нет ошибок-#
if(!isset($error)){
$topic = $sel_clan_t->fetch(PDO::FETCH_LAZY);
$clan_u = $sel_clan_u->fetch(PDO::FETCH_LAZY);
if(($clan_u['prava'] == 3 or $clan_u['prava'] == 4) or $topic['user_id'] == $user['id']){
$upd_clan_t = $pdo->prepare("UPDATE `clan_topic` SET `title` = :title, `msg` = :msg, `edit` = `edit` + 1 WHERE `id` = :topic_id LIMIT 1");
$upd_clan_t->execute(array(':title' => $title, ':msg' => $msg, ':topic_id' => $topic_id)); 
$ins_clan_l = $pdo->prepare("INSERT INTO `clan_log` SET `type` = :type, `log` = :log, `clan_id` = :clan_id, `time` = :time");
$ins_clan_l ->execute(array(':type' => 9, ':log' => "<a href='/hero/$user[id]' style='display:inline;text-decoration:underline;padding:0px;'>$user[nick]</a> отредактировал(а) топик <a href='/clan/topic_read/$clan_id?topic_id=$topic_id' style='display:inline;text-decoration:underline;padding:0px;'>$title</a>", ':clan_id' => $clan_id, ':time' => time())); 
header("Location: /clan/topic_read/$clan_id?topic_id=$topic_id");
}else{
header("Location: /clan/topic_read/$clan_id?topic_id=$topic_id");
$_SESSION['err'] = 'Вы не можете редактировать топик!';
exit();
}
}else{
header("Location: /clan/topic_read/$clan_id?topic_id=$topic_id");
$_SESSION['err'] = $error;
exit();
}
}else{
header('Location: /clan');
$_SESSION['err'] = 'Данные не переданы!';
exit();
}
}

#-Закрытие или открытие-#
switch($act){
case 'close':
if(isset($_GET['clan_id']) and isset($_GET['topic_id'])){
$clan_id = check($_GET['clan_id']); //ID клана
$topic_id = check($_GET['topic_id']); //ID топика	

#-Проверяем существует ли клан-#
$sel_clan = $pdo->prepare("SELECT `id` FROM `clan` WHERE `id` = :clan_id");
$sel_clan->execute(array(':clan_id' => $clan_id));
if($sel_clan-> rowCount() == 0) $error = 'Клан не найден!';
#-Проверяем существует ли топик-#
$sel_clan_t = $pdo->prepare("SELECT `id`, `close`, `clan_id` FROM `clan_topic` WHERE `id` = :topic_id AND `clan_id` = :clan_id");
$sel_clan_t->execute(array(':topic_id' => $topic_id, ':clan_id' => $clan_id));
if($sel_clan_t-> rowCount() == 0) $error = 'Топик не найден!';
#-Есть ли права в клане-#
$sel_clan_u = $pdo->prepare("SELECT `id`, `clan_id`, `user_id`, `prava` FROM `clan_users` WHERE `user_id` = :user_id AND `clan_id` = :clan_id AND (`prava` = 3 OR `prava` = 4)");
$sel_clan_u->execute(array(':user_id' => $user['id'], ':clan_id' => $clan_id));
if($sel_clan_u->rowCount() == 0) $error = 'Нет прав на это действие!';	
	
#-Если нет ошибок-#	
if(!isset($error)){
$topic = $sel_clan_t->fetch(PDO::FETCH_LAZY);
#-Закрыть или открыть-#
if($topic['close'] == 0){
$close = '1';
$ins_clan_c = $pdo->prepare("INSERT INTO `clan_comment` SET `comment` = :comment, `topic_id` = :topic_id, `clan_id` = :clan_id, `user_id` = :user_id, `time` = :time");
$ins_clan_c->execute(array(':comment' => 'Топик закрыт!', ':topic_id' => $topic['id'], ':clan_id' => $clan_id, ':user_id' => $user['id'], ':time' => time())); 
}else{
$close = '0';	
$ins_clan_c = $pdo->prepare("INSERT INTO `clan_comment` SET `comment` = :comment, `topic_id` = :topic_id, `clan_id` => :clan_id, `user_id` = :user_id, `time` = :time");
$ins_clan_c->execute(array(':comment' => 'Топик открыт!', ':topic_id' => $topic['id'], ':clan_id' => $clan_id, ':user_id' => $user['id'], ':time' => time())); 
}
$upd_clan_t = $pdo->prepare("UPDATE `clan_topic` SET `close` = :close WHERE `id` = :topic_id");
$upd_clan_t->execute(array(':close' => $close, ':topic_id' => $topic['id'])); 
header("Location: /clan/topic_read/$clan_id?topic_id=$topic[id]");
}else{
header("Location: /clan/topic_read/$clan_id?topic_id=$topic[id]");
$_SESSION['err'] = $error;
exit();
}
}else{
header('Location: /forum_razdel');
$_SESSION['err'] = 'Данные не переданы';
exit();
}
}

#-Закрепить или открепить-#
switch($act){
case 'fix':
if(isset($_GET['clan_id']) and isset($_GET['topic_id'])){
$clan_id = check($_GET['clan_id']); //ID клана
$topic_id = check($_GET['topic_id']); //ID топика	

#-Проверяем существует ли клан-#
$sel_clan = $pdo->prepare("SELECT `id` FROM `clan` WHERE `id` = :clan_id");
$sel_clan->execute(array(':clan_id' => $clan_id));
if($sel_clan-> rowCount() == 0) $error = 'Клан не найден!';
#-Проверяем существует ли топик-#
$sel_clan_t = $pdo->prepare("SELECT `id`, `verh`, `clan_id` FROM `clan_topic` WHERE `id` = :topic_id AND `clan_id` = :clan_id");
$sel_clan_t->execute(array(':topic_id' => $topic_id, ':clan_id' => $clan_id));
if($sel_clan_t-> rowCount() == 0) $error = 'Топик не найден!';
#-Есть ли права в клане-#
$sel_clan_u = $pdo->prepare("SELECT `id`, `clan_id`, `user_id`, `prava` FROM `clan_users` WHERE `user_id` = :user_id AND `clan_id` = :clan_id AND (`prava` = 3 OR `prava` = 4)");
$sel_clan_u->execute(array(':user_id' => $user['id'], ':clan_id' => $clan_id));
if($sel_clan_u->rowCount() == 0) $error = 'Нет прав на это действие!';	
	
#-Если нет ошибок-#	
if(!isset($error)){
$topic = $sel_clan_t->fetch(PDO::FETCH_LAZY);
#-Закрепить или открепить-#
if($topic['verh'] == 0){
$verh = '1';
$ins_clan_c = $pdo->prepare("INSERT INTO `clan_comment` SET `comment` = :comment, `topic_id` = :topic_id, `clan_id` = :clan_id, `user_id` = :user_id, `time` = :time");
$ins_clan_c->execute(array(':comment' => 'Топик закреплен!', ':topic_id' => $topic['id'], ':clan_id' => $clan_id, ':user_id' => $user['id'], ':time' => time())); 
}else{
$verh = '0';	
$ins_clan_c = $pdo->prepare("INSERT INTO `clan_comment` SET `comment` = :comment, `topic_id` = :topic_id, `clan_id` = :clan_id, `user_id` = :user_id, `time` = :time");
$ins_clan_c->execute(array(':comment' => 'Топик откреплен!', ':topic_id' => $topic['id'], ':clan_id' => $clan_id, ':user_id' => $user['id'], ':time' => time())); 
}
$upd_clan_t = $pdo->prepare("UPDATE `clan_topic` SET `verh` = :verh WHERE `id` = :topic_id");
$upd_clan_t->execute(array(':verh' => $verh, ':topic_id' => $topic['id'])); 
header("Location: /clan/topic_read/$clan_id?topic_id=$topic[id]");
}else{
header("Location: /clan/topic_read/$clan_id?topic_id=$topic[id]");
$_SESSION['err'] = $error;
exit();
}
}else{
header('Location: /forum_razdel');
$_SESSION['err'] = 'Данные не переданы';
exit();
}
}

#-Удаление-#
switch($act){
case 'del':
if(isset($_GET['clan_id']) and isset($_GET['topic_id'])){
$clan_id = check($_GET['clan_id']); //ID клана
$topic_id = check($_GET['topic_id']); //ID топика	

#-Проверяем существует ли клан-#
$sel_clan = $pdo->prepare("SELECT `id` FROM `clan` WHERE `id` = :clan_id");
$sel_clan->execute(array(':clan_id' => $clan_id));
if($sel_clan-> rowCount() == 0) $error = 'Клан не найден!';
#-Проверяем существует ли топик-#
$sel_clan_t = $pdo->prepare("SELECT `id`, `clan_id`, `razdel_id` FROM `clan_topic` WHERE `id` = :topic_id AND `clan_id` = :clan_id");
$sel_clan_t->execute(array(':topic_id' => $topic_id, ':clan_id' => $clan_id));
if($sel_clan_t-> rowCount() == 0) $error = 'Топик не найден!';
#-Есть ли права в клане-#
$sel_clan_u = $pdo->prepare("SELECT `id`, `clan_id`, `user_id`, `prava` FROM `clan_users` WHERE `user_id` = :user_id AND `clan_id` = :clan_id AND (`prava` = 3 OR `prava` = 4)");
$sel_clan_u->execute(array(':user_id' => $user['id'], ':clan_id' => $clan_id));
if($sel_clan_u->rowCount() == 0) $error = 'Нет прав на это действие!';	
	
#-Если нет ошибок-#	
if(!isset($error)){
$topic = $sel_clan_t->fetch(PDO::FETCH_LAZY);
$del_clan_t = $pdo->prepare("DELETE FROM `clan_topic` WHERE `id` = :topic_id");
$del_clan_t->execute(array(':topic_id' => $topic['id']));
$del_clan_c = $pdo->prepare("DELETE FROM `clan_comment` WHERE `topic_id` = :topic_id");
$del_clan_c->execute(array(':topic_id' => $topic['id']));
header("Location: /clan/topic/$clan_id?razdel_id=$topic[razdel_id]");
}else{
header("Location: /clan/topic/$clan_id?topic_id=$topic[razdel_id]");
$_SESSION['err'] = $error;
exit();
}
}else{
header('Location: /forum_razdel');
$_SESSION['err'] = 'Данные не переданы';
exit();
}
}
?>