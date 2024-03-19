<?php
require_once '../../system/system.php';
$head = 'Выбор подарка';
echo only_reg();
echo gift_level();
require_once H.'system/head.php';
#-Выбор получателя-#
if(isset($_GET['id']) and !empty($_GET['id']) and $_GET['id'] != $user['id']){
$id = check($_GET['id']);
$sel_users = $pdo->prepare("SELECT `id`, `ev_gift`, `nick` FROM `users` WHERE `id` = :id");
$sel_users->execute(array(':id' => $id));
if($sel_users -> rowCount() != 0){
$all = $sel_users->fetch(PDO::FETCH_LAZY);
}else{
header('Location: /');
exit();
}	

#-Можно ли дарить подарки-#
if($all['ev_gift'] == 0){
#-Форма отправки подарка-#
if(isset($_GET['g_id'])){
$g_id = check($_GET['g_id']);
$g_gold = check($_GET['g_gold']);
echo'<div class="page">';
echo'<center>';
echo'<form method="post" action="/gift_act?act=give&u_id='.$all['id'].'&g_id='.$g_id.'">';
echo"<input class='input_form' type='text' name='description' maxlength='300' placeholder='Описание (можно не писать)'/><br/>";
echo'<input class="button_green_i" name="submit" type="submit"  value="Подарить за '.$g_gold.' золота"/>';
echo'</form>';
echo'</center>';
echo'<div style="padding-top: 3px;"></div>';
echo"<a href='/gift_give?id=$all[id]' class='button_red_a'>К выбору подарка</a>";
echo'<div style="padding-top: 5px;"></div>';
echo'<div class="body_list">';
echo'<div class="menulist">';
echo'<div class="line_1"></div>';
echo'<li><a href="/smiles"><img src="/style/images/chat/smiles_b.png"/> Смайлы</a></li>';
echo'</div>';
echo'</div>';
echo'</div>';
}else{

echo'<div class="body_list">';
#-Сортировка-#
$all = '<a href="/gift_give?id='.$_GET['id'].'&type=1" style="text-decoration:none;"><span class="body_sel">'.(($_GET['type'] == 1 or $_GET['type'] > 4 or !isset($_GET['type']))  ? "<b>Все</b>" : "Все").'</span></a>';
$halloween = '<a href="/gift_give?id='.$_GET['id'].'&type=2" style="text-decoration:none;"><span class="body_sel">'.($_GET['type'] == 2 ? "<b>Хэллоуин</b>" : "Хэллоуин").'</span></a>';
$newyear = '<a href="/gift_give?id='.$_GET['id'].'&type=3" style="text-decoration:none;"><span class="body_sel">'.($_GET['type'] == 3 ? "<b>Новый год</b>" : "Новый год").'</span></a>';
$march_8 = '<a href="/gift_give?id='.$_GET['id'].'&type=4" style="text-decoration:none;"><span class="body_sel">'.($_GET['type'] == 4 ? "<b>8 марта</b>" : "8 марта").'</span></a>';


echo'<div class="line_1_m"></div>';
echo'<div style="padding: 5px;">';
echo''.$all.''.$halloween.''.$newyear.' '.$march_8.'';
echo'</div>';
echo'<div class="line_1"></div>';   
   
echo'<div style="padding-left: 5px; padding-top: 3px;">';
#-Выбор подарка-#
if($_GET['type'] == 1 or $_GET['type'] > 4 or !isset($_GET['type'])){
$sel_gift = $pdo->query("SELECT * FROM `gift`");
}
if($_GET['type'] == 2){
$sel_gift = $pdo->prepare("SELECT * FROM `gift` WHERE `type` = :type");
$sel_gift->execute(array(':type' => 'halloween'));
}
if($_GET['type'] == 3){
$sel_gift = $pdo->prepare("SELECT * FROM `gift` WHERE `type` = :type");
$sel_gift->execute(array(':type' => 'newyear'));
}
if($_GET['type'] == 4){
$sel_gift = $pdo->prepare("SELECT * FROM `gift` WHERE `type` = :type");
$sel_gift->execute(array(':type' => 'march 8'));
}
while($gift = $sel_gift->fetch(PDO::FETCH_LAZY)){
echo"<a href='/gift_give?id=$id&g_id=$gift[id]&g_gold=$gift[gold]' class='gift'><img src='$gift[images]' alt=''/></a> ";		
}
echo'</div>';
echo'</div>';
}

}else{
echo'<div class="body_list">';
echo'<div class="error_list">';
echo'<img src="/style/images/body/error.png" alt=""/> Получатель запретил дарить подарки!';
echo'</div>';
echo'</div>';
}
}else{
echo'<div class="body_list">';
echo'<div class="error_list">';
echo'<img src="/style/images/body/error.png" alt=""/> Получатель не выбран или это вы!';
echo'</div>';
echo'</div>';
}
require_once H.'system/footer.php';
?>