<?php
require_once '../../system/system.php';
$head = 'Скидки';
only_reg();
admin();
require_once H . 'system/head.php';
echo '<div class="page">';
$type = check($_GET['type']);
echo '<center>';
echo '<form method="post" action="/admin_stock_act?act=setup">';
echo "<input class='input_form' type='text' name='type' value='$type' hidden/>";
echo '<input class="input_form" type="number" name="prosent" placeholder="Проценты"/><br/>';
echo '<input class="input_form" type="number" name="time_hour" placeholder="Часы"/><br/>';
echo '<input class="input_form" type="number" name="time_minut" placeholder="Минуты"/><br/>';
echo '<input class="button_green_i" name="submit" type="submit" value="Установить"/>';
echo '</form>';
echo '<div style="padding-top: 5px;"></div>';
echo '</div>';
require_once H . 'system/footer.php';
?>