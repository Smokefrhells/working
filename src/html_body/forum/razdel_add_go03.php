<?php
require_once '../../system/system.php';
$head = 'Создание раздела';
echo only_reg();
require_once H.'system/head.php';
echo'<div class="page">';
#-Только если не забанены на форуме-#
if($user['ban'] == 0 and $user['level'] >= 5 and $user['save'] == 1  and $user['prava']>0){
#-Проверяем наличие раздела-#
if(isset($_GET['razdel'])){
$razdel_id = check($_GET['razdel']);
#-Проверяем существует ли раздел-#
$sel_f_razdel = $pdo->prepare("SELECT * FROM `forum_razdel` WHERE `id` = :razdel_id");
$sel_f_razdel->execute(array(':razdel_id' => $razdel_id));
if($sel_f_razdel-> rowCount() != 0){
echo'<center>';
echo'<form method="post" action="/forum_act?act=add&pod=1&razdel='.$razdel_id.'">';
echo"<input class='input_form' type='text' name='title' placeholder='Название раздела' maxlength='50' required/><br/>";
echo'</center>';
echo'<input class="button_i_mini" name="submit" type="submit" value="Создать"/>';
echo'</form>';
echo'<div style="padding-top: 5px;"></div>';
echo'</div>';
}else{
echo'<div class="body_list">';	
echo'<div class="line_1_m"></div>';
echo'<div class="error_list">';
echo'<img src="/style/images/body/error.png" alt=""/>Такого раздела нет!';
echo'</div>';	
echo'</div>';	
}	
}
}else{
echo'<div class="line_1_m"></div>';
echo'<div class="body_list">';
echo'<div class="error_list">';
echo'<img src="/style/images/body/error.png" alt=""/>Вы не можете создавать топики';
echo'</div>';
echo'</div>';
}
#-Смайлы-#
echo'<div id="panel_smiles">';
echo'<div class="line_1_m"></div>';
echo smiles_kolobok();
echo'</div>';
echo'</div>';
require_once H.'system/footer.php';
?>