<?php
require_once '../../system/system.php';
$head = 'Блок';
echo only_reg();
echo admod();
require_once H.'system/head.php';
echo'<div class="page">';
echo'<center>';
echo'<form method="post" action="/block_act?act=go&ank_id='.$_GET['ank_id'].'">';
echo'<input class="input_form" type="number" name="day" placeholder="Кол-во дней"/><br/>';
echo'<input class="input_form" type="text" name="cause" maxlength="255" placeholder="Причина"/><br/>';
echo'<input class="button_green_i" name="submit" type="submit" value="Заблокировать"/>';
echo'</form>';
echo'<div style="padding-top: 5px;"></div>';
echo'</div>';
echo'<div class="body_list">';
echo'<div class="line_1"></div>';
echo'<div class="info_list">10000 - эта цифра пожизненого блока, в дальнейшем игроки с таким блоком будут удалены из игры.<br/>Устанавливайте данное время блока за серьезные нарушения.</div>';
echo'</div>';
require_once H.'system/footer.php';
?>