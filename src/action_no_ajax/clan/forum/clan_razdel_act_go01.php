<?php
require_once '../../../system/system.php';
echo only_reg();
echo clan_level();
echo save();

#-Создание раздела-#
switch($act){
case 'add':
if(isset($_POST['name']) and isset($_GET['clan_id'])){
$name = check($_POST['name']); //Имя раздела
$prava = intval(check($_POST['prava']));
$clan_id = check($_GET['clan_id']); //ID клана

#-Проверяем существует ли клан-#
$sel_clan = $pdo->prepare("SELECT `id` FROM `clan` WHERE `id` = :clan_id");
$sel_clan->execute(array(':clan_id' => $clan_id));
if($sel_clan-> rowCount() == 0) $error = 'Клан не найден!';
#-Не более 10 разделов-#
$sel_clan_r = $pdo->prepare("SELECT `id`, `clan_id` FROM `clan_razdel` WHERE `clan_id` = :clan_id");
$sel_clan_r->execute(array(':clan_id' => $clan_id));
if($sel_clan_r-> rowCount() > 9) $error = 'Не более 10 разделов!';
#-Есть ли игрок с правами-#
$sel_clan_u = $pdo->prepare("SELECT `id`, `clan_id`, `user_id`, `prava` FROM `clan_users` WHERE `user_id` = :user_id AND `clan_id` = :clan_id AND (`prava` = 4 OR `prava` = 3)");
$sel_clan_u->execute(array(':user_id' => $user['id'], ':clan_id' => $clan_id));
if($sel_clan_u->rowCount() == 0) $error = 'Игрок не найден или нет прав!';
#-Имя раздела-#
if(preg_match('/[\^\<\>\&\`\$]/',$_POST['name'])) $error = 'Некорректное название!';
if(mb_strlen($name) < 1) $error = 'Пустое название!';
if(mb_strlen($name) > 50) $error = 'Слишком длинное название!';

#-Если нет ошибок-#
if(!isset($error)){
$ins_clan_r = $pdo->prepare("INSERT INTO `clan_razdel` SET `name` = :name, `clan_id` = :clan_id,`prava` = :prava, `time` = :time");
$ins_clan_r->execute(array(':name' => $name, ':clan_id' => $clan_id, ':prava' => $prava, ':time' => time())); 
$razdel_id = $pdo->lastInsertId();
$ins_clan_l = $pdo->prepare("INSERT INTO `clan_log` SET `type` = :type, `log` = :log, `clan_id` = :clan_id, `time` = :time");
$ins_clan_l ->execute(array(':type' => 9, ':log' => "<a href='/hero/$user[id]' style='display:inline;text-decoration:underline;padding:0px;'>$user[nick]</a> создал(а) раздел <a href='/clan/topic/$clan_id?razdel_id=$razdel_id' style='display:inline;text-decoration:underline;padding:0px;'>$name</a>", ':clan_id' => $clan_id, ':time' => time())); 
header("Location: /clan/razdel/$clan_id");
}else{
header("Location: /clan/razdel/$clan_id?razdel_create=on");
$_SESSION['err'] = $error;
exit();
}
}else{
header('Location: /clan');
$_SESSION['err'] = 'Данные не переданы!';
exit();
}
}

#-Удаление раздела-#
switch($act){
case 'del':
if(isset($_GET['clan_id']) and isset($_GET['razdel_id'])){
$clan_id = check($_GET['clan_id']); //ID клана
$razdel_id = check($_GET['razdel_id']); //ID раздела

#-Проверяем существует ли клан-#
$sel_clan = $pdo->prepare("SELECT `id` FROM `clan` WHERE `id` = :clan_id");
$sel_clan->execute(array(':clan_id' => $clan_id));
if($sel_clan-> rowCount() == 0) $error = 'Клан не найден!';
#-Существует ли раздел-#
$sel_razdel = $pdo->prepare("SELECT `id`, `name` FROM `clan_razdel` WHERE `id` = :razdel_id AND `clan_id` = :clan_id");
$sel_razdel->execute(array(':razdel_id' => $razdel_id, ':clan_id' => $clan_id));
if($sel_razdel-> rowCount() == 0) $error = 'Раздел не найден!';
#-Есть ли игрок с правами-#
$sel_clan_u = $pdo->prepare("SELECT `id`, `clan_id`, `user_id`, `prava` FROM `clan_users` WHERE `user_id` = :user_id AND `clan_id` = :clan_id AND (`prava` = 4 OR `prava` = 3)");
$sel_clan_u->execute(array(':user_id' => $user['id'], ':clan_id' => $clan_id));
if($sel_clan_u->rowCount() == 0) $error = 'Игрок не найден или нет прав!';

#-Если нет ошибок-#
if(!isset($error)){
$razdel = $sel_razdel->fetch(PDO::FETCH_LAZY);
$del_clan_r = $pdo->prepare("DELETE FROM `clan_razdel` WHERE `id` = :razdel_id");
$del_clan_r->execute(array(':razdel_id' => $razdel['id'])); 
$sel_topic = $pdo->prepare("SELECT * FROM `clan_topic` WHERE `razdel_id` = :razdel_id");
$sel_topic->execute(array(':razdel_id' => $razdel['id']));
while($topic = $sel_topic->fetch(PDO::FETCH_LAZY)){
$del_clan_c = $pdo->prepare("DELETE FROM `clan_comment` WHERE `topic_id` = :topic_id");
$del_clan_c->execute(array(':topic_id' => $topic['id']));
$del_clan_t = $pdo->prepare("DELETE FROM `clan_topic` WHERE `id` = :topic_id");
$del_clan_t->execute(array(':topic_id' => $topic['id']));
}
$ins_clan_l = $pdo->prepare("INSERT INTO `clan_log` SET `type` = :type, `log` = :log, `clan_id` = :clan_id, `time` = :time");
$ins_clan_l ->execute(array(':type' => 9, ':log' => "<a href='/hero/$user[id]' style='display:inline;text-decoration:underline;padding:0px;'>$user[nick]</a> удалил(а) раздел $razdel[name] из форума", ':clan_id' => $clan_id, ':time' => time())); 
header("Location: /clan/razdel/$clan_id?razdel_delete=on");
}else{
header("Location: /clan/razdel/$clan_id?razdel_delete=on");
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