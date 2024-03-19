<?php
require_once '../../system/system.php';
echo only_reg();
echo save();
#-Создание топика-#
switch($act){
case 'add':
if($_GET['pod']==1){
$msg = check($_POST['title']); //Сообщение
$razdel_id = check($_GET['razdel']); //Раздел
#-Проверяем существует ли раздел-#
$sel_f_razdel = $pdo->prepare("SELECT * FROM `forum_razdel` WHERE `id` = :razdel_id");
$sel_f_razdel->execute(array(':razdel_id' => $razdel_id));
if($sel_f_razdel-> rowCount() != 0){
#-Проверяем данные-#
#-Проверяем можно ли создавать топик-#
if($user['prava']==0){
if($user['topic_time'] > time()) $error = 'Создавать топик можно раз в 10 мин.';
}
#-Заголовок-#
if(preg_match('/[\^\<\>\&\`\$]/',$_POST['title'])) $error = 'Некорректное сообщение!';
if(mb_strlen($msg) < 1) $error = 'Пустой заголовок!';
if(mb_strlen($msg) > 50) $error = 'Слишком длинный заголовок!';
#-Раздел-#
if(!preg_match('/^[0-9]+$/u',$_GET['razdel'])) $error = 'Неккоректный раздел!';
#-Если нет ошибок-#
if(!isset($error)){
$ins_forum_t = $pdo->prepare("INSERT INTO `forum_razdel` SET `name` = :name, `pod` = :pod");
$ins_forum_t->execute(array(':name' => $msg, ':pod' => $razdel_id)); 
header("Location: /forum_topic/$razdel_id");
}else{
header("Location: /topic_add?razdel=$razdel_id");
$_SESSION['err'] = $error;
exit();
}
}else{
header("Location: /forum_razdel");
$_SESSION['err'] = 'Раздел не найден!';
exit();
}
exit();
}elseif($user['ban'] == 0 and $user['level'] >= 30){
if(isset($_POST['title']) and isset($_POST['msg']) and isset($_GET['razdel'])){
$title = check($_POST['title']); //Заголовок
$msg = check($_POST['msg']); //Сообщение
$razdel_id = check($_GET['razdel']); //Раздел
#-Проверяем существует ли раздел-#
$sel_f_razdel = $pdo->prepare("SELECT * FROM `forum_razdel` WHERE `id` = :razdel_id");
$sel_f_razdel->execute(array(':razdel_id' => $razdel_id));
if($sel_f_razdel-> rowCount() != 0){
#-Проверяем данные-#
#-Проверяем можно ли создавать топик-#
if($user['prava']==0){
if($user['topic_time'] > time()) $error = 'Создавать топик можно раз в 10 мин.';
}
#-Заголовок-#
if(preg_match('/[\^\<\>\&\`\$]/',$_POST['title'])) $error = 'Некорректное сообщение!';
if(mb_strlen($title) < 1) $error = 'Пустой заголовок!';
if(mb_strlen($title) > 50) $error = 'Слишком длинный заголовок!';
#-Сообщение-#
if(preg_match('/[\^\<\>\&\`\$]/',$_POST['msg'])) $error = 'Некорректное сообщение!';
if(mb_strlen($msg) < 1) $error = 'Пустое сообщение!';
if(mb_strlen($msg) > 5000) $error = 'Слишком длинное сообщение!';
#-Раздел-#
if(!preg_match('/^[0-9]+$/u',$_GET['razdel'])) $error = 'Неккоректный раздел!';
#-Если нет ошибок-#
if(!isset($error)){
$ins_forum_t = $pdo->prepare("INSERT INTO `forum_topic` SET `title` = :title, `msg` = :msg, `user_id` = :user_id, `razdel_id` = :razdel_id, `time` = :time");
$ins_forum_t->execute(array(':title' => $title, ':msg' => $msg, ':user_id' => $user['id'], ':razdel_id' => $razdel_id, ':time' => time())); 
#-Ставим время на создание следующего топика-#
$upd_users = $pdo->prepare("UPDATE `users` SET `topic_time` = :time WHERE `id` = :user_id");
$upd_users->execute(array(':time' => time()+600, ':user_id' => $user['id'])); 
header("Location: /forum_topic/$razdel_id");
}else{
header("Location: /topic_add?razdel=$razdel_id");
$_SESSION['err'] = $error;
exit();
}
}else{
header("Location: /forum_razdel");
$_SESSION['err'] = 'Раздел не найден!';
exit();
}
}else{
header("Location: /forum_razdel");
$_SESSION['err'] = 'Ошибка!';
exit();
}
}else{
header('Location: /forum_razdel');
$_SESSION['err'] = 'Вы не можете создать топик!';
exit();
}
}

#-Редактирование топика-#
switch($act){
case 'edit':
#-Проверяем забанены или нет-#
if($user['ban'] == 0){
if(isset($_POST['title']) and isset($_POST['msg']) and isset($_GET['topic'])){
$title = check($_POST['title']); //Заголовок
$msg = check($_POST['msg']); //Сообщение
$topic_id = check($_GET['topic']); //Топик
#-Проверяем существует ли такой топик-#
$sel_f_topic = $pdo->prepare("SELECT * FROM `forum_topic` WHERE `id` = :topic_id");
$sel_f_topic->execute(array(':topic_id' => $topic_id));
if($sel_f_topic-> rowCount() != 0){
$topic = $sel_f_topic->fetch(PDO::FETCH_LAZY);
#-Проверяем данные-#
#-Заголовок-#
if(preg_match('/[\^\<\>\&\`\$]/',$_POST['title'])) $error = 'Некорректное сообщение!';
if(mb_strlen($title) < 1) $error = 'Пустой заголовок!';
if(mb_strlen($title) > 50) $error = 'Слишком длинный заголовок!';
#-Сообщение-#
if(preg_match('/[\^\<\>\&\`\$]/',$_POST['msg'])) $error = 'Некорректное сообщение!';
if(mb_strlen($title) < 1) $error = 'Пустое сообщение!';
if(mb_strlen($title) > 5000) $error = 'Слишком длинное сообщение!';
#-Раздел-#
if(!preg_match('/^[0-9]+$/u',$_GET['topic'])) $error = 'Некорректный идентификатор!';
#-Проверяем права-#
if($user['id'] == $topic['user_id'] or $user['prava'] == 1 or $user['prava'] == 2 or $user['prava'] == 3){
#-Если нет ошибок-#
if(!isset($error)){
$upd_forum_t = $pdo->prepare("UPDATE `forum_topic` SET `title` = :title, `msg` = :msg, `edit` = :edit WHERE `id` = :topic_id");
$upd_forum_t->execute(array(':title' => $title, ':msg' => $msg, ':edit' => $topic['edit']+1, ':topic_id' => $topic_id)); 
header("Location: /forum_topic/read/$topic_id");
}else{
header("Location: /forum_topic/edit/$topic_id");
$_SESSION['err'] = $error;
exit();
}
}else{
header('Location: /forum_razdel');
$_SESSION['err'] = 'Нет прав на редактирование или истекло время!';
exit();
}
}else{
header("Location: /forum_razdel");
$_SESSION['err'] = 'Топик не найден!';
exit();
}
}else{
header("Location: /forum_razdel");
$_SESSION['err'] = 'Ошибка!';
exit();
}
}else{
header('Location: /forum_razdel');
$_SESSION['err'] = 'Вы забанены!';
exit();
}
}

#-Фиксация топика-#
switch($act){
case 'fix':
#-Проверяем права-#
if($user['prava'] == 1 or $user['prava'] == 2 or $user['prava'] == 3){
if(isset($_GET['topic'])){
$topic_id = check($_GET['topic']); //Топик
#-Проверяем существует ли такой топик-#
$sel_f_topic = $pdo->prepare("SELECT * FROM `forum_topic` WHERE `id` = :topic_id");
$sel_f_topic->execute(array(':topic_id' => $topic_id));
if($sel_f_topic-> rowCount() != 0){
$topic = $sel_f_topic->fetch(PDO::FETCH_LAZY);
#-Закрепить или открепить-#
if($topic['verh'] == 0){
$verh = '1';
$ins_forum_com = $pdo->prepare("INSERT INTO `forum_comment` SET `comment` = :comment, `topic_id` = :topic_id, `user_id` = :user_id, `time` = :time");
$ins_forum_com ->execute(array(':comment' => 'Топик закреплен!', ':topic_id' => $topic_id, ':user_id' => $user['id'], ':time' => time())); 
}else{
$verh = '0';	
$ins_forum_com = $pdo->prepare("INSERT INTO `forum_comment` SET `comment` = :comment, `topic_id` = :topic_id, `user_id` = :user_id, `time` = :time");
$ins_forum_com ->execute(array(':comment' => 'Топик откреплен!', ':topic_id' => $topic_id, ':user_id' => $user['id'], ':time' => time())); 
}
$upd_forum_t = $pdo->prepare("UPDATE `forum_topic` SET `verh` = :verh WHERE `id` = :topic_id");
$upd_forum_t->execute(array(':verh' => $verh,':topic_id' => $topic_id)); 
header ("Location: /forum_topic/read/$topic_id");
}else{
header ("Location: /forum_razdel");
$_SESSION['err'] = 'Топик не найден!';
exit();
}
}else{
header ("Location: /forum_razdel");
$_SESSION['err'] = 'Ошибка!';
exit();
}
}else{
header('Location: /forum_razdel');
$_SESSION['err'] = 'Нет прав!';
exit();
}
}

#-Закрытие или открытие-#
switch($act){
case 'close':
#-Проверяем права-#
if($user['prava'] == 1 or $user['prava'] == 2 or $user['prava'] == 3){
if(isset($_GET['topic'])){
$topic_id = check($_GET['topic']); //Топик
#-Проверяем существует ли такой топик-#
$sel_f_topic = $pdo->prepare("SELECT * FROM `forum_topic` WHERE `id` = :topic_id");
$sel_f_topic->execute(array(':topic_id' => $topic_id));
if($sel_f_topic-> rowCount() != 0){
$topic = $sel_f_topic->fetch(PDO::FETCH_LAZY);
#-Закрепить или открепить-#
if($topic['close'] == 0){
$close = '1';
$ins_forum_com = $pdo->prepare("INSERT INTO `forum_comment` SET `comment` = :comment, `topic_id` = :topic_id, `user_id` = :user_id, `time` = :time");
$ins_forum_com ->execute(array(':comment' => 'Топик закрыт!', ':topic_id' => $topic_id, ':user_id' => $user['id'], ':time' => time())); 
}else{
$close = '0';	
$ins_forum_com = $pdo->prepare("INSERT INTO `forum_comment` SET `comment` = :comment, `topic_id` = :topic_id, `user_id` = :user_id, `time` = :time");
$ins_forum_com ->execute(array(':comment' => 'Топик открыт!', ':topic_id' => $topic_id, ':user_id' => $user['id'], ':time' => time())); 
}
$upd_forum_t = $pdo->prepare("UPDATE `forum_topic` SET `close` = :close WHERE `id` = :topic_id");
$upd_forum_t->execute(array(':close' => $close, ':topic_id' => $topic_id)); 
header("Location: /forum_topic/read/$topic_id");
}else{
header("Location: /forum_razdel");
$_SESSION['err'] = 'Топик не найден!';
exit();
}
}else{
header("Location: /forum_razdel");
$_SESSION['err'] = 'Ошибка!';
exit();
}
}else{
header('Location: /forum_razdel');
$_SESSION['err'] = 'Нет прав!';
exit();
}
}

#-Удаление топика-#
switch($act){
case 'delete':
#-Проверяем права-#
if($user['prava'] == 1 or $user['prava'] == 2 or $user['prava'] == 3){
if(isset($_GET['topic'])){
$topic_id = check($_GET['topic']); //Топик
#-Проверяем существует ли такой топик-#
$sel_f_topic = $pdo->prepare("SELECT * FROM `forum_topic` WHERE `id` = :topic_id");
$sel_f_topic->execute(array(':topic_id' => $topic_id));
if($sel_f_topic-> rowCount() != 0){
$topic = $sel_f_topic->fetch(PDO::FETCH_LAZY);
#-Убираем у игроков статус прочитано-#
$sel_nav_f = $pdo->query("SELECT `id`, `topic` FROM `forum_navigation`");
while($nav_f = $sel_nav_f->fetch(PDO::FETCH_LAZY)){
$new_m = preg_replace('/\{'.$topic['id'].'\}/', '', $nav_f['topic']);
$upd_nav_f = $pdo->prepare("UPDATE `forum_navigation` SET `topic` = :topic WHERE `id` = :id");
$upd_nav_f->execute(array(':topic' => $new_m, ':id' => $nav_f['id']));
}
$del_forum_t = $pdo->prepare("DELETE FROM `forum_topic` WHERE `id` = :topic_id");
$del_forum_t->execute(array(':topic_id' => $topic_id)); 
$del_forum_c = $pdo->prepare("DELETE FROM `forum_comment` WHERE `topic_id` = :topic_id");
$del_forum_c->execute(array(':topic_id' => $topic_id)); 
header ("Location: /forum_topic/$topic[razdel_id]");
$_SESSION['ok'] = 'Топик удален!';
exit();
}else{
header("Location: /forum_razdel");
$_SESSION['err'] = 'Топик не найден!';
exit();
}
}else{
header("Location: /forum_razdel");
$_SESSION['err'] = 'Ошибка!';
exit();
}
}else{
header('Location: /forum_razdel');
$_SESSION['err'] = 'Нет прав!';
exit();
}
}

#-Редактирование комментария-#
switch($act){
case 'edit_comm':
if(isset($_GET['comment_id']) and isset($_POST['comment'])){
$comment_id = check($_GET['comment_id']); //ID Комментария
$comment_msg = check($_POST['comment']); //Комментарий
if(preg_match('/[\^\<\>\&\`\$]/',$_POST['comment'])) $error = 'Неккоректный комментарий!';
if(mb_strlen($comment_msg) < 1) $error = 'Пустой комментарий!';
if(mb_strlen($comment_msg) > 2000) $error = 'Слишком длинный комментарий!';
#-Проверяем существует ли такой Комментарий-#
$sel_f_comment = $pdo->prepare("SELECT * FROM `forum_comment` WHERE `id` = :comment_id");
$sel_f_comment->execute(array(':comment_id' => $comment_id));
if($sel_f_comment-> rowCount() == 0) $error = 'Комментарий не найден!';
#-Если нет ошибок-#
if(!isset($error)){
$comment = $sel_f_comment->fetch(PDO::FETCH_LAZY);
#-Проверяем права и время-#
if($user['ban'] == 0){
if($comment['user_id'] == $user['id'] and $comment['time'] > time()-600){
$upd_forum_c = $pdo->prepare("UPDATE `forum_comment` SET `comment` = :comment WHERE `id` = :comment_id");
$upd_forum_c->execute(array(':comment' => $comment_msg, ':comment_id' => $comment['id'])); 
header("Location: /forum_topic/read/$comment[topic_id]");
}else{
header("Location: /forum_topic/read/$comment[topic_id]");
$_SESSION['err'] = 'Нет прав на удаление или истекло время!';
exit();
}
}else{
header("Location: /forum_topic/read/$comment[topic_id]");
$_SESSION['err'] = 'Вы забанены!';
exit();
}
}else{
header("Location: /forum_topic/read/$comment[topic_id]");
$_SESSION['err'] = $error;
exit();
}
}else{
header('Location: /forum_razdel');
$_SESSION['err'] = 'Данные не переданы!';
exit();
}
}

#-Удаление комментария-#
switch($act){
case 'del_com':
if(isset($_GET['comment'])){
$comment_id = check($_GET['comment']); //Комментарий
$topic_id = check($_GET['topic']);
#-Проверяем существует ли такой Комментарий-#
$sel_f_comment = $pdo->prepare("SELECT * FROM `forum_comment` WHERE `id` = :comment_id");
$sel_f_comment->execute(array(':comment_id' => $comment_id));
if($sel_f_comment-> rowCount() != 0){
$comment = $sel_f_comment->fetch(PDO::FETCH_LAZY);
#-Проверяем права и время удаления-#
if($user['prava'] == 1 or $user['prava'] == 2){
$del_forum_c = $pdo->prepare("DELETE FROM `forum_comment` WHERE `id` = :comment_id");
$del_forum_c->execute(array(':comment_id' => $comment_id)); 
header("Location: /forum_topic/read/$topic_id");
}else{
header('Location: /forum_razdel');
$_SESSION['err'] = 'Нет прав на удаление или истекло время!';
exit();
}
}else{
header("Location: /forum_topic/read/$topic_id");
$_SESSION['err'] = 'Комментарий не найден!';
exit();
}
}else{
header('Location: /forum_razdel');
$_SESSION['err'] = 'Нет прав!';
exit();
}
}

#-Отмечаем всё как прочитанное-#
switch($act){
case 'read_all':
if(isset($_GET['razdel'])){
$razdel = check($_GET['razdel']);
#-Выборка всех топиков раздела-#
$sel_topic_a = $pdo->prepare("SELECT `id`, `razdel_id` FROM `forum_topic` WHERE `razdel_id` = :razdel");
$sel_topic_a->execute(array(':razdel' => $razdel));
while($topic_a = $sel_topic_a->fetch(PDO::FETCH_LAZY)){
#-Навигация по форуму-#
$sel_f_nav = $pdo->prepare("SELECT * FROM `forum_navigation` WHERE `user_id` = :user_id");
$sel_f_nav->execute(array(':user_id' => $user['id']));
if($sel_f_nav->rowCount() != 0){
$nav_f = $sel_f_nav->fetch(PDO::FETCH_LAZY);
#-Если не читали топик записываем-#
if(!preg_match('/\{'.$topic_a['id'].'\}/',$nav_f['topic'])){
$upd_nav_f = $pdo->prepare("UPDATE `forum_navigation` SET `topic` = :topic WHERE `user_id` = :user_id");
$upd_nav_f->execute(array(':topic' => $nav_f['topic'].'{'.$topic_a['id'].'}', ':user_id' => $user['id']));
}
}
}
header("Location: /forum_topic/$razdel");
}else{
header('Location: /forum_razdel');
$_SESSION['err'] = 'Данные не переданы!';
exit();
}
}
?>