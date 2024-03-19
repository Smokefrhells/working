<?php
require_once '../../system/system.php';
echo only_reg();
echo save();
session_start();
#-Отправка сообщений в чат-#
switch($act){
case 'send':
if($user['ban'] == 0 and $user['level'] >= 15){
if(isset($_POST['msg'])){
$msg = check($_POST['msg']); //Сообщение
$ank_id = check($_POST['ank_id']); //ID кому отвечаем
$chat = check($_GET['chat']);
if($chat == 1) $adress = '/chat?type_chat=obs';
if($chat == 2) $adress = '/chat?type_chat=torg';
#-Проверяем данные-#
#-Сообщение-#
if(preg_match('/[\^\<\>\&\`\$]/',$_POST['msg'])) $error = 'Некорректное сообщение!';
if(mb_strlen($msg) < 1) $error = 'Пустое сообщение!';
if(mb_strlen($msg) > 2000) $error = 'Слишком длинное сообщение!';
#-Время обращения-#
if (isset($_SESSION["telecod_ip"])){
$t = ((int)(time()-$_SESSION["telecod_ip"]));
if ($t < 3) $error = 'Не так часто!';}
$_SESSION["telecod_ip"]=time();
#-Если нет ошибок-#
if(!isset($error)){
    
            $arrReplace = array(
                '.ga',
                '.tk',
                'Пиздaбол',
                'пиздa',
                'pizda',
                'рizda',
                'пи зда',
                'пизд',
                'пиЗд',
                'пИзд',
                'говно',
                'звездабол',
                '.ga',
                'mymars',
                ' лох ',
                '.su',
                'параша',
                'пидор',
                'нахуй',
                'Зaeбалa',
                'xyйня',
                'Хyй',
                'пиздец',
                'гандон',
                'dobr ',
                ' .com',
                'zlo .',
                'Dobr ',
                'o zlo',
                ' .com ',
                'dobrozlo',
                'dobr o',
                'доброзло',
                '.де ',
                '.Де',
                'Н ОВАЯ ОНЛ АЙН',
                'до бр',
                '.д е',
                'ссыл ку н',
                'wartan',
                'НАХ**',
                '.t k',
               'registr.php',
               'inv=',
               'big m',
               'g mar',
               's.ru',
               'https',
               'ht tp',
               'http://nvwwc4ttfzww6ytj.cmle.ru/registration?id=8447');


            $size = count($arrReplace);
            while ($size --) {
                if (substr_count($msg, $arrReplace[$size])) {

#-Баним игрока-#
$upd_users = $pdo->prepare("UPDATE `users` SET `ban` = :ban, `violation` = :violation, `cause` = :cause WHERE `id` = :id");
$upd_users->execute(array(':ban' => time()+(60*30), ':violation' => $user['violation']+1, ':cause' => 'антимат', ':id' => $user['id']));
#-Записываем уведомление в обычный чат-#
$ins_chat_o = $pdo->prepare("INSERT INTO `chat` SET  `type` = :type, `msg` = :msg, `user_id` = :user_id, `time` = :time");
$ins_chat_o->execute(array(':type' => 1, ':msg' => "На игрока [url=/hero/$user[id]]$user[nick][/url] наложена молчанка.<br/>Причина: сработал антимат", ':user_id' => 2, ':time' => time()));
                    $msg = 'Cпам-Мат';
                    $_SESSION['err'] = 'Вы Забанены!';
                    break;
                }
            }    
    
    
$upd_chat = $pdo->prepare("INSERT INTO `chat` SET `msg` = :msg, `user_id` = :user_id, `ank_id` = :ank_id, `type` = :type, `time` = :time");
$upd_chat->execute(array(':msg' => $msg, ':user_id' => $user['id'], ':ank_id' => $ank_id, ':type' => $chat, ':time' => time())); 
header("Location: $adress");
}else{
header("Location: $adress");
$_SESSION['err'] = $error;
exit();
}
}else{
header("Location: $adress");
$_SESSION['err'] = 'Данные не переданы!';
exit();
}
}else{
header("Location: $adress");
$_SESSION['err'] = 'Вы не можете оставлять сообщения!';
exit();
}
}
?>