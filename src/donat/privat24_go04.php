<?php
require_once '../system/system.php';
$head = 'Приват 24';
echo only_reg();
require_once H.'system/head.php';
echo'<div class="page">';
echo'<div style="padding: 3px; color: #bfbfbf;">';
echo'Переведите средства на один из кошельков <b>с указанием вашего ID и НИКА</b>.<br/>';
echo'Далее напишите Администрации сообщение с текстом: <b>Покупка золота через Приват24</b>.<br/>';
echo'<span class="orange"><img src="/style/images/many/gold.png" alt=""/>100 золота => 4 гривны.<br/>';
echo'<span class="orange"><img src="/style/images/many/gold.png" alt=""/>1000 золота => 40 гривен.<br/>';
echo'<span class="orange"><img src="/style/images/many/gold.png" alt=""/>5000 золота => 200 гривен.<br/>';
echo'<span class="orange"><img src="/style/images/many/gold.png" alt=""/>10000 золота => 400 гривен.<br/>';

/*
echo'<span class="orange"><img src="/style/images/many/gold.png" alt=""/>300 золота => 4 гривны.<br/>';
echo'<span class="orange"><img src="/style/images/many/gold.png" alt=""/>3000 золота => 40 гривен.<br/>';
echo'<span class="orange"><img src="/style/images/many/gold.png" alt=""/>15000 золота => 200 гривен.<br/>';
echo'<span class="orange"><img src="/style/images/many/gold.png" alt=""/>30000 золота => 400 гривен.<br/>';
*/
echo"<img src='/style/images/user/user.png' alt=''/> Ваш ID: $user[id]</span><br/>";
echo"Доступные кошельки:";
echo'<span class="yellow"><br/>';
echo'<img src="/style/images/body/webmoney_ua.png" alt=""/> 5169 3600 0638 5698<br/>';
echo'</span>';
echo'</div>';
echo'<div class="line_1"></div>';
echo'<div class="body_list">';
echo'<div class="info_list">';
echo'<img src="/style/images/body/imp.png" alt=""/> Золото поступит на счет в течении 3 часов';
echo'</div>';
echo'</div>';
echo'<div class="menulist">';
echo'<div class="line_1"></div>';
echo'<div class="body_list">';
echo'<div class="info_list">';
echo'<a href="/payment"><img src="/style/images/ico/png/back.png" width="15px" height="15px" alt=""/> <span class="gray"><big>Вернуться назад</big></span></a>';
echo'</div>';
echo'</div>';
echo'</div>';
require_once H.'system/footer.php';
?>