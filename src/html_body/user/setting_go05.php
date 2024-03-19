<?php
require_once '../../system/system.php';
echo only_reg();
$head = 'Настройки';
require_once H.'system/head.php';
echo'<div class="page">';
#-Если выбран элемент для смены-#
if(isset($_GET['change'])){
$change = check($_GET['change']);
#-Проверяем что сменить-#
#-Ник-#
if($change == 'nick'){
echo'<center>';
echo'<form method="post" action="/setting_act?act=nick">';
echo"<input class='input_form' type='text' name='nick' value='$user[nick]' maxlength='15'/><br/>";
echo'<input class="button_green_i" name="submit" type="submit"  value="Изменить"/>';
echo'<div style="padding-top: 3px;"></div>';
echo'<a href="/setting" class="button_red_a">Скрыть</a>';
echo'</center>';
echo'<div style="padding-top: 5px;"></div>';
}
#-Пароль-#
if($change == 'password'){
echo'<center>';
echo'<form method="post" action="/setting_act?act=password">';
echo"<input class='input_form' type='text' name='pass_old' placeholder='Текущий пароль' maxlength='25'/><br/>";
echo"<input class='input_form' type='text' name='pass_new' placeholder='Новый пароль' maxlength='25'/><br/>";
echo"<input class='input_form' type='text' name='pass_rep' placeholder='Повторите пароль' maxlength='25'/><br/>";
echo'<input class="button_green_i" name="submit" type="submit"  value="Изменить"/>';
echo'<div style="padding-top: 3px;"></div>';
echo'<a href="/setting" class="button_red_a">Скрыть</a>';
echo'</center>';
echo'<div style="padding-top: 5px;"></div>';	
}
#-Пол-#
if($change == 'pol'){
echo'<center>';
echo'<form method="post" action="/setting_act?act=pol">';
echo'<select name="pol">';
echo'<option value="1">Мужской</option>';
echo'<option value="2">Женский</option>';
echo'</select><br/>';
echo'<input class="button_green_i" name="submit" type="submit"  value="Сменить"/>';
echo'<div style="padding-top: 3px;"></div>';
echo'<a href="/setting" class="button_red_a">Скрыть</a>';
echo'</center>';
echo'<div style="padding-top: 5px;"></div>';		
}
#-Сторона-#
if($change == 'storona'){
echo'<center>';
echo'<form method="post" action="/setting_act?act=storona">';
echo'<select name="storona">';
echo'<option value="1">Свет</option>';
echo'<option value="2">Тьма</option>';
echo'</select><br/>';
echo'<input class="button_green_i" name="submit" type="submit"  value="Сменить"/>';
echo'<div style="padding-top: 3px;"></div>';
echo'<a href="/setting" class="button_red_a">Скрыть</a>';
echo'</center>';
echo'<div style="padding-top: 5px;"></div>';		
}
#-Статус-#
if($change == 'status'){
echo'<center>';
echo'<form method="post" action="/setting_act?act=status">';
echo"<input class='input_form' type='text' name='status' value='$user[status]' maxlength='100'/><br/>";
echo'<input class="button_green_i" name="submit" type="submit"  value="Изменить"/>';
echo'<div style="padding-top: 3px;"></div>';
echo'<a href="/setting" class="button_red_a">Скрыть</a>';
echo'</form>';
echo'<div style="padding-top: 5px;"></div>';
echo'</center>';
}	
#-Меню-#
}else{
echo'<div style="padding-top: 5px;"></div>';
echo"<a href='?change=nick' class='button_green_a'>Изменить ник за <img src='/style/images/many/gold.png' alt=''/>1000</a>";
echo'<div style="padding-top: 3px;"></div>';
echo"<a href='?change=pol' class='button_green_a'>Сменить пол за <img src='/style/images/many/gold.png' alt=''/>500</a>";
echo'<div style="padding-top: 3px;"></div>';
if($user['storona'] == 0){
echo"<a href='?change=storona' class='button_green_a'>Сменить сторону за <img src='/style/images/many/gold.png' alt=''/>0</a>";
}else{
echo"<a href='?change=storona' class='button_green_a'>Сменить сторону за <img src='/style/images/many/gold.png' alt=''/>500</a>";
}
echo'<div style="padding-top: 3px;"></div>';
echo"<a href='?change=password' class='button_green_a'>Изменить пароль</a>";
echo'<div style="padding-top: 3px;"></div>';
echo"<a href='?change=status' class='button_green_a'>Изменить статус</a>";

                                                            #-Отправка уведомлений игроку-#
#-Кланы-#
echo'<div style="padding-top: 3px;"></div>';
if($user['ev_clan'] == 0){
echo"<a href='/event_act?act=clan' class='button_green_a'>Приглашать в клан: Да</a>";
}else{
echo"<a href='/event_act?act=clan' class='button_green_a'>Приглашать в клан: Нет</a>";
}

#-Помощь с боссами-#
echo'<div style="padding-top: 3px;"></div>';
if($user['ev_help'] == 0){
echo"<a href='/event_act?act=help' class='button_green_a'>Звать на помощь: Да</a>";
}else{
echo"<a href='/event_act?act=help' class='button_green_a'>Звать на помощь: Нет</a>";
}

#-Сообщения-#
echo'<div style="padding-top: 3px;"></div>';
if($user['ev_mail'] == 0){
echo"<a href='/event_act?act=mail' class='button_green_a'>Писать сообщения: Да</a>";
}
if($user['ev_mail'] == 1){
echo"<a href='/event_act?act=mail' class='button_green_a'>Писать сообщения: Только друзья</a>";
}
if($user['ev_mail'] == 2){
echo"<a href='/event_act?act=mail' class='button_green_a'>Писать сообщения: Нет</a>";
}

echo'<div style="padding-top: 5px;"></div>';
}
echo'</div>';
require_once H.'system/footer.php';
?>