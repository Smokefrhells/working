<?php
require_once '../../system/system.php';
$head = 'Сохранение';
echo only_reg();
echo no_save();
echo save_campaign();
require_once H.'system/head.php';
?>
<div class="page">
<center>
<form method="post" action="/save_a?act=save">
<input class='input_form' type='text' name='nick' value='<?=$user['nick']?>' maxlength='15'/><br/>
<input class='input_form' type='text' name='email' placeholder="Email"/><br/>
<input class="input_form" type="text" name="pass" autocomplete="off" placeholder="Пароль" maxlength="30"/><br/>
<input class="input_form" type="text" name="pass_dubl" autocomplete="off" placeholder="Повторите пароль" maxlength="30"/><br/>
<input class="button_green_i" name="submit" type="submit"  value="Сохранить"/>
</form>
<div style="padding-top: 5px;"></div>
</center>
</div>
<div class="line_1"></div>
<div class="body_list">
<div class="info_list">
<img src="/style/images/body/imp.png" alt=""/>За сохранение вы получите <img src="/style/images/many/gold.png" alt=""/>200 золота и <img src="/style/images/body/premium.png" alt=""/>Премиум аккаунт на 1 сутки
</div>
</div>
<?
require_once H.'system/footer.php';
?>
