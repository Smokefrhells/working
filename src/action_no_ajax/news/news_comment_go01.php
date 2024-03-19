<?php
require_once '../../system/system.php';
echo only_reg();
#-Комментирование новости-#
switch($act){
case 'comm':
if($user['ban_forum'] == 0){
#-Если уровень меньше 15-#
if($user['level'] >= 15){
#-Только если сохранен-#
if($user['save'] ==1){	
if(isset($_POST['comment']) and isset($_GET['news'])){
$comment = check($_POST['comment']); //Комментарий
$ank_id = check($_POST['ank_id']); //ID кому отвечаем
$news_id = check($_GET['news']); //ID новости
#-Проверяем данные-#
#-Комментарий-#
if(preg_match('/[\^\<\>\&\`\$]/',$_POST['comment'])) $error = 'Некорректное сообщение!';
if(mb_strlen($comment) < 1) $error = 'Пустой комментарий!';
if(mb_strlen($comment) > 2000) $error = 'Слишком длинный комментарий!';
#-Если нет ошибок-#
if(!isset($error)){
$ins_news_comm = $pdo->prepare("INSERT INTO `news_comment` SET `comment` = :comment, `news_id` = :news_id, `user_id` = :user_id, `ank_id` = :ank_id, `time` = :time");
$ins_news_comm->execute(array(':comment' => $comment, ':news_id' => $news_id, ':user_id' => $user['id'], ':ank_id' => $ank_id, ':time' => time())); 
header("Location: /news_read/$news_id");
}else{
header("Location: /news_read/$news_id");
$_SESSION['err'] = $error;
exit();
}
}else{
header("Location: /news_read/$news_id");
$_SESSION['err'] = 'Данные отсутствуют! Обратитесь к администрации.';
exit();
}
}else{
header('Location: /');
}
}else{
header('Location: /');
}
}else{
header('Location: /');
}
}
?>