<?php
require_once '../../system/system.php';
$head = 'Восстановление пароля';
echo reg();
$text_location = '<img src="/style/images/body/text.png" class="text_logo"/>';
require_once H.'system/head.php';
echo'<img src="/style/images/body/logo.jpg" class="img"/>';
?>
<div class="page">
<center>
<form method="post" action="/restorn_act?act=rest">
<input class='input_form' type='text' name='nick' placeholder="Ник героя" maxlength='25'/><br/>
<input class='input_form' type='text' name='email' placeholder="Email"/><br/>
<div style="padding-top: 3px;"></div>
<input class="button_green_i" name="submit" type="submit"  value="Восстановить"/>
</form>
<div style="padding-top: 5px;"></div>
</center>
</div>
<?
require_once H.'system/footer.php';
?>
