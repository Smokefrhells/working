<?php
require_once '../../system/system.php';
$head = 'Рефералы';
echo only_reg();
require_once H.'system/head.php';
echo'<div class="page">';
echo'<div style="padding: 3px; color: #bfbfbf;">';
echo'Приглашайте новых людей в игру и получайте за это <img src="/style/images/many/gold.png" alt=""/><b>Золото.</b> За каждого игрока который купит золото вы получаете <b>15%</b> от суммы покупки.<br/>';
echo"Ваша реферальная ссылка: ";
echo"<input class='input_form' type='text' name='nick' value='$set[site]/?ref=$user[id]' maxlength='15'/><br/>";
echo'</div>';
echo'<div class="body_list">';
echo'<div class="menulitl">';
#-Выводим рефералов-#
$sel_users_c = $pdo->prepare("SELECT COUNT(*) FROM `users` WHERE `ref_id` = :ref_id");
$sel_users_c->execute(array(':ref_id' => $user['id']));
$amount = $sel_users_c->fetch(PDO::FETCH_LAZY);
echo'<div class="line_1"></div>';
echo'<div style="padding: 5px; color: #00a800;">';
echo"<img src='/style/images/body/ok.png' alt=''/>Количество рефералов: $amount[0]";
echo'</div>';
echo'<div class="line_1"></div>';
echo'<div style="padding: 5px; color: #00a800;">';
echo"<img src='/style/images/many/gold.png' alt=''/>Заработано золота: $user[referer_gold]";
echo'</div>';
#-Действия постраничной навигации-#
$num = 10;  
$page = $_GET['page'];  
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;$page = intval($page);if(empty($page) or $page < 0) $page = 1;if($page > $total) $page = $total;$start = $page * $num - $num;  
$sel_users = $pdo->prepare("SELECT `id`, `pol`, `nick`, `time_online`, `param`, `level`, `ref_id`, `avatar`, `sila`, `s_sila`, `sila_bonus`, `zashita`, `s_zashita`, `zashita_bonus`, `health`, `s_health`, `health_bonus` FROM `users` WHERE `ref_id` = :ref_id ORDER BY `time` DESC LIMIT $start, $num");
$sel_users->execute(array(':ref_id' => $user['id']));
#-Если есть записи-#
if($sel_users-> rowCount() != 0){
while($all = $sel_users->fetch(PDO::FETCH_LAZY))  
{
echo'<div class="line_1"></div>';
echo"<li><a href='/hero/$all[id]'><img src='".avatar_img_min($all['avatar'], $all['pol'])."' class='menulitl_img' width='30' height='30' alt=''/><div class='menulitl_block'><img src='/style/images/user/user.png'><span class='menulitl_name'>$all[nick]</span><br/><div class='menulitl_param'><img src='/style/images/user/level.png' alt=''/>$all[level] <img src='/style/images/user/sila.png' alt=''/>".($all['sila']+$all['s_sila']+$all['sila_bonus'])." <img src='/style/images/user/zashita.png' alt=''/>".($all['zashita']+$all['s_zashita']+$all['zashita_bonus'])." <img src='/style/images/user/health.png' alt=''/>".($all['health']+$all['s_health']+$all['health_bonus'])."</div></div></a></li>";
}
}
echo'</div>';
echo'</div>';
#-Вывод постраничной навигации-#
if($posts > $num){
$action = '';
echo'<div class="body_list">';
echo'<div class="line_1"></div>';
$z = pages($posts,$total,$action);
echo'</div>';
}
echo'</div>';
require_once H.'system/footer.php';
?>