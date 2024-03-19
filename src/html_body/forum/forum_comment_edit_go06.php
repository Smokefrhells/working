<?php
require_once '../../system/system.php';
echo only_reg();
$id = check($_GET['id']);
if(empty($_GET['id'])) $error = 'Ошибка!';
if(!isset($_GET['id'])) $error = 'Ошибка!';
if(!isset($error)){
$sel_forum_c = $pdo->prepare("SELECT * FROM `forum_comment` WHERE `id` = :id");
$sel_forum_c->execute(array(':id' => $id));
if($sel_forum_c-> rowCount() != 0){
$comment = $sel_forum_c->fetch(PDO::FETCH_LAZY);
}else{
header ('Location: /forum_razdel');
$_SESSION['err'] = 'Топик не найден!';
exit();
}
}else{
header ('Location: /forum_razdel');
$_SESSION['err'] = $error;
exit();
}
$head = 'Редактирование';
require_once H.'system/head.php';
#-Проверяем можем ли мы редактировать-#
echo'<div class="page">';
if($user['ban'] == 0){
if($comment['user_id'] == $user['id'] and $comment['time'] > time()-600){
echo'<center>';
echo'<form method="post" action="/forum_act?act=edit_comm&comment_id='.$comment['id'].'">';
echo"<textarea class='input_form' type='text' name='comment' id='mail'>$comment[comment]</textarea><br/>";
echo'</center>';
echo'<input class="button_i_mini" name="submit" type="submit" value="Сохранить"/>';
echo'</form>';
echo'<img src="/style/images/body/smiles.png" alt="Смайл" class="smiles_button"/>';
echo'<div style="padding-top: 5px;"></div>';
#-Смайлы-#
echo'<div id="panel_smiles">';
echo'<div class="line_1_m"></div>';
echo smiles_kolobok();
echo'</div>';
}else{ //Если нет прав
echo'<div class="body_list">';
echo'<div class="error_list">';
echo'<img src="/style/images/body/error.png" alt="">У вас нет прав на редактирование или истекло время!';
echo'</div>';
echo'</div>';
}
}else{
echo'<div class="body_list">';
echo'<div class="error_list">';
echo'<img src="/style/images/body/error.png" alt=""/>Вы забанены!';
echo'</div>';
echo'</div>';
}
#-Меню-#`
echo'<div class="body_list">';
echo'<div class="menulist">';
echo'<div class="line_1"></div>';
echo'<li><a href="/smiles"><img src="/style/images/chat/smiles_b.png" alt=""/> Смайлы</a></li>';
echo'<div class="line_1"></div>';
echo"<li><a href='/forum_topic/read/$topic[id]'><img src='/style/images/body/back.png' alt=''/> Топик</a></li>";
echo'</div>';
echo'</div>';
echo'</div>';
require_once H.'system/footer.php';
?>