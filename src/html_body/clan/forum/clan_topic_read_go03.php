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
$head = 'Топик';
require_once H.'system/head.php';

#-Выборка игрока который создал топик-#
$sel_users = $pdo->prepare("SELECT `id`, `nick`, `avatar`, `pol` FROM `users` WHERE `id` = :user_id");
$sel_users->execute(array(':user_id' => $topic['user_id']));
#-Если есть такой игрок-#
if($sel_users-> rowCount() != 0){
$all = $sel_users->fetch(PDO::FETCH_LAZY);
}
#-Состоим в клане или нет-#
$sel_clan_u_me = $pdo->prepare("SELECT `id`, `user_id`, `clan_id`, `prava` FROM `clan_users` WHERE `user_id` = :user_id AND `clan_id` = :clan_id");
$sel_clan_u_me->execute(array(':user_id' => $user['id'], ':clan_id' => $clan['id']));
if($sel_clan_u_me->rowCount() != 0){
$clan_u_me = $sel_clan_u_me->fetch(PDO::FETCH_LAZY);
}

echo'<div class="line_1_m"></div>';
echo'<div class="body_list">';
echo'<div class="menulist">';
#-Если игрок создал топик или есть соответ. права в клане-#
if($user['id'] == $topic['user_id'] or $clan_u_me['prava'] == 4 or $clan_u_me['prava'] == 3){
echo"<a href='/clan/topic_read/$clan[id]?topic_id=$topic[id]&panel_forum=on' id='button_p_f'><span class='gray'><img src='/style/images/forum/topic_setting.png' alt=''/>$topic[title]</span></a>";
}else{
echo'<div style="padding: 5px;">';
echo"<span class='gray'><img src='/style/images/forum/topic.png' alt=''/>$topic[title]</span>";
echo'</div>';
}
echo'<div class="line_1"></div>';

#-ПАНЕЛЬ МОДЕРИРОВАНИЯ-#
echo''.($_GET['panel_forum'] == 'on' ? '<div id="panel_forum.enabled">' : '<div id="panel_forum">').'';
#-Редактирование-#
echo'<li><a href="/clan/topic_edit/'.$clan['id'].'?topic_id='.$topic['id'].'"><img src="/style/images/forum/topic_red.png" alt=""/>Редактировать</a></li>';
if($clan_u_me['prava'] == 4 or $clan_u_me['prava'] == 3){
#-Открытие или закрытие-#
echo'<div class="line_1"></div>';
if($topic['close'] == 0){
echo'<li><a href="/clan_topic_act?act=close&clan_id='.$clan['id'].'&topic_id='.$topic['id'].'"><img src="/style/images/forum/topic_close.png" alt=""/>Закрыть</a></li>';
}else{
echo'<li><a href="/clan_topic_act?act=close&clan_id='.$clan['id'].'&topic_id='.$topic['id'].'"><img src="/style/images/forum/topic_open.png" alt=""/>Открыть</a></li>';
}
#-Закрепить или открепить-#
echo'<div class="line_1"></div>';
if($topic['verh'] == 0){
echo'<li><a href="/clan_topic_act?act=fix&clan_id='.$clan['id'].'&topic_id='.$topic['id'].'"><img src="/style/images/forum/topic_attach.png" alt="З"/>Закрепить</a></li>';
}else{
echo'<li><a href="/clan_topic_act?act=fix&clan_id='.$clan['id'].'&topic_id='.$topic['id'].'"><img src="/style/images/forum/topic_no_attach.png" alt=""/>Открепить</a></li>';
}
#-Удаление топика-#
echo'<div class="line_1"></div>';
echo'<li><a href="/clan_topic_act?act=del&clan_id='.$clan['id'].'&topic_id='.$topic['id'].'"><img src="/style/images/forum/topic_del.png" alt=""/>Удалить</a></li>';
}
#-Скрыть-#
echo'<div class="line_1"></div>';
echo'<li><a href="/clan/topic_read/'.$clan['id'].'?topic_id='.$topic['id'].'" id="button_p_f"><img src="/style/images/body/error.png" alt=""/>Скрыть</a></li>';
echo'<div class="line_1"></div>';
echo'</div>';
echo'</div>';
echo'</div>';

#-ВЫВОД ТОПИКА-#
echo'<div class="page">';
echo'<div style="padding: 3px; white-space: pre-wrap;">';
echo"<a href='/hero/$all[id]'> <span class='user_nick'>$all[nick]</span></a>";
echo"<div style='font-size: 13px; float: right;'><span class='white'> ".vremja($topic['time'])."</span></div><br/>";
echo"<span class='gray'>".msg(bbcode($topic['msg']))."</span><br/>";
echo'<div style="padding-top: 3px;"></div>';
if($topic['edit'] > 0){
$ed = $topic['edit'].' '.true_wordform($topic['edit'], 'раз', 'раза', 'раз');
echo"<div style='font-size: 13px;'><span class='white'>Отредактировано: $ed</span></div>";
}
echo'</div>';
echo'</div>';
echo'<div class="line_1_m"></div>
<div class=""menubig_block">';

#-КОММЕНТАРИИ-#
#-Страница-#
if(isset($_GET['page'])){
$page = check($_GET['page']);
}else{
$page = 1;
}
#-Считаем количество комментарий-#
$sel_clan_c = $pdo->prepare("SELECT COUNT(*) FROM `clan_comment` WHERE `topic_id` = :topic_id");
$sel_clan_c->execute(array(':topic_id' => $topic['id']));
$amount = $sel_clan_c->fetch(PDO::FETCH_LAZY);
#-Действия постраничной навигации-#
$num = 10;  
$page = $_GET['page'];  
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;$page = intval($page);if(empty($page) or $page < 0) $page = 1;if($page > $total) $page = $total;$start = $page * $num - $num;  
$sel_comment = $pdo->prepare("SELECT * FROM `clan_comment` WHERE `topic_id` = :topic_id ORDER BY `time` LIMIT $start, $num");
$sel_comment->execute(array(':topic_id' => $topic['id']));
#-Если есть записи-#
if($sel_comment-> rowCount() != 0){
#-Пишем сколько комментариев всего-#
echo'<div class="body_list">';
echo'<div class="error_list">';
echo'<img src="/style/images/body/comm.png" alt="">Комментариев: '.$amount[0].'';
echo'</div>';
echo'<div class="line_1_m"></div>';	
echo'</div>';

#-ВЫВОД КОММЕНТАРИЙ-#
echo'<div style="padding-top: 5px;"></div>';
echo'<div class="body_list">';
while($comment = $sel_comment->fetch(PDO::FETCH_LAZY))  
{	
echo'<div class="line_1"></div>';
#-Выборка игрока который оставил комментарий-#
$sel_users = $pdo->prepare("SELECT `id`, `nick`, `avatar`, `pol` FROM `users` WHERE `id` = :user_id");
$sel_users->execute(array(':user_id' => $comment['user_id']));
$all = $sel_users->fetch(PDO::FETCH_LAZY);
#-Выборка прав игрока в клане-#
$sel_clan_u = $pdo->prepare("SELECT `id`, `user_id`, `clan_id`, `prava` FROM `clan_users` WHERE `user_id` = :user_id AND `clan_id` = :clan_id");
$sel_clan_u->execute(array(':user_id' => $comment['user_id'], ':clan_id' => $clan['id']));
$clan_u = $sel_clan_u->fetch(PDO::FETCH_LAZY);

echo'<div style="padding: 3px;">';
echo"<img src='".avatar_img_min($all['avatar'], $all['pol'])."' class='menulitl_img' width='30' height='30' alt=''/><div class='menulitl_block'><a href='/hero/$all[id]'><span class='user_nick'>$all[nick]</span></a> ";
if($comment['user_id'] != $user['id']){
echo"<a href='/clan/topic_read/$clan[id]?topic_id=$topic[id]&nick=$all[nick]'><span id='otv' class='$all[nick]'>[Отв.]</span></a> ";
}else{
echo"<a href='/clan_comment_act?act=del_com&comm_id=$comment[id]&topic_id=$topic[id]&clan_id=$clan[id]&page=$page'><span style='color: #666666; font-size: 13px;'>[X]</span></a> ";
}
echo"<div style='float: right; font-size: 12px; color: #666666;'>".vremja($comment['time'])."</div><br/>";
if($clan_u['prava'] == 3){$color = 'blue';}
if($clan_u['prava'] == 4){$color = 'red';}
if($clan_u['prava'] == 0){$color = 'gray';}
echo"<span class='$color'>".msg($comment['comment'])."</span><br/>";
echo'</div>';
echo'</div>';	
}	
echo'</div>';	
}else{//Если нет комментарий
echo'<div class="body_list">';
echo'<div class="error_list">';
echo'<img src="/style/images/body/error.png" alt="">Нет комментариев!';
echo'</div>';
echo'</div>';
}

#-Вывод постраничной навигации-#
if($posts > $num){
$action = "&topic_id=$topic[id]";
echo'<div class="body_list">';
echo'<div class="line_1"></div>';
$z = pages($posts,$total,$action);
echo'</div>';
}

#-Форма комментирования-#
if($topic['close'] == 0 and $sel_clan_u_me->rowCount() != 0){
echo'<div class="line_1"></div>';
if(isset($_GET['nick'])){
$nick = check($_GET['nick']);
$otv = "$nick, ";
}
echo'<div style="padding-top: 5px;"></div>';
echo'<center>';
echo'<span class="white">Комментарий:</span>';
echo'<form method="post" action="/clan_comment_act?act=comm&clan_id='.$clan['id'].'&topic_id='.$topic['id'].'&page='.$page.'">';
echo"<textarea class='input_form' type='text' name='comment' id='mail' required>$otv</textarea><br/>";
echo'</center>';
echo'<input class="button_i_mini" name="submit" type="submit"  value="Отправить"/>';
echo'</form>';
echo'<img src="/style/images/body/smiles.png" alt="" class="smiles_button"/>';
echo'<div style="padding-top: 5px;"></div>';
echo'</center>';
echo'</div>';
}else{
echo'<div class="line_1"></div>';
echo'<div class="error_list">';
echo'<img src="/style/images/body/error.png" alt=""/>Вы не можете оставлять комментарии!';
echo'</div>';
}

#-Смайлы-#
echo'<div id="panel_smiles">';
echo'<div class="line_1_m"></div>';
echo smiles_kolobok();
echo'</div>';
echo'</div>';
#-Меню-#`
echo'<div class="body_list">';
echo'<div class="menulist">';
echo'<div class="line_1"></div>';
echo"<li><a href='/smiles'><img src='/style/images/chat/smiles_b.png' alt=''/> Смайлы</a></li>";
echo'<div class="line_1"></div>';
echo"<li><a href='/clan/topic/$clan[id]?razdel_id=$topic[razdel_id]'><img src='/style/images/body/back.png' alt=''/> Топики</a></li>";
echo'</div>';
require_once H.'system/footer.php';
?>