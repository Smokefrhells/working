<?php
require_once '../../system/system.php';
$head = 'Форум';
echo only_reg();
require_once H.'system/head.php';
echo'<div class="body_list">';
echo'<div class="menulist">';
#-Есть ли игрок в навигации по форуму-#
$sel_f_nav = $pdo->prepare("SELECT * FROM `forum_navigation` WHERE `user_id` = :user_id");
$sel_f_nav->execute(array(':user_id' => $user['id']));
#-Если нет, то создаем таблицу-#
if($sel_f_nav->rowCount() == 0){
$ins_nav = $pdo->prepare("INSERT INTO `forum_navigation` SET `user_id` = :user_id");
$ins_nav->execute(array(':user_id' => $user['id']));
}else{
$nav_f = $sel_f_nav->fetch(PDO::FETCH_LAZY);
}

#-Выборка разделов форума-#
$sel_forum = $pdo->query("SELECT * FROM `forum_razdel` WHERE `pod` = '0'");
#-Если есть записи-#
if($sel_forum-> rowCount() != 0){
#-Выводим разделы форума-#
while($forum = $sel_forum->fetch(PDO::FETCH_LAZY))  
{
$f_num = 0; //Начальное кол-во тем
#-Считаем топики данного раздела-#
$sel_c_topic = $pdo->prepare("SELECT COUNT(*) FROM `forum_topic` WHERE `razdel_id` = :razdel_id");
$sel_c_topic->execute(array(':razdel_id' => $forum['id']));
$c_topic = $sel_c_topic->fetch(PDO::FETCH_LAZY);
#-Если есть топики-#
if($c_topic[0] > 0){
for($i=0; $i<= $c_topic[0]; $i++){
$sel_topic = $pdo->prepare("SELECT `id`, `razdel_id` FROM `forum_topic` WHERE `razdel_id` = :razdel_id ORDER BY `id` LIMIT $i, 1");
$sel_topic->execute(array(':razdel_id' => $forum['id']));
$topic = $sel_topic->fetch(PDO::FETCH_LAZY);
if(preg_match('/\{'.$topic['id'].'\}/',$nav_f['topic'])){
$f_num = $f_num+1;
}
}
}
#-Если кол-во топиков одинаковое, то все прочитаны-#
if($f_num == $c_topic[0]){
$f_inf = $c_topic[0];
}else{
$f_inf = "<span class='green'>+".($c_topic[0]-$f_num)."</span>";
}
echo'<div class="line_1"></div>';
echo"<li><a href='/forum_topic/$forum[id]'><img src='/style/images/forum/forum.png' alt=''/> <span class='gray'>$forum[name]</span> <div style='float: right; color:#666666;'>$f_inf</div></a></li>";
}
}
echo'</div>';
echo'</div>';
require_once H.'system/footer.php';
?>