<?php
require_once '../../system/system.php';
echo only_reg();
$id = check($_GET['id']);
if(empty($_GET['id'])) $error = 'Ошибка!';
if(!isset($_GET['id'])) $error = 'Ошибка!';
if(!isset($error)){
$sel_forum_t = $pdo->prepare("SELECT * FROM `forum_topic` WHERE `id` = :id");
$sel_forum_t->execute(array(':id' => $id));
if($sel_forum_t-> rowCount() != 0){
$topic = $sel_forum_t->fetch(PDO::FETCH_LAZY);
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
if($user['id'] == $topic['user_id'] or $user['prava'] == 1 or $user['prava'] == 2 or $user['prava'] == 3){
echo'<center>';
echo'<form method="post" action="/forum_act?act=edit&topic='.$topic['id'].'">';
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
echo'<div class="line_1_m"></div>';
echo'<div class="error_list">';
echo'<img src="/style/images/body/error.png" alt="">У вас нет прав на редактирование!';
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