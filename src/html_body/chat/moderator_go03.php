<?php
require_once '../../system/system.php';
$head = 'Модераторы';
echo only_reg();
require_once H.'system/head.php';
echo'<div class="body_list">';
echo'<div class="menulitl">';
#-Делаем выборку модераторов-#
$sel_users = $pdo->prepare("SELECT `id`, `avatar`, `prava`, `pol`, `nick`, `level`, `sila`, `s_sila`, `sila_bonus`, `zashita`, `s_zashita`, `zashita_bonus`, `health`, `s_health`, `health_bonus`, `time_online` FROM `users` WHERE `prava` = :prava ORDER BY `time` DESC");
$sel_users->execute(array(':prava' => 2));
#-Если есть записи-#
if($sel_users-> rowCount() != 0){
while($all = $sel_users->fetch(PDO::FETCH_LAZY))  
{
#-Онлайн или нет-#
$time = time() - 1200;
if($all['time_online'] > $time){
$img_online = '/style/images/user/online.png';
}else{
$img_online = '/style/images/user/offline.png';
}
echo'<div class="line_1"></div>';	
echo"<li><a href='/hero/$all[id]'><img src='".avatar_img_min($all['avatar'], $all['pol'])."' class='menulitl_img' width='30' height='30' alt=''/><div class='menulitl_block'><img src='$img_online'> <span class='menulitl_name'>$all[nick]</span><br/><div class='menulitl_param'><img src='/style/images/user/level.png' alt=''/>$all[level] <img src='/style/images/user/sila.png' alt=''/>".($all['sila']+$all['s_sila']+$all['sila_bonus'])." <img src='/style/images/user/zashita.png' alt=''/>".($all['zashita']+$all['s_zashita']+$all['zashita_bonus'])." <img src='/style/images/user/health.png' alt=''/>".($all['health']+$all['s_health']+$all['health_bonus'])."</div></div></a></li>";
}
}else{
echo'<div class="body_list">';	
echo'<div class="error_list">';	
echo'<img src="/style/images/body/error.png" alt=""/>Нет модераторов!';
echo'</div>';
echo'</div>';	
}
echo'</div>';
echo'</div>';
require_once H.'system/footer.php';
?>