<?php
require "../../pass/lib/password.php";
require_once '../../system/system.php';
echo only_reg();
/*Действия настроек*/

#-Смена ника-#
switch($act){
case 'nick':
if(isset($_POST['nick'])){
$nick = check($_POST['nick']); //Ник
#-Проверяем данные-#
#-Ник-#
if(preg_match('/[\^\<\>\&\`\$]/',$_POST['nick'])) $error = 'Введите другой ник!';
if(mb_strlen($nick) < 1) $error = 'Введите ник!';
if(mb_strlen($nick) > 15) $error = 'Слишком длинный ник!';
#-Выборка ника из бд-#
$sel_users = $pdo->prepare("SELECT `nick` FROM `users` WHERE `nick` = :num_1");
$sel_users->execute(array(':num_1' => $nick)); 
$all = $sel_users->fetch(PDO::FETCH_LAZY);
#-Проверяем поменял ли игрок ник-#
if($all['nick'] != $user['nick']){
if($sel_users-> rowCount() != 0) $error = 'Такой ник занят!';
#-Достаточно ли золота-#
if($user['gold'] >= 1000){
#-Если нет ошибок-#
if(!isset($error)){
$upd_users = $pdo->prepare("UPDATE `users` SET `nick` = :nick, `gold` = :gold WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':nick' => $nick, ':gold' => $user['gold']-1000, ':id' => $user['id'])); 
#-Ставим куки-#
setcookie('UsN',$nick, time()+86400, '/');
header('Location: /setting');
$_SESSION['ok'] = 'Ник сменен!';
exit();
}else{
header('Location: /setting?change=nick');
$_SESSION['err'] = $error;
exit();
}
}else{
header('Location: /setting?change=nick');
$_SESSION['err'] = 'Недостаточно золота!';
exit();
}
}else{
header('Location: /setting');
exit();
}
}else{
header ("Location: /setting");
$_SESSION['err'] = 'Введите данные!';
exit();
}
}

#-Смена пароля-#
switch($act){
case 'password':
if(isset($_POST['pass_old']) and isset($_POST['pass_new']) and isset($_POST['pass_rep'])){
$pass_old = check($_POST['pass_old']); //Старый пароль
$pass_new = check($_POST['pass_new']); //Новый пароль
$pass_rep = check($_POST['pass_rep']); //Повтор пароля
$pass_encrypt = password_hash($pass_rep, PASSWORD_DEFAULT); //Зашифрованный пароль
#-Проверяем данные-#
#-Пароль старый-#
if(!preg_match('/^([а-яА-яЁёa-zA-Z0-9]+)([а-яА-яЁёa-zA-Z0-9!@#$%^&*()\-_\+;:,.\/?\|\~\[\]\{\}])+$/u',$_POST['pass_old'])) $error = 'Неккоректный пароль!';
if(mb_strlen($pass_old) < 6) $error = 'Минимальная длина пароля 6 символов!';
if(mb_strlen($pass_old) > 25) $error = 'Слишком длинный пароль!';
#-Пароль новый-#
if(!preg_match('/^([а-яА-яЁёa-zA-Z0-9]+)([а-яА-яЁёa-zA-Z0-9!@#$%^&*()\-_\+;:,.\/?\|\~\[\]\{\}])+$/u',$_POST['pass_new'])) $error = 'Неккоректный пароль!';
if(mb_strlen($pass_new) < 6) $error = 'Минимальная длина пароля 6 символов!';
if(mb_strlen($pass_new) > 25) $error = 'Слишком длинный пароль!';
#-Повтор пароля-#
if(mb_strlen($pass_rep) < 6) $error = 'Повторите пароль!';
if(mb_strlen($pass_rep) != mb_strlen($pass) & $pass_dubl != $pass) $error = 'Пароли не совпадают!';
#-Выборка пароля из бд-#
$sel_users = $pdo->prepare("SELECT `id`,`password` FROM `users` WHERE `id` = :id");
$sel_users->execute(array(':id' => $user['id'])); 
$result = $sel_users->fetch(PDO::FETCH_LAZY);
#-Проверяем пароль-#
if(password_verify($pass_old,$result['password'])){
#-Если нет ошибок-#
if(!isset($error)){
$upd_users = $pdo->prepare("UPDATE `users` SET `password` = :password WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':password' => $pass_encrypt, ':id' => $user['id'])); 
header('Location: /setting');
$_SESSION['ok'] = 'Пароль сменен!';
exit();
}else{
header('Location: /setting?change=password');
$_SESSION['err'] = $error;
exit();
}
}else{
header('Location: /setting?change=password');
$_SESSION['err'] = 'Старый пароль неверный!';
exit();
}
}else{
header ("Location: /setting");
$_SESSION['err'] = 'Введите данные!';
exit();
}
}

#-Смена пола-#
switch($act){
case 'pol':
if(isset($_POST['pol'])){
$pol = check($_POST['pol']);
if(!in_array($pol, array('1','2'))) $error = 'Вы вообще какого пола?!';
#-Проверяем поменял ли игрок пол-#
if($pol != $user['pol']){
#-Достаточно ли золота-#
if($user['gold'] >= 500){
#-Если нет ошибок-#
if(!isset($error)){
$upd_users = $pdo->prepare("UPDATE `users` SET `pol` = :pol, `gold` = :gold WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':pol' => $pol, ':gold' => $user['gold']-500, ':id' => $user['id'])); 	
header('Location: /setting');
$_SESSION['ok'] = 'Пол сменен!';
exit();
}else{
header('Location: /setting?change=pol');
$_SESSION['err'] = $error;
exit();
}
}else{
header('Location: /setting?change=pol');
$_SESSION['err'] = 'Недостаточно золота!';
exit();
}
}else{
header ("Location: /setting");
exit();
}
}else{
header ("Location: /setting");
$_SESSION['err'] = 'Введите данные!';
exit();
}
}

#-Смена Стороны-#
switch($act){
case 'storona':
if(isset($_POST['storona'])){
$storona = check($_POST['storona']);
if(!in_array($storona, array('1','2'))) $error = 'Сторона выбрана неверно!';
if($user['battle'] != 0) $error = 'Вы в бою!';
$sel_zamki_u = $pdo->prepare("SELECT `id`, `user_id` FROM `zamki_users` WHERE `user_id` = :user_id");
$sel_zamki_u->execute(array(':user_id' => $user['id']));
if($sel_zamki_u-> rowCount() != 0) $error = 'Вы участвуете в сражении!';
#-Проверяем поменял ли игрок сторону-#
if($storona != $user['storona']){
#-Цена золота-#
if($user['storona'] == 0){
$gold = 0;
}else{
$gold = 500;
}
#-Достаточно ли золота-#
if($user['gold'] >= $gold){
#-Если нет ошибок-#
if(!isset($error)){
$upd_users = $pdo->prepare("UPDATE `users` SET `storona` = :storona, `gold` = :gold WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':storona' => $storona, ':gold' => $user['gold']-$gold, ':id' => $user['id'])); 	
header('Location: /setting');
$_SESSION['ok'] = 'Сторона сменена!';
exit();
}else{
header('Location: /setting?change=storona');
$_SESSION['err'] = $error;
exit();
}
}else{
header('Location: /setting?change=storona');
$_SESSION['err'] = 'Недостаточно золота!';
exit();
}
}else{
header ("Location: /setting");
exit();
}
}else{
header ("Location: /setting");
$_SESSION['err'] = 'Введите данные!';
exit();
}
}

/*Смена статуса*/
switch($act){
case 'status':
if(isset($_POST['status'])){
$status = check($_POST['status']); //Статус
#-Проверяем данные-#
if(mb_strlen($status) > 0){
if(preg_match('/[\^\<\>\&\`\$]/',$_POST['status'])) $error = 'Недопустимые символы!';
if(mb_strlen($status) > 100) $error = 'Слишком длинный статус!';
}
#-Если нет ошибок-#
if(!isset($error)){
$upd_users = $pdo->prepare("UPDATE `users` SET `status` = :status WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':status' => $status, ':id' => $user['id'])); 
header("Location: /setting");
$_SESSION['ok'] = 'Сохранено!';
exit();
}else{
header('Location: /setting?change=status');
$_SESSION['err'] = $error;
exit();
}
}else{
header('Location: /setting?change=status');
$_SESSION['err'] = 'Данные отсутствуют! Обратитесь к администрации.';
exit();
}
}

/*Удаляем статус*/
switch($act){
case 'status_del':
if(mb_strlen($user['status']) > 0){
$upd_users = $pdo->prepare("UPDATE `users` SET `status` = :status WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':status' => '', ':id' => $user['id'])); 
}
header("Location: /hero/$user[id]");
exit();
}

/*Показывать скрывать статистику*/
switch($act){
case 'statik':
#-Если не скрыто то скрываем-#
if($user['statik'] == 0){
$upd_users = $pdo->prepare("UPDATE `users` SET `statik` = 1 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':id' => $user['id'])); 
}else{
$upd_users = $pdo->prepare("UPDATE `users` SET `statik` = 0 WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':id' => $user['id'])); 
}
header("Location: /hero/$user[id]");
exit();
}
?>