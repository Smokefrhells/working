<?php
require_once '../../system/system.php';
echo only_reg();
$id = check($_GET['id']);
if(empty($_GET['id'])) $error = 'Ошибка!';
if(!isset($_GET['id'])) $error = 'Ошибка!';
if(!isset($error)){
$sel_news = $pdo->prepare("SELECT * FROM `news` WHERE `id` = :id");
$sel_news->execute(array(':id' => $id));
if($sel_news-> rowCount() != 0){
$news = $sel_news->fetch(PDO::FETCH_LAZY);
}else{
header ("Location: /news");
$_SESSION['err'] = 'Новость не найдена!';
exit();
}
}else{
header ("Location: /news");
$_SESSION['err'] = $error;
exit();
}
$head = 'Новости';
require_once H.'system/head.php';
#-Выборка игрока-#
$sel_users = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :news_id");
$sel_users->execute(array(':news_id' => $news['user_id']));
#-Если есть такой игрок-#
if($sel_users-> rowCount() != 0){
$all = $sel_users->fetch(PDO::FETCH_LAZY);
}
#-ЗАГОЛОВОК-#
echo'<div class="line_1"></div>';
echo'<div class="body_list">';
echo'<div class="menulist">';
#-Если игрок создал топик или есть соответ. права-#
if($user['prava'] == 1){
echo"<li><a href='/news_read/$news[id]?panel_forum=on' id='button_p_f'><span class='gray'><img src='/style/images/news/news.png' alt=''/>$news[title]</span></a></li>";
}else{
echo'<div style="padding: 5px;">';
echo"<span class='gray'><img src='/style/images/news/news.png' alt=''/>$news[title]</span>";
echo'</div>';
}
echo'<div class="line_1_m"></div>';
#-ПАНЕЛЬ МОДЕРИРОВАНИЯ-#
//Параметры
$edit = '<li><a href="/news_read/edit/'.$news['id'].'"><img src="/style/images/forum/topic_red.png" alt=""/>Редактировать</a></li>';
$close = '<li><a href="/news_act?act=close&news='.$news['id'].'"><img src="/style/images/forum/topic_close.png" alt=""/>Закрыть</a></li>';
$open = '<li><a href="/news_act?act=close&news='.$news['id'].'"><img src="/style/images/forum/topic_open.png" alt=""/>Открыть</a></li>';
$delete = '<li><a href="/news_read/'.$news['id'].'?t=del"><img src="/style/images/forum/topic_del.png" alt=""/>Удалить</a></li>';
#-Показываем панель без Javascript-#
if(isset($_GET['panel_forum'])){
echo $edit;
if($user['prava'] == 1){
echo'<div class="line_1"></div>';
if($news['close'] == 0){echo $close;}else{echo $open;}
echo'<div class="line_1"></div>';
if(isset($_GET['t'])){
if($_GET['t'] == 'del'){
echo'<li><a href="/news_act?act=delete&news='.$news['id'].'"><img src="/style/images/forum/topic_del.png" alt=""/>Подтвердить удаление</a></li>';	
}
}else{
echo $delete;
}
}
echo'<div class="line_1"></div>';
echo"<li><a href='/news_read/$news[id]'><img src='/style/images/body/error.png' alt=''>Скрыть</a></li>";
echo'<div class="line_1"></div>';
}
#-Панель для javascript-#
echo'<div id="panel_forum">';
echo $edit;
if($user['prava'] == 1){
echo'<div class="line_1"></div>';
if($news['close'] == 0){echo $close;}else{echo $open;}
echo'<div class="line_1"></div>';
if(isset($_GET['t'])){
if($_GET['t'] == 'del'){
echo'<li><a href="/news_act?act=delete&news='.$news['id'].'"><img src="/style/images/forum/topic_del.png" alt=""/>Подтвердить удаление</a></li>';	
}
}else{
echo $delete;
}
}
echo'<div class="line_1"></div>';
echo'</div>';
echo'</div>';
echo'</div>';
#-ВЫВОД НОВОСТИ-#
echo'<div class="page">';
echo'<div style="padding: 3px; white-space: pre-wrap;">';
echo" <a href='/hero/$all[id]'> <span class='user_nick'> $all[nick]</span></a>";
echo"<div style='font-size: 13px; float: right;'><span class='white'> ".vremja($news['time'])."</span></div><br/>";
echo"<span class='gray'>".bbcode(msg($news['msg']))."</span><br/>";
echo'<div style="padding-top: 3px;"></div>';
if($news['edit'] > 0){
$ed = $news['edit'].' '.true_wordform($news['edit'], 'раз', 'раза', 'раз');
echo"<div style='font-size: 13px;'><span class='white'>Отредактировано: $ed</span></div>";
}
echo'</div>';
echo'</div>';
echo'<div class="line_1_m"></div>';

#-КОММЕНТАРИИ-#
#-Считаем количество комментарий-#
$sel_news_comm = $pdo->prepare("SELECT COUNT(*) FROM `news_comment` WHERE `news_id` = :news_id");
$sel_news_comm->execute(array(':news_id' => $news['id']));
$amount = $sel_news_comm->fetch(PDO::FETCH_LAZY);
#-Действия постраничной навигации-#
$num = 10;  
$page = $_GET['page'];  
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;$page = intval($page);if(empty($page) or $page < 0) $page = 1;if($page > $total) $page = $total;$start = $page * $num - $num;  
$sel_comment = $pdo->prepare("SELECT * FROM `news_comment` WHERE `news_id` = :news_id ORDER BY `time` LIMIT $start, $num");
$sel_comment->execute(array(':news_id' => $news['id']));
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
$sel_users = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :user_id");
$sel_users->execute(array(':user_id' => $comment['user_id']));
$all = $sel_users->fetch(PDO::FETCH_LAZY);
#-Показываем комментарии-#
echo"<img src='".avatar_img_min($all['avatar'], $all['pol'])."' class='menulitl_img' width='30' height='30' alt=''/><div class='menulitl_block'><a href='/hero/$all[id]'><span class='user_nick'>$all[nick]</span></a> ";
if($comment['user_id'] != $user['id']){
echo"<a href='/news_read/$news[id]?ank_id=$all[id]'><span id='otv' class='$all[nick]/$all[id]'>[Отв.]</span></a> ";
}
if($comment['user_id'] == $user['id'] and $comment['time'] > time()-600){
echo"<a href='/news/edit_comment/$comment[id]'><span style='color: #666666; font-size: 13px;'>[Ред.]</span></a> ";
}
if($user['prava'] == 1){
if($comment['user_id'] != $user['id']){
if($all['ban_forum'] == 0){
echo"<a href='/news_act?act=ban&user_id=$all[id]&news=$news[id]'><span style='color: #666666; font-size: 13px;'>[Молч.]</span></a> ";
}else{
echo"<a href='/news_act?act=ban&user_id=$all[id]&news=$news[id]'><span style='color: #666666; font-size: 13px;'>[Не молч.]</span></a> ";
}
}
echo"<a href='/news_act?act=del_com&comment=$comment[id]&news=$news[id]'><span style='color: #666666; font-size: 13px;'>[X]</span></a> ";
}
echo"<div style='float: right; font-size: 12px; color: #666666;'>".vremja($comment['time'])."</div><br/>";
if($all['premium'] != 0){$color = 'orange';}
if($all['prava'] == 2){$color = 'blue';}
if($all['prava'] == 3){$color = 'yellow';}
if($all['prava'] == 1){$color = 'red';}
if($all['prava'] == 0 and $all['premium'] == 0){$color = 'gray';}
if($comment['ank_id'] == $user['id']){$color = 'green';}
echo"<span class='$color'>".msg($comment['comment'])."</span><br/>";
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
$action = '';
echo'<div class="body_list">';
echo'<div class="line_1"></div>';
$z = pages($posts,$total,$action);
echo'</div>';
}

#-Форма комментирования-#
if($news['close'] == 0 and $user['ban_forum'] == 0 and $user['level'] >= 15 and $user['save'] == 1){
echo'<div class="line_1"></div>';
#-Ответ, если есть id-#
if(isset($_GET['ank_id'])){
$ank_id = check($_GET['id']);
if($ank_id != $user['id']){
$sel_ank = $pdo->prepare("SELECT `id`, `nick` FROM `users` WHERE `id` = :user_id");
$sel_ank->execute(array(':user_id' => $ank_id));
#-Если есть такой игрок-#
if($sel_ank-> rowCount() != 0){
$ank = $sel_ank->fetch(PDO::FETCH_LAZY);
$otv_nick = "$ank[nick], ";
$ank_id = $ank['id'];
}
}	
}
echo'<div style="padding-top: 5px;"></div>';
echo'<center>';
echo'<span class="white">Комментарий:</span>';
echo'<form method="post" action="/news_comment?act=comm&news='.$news['id'].'">';
echo"<textarea class='input_form' type='text' name='comment' id='mail'>$otv_nick</textarea><br/>";
echo"<input class='input_form' type='hidden' name='ank_id' value='$ank_id'/>";
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
echo"<li><a href='/moderator'><img src='/style/images/chat/moder.png' alt=''/> Модераторы</a></li>";
echo'<div class="line_1"></div>';
echo"<li><a href='/news'><img src='/style/images/body/back.png' alt=''/> Новости</a></li>";
echo'</div>';
require_once H.'system/footer.php';
?>