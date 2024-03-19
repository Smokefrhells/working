<?php
require "../../pass/lib/password.php";
require_once '../../system/system.php';
echo only_reg();

/*Сохранение персонажа*/
switch($act){
case 'save':
if($user['save'] == 0 and $user['start'] == 7){
if(isset($_POST['nick']) and isset($_POST['email']) and isset($_POST['pass']) and isset($_POST['pass_dubl'])){
$nick = check($_POST['nick']); //Ник
$email = check($_POST['email']); //Email
$pass = check($_POST['pass']); //Пароль
$pass_dubl = check($_POST['pass_dubl']); //Повтор пароля
$pass_encrypt = password_hash($pass, PASSWORD_DEFAULT); //Зашифрованный пароль
$hash = hash_cod('0'); //Хеш код
#-Проверяем данные-#
#-Ник-#
if(preg_match('/[\^\<\>\&\`\$]/',$_POST['nick'])) $error = 'Введите другой ник!';
if(mb_strlen($nick) < 1) $error = 'Введите ник!';
if(mb_strlen($nick) > 15) $error = 'Слишком длинный ник!';
#-Email-#
if(!preg_match("/^([A-Za-z0-9_\.-]+)@([A-Za-z0-9_\.-]+)\.([A-Za-z\.]{2,6})$/", $_POST['email'])) $error = 'Некорректный email!';
if(mb_strlen($email) < 1) $error = 'Введите email!';
if(mb_strlen($email) > 200) $error = 'Слишком длинный email!';
#-Пароль-#
if(!preg_match('/^([a-zA-Z0-9]+)([a-zA-Z0-9!@#$%^&*()\-_\+;:,.\/?\|\~\[\]\{\}])+$/u',$_POST['pass'])) $error = 'Некорректный пароль!';
if(mb_strlen($pass) < 6) $error = 'Минимальная длина пароля 6 символов!';
if(mb_strlen($pass) > 25) $error = 'Слишком длинный пароль!';
#-Повтор пароля-#
if(mb_strlen($pass_dubl) < 6) $error = 'Повторите пароль!';
if(mb_strlen($pass_dubl) != mb_strlen($pass) & $pass_dubl != $pass) $error = 'Пароли не совпадают!';
#-Выборка ника из бд-#
$sel_users_n = $pdo->prepare("SELECT `nick` FROM `users` WHERE `nick` = :nick");
$sel_users_n->execute(array(':nick' => $nick)); 
if($sel_users_n-> rowCount() != 0) $error = 'Введите свой ник!';
#-Выборка email из бд-#
$sel_users_e = $pdo->prepare("SELECT `email` FROM `users` WHERE `email` = :email");
$sel_users_e->execute(array(':email' => $email)); 
if($sel_users_e-> rowCount() != 0) $error = 'Email уже существует!';
#-Если нет ошибок-#
if(!isset($error)){
if($user['premium_time'] == 0){
$upd_users = $pdo->prepare("UPDATE `users` SET `nick` = :nick, `email` = :email, `password` = :password, `save` = 1, `start` = 8, `gold` = :gold, `premium` = :premium, `premium_time` = :premium_time WHERE `id` = :id");
$upd_users->execute(array(':nick' => $nick, ':email' => $email, ':password' => $pass_encrypt, ':gold' => $user['gold']+200, ':premium' => 2, ':premium_time' => time()+86400, ':id' => $user['id'])); 
}else{
$upd_users = $pdo->prepare("UPDATE `users` SET `nick` = :nick, `email` = :email, `password` = :password, `save` = 1, `start` = 8, `gold` = :gold, `premium` = :premium, `premium_time` = `premium_time` + :premium_time WHERE `id` = :id");
$upd_users->execute(array(':nick' => $nick, ':email' => $email, ':password' => $pass_encrypt, ':gold' => $user['gold']+200, ':premium' => 2, ':premium_time' => 86400, ':id' => $user['id'])); 
}
#-Отправляем email-#
$subject = "Сохранение героя";
$message = "<html><body>
Вы сохранили игрока в игре <span style='color: #cb862c; text-decoration:underline;'><b>Война Героев</b></span> на этот email.<br/>
<span style='color: #cb862c;'><b>Ваш ник:</b></span><br/>
$nick<br/>
<span style='color: #cb862c;'><b>Ваш пароль:</b></span><br/>
$pass<br/>
__________________________________________________<br/>
С Уважением Администрация игры Война Героев
</body>
</html>
";
$headers= "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n";
$headers .= "From: WarHeros <support@warheros.ru>\r\n";
$headers .= "Cc: support@warheros.ru\r\n";
$headers .= "Bcc: support@warheros.ru\r\n";
$to = "$email";
mail($to, $subject, $message, $headers);
#-Ставим куки-#
setcookie('UsN',$nick, time()+604800, '/');
header ("Location: /");
$_SESSION['ok'] = 'Герой сохранен!';
exit();
}else{
header('Location: /save');
$_SESSION['err'] = $error;
exit();
}
}else{
header('Location: /save');
$_SESSION['err'] = 'Данные отсутствуют! Обратитесь к администрации.';
exit();
}
}else{
header('Location: /');
$_SESSION['err'] = 'Ошибка!';
exit();	
}
}
?>