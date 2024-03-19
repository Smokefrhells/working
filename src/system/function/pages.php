<?php
#-Постраничная навигация-#
function pages($posts,$total,$action){
$num=10;
$page = $_GET['page'];$total = intval(($posts - 1) / $num) + 1;$page = intval($page);if(empty($page) or $page < 0) $page = 1;  
if($page > $total) $page = $total;  
$start = $page * $num - $num;  
if ($page != 1) $pervpage = "<a href='?page=1$action' class='ajax_page' id='page_a'>«</a>";  
if ($page != $total) $nextpage = "<a href='?page=$total$action' class='ajax_page' id='page_a'>»</a>";  
// Находим две ближайшие станицы с обоих краев, если они есть  
if($page - 2 > 0) $page2left = "<a href='?page=".($page - 2)."".$action."' class='ajax_page' id='page_a'>".($page - 2)."</a>";  
if($page - 1 > 0) $page1left = "<a href='?page=".($page - 1)."".$action."' class='ajax_page' id='page_a'>".($page - 1)."</a>"; 
if($page + 2 <= $total) $page2right = "<a href='?page=".($page + 2)."".$action."' class='ajax_page' id='page_a'>".($page + 2)."</a>";  
if($page + 1 <= $total) $page1right = "<a href='?page=". ($page + 1) ."".$action."' class='ajax_page' id='page_a'>".($page + 1)."</a>"; 
#-Вывод-#
echo"<center><div class='navigation'>$pervpage$page2left$page1left<div class='page_navi'>$page</div>$page1right$page2right$nextpage</div></center>";  
 return;
}
?>