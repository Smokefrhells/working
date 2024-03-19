<?php
require_once '../../system/system.php';
$head = 'Конкурс рефералов';
echo only_reg();
echo save();
require_once H.'system/head.php';

echo'<div class="body_list">';
echo'<div class="menulitl">';

#-Считаем рефералов-#
$sel_ref_c = $pdo->query("SELECT COUNT(*) FROM `users` WHERE `ref_comp` >= 5");
$amount = $sel_ref_c->fetch(PDO::FETCH_LAZY);

#-Действия постраничной навигации-#
$num = 10;
$page = $_GET['page'];
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;$page = intval($page);if(empty($page) or $page < 0) $page = 1;if($page > $total) $page = $total;$start = $page * $num - $num;  
$sel_users = $pdo->prepare("SELECT `id`, `nick`, `avatar`, `pol`, `ref_comp`, `time_online` FROM `users` WHERE `ref_comp` >= 5 ORDER BY `ref_comp` DESC LIMIT $start, $num");
$sel_users->execute(array(':ref_id' => $user['id']));
if($sel_users-> rowCount() != 0){
while($all = $sel_users->fetch(PDO::FETCH_LAZY))  
{
#-Первое место-#
$sel_users_1 = $pdo->prepare("SELECT `id`, `nick`, `avatar`, `pol`, `ref_comp`, `time_online` FROM `users` WHERE `ref_comp` >= 5 ORDER BY `ref_comp` DESC LIMIT 0, 1");
$sel_users_1->execute(array(':ref_id' => $user['id']));
$mesto_1 = $sel_users_1->fetch(PDO::FETCH_LAZY);
#-Второе место-#
$sel_users_2 = $pdo->prepare("SELECT `id`, `nick`, `avatar`, `pol`, `ref_comp`, `time_online` FROM `users` WHERE `ref_comp` >= 5 ORDER BY `ref_comp` DESC LIMIT 1, 1");
$sel_users_2->execute(array(':ref_id' => $user['id']));
$mesto_2 = $sel_users_2->fetch(PDO::FETCH_LAZY);
#-Третье место-#
$sel_users_3 = $pdo->prepare("SELECT `id`, `nick`, `avatar`, `pol`, `ref_comp`, `time_online` FROM `users` WHERE `ref_comp` >= 5 ORDER BY `ref_comp` DESC LIMIT 2, 1");
$sel_users_3->execute(array(':ref_id' => $user['id']));
$mesto_3 = $sel_users_3->fetch(PDO::FETCH_LAZY);

echo'<div class="line_1"></div>';
if($all['id'] == $mesto_1['id'] or $all['id'] == $mesto_2['id'] or $all['id'] == $mesto_3['id'] ){
if($all['id'] == $mesto_1['id']) $mesto = 1;
if($all['id'] == $mesto_2['id']) $mesto = 2;
if($all['id'] == $mesto_3['id']) $mesto = 3;
echo'<li><a href="/hero/'.$all['id'].'">';
echo'<div class="t_max">';
echo"<div class='t_img'><img src='".avatar_img_min($all['avatar'], $all['pol'])."' width='50' height='50' alt=''/></div>";
echo'<div class="t_name">'.online($all['time_online']).' '.($all['id'] == $user['id'] ? "<span class='green'><b>$all[nick]</b></span>" : "<b>$all[nick]</b>").'';
echo"<div class='t_param'><img src='/style/images/user/user.png' alt=''/>Пригласил: $all[ref_comp]<br/><img src='/style/images/body/rating.png' alt=''/>$mesto место</div>";
echo'</div>';
echo'</div>';
echo'</a></li>';
}else{
echo"<li><a href='/hero/$all[id]'> <img src='".avatar_img_min($all['avatar'], $all['pol'])."' class='menulitl_img' width='30' height='30' alt=''/><div class='menulitl_block'>".online($all['time_online'])." <span class='menulitl_name'>".($all['id'] == $user['id'] ? "<span class='green'>$all[nick]</span>" : "$all[nick]")."</span><br/><div class='menulitl_param'><img src='/style/images/user/user.png' alt=''/>Пригласил: $all[ref_comp]</div></div></a></li>";
}
}
}else{
echo'<div class="error_list">';
echo'<center><img src="/style/images/body/error.png" alt=""/>Участников нет!</center>';
echo'</div>';
}
echo'</div>';

#-Вывод постраничной навигации-#
if($posts > $num){
$action = '';
echo'<div class="line_1"></div>';
$z = pages($posts,$total,$action);
}

#-Выборка кол-во записей истории-#
$sel_ref_h = $pdo->query("SELECT COUNT(*) FROM `referal`");
$ref_h = $sel_ref_h->fetch(PDO::FETCH_LAZY);
echo'<div class="menulist">';
echo'<div class="line_1"></div>';
echo'<li><a href="/referal_history"><img src="/style/images/clan/history.png" alt=""/> История <div style="float:right; color:#666666;">['.$ref_h[0].']</div></a></li>';

echo'<div class="line_1"></div>';
echo'<li><a href="/ref"><img src="/style/images/body/ref.png" alt=""/> Ваших рефералов: '.($user['ref_comp'] >= 5 ? "<span class='green'>$user[ref_comp]</span>" : "<span class='red'>$user[ref_comp] (Не участвуете)</span>").'</a></li>';
echo'</div>';

echo'<div class="menulist">';
echo'<div class="line_1"></div>';

echo'<li><a href="/referal_bonus"><img src="/style/images/user/user.png" alt=""/>  Призы</a></li>';
echo'</div>';


echo'<div class="line_1"></div>';
echo'<div class="svg_list">';
echo'<img src="/style/images/body/imp.png" alt=""/> Подведение итогов состоится: <img src="/style/images/body/time.png" alt=""/>24.08.19<br/>';
echo'</div>';
echo'<div class="line_1"></div>';
echo'<div class="svg_list">';
echo'<img src="/style/images/body/imp.png" alt=""/> Минимальное кол-во рефералов: 5<br/>';
echo'</div>';

echo'<div class="line_1"></div>';
echo'<div class="svg_list">';
echo'<img src="/style/images/body/imp.png" alt=""/> Запрещено  создавать мультов! (БАН)<br/>';
echo'</div>';

echo'<div class="line_1"></div>';
echo'<div class="svg_list">';
echo'<img src="/style/images/body/imp.png" alt=""/> Минимальный уровень реферала: 20<br/>';
echo'</div>';

echo'</div>';
require_once H . 'system/footer.php';
?>