<?php
require_once '../../../system/system.php';
echo only_reg();
echo clan_level();
echo save();
if(isset($_GET['razdel_id']) and isset($_GET['id'])){
$razdel_id = check($_GET['razdel_id']);
$clan_id = check($_GET['id']);
#-Выборка раздела-#
$sel_clan_r = $pdo->prepare("SELECT * FROM `clan_razdel` WHERE `id` = :razdel_id AND `clan_id` = :clan_id");
$sel_clan_r->execute(array(':razdel_id' => $razdel_id, ':clan_id' => $clan_id));
#-Выборка клана-#
$sel_clan = $pdo->prepare("SELECT `id` FROM `clan` WHERE `id` = :id");
$sel_clan->execute(array(':id' => $clan_id));
if($sel_clan_r-> rowCount() != 0 and $sel_clan-> rowCount() != 0){
$clan = $sel_clan->fetch(PDO::FETCH_LAZY);
$razdel = $sel_clan_r->fetch(PDO::FETCH_LAZY);
$head = $razdel['name'];
}else{
header('Location: /clan');
exit();	
}
}else{
header('Location: /clan');
exit();
}
require_once H.'system/head.php';

#-Создание топика-#
if($_GET['topic_create'] == 'on'){
echo'<div class="page">';
echo'<center>';
echo'<form method="post" action="/clan_topic_act?act=add&razdel='.$razdel['id'].'&clan_id='.$clan_id.'">';
echo"<input class='input_form' type='text' name='title' placeholder='Заголовок' maxlength='50' required/><br/>";
echo"<textarea class='input_form' type='text' name='msg' id='mail' placeholder='Текст' maxlength='5000' required></textarea><br/>";
echo'</center>';
echo'<input class="button_i_mini" name="submit" type="submit" value="Создать"/>';
echo'</form>';
echo'</center>';
echo'<img src="/style/images/body/smiles.png" alt="" class="smiles_button"/>';
echo'<div style="padding-top: 5px;"></div>';
echo'</div>';
echo'</div>';
#-Смайлы-#
echo'<div id="panel_smiles">';
echo'<div class="line_1_m"></div>';
echo smiles_kolobok();
echo'</div>';
echo'</div>';
echo'<div class="body_list">';
echo'<div class="menulist">';
echo'<div class="line_1"></div>';
echo"<li><a href='/bbcode'><img src='/style/images/body/ok.png' alt=''/> BBCode</a></li>";
echo'<div class="line_1"></div>';
echo'<li><a href="/smiles"><img src="/style/images/chat/smiles_b.png" alt=""/> Смайлы</a></li>';
echo'<div class="line_1"></div>';
echo"<li><a href='/clan/topic/$clan_id?razdel_id=$razdel[id]'><img src='/style/images/body/back.png' alt=''/> Назад</a></li>";
echo'</div>';
echo'</div>';
echo'</div>';

}else{
echo'<div class="body_list">';
echo'<div class="menulist">';
#-Считаем количество топиков которые есть в разделе-#
$sel_c_forum_t = $pdo->prepare("SELECT COUNT(*) FROM `clan_topic` WHERE `razdel_id` = :razdel_id AND `clan_id` = :clan_id");
$sel_c_forum_t->execute(array(':razdel_id' => $razdel_id, ':clan_id' => $clan_id));
$amount = $sel_c_forum_t->fetch(PDO::FETCH_LAZY);
#-Действия постраничной навигации-#
$num = 10;  
$page = $_GET['page'];  
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;$page = intval($page);if(empty($page) or $page < 0) $page = 1;if($page > $total) $page = $total;$start = $page * $num - $num;  

#-Закрепленные топики-#
$sel_forum_t1 = $pdo->prepare("SELECT * FROM `clan_topic` WHERE `razdel_id` = :razdel_id AND `verh` = 1 AND `clan_id` = :clan_id ORDER BY `time` DESC LIMIT $start, $num");
$sel_forum_t1->execute(array(':razdel_id' => $razdel_id, ':clan_id' => $clan_id));
while($forum_t1 = $sel_forum_t1->fetch(PDO::FETCH_LAZY))  
{
#-Считаем комментарии топика-#
$sel_comment_t1 = $pdo->prepare("SELECT COUNT(*) FROM `clan_comment` WHERE `topic_id` = :topic_id AND `clan_id` = :clan_id");
$sel_comment_t1->execute(array(':topic_id' => $forum_t1['id'], ':clan_id' => $clan_id));
if($sel_comment_t1->rowCount() != 0){
$comment_t1 = $sel_comment_t1->fetch(PDO::FETCH_LAZY);
}
echo'<div class="line_1"></div>';
echo"<li><a href='/clan/topic_read/$clan[id]?topic_id=$forum_t1[id]'><img src='/style/images/forum/topic_attach.png' alt=''/> <span class='gray'><b>$forum_t1[title]</b></span><div style='float: right; color:#666666;'>$comment_t1[0]</div></a></li>";
}

#-Обычные топики-#
$sel_forum_t2 = $pdo->prepare("SELECT * FROM `clan_topic` WHERE `razdel_id` = :razdel_id AND `clan_id` = :clan_id AND `verh` = 0 ORDER BY `time` DESC LIMIT $start, $num");
$sel_forum_t2->execute(array(':razdel_id' => $razdel_id, ':clan_id' => $clan_id));
while($forum_t2 = $sel_forum_t2->fetch(PDO::FETCH_LAZY))  
{	
#-Считаем комментарии топика-#
$sel_comment_t2 = $pdo->prepare("SELECT COUNT(*) FROM `clan_comment` WHERE `topic_id` = :topic_id AND `clan_id` = :clan_id");
$sel_comment_t2->execute(array(':topic_id' => $forum_t2['id'], ':clan_id' => $clan_id));
if($sel_comment_t2->rowCount() != 0){
$comment_t2 = $sel_comment_t2->fetch(PDO::FETCH_LAZY);
}
#-Закрыт или нет-#
if($forum_t2['close'] == 0){$img = '/style/images/forum/topic_open.png';}else{$img = '/style/images/forum/topic_close.png';}
echo'<div class="line_1"></div>';
echo"<li><a href='/clan/topic_read/$clan[id]?topic_id=$forum_t2[id]'><img src='$img' alt=''/> <span class='gray'>$forum_t2[title]</span><div style='float: right; color:#666666;'>$comment_t2[0]</div></a></li>";
}

#-Состоим в клане или нет-#
$sel_clan_u_me = $pdo->prepare("SELECT `id`, `user_id`, `clan_id` FROM `clan_users` WHERE `user_id` = :user_id AND `clan_id` = :clan_id");
$sel_clan_u_me->execute(array(':user_id' => $user['id'], ':clan_id' => $clan['id']));
if($sel_clan_u_me->rowCount() != 0){
echo'<div class="line_1"></div>';
echo"<li><a href='/clan/topic/$clan_id?razdel_id=$razdel_id&topic_create=on'><img src='/style/images/forum/topic_add.png' alt=''/> <span class='yellow'>Создать топик</span></a></li>";	
}

echo'<div class="line_1"></div>';
echo"<li><a href='/clan/razdel/$clan_id'><img src='/style/images/body/back.png' alt=''/> Разделы</a></li>";
echo'</div>';

#-Отображение постраничной навигации-#
if($posts > $num){
$action = "&razdel_id=$razdel[id]";
echo'<div class="line_1"></div>';
$z = pages($posts,$total,$action);}
echo'</div>';
}
require_once H.'system/footer.php';
?>