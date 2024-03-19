<?php
require_once '../../system/system.php';
echo only_reg();
echo clan_level();
$head = 'История клана';
require_once H.'system/head.php';
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
#-Делаем выборку самого последнего лога и записываем его в бд-#
$sel_end = $pdo->prepare("SELECT `id`, `clan_id` FROM `clan_log` WHERE `clan_id` = :clan_id ORDER BY `time` DESC LIMIT 1");
$sel_end->execute(array(':clan_id' => $clan['id']));
if($sel_end-> rowCount() != 0){ //Если есть вообще сообщения
$end = $sel_end->fetch(PDO::FETCH_LAZY);
#-Запысываем игроку последнее сообщение которое он прочитал-#
$upd_clan_u = $pdo->prepare("UPDATE `clan_users` SET `log_id` = :log_id WHERE `clan_id` = :clan_id AND `user_id` = :user_id LIMIT 1");
$upd_clan_u->execute(array(':clan_id' => $clan['id'], ':log_id' => $end['id'], ':user_id' => $user['id']));
}
#-Вывод лога событий-#
echo'<div class="body_list">';
#-Считаем количество записей-#
$sel_c_log = $pdo->prepare("SELECT COUNT(*) FROM `clan_log` WHERE `clan_id` = :clan_id");
$sel_c_log->execute(array(':clan_id' => $clan['id']));
$amount = $sel_c_log->fetch(PDO::FETCH_LAZY);
#-Действия постраничной навигации-#
$num = 10;  
$page = $_GET['page'];  
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;$page = intval($page);if(empty($page) or $page < 0) $page = 1;if($page > $total) $page = $total;$start = $page * $num - $num;  
$sel_log = $pdo->prepare("SELECT * FROM `clan_log` WHERE `clan_id` = :clan_id ORDER BY `time` DESC LIMIT $start, $num");
$sel_log->execute(array(':clan_id' => $clan['id']));
#-Если есть записи-#
if($sel_log-> rowCount() != 0){
echo'<div class="body_list">';
while($log = $sel_log->fetch(PDO::FETCH_LAZY))  
{
echo'<div class="line_1"></div>';
echo'<div class="svg_list">';
#-Вступ в клан-#
if($log['type'] == 1){
echo"<img src='/style/images/body/ok.png' alt=''/>$log[log]";
}
#-Выход с клана-#
if($log['type'] == 2){
echo"<img src='/style/images/body/error.png' alt=''/>$log[log]";
}
#-Казна-#
if($log['type'] == 3){
echo"<img src='/style/images/clan/kazna.png' alt=''/>$log[log]";
}
#-Исключение-#
if($log['type'] == 4){
echo"<img src='/style/images/body/error.png' alt=''/>$log[log]";
}
#-Права-#
if($log['type'] == 5){
echo"<img src='/style/images/clan/crown.png' alt=''/>$log[log]";
}
#-Права-#
if($log['type'] == 6){
echo"<img src='/style/images/clan/edit.png' alt=''/>$log[log]";
}
#-Постройки клана-#
if($log['type'] == 7){
echo"<img src='/style/images/clan/building.png' alt=''/>$log[log]";
}
#-Турнир-#
if($log['type'] == 8){
echo"<img src='/style/images/body/rating.png' alt=''/>$log[log]";
}
#-Форум-#
if($log['type'] == 9){
echo"<img src='/style/images/forum/forum.png' alt=''/>$log[log]";
}
#-Объявления-#
if($log['type'] == 10){
echo"<img src='/style/images/clan/ad.png' alt=''/>$log[log]";
}
#-Клановые боссы-#
if($log['type'] == 11){
echo"<img src='/style/images/clan/boss.png' alt=''/>$log[log]";
}

echo'</div>';
}
echo'</div>';
}
#-Отображение постраничной навигации-#
if($posts > $num){
$action = '';
echo'<div class="body_list">';
echo'<div class="line_1"></div>';
$z = pages($posts,$total,$action);
echo'</div>';
}
require_once H.'system/footer.php';
?>