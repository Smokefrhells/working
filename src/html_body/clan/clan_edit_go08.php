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
$head = 'Редактировать';
require_once H.'system/head.php';
#-Проверяем что мы состоим в клане-#
$sel_clan_u = $pdo->prepare("SELECT * FROM `clan_users` WHERE `clan_id` = :clan_id AND `user_id` = :user_id AND (`prava` = 3 OR `prava` = 4)");
$sel_clan_u->execute(array(':clan_id' => $clan['id'], ':user_id' => $user['id']));
if($sel_clan_u-> rowCount() != 0){
$clan_u = $sel_clan_u->fetch(PDO::FETCH_LAZY); 

echo'<div class="page">';
echo'<center>';
echo'<span class="gray">Описание:</span>';
echo'<form method="post" action="/clan_edit_act?act=edit_d&clan_id='.$clan['id'].'">';
echo'<textarea class="input_form" type="text" name="description" id="mail" maxlength="2000">'.$clan['description'].'</textarea>';
echo'<center>';
echo'<img src="/style/images/body/smiles.png" alt="Смайл" class="smiles_button_t"/>';
#-Смайлы-#
echo'<div id="panel_smiles">';
echo'<div style="padding-top: 25px;"></div>';
echo'<div class="line_1_m"></div>';
echo smiles_kolobok();
echo'</div>';

#-Тип заявок-#
echo'<center>';
echo'<select name="type">';
if($clan['close'] == 0){
echo'<option value="0">Открытый</option>';
echo'<option value="1">Закрытый</option>';
}else{
echo'<option value="1">Закрытый</option>';	
echo'<option value="0">Открытый</option>';
}
echo'</select>';
echo'<input class="button_green_i" name="submit" type="submit"  value="Сохранить"/>';
echo'</form>';

#-Название-#
if(isset($_GET['name'])){
echo'<div style="padding-top: 5px;"></div>';
echo'<center>';
echo'<form method="post" action="/clan_edit_act?act=edit_n&clan_id='.$clan['id'].'">';
echo"<input class='input_form' type='text' name='name' maxlength='25' placeholder='Новое название'/><br/>";
echo'<input class="button_green_i" name="submit" type="submit"  value="Изменить"/>';
echo'</form>';
echo'</center>';
}else{
echo'<div style="padding-top: 3px;"></div>';
echo'<a href="/clan/edit/'.$clan['id'].'?name=1" class="button_green_a">Изменить название за <img src="/style/images/many/gold.png" alt=""/>350</a>';
}
echo'<div style="padding-top: 5px;"></div>';
echo'</div>';
}
require_once H.'system/footer.php';
?>