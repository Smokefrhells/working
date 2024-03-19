<?php
require_once '../../system/system.php';
echo only_reg();
echo avatar_level();
#-Загрузка аватара-#
switch($act){
case 'go':
// Пути загрузки файлов
$path = '../../style/avatar/';
$tmp_path = '../../tmp/';
#-Проверяем не скрипт ли это-#
$blacklist = array(".php", ".phtml", ".php3", ".php4", ".html", ".htm");
 foreach ($blacklist as $item)
 if(preg_match("/$item\$/i", $_FILES['somename']['name'])) $error = 'Ошибка!';
// Массив допустимых значений типа файла
$types = array('image/png', 'image/jpeg');
// Максимальный размер файла
$size = 252000;
// Обработка запроса
if($_SERVER['REQUEST_METHOD'] == 'POST'){
// Проверяем тип файла
if(!in_array($_FILES['picture']['type'], $types)) $error = 'Неверный тип файла!';
// Проверяем размер файла
if($_FILES['picture']['size'] > $size) $error = 'Слишком большой размер файла';
// Если нет ошибок загружаем
if(!isset($error)){
#-Удаляем старую картинку если она была загружена-#
if($user['avatar'] != ''){
unlink("../../style/avatar/$user[avatar]");
}
if(!@copy($_FILES['picture']['tmp_name'], $path . $_FILES['picture']['name']))
// Какой тип присвоить изображению
if($_FILES['picture']['type'] == 'image/jpeg'){$type = 'jpeg';}
if($_FILES['picture']['type'] == 'image/png'){$type = 'png';}
#-Новое имя изображения-#
$rand = rand(1, 999);	
$img_name = "$user[id]$rand";
#-Записываем картинку в базу данных-#
$upd_us = $pdo->prepare("UPDATE `users` SET `avatar` = :avatar WHERE `id` = :user_id");
$upd_us->execute(array(':avatar' => "$img_name.$type", ':user_id' => $user['id']));
// Переименовываем имя на ID игрока (Для быстрого поиска)
rename("../../style/avatar/".$_FILES['picture']['name'], "../../style/avatar/$img_name.$type");
header("Location: /hero/$user[id]");
$_SESSION['ok'] = 'Успешная загрузка!';
exit();		
}else{
header('Location: /download_avatar');
$_SESSION['err'] = $error;
exit();		
}
}
}

#-Удаление аватара-#
switch($act){
case 'del':
#-Проверяем что аватар загружен-#
if($user['avatar'] != ''){
unlink("../../style/avatar/$user[avatar]");
#-Убираем аватар с базы-#	
$upd_us = $pdo->prepare("UPDATE `users` SET `avatar` = '' WHERE `id` = :user_id");
$upd_us->execute(array(':user_id' => $user['id']));	
header("Location: /download_avatar");
exit();	
}else{
header('Location: /download_avatar');
$_SESSION['err'] = 'Аватар не найден!';
exit();	
}
}
?>