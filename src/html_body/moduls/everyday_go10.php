<?php
require_once '../../system/system.php';
$head = 'Ежедневная награда';
echo only_reg();
require_once H.'system/head.php';
echo'<div class="body_list">';

#-Награда за первый день-#
if($user['every_num'] >= 1 and $user['every_statys'] >= 1){
echo'<div style="opacity: 0.5;">';
echo'<div class="line_1"></div>';
echo'<div class="body_list">';
echo'<div class="t_max">';
echo'<div class="t_img"><img src="/style/images/day/day_1.png" alt=""/></div>';
echo'<div class="t_name"><b>День</b><br/><img src="/style/images/body/chest.png" alt=""/><span class="gray">Обычный сундук<br/><img src="/style/images/body/ok.png" alt=""/>Получено</span></div>';
echo'</div>';
echo'</div>';
echo'</div>';
}else{
echo'<div class="line_1"></div>';
echo'<div class="body_list">';
echo''.($user['every_num'] == 1 ? "<a href='/everyday_act?act=day1' style='text-decoration:none;'>" : "").'';
echo'<div class="t_max">';
echo'<div class="t_img"><img src="/style/images/day/day_1.png" alt=""/></div>';
echo'<div class="t_name"><b>День</b><br/><img src="/style/images/body/chest.png" alt=""/><span class="gray">Обычный сундук</span><br/><img src="/style/images/body/ok.png" alt=""/><span class="green">Доступно</span></div>';
echo'</div>';
echo'</a>';
echo'</div>';
}

#-Награда за второй день-#
if($user['every_num'] >= 2 and $user['every_statys'] >= 2){
echo'<div style="opacity: 0.5;">';
echo'<div class="line_1"></div>';
echo'<div class="body_list">';
echo'<div class="t_max">';
echo'<div class="t_img"><img src="/style/images/day/day_2.png" alt=""/></div>';
echo'<div class="t_name"><b>День</b><br/><img src="/style/images/many/gold.png" alt=""/><span class="gray">15 золота<br/><img src="/style/images/body/ok.png" alt=""/>Получено</span></div>';
echo'</div>';
echo'</div>';
echo'</div>';
}else{
echo'<div class="line_1"></div>';
echo'<div class="body_list">';
echo''.($user['every_num'] == 2 ? "<a href='/everyday_act?act=day2' style='text-decoration:none;'>" : "").'';
echo'<div class="t_max">';
echo'<div class="t_img"><img src="/style/images/day/day_2.png" alt=""/></div>';
echo'<div class="t_name"><b>День</b><br/><img src="/style/images/many/gold.png" alt=""/><span class="gray">15 золота</span><br/>'.($user['every_num'] == 2 ? "<img src='/style/images/body/ok.png' alt=''/><span class='green'>Доступно</span>" : "<img src='/style/images/body/error.png' alt=''/><span class='red'>Не доступно</span>").'</div>';
echo'</div>';
echo'</a>';
echo'</div>';	
}

#-Награда за третий день-#
if($user['every_num'] >= 3 and $user['every_statys'] >= 3){
echo'<div style="opacity: 0.5;">';
echo'<div class="line_1"></div>';
echo'<div class="body_list">';
echo'<div class="t_max">';
echo'<div class="t_img"><img src="/style/images/day/day_3.png" alt=""/></div>';
echo'<div class="t_name"><b>День</b><br/><img src="/style/images/many/gold.png" alt=""/><span class="gray">30 золота<br/><img src="/style/images/body/ok.png" alt=""/>Получено</span></div>';
echo'</div>';
echo'</div>';
echo'</div>';
}else{
echo'<div class="line_1"></div>';
echo'<div class="body_list">';
echo''.($user['every_num'] == 3 ? "<a href='/everyday_act?act=day3' style='text-decoration:none;'>" : "").'';
echo'<div class="t_max">';
echo'<div class="t_img"><img src="/style/images/day/day_3.png" alt=""/></div>';
echo'<div class="t_name"><b>День</b><br/><img src="/style/images/many/gold.png" alt=""/><span class="gray">30 золота</span><br/>'.($user['every_num'] == 3 ? "<img src='/style/images/body/ok.png' alt=''/><span class='green'>Доступно</span>" : "<img src='/style/images/body/error.png' alt=''/><span class='red'>Не доступно</span>").'</div>';
echo'</div>';
echo'</a>';
echo'</div>';	
}

#-Награда за четвертый день-#
if($user['every_num'] >= 4 and $user['every_statys'] >= 4){
echo'<div style="opacity: 0.5;">';
echo'<div class="line_1"></div>';
echo'<div class="body_list">';
echo'<div class="t_max">';
echo'<div class="t_img"><img src="/style/images/day/day_4.png" alt=""/></div>';
echo'<div class="t_name"><b>День</b><br/><img src="/style/images/user/zashita.png" alt=""/><span class="gray">Зелье Защиты<br/><img src="/style/images/body/ok.png" alt=""/>Получено</span></div>';
echo'</div>';
echo'</div>';
echo'</div>';
}else{
echo'<div class="line_1"></div>';
echo'<div class="body_list">';
echo''.($user['every_num'] == 4 ? "<a href='/everyday_act?act=day4' style='text-decoration:none;'>" : "").'';
echo'<div class="t_max">';
echo'<div class="t_img"><img src="/style/images/day/day_4.png" alt=""/></div>';
echo'<div class="t_name"><b>День</b><br/><img src="/style/images/user/zashita.png" alt=""/><span class="gray">Зелье Защиты</span><br/>'.($user['every_num'] == 4 ? "<img src='/style/images/body/ok.png' alt=''/><span class='green'>Доступно</span>" : "<img src='/style/images/body/error.png' alt=''/><span class='red'>Не доступно</span>").'</div>';
echo'</div>';
echo'</a>';
echo'</div>';
}

#-Награда за пятый день-#
if($user['every_num'] >= 5 and $user['every_statys'] >= 5){
echo'<div style="opacity: 0.5;">';
echo'<div class="line_1"></div>';
echo'<div class="body_list">';
echo'<div class="t_max">';
echo'<div class="t_img"><img src="/style/images/day/day_5.png" alt=""/></div>';
echo'<div class="t_name"><b>День</b><br/><img src="/style/images/user/health.png" alt=""/><span class="gray">Зелье Здоровья<br/><img src="/style/images/body/ok.png" alt=""/>Получено</span></div>';
echo'</div>';
echo'</div>';
echo'</div>';
}else{
echo'<div class="line_1"></div>';
echo'<div class="body_list">';
echo''.($user['every_num'] == 5 ? "<a href='/everyday_act?act=day5' style='text-decoration:none;'>" : "").'';
echo'<div class="t_max">';
echo'<div class="t_img"><img src="/style/images/day/day_5.png" alt=""/></div>';
echo'<div class="t_name"><b>День</b><br/><img src="/style/images/user/health.png" alt=""/><span class="gray">Зелье Здоровья</span><br/>'.($user['every_num'] == 5 ? "<img src='/style/images/body/ok.png' alt=''/><span class='green'>Доступно</span>" : "<img src='/style/images/body/error.png' alt=''/><span class='red'>Не доступно</span>").'</div>';
echo'</div>';
echo'</a>';
echo'</div>';
}

#-Награда за шестой день-#
if($user['every_num'] >= 6 and $user['every_statys'] >= 6){
echo'<div style="opacity: 0.5;">';
echo'<div class="line_1"></div>';
echo'<div class="body_list">';
echo'<div class="t_max">';
echo'<div class="t_img"><img src="/style/images/day/day_6.png" alt=""/></div>';
echo'<div class="t_name"><b>День</b><br/><img src="/style/images/user/sila.png" alt=""/><span class="gray">Зелье Силы<br/><img src="/style/images/body/ok.png" alt=""/>Получено</span></div>';
echo'</div>';
echo'</div>';
echo'</div>';
}else{
echo'<div class="line_1"></div>';
echo'<div class="body_list">';
echo''.($user['every_num'] == 6 ? "<a href='/everyday_act?act=day6' style='text-decoration:none;'>" : "").'';
echo'<div class="t_max">';
echo'<div class="t_img"><img src="/style/images/day/day_6.png" alt=""/></div>';
echo'<div class="t_name"><b>День</b><br/><img src="/style/images/user/sila.png" alt=""/><span class="gray">Зелье Силы</span><br/>'.($user['every_num'] == 6 ? "<img src='/style/images/body/ok.png' alt=''/><span class='green'>Доступно</span>" : "<img src='/style/images/body/error.png' alt=''/><span class='red'>Не доступно</span>").'</div>';
echo'</div>';
echo'</a>';
echo'</div>';
}

#-Награда за седьмой день-#
if($user['every_num'] >= 7 and $user['every_statys'] >= 7){
echo'<div style="opacity: 0.5;">';
echo'<div class="line_1"></div>';
echo'<div class="body_list">';
echo'<div class="t_max">';
echo'<div class="t_img"><img src="/style/images/day/day_7.png" alt=""/></div>';
echo'<div class="t_name"><b>День</b><br/><img src="/style/images/body/chest.png" alt=""/><span class="gray">Золотой сундук<br/><img src="/style/images/body/ok.png" alt=""/>Получено</span></div>';
echo'</div>';
echo'</div>';
echo'</div>';
}else{
echo'<div class="line_1"></div>';
echo'<div class="body_list">';
echo''.($user['every_num'] == 7 ? "<a href='/everyday_act?act=day7' style='text-decoration:none;'>" : "").'';
echo'<div class="t_max">';
echo'<div class="t_img"><img src="/style/images/day/day_7.png" alt=""/></div>';
echo'<div class="t_name"><b>День</b><br/><img src="/style/images/body/chest.png" alt=""/><span class="gray">Золотой сундук</span><br/>'.($user['every_num'] == 7 ? "<img src='/style/images/body/ok.png' alt=''/><span class='green'>Доступно</span>" : "<img src='/style/images/body/error.png' alt=''/><span class='red'>Не доступно</span>").'</div>';
echo'</div>';
echo'</a>';
echo'</div>';
}
echo'</div>';
require_once H.'system/footer.php';
?>