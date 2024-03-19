<?php
require_once '../../system/system.php';
$head = 'Турнир';
echo only_reg();
echo clan_level();
require_once H.'system/head.php';
$sel_clan = $pdo->prepare("SELECT `id`, `figur` FROM `clan` WHERE `id` = :clan_id");
$sel_clan->execute(array(':clan_id' => $user['clan_id']));
$clan = $sel_clan->fetch(PDO::FETCH_LAZY);
$clan_figur = $clan['figur'];
$clan_id = $clan['id'];
require_once H.'system/game/tournament_clan.php';
echo'<div class="page">';
echo'<div class="body_list">';

#-Навигация-#
$figur = '<a href="/tournament_clan?type=1" style="text-decoration:none;"><span class="body_sel">'.(($_GET['type'] == 1 or $_GET['type'] > 2 or !isset($_GET['type']))  ? "<b>Статуэтки</b> <img src='/style/images/body/rating.png' alt=''/>$mesto_figur" : "Статуэтки").'</span></a>';

#-Тип рейтинга-#
$type = check($_GET['type']);
if(empty($type) or $type > 2){
$type = 1;
}

#-Вывод-#
echo'<div class="body_list">';	
echo'<div style="padding: 5px;">';
echo''.$figur.'';
echo'</div>';
echo'</div>';

echo'<div class="menulitl">';

#-Статуэтки-#
if($_GET['type'] == 1 or !isset($_GET['type']) or $_GET['type'] != 2){
#-Кол-во записей-#
$sel_count_t = $pdo->query("SELECT COUNT(*) FROM `clan` WHERE `figur` > 0 AND `level` = 150");
$amount = $sel_count_t->fetch(PDO::FETCH_LAZY);
$num = 10;  
$page = $_GET['page'];  
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;$page = intval($page);if(empty($page) or $page < 0) $page = 1;if($page > $total) $page = $total;$start = $page * $num - $num;  
#-Вывод записей-#
$sel_clan_t = $pdo->query("SELECT * FROM `clan` WHERE `figur` > 0 AND `level` = 150 ORDER BY `figur` DESC, `id` LIMIT $start, $num");
#-Если есть записи-#
$t = $start+$amount[0];
for($i = $start; $i <= $t; $i++){
while($clan = $sel_clan_t->fetch(PDO::FETCH_LAZY))  
{
$i = $i+1;
#-Выборка количества участников-#
$sel_clan_u_c = $pdo->prepare("SELECT COUNT(*) FROM `clan_users` WHERE `clan_id` = :clan_id");
$sel_clan_u_c->execute(array(':clan_id' => $clan['id']));
$kolvo = $sel_clan_u_c->fetch(PDO::FETCH_LAZY);
echo'<div class="line_1"></div>';
echo"<li><a href='/clan/view/$clan[id]'> <img src='".avatar_clan($clan['avatar'])."' class='menulitl_img_no' width='30' height='30' alt=''/><div class='menulitl_block'><span class='menulitl_name'><img src='/style/images/body/clan.png' alt=''/>$clan[name]</span><br/><div class='menulitl_param'><img src='/style/images/user/figur.png' alt=''/>$clan[figur] <img src='/style/images/user/user.png' alt=''/>$kolvo[0]/$clan[quatity_user] чел.</div></div></a></li>";
}
}
}

echo'</div>';

#-Отображение постраничной навигации-#
if($posts > $num){
if(!isset($_GET['type'])){$type = 1;}else{$type = $_GET['type'];}
$action = "&type=$type";
echo'<div class="line_1"></div>';
$z = pages($posts,$total,$action);
}
echo'</div>';
require_once H.'system/footer.php';
?>