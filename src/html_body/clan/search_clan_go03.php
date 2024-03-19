<?php
require_once '../../system/system.php';
$head = 'Поиск кланов';
echo only_reg();
echo clan_level();
require_once H.'system/head.php';
echo'<div class="page">';
echo'<center>';
echo'<form method="get" action="/search_clan">';
echo'<input class="input_form" type="text" name="search" placeholder="Название клана" value="'.$_GET['search'].'" maxlength="25"/><br/>';
echo'<input class="button_green_i" name="submit" type="submit"  value="Поиск"/>';
echo'</form>';
echo'<div style="padding-top: 5px;"></div>';
echo'</center>';
echo'</div>';

#-Поиск кланов-#
if(isset($_GET['search'])){
$search = check($_GET['search']);
if(!empty($search)){
echo'<div class="body_list">';
echo'<div class="menulitl">';
$sel_clan_c = $pdo->prepare("SELECT COUNT(*) FROM `clan` WHERE `name` LIKE :search");
$sel_clan_c->execute(array(':search' => "%$search%"));
$amount = $sel_clan_c->fetch(PDO::FETCH_LAZY);
echo'<div class="line_1"></div>';
echo'<div class="svg_list">';
echo"<img src='/style/images/body/search.png' alt=''/> Найдено кланов: $amount[0]";
echo'</div>';
#-Действия постраничной навигации-#
$num = 10;  
$page = $_GET['page'];  
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;$page = intval($page);if(empty($page) or $page < 0) $page = 1;if($page > $total) $page = $total;$start = $page * $num - $num;  
$sel_clan = $pdo->prepare("SELECT * FROM `clan` WHERE `name` LIKE :search ORDER BY `level` DESC LIMIT $start, $num");
$sel_clan->execute(array(':search' => "%$search%"));
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
}
echo'</div>';
echo'</div>';	
}
if($posts > $num){
$action = "&search=$search";
echo'<div class="body_list">';
echo'<div class="line_1"></div>';
$z = pages($posts,$total,$action);
echo'</div>';
}
}
require_once H.'system/footer.php';
?>
