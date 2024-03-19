<?php
require_once '../../system/system.php';
echo only_reg();
echo clan_level();
$head = 'Заявки в клан';
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
#-Проверяем что мы состоим в клане-#
$sel_clan_u = $pdo->prepare("SELECT * FROM `clan_users` WHERE `clan_id` = :clan_id AND `user_id` = :user_id");
$sel_clan_u->execute(array(':clan_id' => $clan['id'], ':user_id' => $user['id']));
$clan_u = $sel_clan_u->fetch(PDO::FETCH_LAZY); 
#-Есть ли право-#
if($clan_u['prava'] == 3 or $clan_u['prava'] == 4 or $clan_u['prava'] == 2){
#-Количество участников клана-#
$sel_clan_a_c = $pdo->prepare("SELECT COUNT(*) FROM `clan_application` WHERE `clan_id` = :clan_id");
$sel_clan_a_c->execute(array(':clan_id' => $clan['id']));
$amount = $sel_clan_a_c->fetch(PDO::FETCH_LAZY);
#-Действия постраничной навигации-#
$num = 10;  
$page = $_GET['page'];  
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;$page = intval($page);if(empty($page) or $page < 0) $page = 1;if($page > $total) $page = $total;$start = $page * $num - $num;  
$sel_clan_a = $pdo->prepare("SELECT * FROM clan_application, users WHERE clan_application.clan_id = :clan_id AND clan_application.user_id = users.id ORDER BY users.level DESC LIMIT $start, $num");
$sel_clan_a->execute(array(':clan_id' => $clan['id']));
if($sel_clan_a-> rowCount() != 0){
echo'<div class="body_list">';
echo'<div class="menulist">';
while($clan_a = $sel_clan_a->fetch(PDO::FETCH_LAZY))  
{
#-Выборка данных игрока-#
$sel_users = $pdo->prepare("SELECT `id` , `nick`, `level`, `avatar`, `pol`, `time_online`, `param` FROM `users` WHERE `id` = :user_id");
$sel_users->execute(array(':user_id' => $clan_a['user_id']));
$all = $sel_users->fetch(PDO::FETCH_LAZY);
echo'<div class="line_1"></div>';
echo'<div class="menulitl">';
echo"<li><a href='/hero/$all[id]'><img src='".avatar_img_min($all['avatar'], $all['pol'])."' class='menulitl_img' width='30' height='30' alt=''/><div class='menulitl_block'>".online($all['time_online'])." <span class='menulitl_name'>".$all['nick']." </span><br/><div class='menulitl_param'><img src='/style/images/user/level.png' alt=''/>$all[level] <img src='/style/images/body/all.png' alt=''/>$all[param]</div></div></a></li>";
echo'</div>';
echo'<div class="line_1"></div>';
echo"<a href='/clan_applic_act?act=ok&clan_id=$clan[id]&app_id=$clan_a[id]&user_id=$clan_a[user_id]' style='display: inline-block;text-decoration:underline;color:#00a800;'><img src='/style/images/body/ok.png' alt=''/>Принять</a>";
if($clan_u['prava'] == 3 or $clan_u['prava'] == 4){
echo"<a href='/clan_applic_act?act=err&clan_id=$clan[id]&app_id=$clan_a[id]' style='display: inline-block;text-decoration:underline;color: #ff0000;'><img src='/style/images/body/error.png' alt=''/>Отказать</a>";
}
}
echo'</div>';
echo'</div>';
#-Отображение постраничной навигации-#
if($posts > $num){
$action = '';
echo'<div class="body_list">';
echo'<div class="line_1"></div>';
$z = pages($posts,$total,$action);
echo'</div>';
}
}else{ //Если нет заявок
echo'<div class="body_list">';	
echo'<div class="error_list">';	
echo'<img src="/style/images/body/error.png" alt=""/>Заявок нет!';
echo'</div>';
echo'</div>';	
}
}
require_once H.'system/footer.php';
?>