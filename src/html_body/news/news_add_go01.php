<?php
require_once '../../system/system.php';
$head = 'Добавление новости';
echo only_reg();
require_once H.'system/head.php';
#-Проверяем можем ли мы редактировать-#
echo'<div class="page">';
if($user['prava'] == 1){
echo'<center>';
echo'<form method="post" action="/news_act?act=add&news='.$news['id'].'">';
echo"<input class='input_form' type='text' name='title' placeholder='Заголовок'/><br/>";
echo"<textarea class='input_form' type='text' name='msg' id='mail' placeholder='Текст'></textarea><br/>";
echo'</center>';
echo'<input class="button_i_mini" name="submit" type="submit" value="Добавить"/>';
echo'</form>';
echo'<img src="/style/images/body/smiles.png" alt="" class="smiles_button"/>';
echo'<div style="padding-top: 5px;"></div>';

#-Смайлы-#
echo'<div id="panel_smiles">';
echo'<div class="line_1_m"></div>';
echo smiles_kolobok();
echo'</div>';

}else{ //Если нет прав
echo'<div class="line_1_m"></div>';
echo'<div class="error_list">';
echo'<img src="/style/images/body/error.png" alt="">У вас нет прав на редактирование!';
echo'</div>';
}
#-Меню-#`
echo'<div class="body_list">';
echo'<div class="menulist">';
echo'<div class="line_1"></div>';
echo'<li><a href="/smiles"><img src="/style/images/chat/smiles_b.png" alt=""/> Смайлы</a></li>';
echo'<div class="line_1"></div>';
echo"<li><a href='/news_read/$news[id]'><img src='/style/images/body/back.png' alt=''/> Новость</a></li>";
echo'</div>';
echo'</div>';
echo'</div>';
require_once H.'system/footer.php';
?>