<?php
require_once '../../system/system.php';
echo only_reg();
echo clan_level();
$id = check($_GET['id']);
if(empty($_GET['id'])) $error = 'Ошибка!';
if(!isset($_GET['id'])) $error = 'Ошибка!';
if(!isset($error)){
$sel_clan = $pdo->prepare("SELECT * FROM `clan` WHERE `id` = :id");
$sel_clan->execute(array(':id' => $id));
if($sel_clan-> rowCount() != 0){
$clan = $sel_clan->fetch(PDO::FETCH_LAZY);
}else{
header('Location: /clan');
$_SESSION['err'] = 'Клан не найден!';
exit();
}
}else{
header('Location: /clan');
$_SESSION['err'] = $error;
exit();
}
$head = 'Загрузка логотипа';
require_once H.'system/head.php';
#-Выборка игрока клана-#
$sel_clan_u = $pdo->prepare("SELECT * FROM `clan_users` WHERE `clan_id` = :clan_id AND `user_id` = :user_id");
$sel_clan_u->execute(array(':clan_id' => $clan['id'], ':user_id' => $user['id']));
if($sel_clan_u-> rowCount() != 0){
$clan_u = $sel_clan_u->fetch(PDO::FETCH_LAZY);

#-Проверка прав-#
if($clan_u['prava'] == 4 or $clan_u['prava'] == 3){
echo'<div class="page">';
echo'<center>';
echo'<form method="post" enctype="multipart/form-data" action="/download_logo_act?act=go&clan_id='.$clan['id'].'">';
echo'<input type="file" class="input_form" name="picture">';
echo'<input type="submit" class="button_green_i" value="Загрузить">';
echo'</form>';
echo'</center>';

#-Если есть логотип-#
if($clan['avatar'] != ''){
echo'<div style="padding-top: 3px;"></div>';
echo'<a href="/style/avatar_clan/'.$clan['avatar'].'" class="button_green_a">Просмотр</a>';
echo'<div style="padding-top: 3px;"></div>';
#-Удаление логотипа-#
if($_GET['conf'] == 'del'){
echo'<a href="/download_logo_act?act=del&clan_id='.$clan['id'].'" class="button_red_a">Подтверждаю</a>';
}else{
echo'<a href="/clan/download_logo/'.$clan['id'].'?conf=del" class="button_red_a">Удалить логотип</a>';
}
}
echo'<div style="padding-top: 5px;"></div>';
echo'<div class="line_1"></div>';
echo'<div class="body_list">';
echo'<div class="svg_list">';
echo'<img src="/style/images/body/imp.png" alt=""/> Размер: 250KB (jpeg, png)';
echo'</div>';
echo'</div>';
echo'</div>';
}
}
require_once H.'system/footer.php';
?>