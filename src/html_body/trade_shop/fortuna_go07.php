<?php
require_once '../../system/system.php';
$head = 'Фортуна';
echo only_reg();
require_once H.'system/head.php';
echo'<div class="page">';
echo'<center>';
if(isset($_GET['koleso'])){
echo'<img src="/style/images/fortuna/'.($_GET['koleso']).'.png" alt=""/>';
}else{
echo'<img src="/style/images/fortuna/koleso_1.png" alt=""/>';
}
echo'</center>';
echo'<div class="body_list">';
echo'<div class="line_1"></div>';
echo'<div style="padding-top: 3px;"></div>';
echo'<a href="/fortuna_act?act=go" class="button_green_a">Крутить '.($user['fortuna'] == 0 ? "бесплатно" : "за <img src='/style/images/many/gold.png' alt=''/>".($user['fortuna']*25)."").'</a>';
echo'<div style="padding-top: 3px;"></div>';
echo'</div>';

#-Лог фортуны-#
$sel_fortuna_l = $pdo->query("SELECT * FROM `fortuna_log` ORDER BY `time` DESC LIMIT 10");
if($sel_fortuna_l-> rowCount() != 0){
echo'<div class="line_1"></div>';
while($fortuna_l = $sel_fortuna_l->fetch(PDO::FETCH_LAZY))  
{
echo'<div class="body_list"><div style="padding: 2px;padding-left: 5px;">';
echo"<span class='yellow'><img src='/style/images/body/fortuna.png' alt=''/> $fortuna_l[log]</span>";
echo'</div></div>';	
}
}

echo'</div>';
require_once H.'system/footer.php';
?>