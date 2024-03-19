<?php
require_once '../system/system.php';
$head = 'Webmoney';
echo only_reg();
require_once H.'system/head.php';
echo'<div class="page">';
echo'<div style="padding: 3px; color: #bfbfbf;">';
echo'Переведите средства на один из кошельков <b>с указанием вашего ID</b>.<br/>';
echo'Далее напишите Администрации сообщение с текстом: <b>Покупка золота через Webmoney</b>.<br/>';
//echo'<span class="orange"><img src="/style/images/many/gold.png" alt=""/>100 золота => 10 руб.<br/>';

echo'<br>';
echo'<span class="orange"><img src="/style/images/many/gold.png" alt=""/>100 золота => 10 руб.<br/>';
echo'<span class="orange"><img src="/style/images/many/gold.png" alt=""/>1000 золота => 100 руб.<br/>';
echo'<span class="orange"><img src="/style/images/many/gold.png" alt=""/>5000 золота => 500 руб.<br/>';
echo'<span class="orange"><img src="/style/images/many/gold.png" alt=""/>10000 золота => 1000 руб.<br/>';
echo'<br>';
/*
echo'<FONT color="LightSeaGreen"><img src="/style/images/many/gold.png" alt=""/><s>100</s></FONT> 300 золота => 10 руб..<br/>';
echo'<FONT color="LightSeaGreen"><img src="/style/images/many/gold.png" alt=""/><s>1000</s></FONT>  3000 золота => 100 руб.<br/>';
echo'<FONT color="LightSeaGreen"><img src="/style/images/many/gold.png" alt=""/><s>5000</s> </FONT> 15000 золота => 500 руб.<br/>';
echo'<FONT color="LightSeaGreen"><img src="/style/images/many/gold.png" alt=""/><s>10000</s> </FONT> 30000 золота => 1000 руб.<br/>';
*/


echo"<FONT color='RED'><img src='/style/images/user/user.png' alt=''/> Ваш ID: $user[id]</span><br/></FONT>";
echo"Доступные кошельки:";
echo'<span class="yellow"><br/>';
echo'<img src="/style/images/body/webmoney_rus.png" alt=""/> R395481309727<br/>';
echo'<img src="/style/images/body/webmoney_usa.png" alt=""/> Z506356973052<br/>';
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