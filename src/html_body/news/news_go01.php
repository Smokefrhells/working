<?php
require_once '../../system/system.php';
$head = 'Новости';
echo only_reg();
require_once H.'system/head.php';
echo'<div class="body_list">';
echo'<div class="menulist">';
#-Читаем новость-#
$upd_users = $pdo->prepare("UPDATE `users` SET `news_read` = 0 WHERE `id` = :user_id");
$upd_users->execute(array(':user_id' => $user['id']));
#-Считаем количество новостей-#
$sel_c_news = $pdo->query("SELECT COUNT(*) FROM `news`");
$amount = $sel_c_news->fetch(PDO::FETCH_LAZY);
#-Действия постраничной навигации-#
$num = 10;  
$page = $_GET['page'];  
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;$page = intval($page);if(empty($page) or $page < 0) $page = 1;if($page > $total) $page = $total;$start = $page * $num - $num;  
$sel_news = $pdo->query("SELECT * FROM `news` ORDER BY `time` DESC LIMIT $start, $num");
#-Если есть записи-#
if($sel_news-> rowCount() != 0){
while($news = $sel_news->fetch(PDO::FETCH_LAZY))  
{
#-Выборка комментариев новости-#
$sel_c_comment = $pdo->prepare("SELECT COUNT(*) FROM `news_comment` WHERE `news_id` = :news_id");
$sel_c_comment->execute(array(':news_id' => $news['id']));
$c_comment = $sel_c_comment->fetch(PDO::FETCH_LAZY);
if($news['close'] == 0){$color = 'green';}else{$color = 'gray';	}
echo'<div class="line_1"></div>';
echo"<li><a href='/news_read/$news[id]'><img src='/style/images/news/news.png' alt=''/> <span class='$color'>$news[title]</span> <div style='float: right; color:#666666;'>$c_comment[0]</div></a></li>";
}
}
echo'</div>';
#-Отображение постраничной навигации-#
if($posts > $num){
$action = '';
echo'<div class="line_1"></div>';
$z = pages($posts,$total,$action);}
echo'</div>';
require_once H.'system/footer.php';
?>