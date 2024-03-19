<?php
require_once '../../system/system.php';
echo only_reg();
echo clan_level();
echo save();
/*Редактирование описания и типа*/
switch($act){
case 'edit_d':
if(isset($_POST['description']) and isset($_POST['type']) and isset($_GET['clan_id'])){
$description = check($_POST['description']); //Описание
$type = check($_POST['type']); //Тип
$clan_id = check($_GET['clan_id']); //ID клана
#-Проверяем описание-#
if(mb_strlen($descriptin) > 0){
if(preg_match('/[\^\<\>\&\`\$]/',$_POST['description'])) $error = 'Некорректное описание!';
if(mb_strlen($descriptin) > 2000) $error = 'Описание в пределах 2000 символов';
}
#-Проверяем тип-#
if(!in_array($type, array('0', '1'))) $error = 'Неверный тип!';
#-Проверяем что клан существует-#
$sel_clan = $pdo->prepare("SELECT `id` FROM `clan` WHERE `id` = :clan_id");
$sel_clan->execute(array(':clan_id' => $clan_id));
if($sel_clan -> rowCount() == 0) $error = 'Клан не найден!';
#-Проверяем что мы состоим в этом клане-#
$sel_clan_u = $pdo->prepare("SELECT * FROM `clan_users` WHERE `clan_id` = :clan_id AND `user_id` = :user_id AND (`prava` = 4 OR `prava` = 3)");
$sel_clan_u->execute(array(':clan_id' => $clan_id, ':user_id' => $user['id']));
if($sel_clan_u -> rowCount() == 0) $error = 'Вы не состоите в клане или у вас нет прав!';
#-Если нет ошибок-#
if(!isset($error)){
#-Редактируем-#
$upd_clan = $pdo->prepare("UPDATE `clan` SET `description` = :description, `close` = :type WHERE `id` = :clan_id");
$upd_clan->execute(array(':description' => $description, ':type' => $type, ':clan_id' => $clan_id));
header("Location: /clan/view/$clan_id");
}else{
header('Location: /clan');
$_SESSION['err'] = $error;
exit();
}
}else{
header('Location: /clan');
$_SESSION['err'] = 'Данные отсутствуют!';
exit();
}
}

#-Новое название клана-#
switch($act){
case 'edit_n':
if(isset($_POST['name']) and isset($_GET['clan_id'])){
$name = check($_POST['name']); //Описание
$clan_id = check($_GET['clan_id']); //ID клана
#-Проверяем название-#
if(preg_match('/[\^\<\>\&\`\$]/',$_POST['name'])) $error = 'Некорректное название!';
if(mb_strlen($name) < 5) $error = 'Название меньше 5 символов!';
if(mb_strlen($name) > 25) $error = 'Название больше 25 символов';
#-Проверяем есть ли такое название клана-#
$sel_clan_n = $pdo->prepare("SELECT `name` FROM `clan` WHERE `name` = :name");
$sel_clan_n->execute(array(':name' => $name)); 
if($sel_clan_n-> rowCount() != 0) $error = 'Уже есть такое название!';
#-Проверяем что клан существует-#
$sel_clan = $pdo->prepare("SELECT `id`, `gold` FROM `clan` WHERE `id` = :clan_id");
$sel_clan->execute(array(':clan_id' => $clan_id));
if($sel_clan -> rowCount() == 0) $error = 'Клан не найден!';
#-Проверяем что мы состоим в этом клане-#
$sel_clan_u = $pdo->prepare("SELECT * FROM `clan_users` WHERE `clan_id` = :clan_id AND `user_id` = :user_id AND (`prava` = 4 OR `prava` = 3)");
$sel_clan_u->execute(array(':clan_id' => $clan_id, ':user_id' => $user['id']));
if($sel_clan_u -> rowCount() == 0) $error = 'Вы не состоите в клане или у вас нет прав!';
#-Если нет ошибок-#
if(!isset($error)){
$clan = $sel_clan->fetch(PDO::FETCH_LAZY);
#-Проверяем достаточно ли золота-#
if($clan['gold'] >= 350){
#-Редактируем-#
$upd_clan = $pdo->prepare("UPDATE `clan` SET `name` = :name, `gold` = :gold WHERE `id` = :clan_id");
$upd_clan->execute(array(':name' => $name, ':gold' => $clan['gold']-350, ':clan_id' => $clan['id']));
#-История клана-#
$ins_clan_l = $pdo->prepare("INSERT INTO `clan_log` SET `type` = :type, `log` = :log, `clan_id` = :clan_id, `time` = :time");
$ins_clan_l ->execute(array(':type' => 6, ':log' => "Название клана изменено на: $name", ':clan_id' => $clan_id, ':time' => time())); 
header("Location: /clan/view/$clan_id");
}else{
header("Location: /clan/edit/$clan_id");
$_SESSION['err'] = 'Недостаточно золота!';
exit();
}
}else{
header("Location: /clan/edit/$clan_id");
$_SESSION['err'] = $error;
exit();
}
}else{
header('Location: /clan');
$_SESSION['err'] = 'Данные отсутствуют!';
exit();
}
}
?>