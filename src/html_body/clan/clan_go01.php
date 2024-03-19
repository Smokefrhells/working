<?php
require_once '../../system/system.php';
echo only_reg();
echo clan_level();
$head = 'Кланы';
require_once H.'system/head.php';
echo'<div class="body_list">';
echo'<img src="/style/images/location/clan.jpg" class="img" alt=""/>';
echo'<div class="menulitl">';
#-Кол-во кланов-#
$sel_clan_c = $pdo->query("SELECT COUNT(*) FROM `clan`");
$amount = $sel_clan_c->fetch(PDO::FETCH_LAZY);
#-Действия постраничной навигации-#
$num = 10;  
$page = $_GET['page'];  
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;$page = intval($page);if(empty($page) or $page < 0) $page = 1;if($page > $total) $page = $total;$start = $page * $num - $num;  
$sel_clan = $pdo->query("SELECT * FROM `clan` ORDER BY `level` DESC, `figur` DESC LIMIT $start, $num");
#-Если есть записи-#
if($sel_clan-> rowCount() != 0){
while($clan = $sel_clan->fetch(PDO::FETCH_LAZY))
{
#-Выборка количества участников-#
$sel_clan_u_c = $pdo->prepare("SELECT COUNT(*) FROM `clan_users` WHERE `clan_id` = :clan_id");
$sel_clan_u_c->execute(array(':clan_id' => $clan['id']));
$kolvo = $sel_clan_u_c->fetch(PDO::FETCH_LAZY);
echo'<div class="line_1"></div>';
echo"<li><a href='/clan/view/$clan[id]'> <img src='".avatar_clan($clan['avatar'])."' class='menulitl_img_no' width='30' height='30' alt=''/><div class='menulitl_block'><span class='menulitl_name'><img src='/style/images/body/clan.png' alt=''/>$clan[name]</span><br/><div class='menulitl_param'><img src='/style/images/user/level.png' alt=''/>$clan[level] <img src='/style/images/user/user.png' alt=''/>$kolvo[0]/$clan[quatity_user] чел.</div></div></a></li>";
}
echo'</div>';
#-Отображение постраничной навигации-#
if($posts > $num){
$action = '';
echo'<div class="body_list">';
echo'<div class="line_1"></div>';
$z = pages($posts,$total,$action);
echo'</div>';
}
echo'<div class="menulist">';
#-Нижнее меню-#
echo'<div class="line_1"></div>';
echo"<li><a href='/search_clan'><img src='/style/images/body/search.png' alt=''/> Найти клан</a></li>";
}else{ //Если нет кланов
echo'<div class="body_list">';	
echo'<div class="error_list">';	
echo'<img src="/style/images/body/error.png" alt=""/>Кланов пока что нет!';
echo'</div>';
echo'</div>';
}

#-Проверяем что мы не состоим в клане-#
$sel_clan_u = $pdo->prepare("SELECT `user_id` FROM `clan_users` WHERE `user_id` = :user_id");
$sel_clan_u ->execute(array(':user_id' => $user['id']));
if($sel_clan_u -> rowCount() == 0){
echo'<div class="line_1"></div>';
echo"<li><a href='/create_clan'><img src='/style/images/many/gold.png' alt=''/>Создать клан</a></li>";
echo'</div>';
}
echo'</div>';
echo'</div>';
echo'</div>';
require_once H.'system/footer.php';
?>