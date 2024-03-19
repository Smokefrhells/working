<?php
require_once '../../system/system.php';
$head = 'Платежи';
echo only_reg();
echo admin();
require_once H.'system/head.php';
#-Последние платежи в игре (10)-#
echo'<div class="body_list">';
$sel_donate = $pdo->query("SELECT * FROM `donate` ORDER BY `id` DESC LIMIT 10");
while($donate = $sel_donate->fetch(PDO::FETCH_LAZY)){
#-Выборка данных игрока-#
$sel_users = $pdo->prepare("SELECT `id`, `avatar`, `pol`, `nick` FROM `users` WHERE `id` = :all_id");
$sel_users->execute(array(':all_id' => $donate['user_id']));
$all = $sel_users->fetch(PDO::FETCH_LAZY);
#-Статус платежа-#
if($donate['statys'] == 1){
$statys = '<img src="/style/images/body/ok.png" alt=""/>Оплачен';
}else{
$statys = '<img src="/style/images/body/error.png" alt=""/>Не оплачен';
}

echo'<div class="line_1"></div>';
echo'<div class="menulitl">';
echo"<li><a href='/hero/$all[id]'><img src='".avatar_img_min($all['avatar'], $all['pol'])."' class='menulitl_img' width='30' height='30' alt=''/><div class='menulitl_block'><img src='/style/images/user/user.png'> <span class='menulitl_name'>$all[nick]</span> [".vremja($donate['time'])."]<br/><div class='menulitl_param'><img src='/style/images/many/gold.png' alt=''/>$donate[gold] <img src='/style/images/body/payment.png' alt=''/>$donate[summa] $statys</div></div></a></li>";
echo'</div>';
}
echo'</div>';
require_once H.'system/footer.php';
?>