<?php
require_once '../../system/system.php';
echo only_reg();
$head = 'Аватар';
require_once H.'system/head.php';
if($user['level'] >= 30){
echo'<div class="page">';
echo'<center>';
echo'<form method="post" enctype="multipart/form-data" action="/download_avatar_act?act=go">';
echo'<input type="file" class="input_form" name="picture">';
echo'<input type="submit" class="button_green_i" value="Загрузить">';
echo'</form>';
echo'</center>';

#-Если есть аватар-#
if($user['avatar'] != ''){
echo'<div style="padding-top: 3px;"></div>';
echo'<a href="/style/avatar/'.$user['avatar'].'" class="button_green_a">Просмотр</a>';
echo'<div style="padding-top: 3px;"></div>';
#-Удаление аватара-#
if($_GET['conf'] == 'del'){
echo'<a href="/download_avatar_act?act=del" class="button_red_a">Подтверждаю</a>';
}else{
echo'<a href="/download_avatar?conf=del" class="button_red_a">Удалить аватар</a>';
}
}
echo'<div style="padding-top: 5px;"></div>';
echo'<div class="line_1"></div>';
echo'<div class="body_list">';
echo'<div class="svg_list">';
echo'<img src="/style/images/body/imp.png" alt=""/> Размер: 250KB (jpeg, png)';
echo'</div>';
echo'</div>';

echo'</div>';
}else{
echo'<div class="line_1_m"></div>';	
echo'<div class="body_list">';
echo'<div class="error_list">';
echo'<img src="/style/images/body/error.png" alt="">Загрузка аватара доступна с <img src="/style/images/user/level.png" alt="">30 ур.';
echo'</div>';
echo'</div>';	
}
require_once H.'system/footer.php';
?>