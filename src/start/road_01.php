<?
require_once '../system/system.php';
$head = 'Лес';
$text_location = '<img src="/style/images/start/forest_text.png" class="text_logo"/>';
reg();
require_once H . 'system/head.php';
#-Реф ссылка или нет-#
if (isset($_GET['ref'])) {
    $ref = "&ref=" . $_GET['ref'] . "";
}
echo '<div class="page">';
echo '<img src="/style/images/start/forest.jpg" class="img" alt=""/>';
echo '<div class="line_3"></div>';
echo '<div class="body_list">';
echo '<div class="menulist">';
echo '<img src="/style/images/start/grey.png" class="menulitl_img" width="30" height="30" alt=""/><div class="menulitl_block"><span class="menulitl_name">Грей:</span> <div class="menulitl_param">Идут жестокие сражения Тьмы и Света и исход битвы зависит от последнего героя... Для начала сражений мне необходимы некоторые сведения о тебе.</div></div>';
echo '</div>';
echo '<div style="padding-top: 3px;"></div>';
echo '</div>';
echo '<div class="line_3"></div>';

echo '<div style="padding-top: 5px;"></div>';
echo '<center>';
echo '<form method="post" action="/reg?act=create&' . $ref . '">';
echo '<select name="pol">';
echo '<option value="1">Мужской</option>';
echo '<option value="2">Женский</option>';
echo '</select><br/>';
echo '<input class="button_green_i" name="submit" type="submit" value="Продолжить"/>';
echo '</form>';
echo '</center>';
echo '<div style="padding-top: 5px;"></div>';
echo '<div class="line_4"></div>';
echo '</div>';
require_once H . 'system/footer.php';
?>