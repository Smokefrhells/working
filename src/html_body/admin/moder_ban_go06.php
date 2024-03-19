<?php
require_once '../../system/system.php';
$head = 'Молчанка';
echo only_reg();
echo admod();
require_once H.'system/head.php';
echo'<div class="page">';
$ank_id = check($_GET['ank_id']);
if(isset($_GET['redicet'])){
$redicet = check($_GET['redicet']);
}else{
$redicet = $_SERVER['REQUEST_URI'];
}
#-Выборка того кого баним-#
$sel_users = $pdo->prepare("SELECT `id`, `nick`, `prava`, `ban` FROM `users` WHERE `id` = :ank_id");
$sel_users->execute(array(':ank_id' => $ank_id));
$all = $sel_users->fetch(PDO::FETCH_LAZY);

#-Проверка что не наш id, не админ и не забанен-#
if($ank_id != $user['id'] and $all['prava'] != 1 and $all['ban'] == 0 and isset($all['id'])){

echo'<div class="body_list">';
#-Информационный лист-#
echo'<div style="padding: 5px;">';
echo'<img src="/style/images/body/ok.png"/><span class="gray">CAPS - 1-3 час.</span><br/>';
echo'<img src="/style/images/body/ok.png"/><span class="gray">Обсуждение действий смотрящих - 1-6 час.</span><br/>';
echo'<img src="/style/images/body/ok.png"/><span class="gray">Мат - 2-24 час.</span><br/>';
echo'<img src="/style/images/body/ok.png"/><span class="gray">Флуд - 2-24 час.</span><br/>';
echo'<img src="/style/images/body/ok.png"/><span class="gray">Реклама - 24-999999 час.</span><br/>';
echo'<img src="/style/images/body/ok.png"/><span class="gray">Угроза (Оскорбление) - 2-48 час.</span><br/>';
echo'<div style="padding-top: 3px;"></div>';
echo"<img src='/style/images/body/error.png'/><span class='gray'>Молчанка игрока: $all[nick]</span><br/>";
echo'</div>';
echo'<div class="line_1"></div>';
echo'</div>';

#-Форма бана-#
echo'<center>';
echo'<form method="post" action="/ban_act?act=ban&user_id='.$all['id'].'&redicet='.$redicet.'">';
echo'<input class="input_form" type="number" name="hour" placeholder="Кол-во часов"/><br/>';
echo'<input class="input_form" type="text" name="cause" maxlength="255" placeholder="Причина"/><br/>';
echo'<input class="button_green_i" name="submit" type="submit" value="Наложить молчанку"/>';
echo'</form>';
echo'</center>';
echo'<div style="padding-top: 5px;"></div>';

}else{
echo'<div class="body_list">';
echo'<div class="error_list">';
echo'<img src="/style/images/body/error.png" alt=""/>Молчанка невозможена!';
echo'</div>';
echo'</div>';
}
echo'</div>';
require_once H.'system/footer.php';
?>