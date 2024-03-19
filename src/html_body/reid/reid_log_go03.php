<?php
require_once '../../system/system.php';
$head = 'Предыдущий лог';
echo only_reg();
echo reid_level();
require_once H.'system/head.php';
echo'<div class="body_list">';

#-Проверяем есть ли босс и что бой не начат-#
$sel_reid = $pdo->query("SELECT * FROM `reid_boss` WHERE `statys` = 0");
if($sel_reid->rowCount() != 0){
	
#-Количество лога-#
$sel_reid_l_c = $pdo->query("SELECT COUNT(*) FROM `reid_log`");
$amount = $sel_reid_l_c->fetch(PDO::FETCH_LAZY);
#-Действия постраничной навигации-#
$num = 10;  
$page = $_GET['page'];  
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;$page = intval($page);if(empty($page) or $page < 0) $page = 1;if($page > $total) $page = $total;$start = $page * $num - $num;  

#-Лог-#
$sel_reid_l = $pdo->query("SELECT * FROM `reid_log` ORDER BY `time` DESC LIMIT $start, $num");
if($sel_reid_l->rowCount() != 0){
while($reid_l = $sel_reid_l->fetch(PDO::FETCH_LAZY)){
echo'<div class="body_list"><div style="padding: 2px; padding-left: 5px;">';
echo"$reid_l[log]";
echo'</div>';
echo'</div>';
}
}
}else{
echo'<div class="body_list">';	
echo'<div class="error_list">';	
echo'<img src="/style/images/body/error.png" alt=""/>Лог недоступен!';
echo'</div>';
echo'</div>';	
}
#-Отображение постраничной навигации-#
if($posts > $num){
$action = '';
echo'<div class="line_1"></div>';
$z = pages($posts,$total,$action);
}
echo'</div>';
require_once H.'system/footer.php';
?>