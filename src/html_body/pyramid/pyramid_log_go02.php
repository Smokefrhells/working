<?php
require_once '../../system/system.php';
$head = 'Лог боя';
echo only_reg();
require_once H.'system/head.php';
echo'<div class="page">';
#-Считаем общее кол-во записей-#
$sel_count_l = $pdo->query("SELECT COUNT(*) FROM `pyramid_battle_l`");
$amount = $sel_count_l->fetch(PDO::FETCH_LAZY);
#-Постраничная навигация-#
$num = 10;  
$page = $_GET['page'];  
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;$page = intval($page);if(empty($page) or $page < 0) $page = 1;if($page > $total) $page = $total;$start = $page * $num - $num;  
#-Выводим историю сражений с боссами-#
$sel_pyramid_l = $pdo->query("SELECT * FROM `pyramid_battle_l` ORDER BY `id` LIMIT $start, $num");
if($sel_pyramid_l-> rowCount() != 0){
while($pyramid_l = $sel_pyramid_l->fetch(PDO::FETCH_LAZY)){
echo'<div style="padding: 3px;">';
echo"<span class='red'>$pyramid_l[log]</span><br/>";
echo'</div>';
}
}
echo'</div>';

echo'<div class="body_list">';
#-Номирация страниц-#
if($posts > $num){
$action = "";
echo'<div class="line_1"></div>';
$z = pages($posts,$total,$action);
}
echo'</div>';
require_once H.'system/footer.php';