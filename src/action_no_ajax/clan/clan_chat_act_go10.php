<?php
require_once '../../system/system.php';
echo only_reg();
echo clan_level();
echo save();
session_start();
#-Отправка сообщения в клан-#
switch($act){
case 'send':
if(isset($_POST['msg']) and isset($_GET['clan_id'])){
$msg = check($_POST['msg']); //Сообщение
$ank_id = check($_POST['ank_id']); //ID кому отвечаем
$clan_id = check($_GET['clan_id']); //ID клана
#-Проверяем данные-#
#-Сообщение-#
if(preg_match('/[\^\<\>\&\`\$]/',$_POST['msg'])) $error = 'Некорректное сообщение!';
if(mb_strlen($msg) < 1) $error = 'Пустое сообщение!';
if(mb_strlen($msg) > 2000) $error = 'Слишком длинное сообщение!';
#-Время обращения-#
if (isset($_SESSION["telecod_ip"])){
$t = ((int)(time()-$_SESSION["telecod_ip"]));
if ($t < 3) $error = 'Не так часто!';}
$_SESSION["telecod_ip"]=time();
#-Проверяем что клан существует-#
$sel_clan = $pdo->prepare("SELECT `id`, `quatity_user` FROM `clan` WHERE `id` = :clan_id");
$sel_clan->execute(array(':clan_id' => $clan_id));
if($sel_clan -> rowCount() == 0) $error = 'Клан не найден!';
#-Проверяем что мы состоим в этом клане-#
$sel_clan_u = $pdo->prepare("SELECT * FROM `clan_users` WHERE `clan_id` = :clan_id AND `user_id` = :user_id");
$sel_clan_u->execute(array(':clan_id' => $clan_id, ':user_id' => $user['id']));
if($sel_clan_u -> rowCount() == 0) $error = 'Вы не состоите в этом клане!';
#-Если нет ошибок-#
if(!isset($error)){
$ins_clan_c = $pdo->prepare("INSERT INTO `clan_chat` SET `msg` = :msg, `clan_id` = :clan_id, `user_id` = :user_id, `ank_id` = :ank_id, `time` = :time");
$ins_clan_c->execute(array(':msg' => $msg, ':clan_id' => $clan_id, ':user_id' => $user['id'], ':ank_id' => $ank_id, ':time' => time())); 
header("Location: /clan/chat/$clan_id");
}else{
header("Location: /clan/chat/$clan_id");
$_SESSION['err'] = $error;
exit();
}
}else{
header('Location: /clan');
$_SESSION['err'] = 'Данные отсутствуют! Обратитесь к администрации.';
exit();
}
}


#-Удаление сообщения из чата-#
switch($act){
case 'del':
if(isset($_GET['msg_id']) and isset($_GET['clan_id'])){
$msg_id = check($_GET['msg_id']); //ID Сообщение
$clan_id = check($_GET['clan_id']); //ID клана
#-Проверяем что клан существует-#
$sel_clan = $pdo->prepare("SELECT `id`, `quatity_user` FROM `clan` WHERE `id` = :clan_id");
$sel_clan->execute(array(':clan_id' => $clan_id));
if($sel_clan -> rowCount() == 0) $error = 'Клан не найден!';
#-Проверяем что мы состоим в этом клане и есть права на удаление-#
$sel_clan_u = $pdo->prepare("SELECT * FROM `clan_users` WHERE `clan_id` = :clan_id AND `user_id` = :user_id AND (`prava` = 3 OR `prava` = 4)");
$sel_clan_u->execute(array(':clan_id' => $clan_id, ':user_id' => $user['id']));
if($sel_clan_u -> rowCount() == 0) $error = 'Вы не состоите в этом клане или нет прав на удаление!';
#-Проверяем что есть такое сообщение-#
$sel_clan_c = $pdo->prepare("SELECT * FROM `clan_chat` WHERE `id` = :msg_id AND `clan_id` = :clan_id");
$sel_clan_c->execute(array(':msg_id' => $msg_id, ':clan_id' => $clan_id));
if($sel_clan_c -> rowCount() == 0) $error = 'Сообщение не найдено!';
#-Если нет ошибок-#
if(!isset($error)){
#-Удаляем сообщение-#
$del_clan_c = $pdo->prepare("DELETE FROM `clan_chat` WHERE `id` = :msg_id");
$del_clan_c->execute(array(':msg_id' => $msg_id)); 
header("Location: /clan/chat/$clan_id");
}else{
header("Location: /clan/chat/$clan_id");
$_SESSION['err'] = $error;
exit();
}
}else{
header('Location: /clan');
$_SESSION['err'] = 'Данные отсутствуют! Обратитесь к администрации.';
exit();
}
}
?>