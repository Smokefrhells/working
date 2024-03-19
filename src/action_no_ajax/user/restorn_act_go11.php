<?php
require "../../pass/lib/password.php";
require_once '../../system/system.php';
echo reg();
/*Восстановление пароля*/
switch($act){
case 'rest':
if(isset($_POST['nick']) and isset($_POST['email'])){
$nick = check($_POST['nick']); //Ник
$email = check($_POST['email']); //Email
#-Проверяем данные-#
#-Ник-#
if(!preg_match('/^([а-яА-яЁёa-zA-Z0-9!@#$%^&*()\-_\+;:,.\/?\|\~\[\]\{\}\s])+$/u',$_POST['nick'])) $error = 'Введите другой ник!';
if(mb_strlen($nick) < 1) $error = 'Введите ник!';
if(mb_strlen($nick) > 25) $error = 'Слишком длинный ник!';
#-Email-#
if(!preg_match("/^([A-Za-z0-9_\.-]+)@([A-Za-z0-9_\.-]+)\.([A-Za-z\.]{2,6})$/", $_POST['email'])) $error = 'Некорректный email!';
if(mb_strlen($email) < 1) $error = 'Введите email!';
if(mb_strlen($email) > 200) $error = 'Слишком длинный email!';
#-Нет ли ошибок-#
if(!isset($error)){
#-Проверяем есть ли ник-#
$sel_users = $pdo->prepare("SELECT `id`, `nick`, `email`, `save` FROM `users` WHERE `nick` = :nick");
$sel_users->execute(array(':nick' => $nick)); 
if($sel_users-> rowCount() != 0){
$all = $sel_users->fetch(PDO::FETCH_LAZY);
#-Игрок должен быть сохранен-#
if($all['save'] == 1){
#-Проверяем email-#
if($all['email'] == $email){
#-Генерируем новый пароль-#
$eng = 'qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890';//Набор символов
$size=8;//Длина
$q_size = strlen($eng);
$newpass='';for($i=0;$i<$size;$i++){$newpass.=$eng[rand(0,$q_size-1)];//Генирируем
}
$pass_encrypt = password_hash($newpass, PASSWORD_DEFAULT); //Зашифрованный пароль
$pass_email = $newpass; //Пароль для отправки на email
#-Меняем пароль-#
$upd_users = $pdo->prepare("UPDATE `users` SET `password` = :password WHERE `id` = :id LIMIT 1");
$upd_users->execute(array(':password' => $pass_encrypt, ':id' => $all['id'])); 
#-Отправляем email-#
$subject = "Новый пароль";
$message = "<html><body>
Вы запросили новый пароль в игре <span style='color: #cb862c; text-decoration:underline;'><b>Война Героев</b></span>.<br/>
<span style='color: #cb862c;'><b>Ваш ник:</b></span><br/>
$all[nick]<br/>
<span style='color: #cb862c;'><b>Ваш новый пароль:</b></span><br/>
$pass_email<br/>
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
$to = "$all[email]";
mail($to, $subject, $message, $headers);
header('Location: /');
$_SESSION['ok'] = 'Пароль отправлен на email!';
exit();	
}else{
header('Location: /restorn');
$_SESSION['err'] = 'Такой email не привязан к этому игроку!';
exit();	
}
}
}else{
header('Location: /restorn');
$_SESSION['err'] = 'Игрок не найден!';
exit();	
}
}else{
header('Location: /restorn');
$_SESSION['err'] = $error;
exit();
}
}else{
header('Location: /restorn');	
}
}
?>