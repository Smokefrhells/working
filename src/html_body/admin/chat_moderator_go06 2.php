<?php
require_once '../../system/system.php';
$head = 'Беседа';
echo only_reg();
echo admod();
require_once H.'system/head.php';

#-Ответ, если есть id-#
if(isset($_GET['id'])){
$id = check($_GET['id']);
if($id != $user['id']){
$sel_users = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :user_id");
$sel_users->execute(array(':user_id' => $id));
#-Если есть такой игрок-#
if($sel_users-> rowCount() != 0){
$all = $sel_users->fetch(PDO::FETCH_LAZY);
$otv_nick = "$all[nick], ";
$ank_id = $all['id'];
}
}	
}

#-Делаем выборку самого последнего сообщения и записываем его в бд-#
$sel_end = $pdo->prepare("SELECT `id` FROM `chat_moderator` ORDER BY `time` DESC LIMIT 1");
$sel_end->execute(array(':chat_id' => $user['chat_id']));
if($sel_end-> rowCount() != 0){ //Если есть вообще сообщения
$end = $sel_end->fetch(PDO::FETCH_LAZY);
#-Неравен тому ID что записан в бд-#
if($end['id'] != $user['chat_moder']){
#-Запысываем игроку последнее сообщение которое он прочитал-#
$upd_users = $pdo->prepare("UPDATE `users` SET `chat_moder` = :chat_moder WHERE `id` = :user_id LIMIT 1");
$upd_users->execute(array(':chat_moder' => $end['id'], ':user_id' => $user['id']));
}
}
?>	
<script type="text/javascript">
	/*<![CDATA[*/
	function sml(id, html) {
		var e = document.getElementById(id);
		if (e != null) {
			e.value += ' ' + html + ' ';
			e.focus();
		}
	}
	function smiles() {
		var e = document.getElementById('smiles');
		if (e != null) {
			if (e.style.display == 'block') e.style.display = 'none';
			else e.style.display = 'block';
		}
	}
	/*]]>*/
</script>	
<form action="/chat/?to=<?=$to?>" class="mb10" method="POST">
	<div class="mt5 mlr10 lwhite">

			<div class="mr10">
				<input id="sml" class="lbfld h25 w100" type="text" value="<?=($to ? $_to['login'].', ':'')?>" size="20" maxlength="255" name="text">
			</div>



			<div class="mt5 mr5 fr"><a onclick="smiles();return false;" class="nd" href="">
	<img class="icon" height="28" src="/images/ico/smailes/big_smile.png" alt=":)">
</a></div>

			<div class="mt5 mr10 fl"><input type="submit" name="send_message" class="ibtn w90px" value="Отправить"></div>
			<div class="mt10 small"><a class="grey1 ml10" href="?">Обновить</a></div>

	
</div>
	<div class="mb10"></div>
	<div class="hr_arr mlr10 mb5"><div class="alf"><div class="art"><div class="acn"></div></div></div></div>
	<div id="smiles" style="display: none;" class="cntr">
	<div class="ml10 mt5 mb5 mr10 sh">
					<a class="smla" href="javascript:sml('sml', ':)')">
				<img class="icon" src="/images/ico/smailes/1.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', ':(')">
				<img class="icon" src="/images/ico/smailes/2.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', ';)')">
				<img class="icon" src="/images/ico/smailes/3.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', ':D')">
				<img class="icon" src="/images/ico/smailes/4.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', ':-)')">
				<img class="icon" src="/images/ico/smailes/9.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', ':p')">
				<img class="icon" src="/images/ico/smailes/10.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', ':-o')">
				<img class="icon" src="/images/ico/smailes/11.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', ':-*')">
				<img class="icon" src="/images/ico/smailes/12.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', ':-0')">
				<img class="icon" src="/images/ico/smailes/13.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', ':mad:')">
				<img class="icon" src="/images/ico/smailes/14.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', ':good:')">
				<img class="icon" src="/images/ico/smailes/15.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', '8)')">
				<img class="icon" src="/images/ico/smailes/16.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', ':-~')">
				<img class="icon" src="/images/ico/smailes/17.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', ':shy:')">
				<img class="icon" src="/images/ico/smailes/18.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', ':evil:')">
				<img class="icon" src="/images/ico/smailes/19.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', ':cry:')">
				<img class="icon" src="/images/ico/smailes/20.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', 'xD')">
				<img class="icon" src="/images/ico/smailes/21.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', ':hmm:')">
				<img class="icon" src="/images/ico/smailes/22.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', ':huh:')">
				<img class="icon" src="/images/ico/smailes/23.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', ':rofl:')">
				<img class="icon" src="/images/ico/smailes/24.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', ':angel:')">
				<img class="icon" src="/images/ico/smailes/25.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', ':glasses:')">
				<img class="icon" src="/images/ico/smailes/26.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', ':ok:')">
				<img class="icon" src="/images/ico/smailes/27.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', ':z:')">
				<img class="icon" src="/images/ico/smailes/28.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', ':emm:')">
				<img class="icon" src="/images/ico/smailes/29.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', ':idea:')">
				<img class="icon" src="/images/ico/smailes/30.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', ':l:')">
				<img class="icon" src="/images/ico/smailes/8.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', ':v:')">
				<img class="icon" src="/images/ico/smailes/76.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', ':|')">
				<img class="icon" src="/images/ico/smailes/6.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', ':]')">
				<img class="icon" src="/images/ico/smailes/5.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', ':?:')">
				<img class="icon" src="/images/ico/smailes/7.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', ':rose:')">
				<img class="icon" src="/images/ico/smailes/53.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', ':-bd')">
				<img class="icon" src="/images/ico/smailes/113.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', ':-q')">
				<img class="icon" src="/images/ico/smailes/112.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', ':m]')">
				<img class="icon" src="/images/ico/smailes/111.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', ':!!')">
				<img class="icon" src="/images/ico/smailes/110.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', 'X_X')">
				<img class="icon" src="/images/ico/smailes/109.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', '@-)')">
				<img class="icon" src="/images/ico/smailes/43.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', '=d]')">
				<img class="icon" src="/images/ico/smailes/41.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', ':-?')">
				<img class="icon" src="/images/ico/smailes/39.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', '[:[')">
				<img class="icon" src="/images/ico/smailes/37.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', '[:-P')">
				<img class="icon" src="/images/ico/smailes/36.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', '[-(')">
				<img class="icon" src="/images/ico/smailes/33.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', '8-]')">
				<img class="icon" src="/images/ico/smailes/105.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', ':-h')">
				<img class="icon" src="/images/ico/smailes/103.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', ':-]')">
				<img class="icon" src="/images/ico/smailes/100.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', ':-c')">
				<img class="icon" src="/images/ico/smailes/101.gif" alt=":)"/>
			</a>
					<a class="smla" href="javascript:sml('sml', ':-$')">
				<img class="icon" src="/images/ico/smailes/32.gif" alt=":)"/>
			</a>
			</div>
	<div class="hr_arr mlr10 mb5"><div class="alf"><div class="art"><div class="acn"></div></div></div></div>
</div>	</form>




<?











echo'
<div class="page">';
#-Форма отправки сообщений-#
echo'<div style="padding-top: 5px;"></div>';
echo'<center>';
echo'<form method="post" action="/chat_moderator_act?act=send">';
echo"<input class='input_form' type='text' name='msg' id='mail' value='$otv_nick'/><br/>";
echo"<input class='input_form' type='hidden' name='ank_id' value='$ank_id'/>";
echo'</center>';
echo'<input class="button_i_mini" name="submit" type="submit"  value="Отправить"/>';
echo'</form>';
echo'<a href="?"><img src="/style/images/chat/update.png" alt="Обновить" class="update_button"/></a>';
echo'<img src="/style/images/body/smiles.png" alt="Смайл" class="smiles_button"/>';
echo'<div style="padding-top: 5px;"></div>';
echo'</center>';

#-Смайлы-#
echo'<div id="panel_smiles">';
echo'<div class="line_1_m"></div>';
echo smiles_kolobok();

echo'</div>';
echo'<div class="body_list">';

/*Вывод сообщений из БД*/
#-Считаем количество записей-#
$sel_c_chat = $pdo->query("SELECT COUNT(*) FROM `chat_moderator`");
$amount = $sel_c_chat->fetch(PDO::FETCH_LAZY);
#-Действия постраничной навигации-#
$num = 10;  
$page = $_GET['page'];  
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;$page = intval($page);if(empty($page) or $page < 0) $page = 1;if($page > $total) $page = $total;$start = $page * $num - $num;  
$sel_chat = $pdo->query("SELECT * FROM `chat_moderator` ORDER BY `time` DESC LIMIT $start, $num");
#-Если есть записи-#
if($sel_chat-> rowCount() != 0){
while($chat = $sel_chat->fetch(PDO::FETCH_LAZY))  
{
#-Выборка игрока-#
$sel_users = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :user_id");
$sel_users->execute(array(':user_id' => $chat['user_id']));
#-Если есть такой игрок-#
if($sel_users-> rowCount() != 0){
$all = $sel_users->fetch(PDO::FETCH_LAZY);
$nick = $all['nick'];
}else{
$nick = 'Неизвестно';
}
echo'<div class="line_1"></div>';
echo"<a href='/hero/$all[id]'><img src='".avatar_img_min($all['avatar'], $all['pol'])."' class='menulitl_img' width='30' height='30' alt=''/><div class='menulitl_block'><span class='user_nick'>$nick</span></a> ";

if($all['id'] != $user['id']){
echo"<a href='/chat_moderator?id=$all[id]'><span id='otv' class='$all[nick]/$all[id]'>[Отв.]</span></a></a> ";
}
#-Если модератор или админ-#
if($user['prava'] == 1 OR $user['prava'] == 2){
echo"<a href='/chat_moderator_act?act=delete&user_id=$all[id]&msg_id=$chat[id]'><span style='color: #666666; font-size: 13px;'>[X]</span></a>";
}
echo"<div style='float: right; font-size: 12px; color: #666666;'>".vremja($chat['time'])."</div><br/>";
if($all['prava'] == 2){$color = 'blue';}
if($all['prava'] == 1){$color = 'red';}
if($all['prava'] == 0 and $all['premium'] == 0){$color = 'gray';}
if($chat['ank_id'] == $user['id']){$color = 'green';}
echo"<span class='$color'>".msg($chat['msg'])."</span><br/>";
echo'</div>';
}
}

#-Отображение постраничной навигации-#
if($posts > $num){
$action = '';
echo'<div class="body_list">';
echo'<div class="line_1"></div>';
$z = pages($posts,$total,$action);
echo'</div>';
}
#-Нижнеее меню-#
echo'<div class="body_list">';
echo'<div class="menulist">';
echo'<div class="line_1"></div>';
echo'<li><a href="/smiles"><img src="/style/images/chat/smiles_b.png"/> Смайлы</a></li>';
echo'<div class="line_1"></div>';
echo'<li><a href="/moderator"><img src="/style/images/chat/moder.png"/> Модераторы</a></li>';
echo'</div>';
echo'</div>';
echo'</div>';
require_once H.'system/footer.php';
?>