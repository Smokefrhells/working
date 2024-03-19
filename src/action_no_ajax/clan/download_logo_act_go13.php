<?php
require_once '../../system/system.php';
echo only_reg();
echo clan_level();
#-Загрузка логотипа-#
switch($act){
case 'go':
if(isset($_GET['clan_id'])){
$clan_id = check($_GET['clan_id']);
#-Существует ли клан-#
$sel_clan = $pdo->prepare("SELECT `id`, `avatar` FROM `clan` WHERE `id` = :id");
$sel_clan->execute(array(':id' => $clan_id));
if($sel_clan-> rowCount() == 0) $error = 'Клан не найден!';
#-Выборка игрока клана-#
$sel_clan_u = $pdo->prepare("SELECT * FROM `clan_users` WHERE `clan_id` = :clan_id AND `user_id` = :user_id AND (`prava` = 4 or `prava` = 3)");
$sel_clan_u->execute(array(':clan_id' => $clan_id, ':user_id' => $user['id']));
if($sel_clan_u-> rowCount() == 0) $error = 'У вас нет прав или не состоите в клане!';

if(!isset($error)){
$clan = $sel_clan->fetch(PDO::FETCH_LAZY);

// Пути загрузки файлов
$path = '../../style/avatar_clan/';
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
if($clan['avatar'] != ''){
unlink("../../style/avatar_clan/$clan[avatar]");
}
if(!@copy($_FILES['picture']['tmp_name'], $path . $_FILES['picture']['name']))
// Какой тип присвоить изображению
if($_FILES['picture']['type'] == 'image/jpeg'){$type = 'jpeg';}
if($_FILES['picture']['type'] == 'image/png'){$type = 'png';}
#-Новое имя изображения-#
$rand = rand(1, 999);	
$img_name = "$clan[id]$rand";
#-Записываем картинку в базу данных-#
$upd_clan = $pdo->prepare("UPDATE `clan` SET `avatar` = :avatar WHERE `id` = :clan_id");
$upd_clan->execute(array(':avatar' => "$img_name.$type", ':clan_id' => $clan['id']));
// Переименовываем имя на ID игрока (Для быстрого поиска)
rename("../../style/avatar_clan/".$_FILES['picture']['name'], "../../style/avatar_clan/$img_name.$type");
header("Location: /clan/download_logo/$clan[id]");
$_SESSION['ok'] = 'Успешная загрузка!';
exit();		
}else{
header("Location: /clan/download_logo/$clan[id]");
$_SESSION['err'] = $error;
exit();		
}	
}
}else{
header("Location: /clan/download_logo/$clan[id]");
$_SESSION['err'] = $error;
exit();		
}
}
}

#-Удаление логотипа-#
switch($act){
case 'del':
if(isset($_GET['clan_id'])){
$clan_id = check($_GET['clan_id']);
#-Существует ли клан-#
$sel_clan = $pdo->prepare("SELECT `id`, `avatar` FROM `clan` WHERE `id` = :id");
$sel_clan->execute(array(':id' => $clan_id));
if($sel_clan-> rowCount() == 0) $error = 'Клан не найден!';
#-Выборка игрока клана-#
$sel_clan_u = $pdo->prepare("SELECT * FROM `clan_users` WHERE `clan_id` = :clan_id AND `user_id` = :user_id AND (`prava` = 4 or `prava` = 3)");
$sel_clan_u->execute(array(':clan_id' => $clan_id, ':user_id' => $user['id']));
if($sel_clan_u-> rowCount() == 0) $error = 'У вас нет прав или не состоите в клане!';

#-Нет ошибок-#
if(!isset($error)){
$clan = $sel_clan->fetch(PDO::FETCH_LAZY);
#-Проверяем что аватар загружен-#
if($clan['avatar'] != ''){
unlink("../../style/avatar_clan/$clan[avatar]");
#-Убираем аватар с базы-#	
$upd_clan = $pdo->prepare("UPDATE `clan` SET `avatar` = '' WHERE `id` = :clan_id");
$upd_clan->execute(array(':clan_id' => $clan['id']));	
header("Location: /clan/download_logo/$clan[id]");
exit();	
}else{
header("Location: /clan/download_logo/$clan[id]");
$_SESSION['err'] = 'Аватар не найден!';
exit();	
}
}else{
header("Location: /clan/download_logo/$clan[id]");
$_SESSION['err'] = $error;
exit();	
}
}
}
?>