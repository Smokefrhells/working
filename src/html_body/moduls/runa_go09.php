<?php
require_once '../../system/system.php';
$head = 'Руны';
echo only_reg();
echo blacksmith_level();
require_once H.'system/head.php';
echo'<img src="/style/images/location/blacksmith.jpg" class="img"/>';
echo'<div class="page">';

#-Кол-во рун-#
$sel_count = $pdo->prepare("SELECT COUNT(*) FROM `weapon_runa` WHERE `user_id` = :user_id AND `weapon_id` = 0");
$sel_count->execute(array(':user_id' => $user['id']));
$amount = $sel_count->fetch(PDO::FETCH_LAZY);
$num = 10;  
$page = $_GET['page'];  
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;$page = intval($page);if(empty($page) or $page < 0) $page = 1;if($page > $total) $page = $total;$start = $page * $num - $num;  
#-Выборка рун-#
$sel_weapon_ru = $pdo->prepare("SELECT * FROM `weapon_runa` WHERE `weapon_id` = 0 AND `user_id` = :user_id ORDER BY `runa` DESC LIMIT $start, $num");
$sel_weapon_ru->execute(array(':user_id' => $user['id']));
#-Если есть руны-#
if($sel_weapon_ru-> rowCount() != 0){
while($runa = $sel_weapon_ru->fetch(PDO::FETCH_LAZY))  
{
echo'<div style="padding-top: 3px;"></div>';
echo"<a href='/blacksmith_act?act=runa_sell&runa_id=$runa[id]' class='button_green_a'><img src='/style/images/runa/$runa[runa].png' alt=''/>[+$runa[runa]] продать  за <img src='/style/images/many/gold.png' alt=''/>$runa[runa]</a>";
}
echo'<div style="padding-top: 3px;"></div>';
}else{
echo'<div class="error_list"><img src="/style/images/body/error.png" alt=""/>Руны отсутствуют!</div>';
}
#-Отображение постраничной навигации-#
if($posts > $num){
$action = '';
echo'<div class="line_1"></div>';
echo'<div class="body_list">';
$z = pages($posts,$total,$action);
echo'</div>';
}

echo'<div class="line_1"></div>';
echo'<div class="body_list">';
echo'<div class="menulist">';
echo'<li><a href="/blacksmith"><img src="/style/images/body/back.png" alt=""/> Кузнец</a></li>';
echo'</div>';
echo'</div>';
echo'</div>';
require_once H.'system/footer.php';
?>