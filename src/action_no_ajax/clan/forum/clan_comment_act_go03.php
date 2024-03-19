<?php
require_once '../../../system/system.php';
echo only_reg();
echo clan_level();
echo save();

#-Добавление комментарий-#
switch($act){
case 'comm':
if(isset($_POST['comment']) and isset($_GET['clan_id']) and isset($_GET['topic_id']) and isset($_GET['page'])){
$comment = check($_POST['comment']); //Комментарий
$clan_id = check($_GET['clan_id']); //ID клана
$topic_id = check($_GET['topic_id']); //ID топика
$page = check($_GET['page']); //Страница

#-Проверяем существует ли клан-#
$sel_clan = $pdo->prepare("SELECT `id` FROM `clan` WHERE `id` = :clan_id");
$sel_clan->execute(array(':clan_id' => $clan_id));
if($sel_clan-> rowCount() == 0) $error = 'Клан не найден!';
#-Проверяем существует ли топик-#
$sel_clan_t = $pdo->prepare("SELECT `id`, `close`, `clan_id`, `user_id`, `title` FROM `clan_topic` WHERE `id` = :topic_id AND `clan_id` = :clan_id AND `close` = 0");
$sel_clan_t->execute(array(':topic_id' => $topic_id, ':clan_id' => $clan_id));
if($sel_clan_t-> rowCount() == 0) $error = 'Топик не найден или закрыт!';
#-Проверяем что в клане-#
$sel_clan_u = $pdo->prepare("SELECT `id`, `clan_id`, `user_id`, `prava` FROM `clan_users` WHERE `user_id` = :user_id AND `clan_id` = :clan_id");
$sel_clan_u->execute(array(':user_id' => $user['id'], ':clan_id' => $clan_id));
if($sel_clan_u->rowCount() == 0) $error = 'Вы не состоите в клане!';

#-Комментарий-#
if(preg_match('/[\^\<\>\&\`\$]/',$_POST['comment'])) $error = 'Некорректный комментарий!';
if(mb_strlen($comment) < 1) $error = 'Пустой комментарий!';
if(mb_strlen($comment) > 2000) $error = 'Слишком длинный комментарий!';
#-Время обращения-#
if(isset($_SESSION["topic_comment"])){
$t = ((int)(time()-$_SESSION["topic_comment"]));
if ($t < 15) $error = 'Доступно через 15 сек!';}
$_SESSION["topic_comment"]=time();

#-Если нет ошибок-#
if(!isset($error)){
$topic = $sel_clan_t->fetch(PDO::FETCH_LAZY);
$ins_clan_c = $pdo->prepare("INSERT INTO `clan_comment` SET `comment` = :comment, `topic_id` = :topic_id, `clan_id` = :clan_id, `user_id` = :user_id, `time` = :time");
$ins_clan_c->execute(array(':comment' => $comment, ':topic_id' => $topic_id, ':clan_id' => $clan_id, ':user_id' => $user['id'], ':time' => time())); 
$ins_clan_l = $pdo->prepare("INSERT INTO `clan_log` SET `type` = :type, `log` = :log, `clan_id` = :clan_id, `time` = :time");
$ins_clan_l ->execute(array(':type' => 9, ':log' => "<a href='/hero/$user[id]' style='display:inline;text-decoration:underline;padding:0px;'>$user[nick]</a> оставил(а) комментарий <a href='/clan/topic_read/$clan_id?topic_id=$topic[id]' style='display:inline;text-decoration:underline;padding:0px;'>$topic[title]</a>", ':clan_id' => $clan_id, ':time' => time())); 
header("Location: /clan/topic_read/$clan_id?page=$page&topic_id=$topic_id");
}else{
header("Location: /clan/topic_read/$clan_id?page=$page&topic_id=$topic_id");
$_SESSION['err'] = $error;
exit();
}
}else{
header('Location: /clan');
$_SESSION['err'] = 'Данные не переданы!';
exit();
}
}

#-Удаление комментарий-#
switch($act){
case 'del_com':
if(isset($_GET['comm_id']) and isset($_GET['clan_id']) and isset($_GET['topic_id']) and isset($_GET['page'])){
$comment_id = check($_GET['comm_id']); //ID комментария
$clan_id = check($_GET['clan_id']); //ID клана
$topic_id = check($_GET['topic_id']); //ID топика
$page = check($_GET['page']); //Страница

#-Проверяем существует ли клан-#
$sel_clan = $pdo->prepare("SELECT `id` FROM `clan` WHERE `id` = :clan_id");
$sel_clan->execute(array(':clan_id' => $clan_id));
if($sel_clan-> rowCount() == 0) $error = 'Клан не найден!';
#-Проверяем существует ли комментарий-#
$sel_clan_c = $pdo->prepare("SELECT `id`, `clan_id`, `user_id` FROM `clan_comment` WHERE `id` = :comment_id AND `clan_id` = :clan_id");
$sel_clan_c->execute(array(':comment_id' => $comment_id, ':clan_id' => $clan_id));
if($sel_clan_c-> rowCount() == 0) $error = 'Комментарий не найден!';
#-Проверяем что в клане-#
$sel_clan_u = $pdo->prepare("SELECT `id`, `clan_id`, `user_id`, `prava` FROM `clan_users` WHERE `user_id` = :user_id AND `clan_id` = :clan_id");
$sel_clan_u->execute(array(':user_id' => $user['id'], ':clan_id' => $clan_id));
if($sel_clan_u->rowCount() == 0) $error = 'Вы не состоите в клане!';

#-Если нет ошибок-#
if(!isset($error)){
$comment = $sel_clan_c->fetch(PDO::FETCH_LAZY);
$clan_u = $sel_clan_u->fetch(PDO::FETCH_LAZY);
if(($clan_u['prava'] == 3 or $clan_u['prava'] == 4) or $comment['user_id'] == $user['id']){
$del_clan_c = $pdo->prepare("DELETE FROM `clan_comment` WHERE `id` = :comment_id");
$del_clan_c->execute(array(':comment_id' => $comment['id']));
header("Location: /clan/topic_read/$clan_id?page=$page&topic_id=$topic_id");
}else{
header("Location: /clan/topic_read/$clan_id?page=$page&topic_id=$topic_id");
$_SESSION['err'] = 'Нет прав на удаление!';
exit();
}
}else{
header("Location: /clan/topic_read/$clan_id?page=$page&topic_id=$topic_id");
$_SESSION['err'] = $error;
exit();
}
}else{
header('Location: /clan');
$_SESSION['err'] = 'Данные не переданы!';
exit();
}
}
?>