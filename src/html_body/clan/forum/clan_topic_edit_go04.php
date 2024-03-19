<?php
require_once '../../../system/system.php';
echo only_reg();
echo clan_level();
echo save();
if(isset($_GET['topic_id']) and isset($_GET['id'])){
$topic_id = check($_GET['topic_id']);
$clan_id = check($_GET['id']);
#-Выборка топика-#
$sel_clan_t = $pdo->prepare("SELECT * FROM `clan_topic` WHERE `id` = :topic_id AND `clan_id` = :clan_id");
$sel_clan_t->execute(array(':topic_id' => $topic_id, ':clan_id' => $clan_id));
#-Выборка клана-#
$sel_clan = $pdo->prepare("SELECT `id` FROM `clan` WHERE `id` = :id");
$sel_clan->execute(array(':id' => $clan_id));
if($sel_clan_t-> rowCount() != 0 and $sel_clan-> rowCount() != 0){
$clan = $sel_clan->fetch(PDO::FETCH_LAZY);
$topic = $sel_clan_t->fetch(PDO::FETCH_LAZY);
}else{
header('Location: /clan');
exit();	
}
}else{
header('Location: /clan');
exit();
}
$head = 'Редактирование';
require_once H.'system/head.php';
#-Есть права на редактирование-#
$sel_clan_u = $pdo->prepare("SELECT `id`, `user_id`, `clan_id`, `prava` FROM `clan_users` WHERE `user_id` = :user_id AND `clan_id` = :clan_id");
$sel_clan_u->execute(array(':user_id' => $user['id'], ':clan_id' => $clan['id']));
if($sel_clan_u-> rowCount() != 0){
$clan_u = $sel_clan_u->fetch(PDO::FETCH_LAZY);
}
if(($clan_u['prava'] == 3 or $clan_u['prava'] == 4) or $topic['user_id'] == $user['id']){

echo'<div class="page">';
echo'<center>';
echo'<form method="post" action="/clan_topic_act?act=edit&clan_id='.$clan['id'].'&topic_id='.$topic['id'].'">';
echo"<input class='input_form' type='text' name='title' value='$topic[title]'/><br/>";
echo"<textarea class='input_form' type='text' name='msg' id='mail'>$topic[msg]</textarea><br/>";
echo'</center>';
echo'<input class="button_i_mini" name="submit" type="submit" value="Сохранить"/>';
echo'</form>';
echo'<img src="/style/images/body/smiles.png" alt="" class="smiles_button"/>';
echo'<div style="padding-top: 5px;"></div>';

#-Смайлы-#
echo'<div id="panel_smiles">';
echo'<div class="line_1_m"></div>';
echo smiles_kolobok();
echo'</div>';

}else{ //Если нет прав
echo'<div class="body_list">';
echo'<div class="line_1_m"></div>';
echo'<div class="error_list">';
echo'<img src="/style/images/body/error.png" alt="">У вас нет прав на редактирование!';
echo'</div>';
echo'</div>';
}
#-Меню-#`
echo'<div class="body_list">';
echo'<div class="menulist">';
echo'<div class="line_1"></div>';
echo"<li><a href='/bbcode'><img src='/style/images/body/ok.png' alt=''/> BBCode</a></li>";
echo'<div class="line_1"></div>';
echo'<li><a href="/smiles"><img src="/style/images/chat/smiles_b.png" alt=""/> Смайлы</a></li>';
echo'<div class="line_1"></div>';
echo"<li><a href='/clan/topic_read/$clan[id]?topic_id=$topic[id]'><img src='/style/images/body/back.png' alt=''/> Топик</a></li>";
echo'</div>';
echo'</div>';
echo'</div>';
require_once H.'system/footer.php';
?>