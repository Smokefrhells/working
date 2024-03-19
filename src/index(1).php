<?php

require_once 'system/system.php';
header('Content-Type:text/html; charset=utf-8');
if ($user['start'] == '0') {
    $head = 'Обучение';
} else {
    $head = 'Главная';
}
//$text_location = '<img src="/style/images/body/text.png" class="text_logo"/>';
require_once H . 'system/head.php';
require_once H . 'copy/copy_func.php';
global $user;
//echo '<div class="page">';
#-Если не авторизованы-#
if (!isset($user['id'])) {
#-Реф ссылка или нет-#
    if (isset($_GET['ref'])) {
        $ref = "?ref=$_GET[ref]";
    }


    /*  ?>
         </head><body>
         <script type="text/javascript" src="/js/diolog.js"></script>
         <div class="line"></div>
         <div class="title center">«Битва Престолов»</div>
         <div class="line"></div>
         <center><body data-feedly-mini="yes"><div class="load"><div class="splash scr1"></div><div class="splash scr2"></div><div class="splash scr3"></div><div class="splash scr4"></div><div class="splash scr5"></div><div class="splash scr6"></div><a href="/start/"><div class="splash scr7"></div></a><a href="?auth"><div class="loginButton">Продолжить</div></a><div class="controls"></div></div>	<div class="line"></div>
                 <div class="fire"></div>
             <div class="mt5"></div>
             <div class="center">
             <div class="mt5"></div>
             <a href="http://statok.net/go/18627"><img src="//statok.net/imageOther/18627" alt="Statok.net" /></a>
             </div>
         <script>
                 var secS = 'с.';
                 var secM = 'с.';
                 var minS = 'м.';
                 var minM = 'м.';
                 var hourS = 'ч.';
                 var hourM = 'ч.';
                 var dayS = 'д.';
                 var dayM = 'д.';
                 var detailOut = false;
                 var readyLink = '0'+(detailOut?secS:' ' + secM);
         </script>
         <script src="/js/t.js" type="text/javascript"></script>
         <br><div style='font-size: xx-small;text-align:center;'><a target='_blank' style='font-size:xx-small;' title='LLC PlayRoom ИНН 7724455887, КПП 772401001, ОГРН 1187746843459'>© LLC PlayRoom</a></div></body>

         </html>

         <?







       echo'<img src="/style/images/body/logo.jpg" class="img"/>';
 echo'<center>';
 echo'<div class="line_3"></div>';
 echo'<div class="body_list">';
 echo'<div style="padding-top: 3px;"></div>';
 #-Кол-во игроков-#
 $sel_users_a = $pdo->query("SELECT COUNT(*) FROM `users`");
 $users_all = $sel_users_a->fetch(PDO::FETCH_LAZY);
 #-Кол-во игроков света-#
 $sel_users_s = $pdo->query("SELECT COUNT(*) FROM `users` WHERE `storona` = 1");
 $users_shine = $sel_users_s->fetch(PDO::FETCH_LAZY);
 $u_s = round(($users_shine[0]/$users_all[0])*100, 0);
 #-Кол-во игроков тьмы-#
 $sel_users_d = $pdo->query("SELECT COUNT(*) FROM `users` WHERE `storona` = 2");
 $users_dark = $sel_users_d->fetch(PDO::FETCH_LAZY);
 $u_d = round(($users_dark[0]/$users_all[0])*100, 0);
 echo'<span class="whit">Баланс сил:<br/> <img src="/style/images/user/shine.png" alt=""/>'.$u_s.'% vs <img src="/style/images/user/dark.png" alt=""/>'.$u_d.'%</span>';
 echo'<div style="padding-top: 4px;"></div>';
 echo'</div>';
 echo'<div class="line_3"></div>';
 echo'<div style="padding-top: 5px;"></div>';
 echo'<a href="/road'.$ref.'" class="button_red_a">Сражаться</a>';
 echo'<div style="padding-top: 5px;"></div>';
 echo'<div class="line_1_m"></div>';
 echo'<div class="body_list">';
 echo'<form method="post" action="/auth?act=login">';
 echo'<input class="input_form" type="text" name="nick" placeholder="Имя героя" maxlength="25"/><br/>';
 echo'<input class="input_form" type="password" name="password" autocomplete="off" placeholder="Пароль входа" maxlength="25"/><br/>';
 echo'<div style="padding-top: 5px;"></div>';
 echo'<input  class="button_green_i" name="submit" type="submit"  value=" Вход "/>';
 echo'<div style="padding-top: 3px;"></div>';

 echo'<a href="/restorn" class="button_red_a">Забыли пароль?</a>';
 echo'<div style="padding-top: 5px;"></div>';
 echo'</form>';
 echo'</div>';
 echo'</center>';
 echo'<div class="line_1_m"></div>';
 echo'<div style="padding: 5px;">';
 echo'<img src="/style/images/body/traing.png" alt="" style="margin-right:2px;"/><span class="yellow"><b>Битва Королевств</b></span><span class="whit"> - увлекательная мобильная игра которая не оставит равнодушным того кто любит настоящие mmorpg сражения.</span>';
 echo'</div>';
 echo'<div class="line_4"></div>';
 #-Если авторизованы-#










         */

/////////////////////////////////////////////////////////////////////
  
    //Авторизация
    if (isset($_GET['auth'])) {
        echo '<div class="case center">';
        echo '<img src="/images/logo-big.png" width="100%">';
        if (isset($_REQUEST['success'])) {
            $error = array();
            $name = fl($_POST['nick']);
            $pass = fl(trim($_POST['password']));
            $sql = cnt("SELECT `nick`, `password` FROM `users` WHERE `nick` = ? and `password` = ?", array($name, md5($pass)));
            if (empty($name))
                $error[] = 'введите логин';
            if (empty($pass))
                $error[] = 'введите пароль';

            if ($sql == 0 and !empty($name) and !empty($pass))
                $error[] = 'пользователь не обнаружен';
            if (empty($error)) {
                setcookie('nick', $name, time() + 86400 * 365, '/');
                setcookie('password', md5($pass), time() + 86400 * 365, '/');

                $_SESSION['index'] = 'Добро пожаловать в игру.</br>
			Приятной игры!<img src="/images/smiles/1.png" width="16px" alt="*">';
                header('location: /');
                exit();
            } else {
                echo '<div class="warning">';
                foreach ($error as $err) {
                    echo 'Ошибка: ' . $err . '<br/>';
                }
                echo '</div>';
            }
        }
        echo '<form method="post" action="/auth?act=login">			
	<input type="text" name="nick" value="" placeholder="Введите логин..." class="center"> <br/>
	<input type="password" name="password" value="" placeholder="Введите пароль..." class="center"> <br/>
	<input type="submit" class="grey-btn" name="success" value="Авторизация" /> <a href="/restorn">Забыли пароль?</a>
	</form>';
        echo '<hr>';
        $client_id = '6818711';
     //   $redirect_uri = 'http://warsking.mobi.ru/vk_resulturl.php';
//	echo '<a href="https://oauth.vk.com/authorize?client_id='.$client_id.'&display=popup&scope=friends&redirect_uri='.$redirect_uri.'" class="btn-default">Войти через VK</a>';
        echo '</div>';
        echo '<div class="line"></div>';
        echo '<a class="link" href="/"><img src="/images/icon/arrow.png" width="16px"> Главная страница</a>';
        echo '<div class="line"></div>';
        echo '  <div class="fire"></div>
            <div class="mt5"></div>
            <div class="center">
            <div class="mt5"></div>';

        echo " 
  
  <br><div style='font-size: xx-small;text-align:center;'><a target='_blank' style='font-size:xx-small;' title='LLC PlayRoom ИНН 7724455887, КПП 772401001, ОГРН 1187746843459'>© LLC PlayRoom</a></div></body>
      ";
      //  include_once 'inc/footer.php'; /* Загружаем ноги */
        die();
    }

    echo '<center><body data-feedly-mini="yes">';
    echo '<div class="load">';
    echo '<div class="splash scr1"></div>';
    echo '<div class="splash scr2"></div>';
    echo '<div class="splash scr3"></div>';
    echo '<div class="splash scr4"></div>';
    echo '<div class="splash scr5"></div>';
  //  if ($_SERVER['HTTPS_HOST'] != 'warsking.mobi'){
       echo '<a href="/road' . $ref . '"><div class="splash scr7"></div></a>';
        echo '<a href="?auth"><div class="loginButton">Продолжить</div></a>';
  //  } else {
   //     $client_id = '6818711';
    //    $client_secret = 'PvkxvixZTZVq6sufakWI';
     //   $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/vk_resulturl.php';
      //  echo '<a href="https://oauth.vk.com/authorize?client_id=' . $client_id . '&display=popup&scope=friends&redirect_uri=' . $redirect_uri . '"><div class="splash scr7"></div></a>';
  //  }
    echo '<div class="controls"></div></div>';

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////






#-Если авторизованы-#
} else {
    require_once H . 'avenax/Maneken.php';
    $itemTypeSale = Maneken::itemMore(Maneken::$idItemReg);

    if ($user['start'] == 0) { //Если еще не проходили обучение
        echo '<img src="/style/images/start/battle.jpg" class="img" alt=""/>';
        echo '<div class="body_list">';
        echo '<div class="line_3"></div>';
        echo '<div class="menulist">';
        echo '<img src="/style/images/start/grey.png" class="menulitl_img" width="30" height="30" alt=""/><div class="menulitl_block"><span class="menulitl_name">Грей:</span> <div class="menulitl_param">Теперь ты можешь сражаться за ту сторону которую выбрал. Но какое сражение без оружия?</div></div>';
        echo '</div>';
        echo '<div style="padding-top: 3px;"></div>';
        echo '</div>';
        echo '<div class="line_3"></div>';
        echo '<div class="weapon">';
        echo '<div class="img_weapon"><img src="/images/items/' . Maneken::$idItemReg . '.png" class="weapon_1" width="48px"  alt=""></div>';
        echo '<div class="weapon_setting">';
        echo '<span style="color: #bfbfbf;"><img src="' . $itemTypeSale->type_img . '" alt=""/> <b>' . $itemTypeSale->nameItem . '</b></span><br/>';
        echo '<div class="weapon_param"><img src="/style/images/user/sila.png" alt=""/>' . $itemTypeSale->str . ' <img src="/style/images/user/zashita.png" alt=""/>' . $itemTypeSale->def . ' <img src="/style/images/user/health.png" alt=""/>' . $itemTypeSale->hp . '<br/>';
        echo '</div></div>';
        echo '</div>';
        echo '<div style="padding-top: 10px;"></div>';
        echo '<a href="/campaign?act=battle" class="button_green_a">Взять оружие и в бой <img src="/style/images/body/left_mini.gif" alt=""/></a>';
        echo '<div style="padding-top: 3px;"></div>';

    } else {

#-Ежедневная награда-#
        if ($user['every_num'] != $user['every_statys']) {
            echo '<div class="page">';
            echo "<div style='padding: 5px;'><center>";
            echo '<a href="/everyday" class="button_green_a"><img src="/style/images/body/gift.png" alt=""/>Получить награду</a>';
            echo '</center></div>';
            echo '</div>';
        }


//echo'<div style="background-repeat: no-repeat;background-image: url(/images/bg_grey.jpg); background-size:cover; position:relative; z-index:2;"><div class="case center"><table width="100%"><td valign="top" class="center" width="33%"><a href="/kvest/"><img src="/images/monsters/avatars/6.png" width="80px"></a><br><a href="/kvest/"><span class="bold">Квест</span></a><br>Сражений: 2</td><td valign="top" class="center" width="33%"><a href="/arena/"><img src="/images/icon/arena_2.png" width="80px"></a><br><a href="/arena/"><span class="bold">Арена</span></a><br>Сражений: 16</td><td valign="top" class="center" width="33%"><a href="/podzem/"><img src="/images/podzem/logo/logo-av_1.png" width="80px"></a><br><a href="/podzem/"><span class="bold">Подземелье</span></a><br></td></table></div></div><div class="line"></div>

//'; 
        /*echo'

        ';
        */
        /* ?>
         <html><head>
             <meta http-equiv="content-type" content="application/xhtml; charset=utf-8"/>
             <meta http-equiv="Content-Style-Type" content="text/css" />
             <meta name="viewport" content="width=device-width, initial-scale=1.0">
             <link rel="shortcut icon" href="/favicon.ico"></link>
             <link rel="stylesheet" href="/style/bootstrap.min.css">
                 <link rel="stylesheet" href="/style/default.css?ver=2.3">
                 <link rel="stylesheet" href="/style/diolog.css?ver=4.3">
             <script type="text/javascript" src="/style/jquery.js"></script>
                 <title> Битва Престолов </title>


         </head><body>
         <script type="text/javascript" src="/style/diolog.js"></script>
         <div class="line"></div>
         <div class="title center">«Персонаж»</div>
         <div class="line"></div>
         <div class="block_lvl"><table width="100%"><tr><td class="center" width="15%"><img src="/images/icon/level.png" width="16px"> 10</td><td width="70%"><div class="block_lvl2"><div class="proc_lvl" style="width: 82.1%;"></div></div></td><td class="center" width="15%">82.1%</td></tr></table></div><div class="line"></div><div class="block center"> Привяжите ваш аккаунт к <a href=/settings?act=email>E-mail</a> адресу</div><div id="wrapper" style="background: #000;">
         <div class="main-hero_p">
         <div class="hero-name_p"><p>
         <img src="/images/icon/male.png" width="16px"> misterX  (52 ур)</p></div><div class="hero-wrapper_p"></div><table align="center" cellspacing="5" cellpadding="5" width="90%"><tbody><tr><td class="center"> <a href="/item/92358/"><div class="bor-6 bor-no-radius" style="background-image:url('/images/items/161.png');background-repeat:no-repeat;background-size:cover;height:55px;width:55px;"> <div style="float:left;margin-top:-5px;"><font color="#ffd166">+25</font></div><div style="float:right;margin-top:-5px;"><img src="/images/runes/rune-3.png" width="20px"></div></div></a><br> <a href="/item/72439/"><div class="bor-6 bor-no-radius" style="background-image:url('/images/items/162.png');background-repeat:no-repeat;background-size:cover;height:55px;width:55px;"> <div style="float:left;margin-top:-5px;"><font color="#ffd166">+25</font></div><div style="float:right;margin-top:-5px;"><img src="/images/runes/rune-4.png" width="20px"></div></div></a><br> <a href="/item/92360/"><div class="bor-6 bor-no-radius" style="background-image:url('/images/items/163.png');background-repeat:no-repeat;background-size:cover;height:55px;width:55px;"> <div style="float:left;margin-top:-5px;"><font color="#ffd166">+25</font></div><div style="float:right;margin-top:-5px;"><img src="/images/runes/rune-3.png" width="20px"></div></div></a><br> <a href="/item/92359/"><div class="bor-6 bor-no-radius" style="background-image:url('/images/items/164.png');background-repeat:no-repeat;background-size:cover;height:55px;width:55px;"> <div style="float:left;margin-top:-5px;"><font color="#ffd166">+25</font></div><div style="float:right;margin-top:-5px;"><img src="/images/runes/rune-3.png" width="20px"></div></div></a><br></td><div class="middle-maneken_p"><img src="/ArenaManeken.php?sex=male&i1=161&i2=162&i3=163&i4=164&i5=165&i6=166&i7=167&i8=152" style="max-width:250px;height:auto;" alt="maneken"/></div><div class="b-gradient-line_p"></div><td class="right"> <a href="/item/97035/"><div class="bor-6 bor-no-radius" style="background-image:url('/images/items/165.png');background-repeat:no-repeat;background-size:cover;height:55px;width:55px;"> <div style="float:left;margin-top:-5px;"><font color="#ffd166">+25</font></div><div style="float:right;margin-top:-5px;"><img src="/images/runes/rune-3.png" width="20px"></div></div></a><br> <a href="/item/97265/"><div class="bor-6 bor-no-radius" style="background-image:url('/images/items/166.png');background-repeat:no-repeat;background-size:cover;height:55px;width:55px;"> <div style="float:left;margin-top:-5px;"><font color="#ffd166">+25</font></div><div style="float:right;margin-top:-5px;"><img src="/images/runes/rune-3.png" width="20px"></div></div></a><br> <a href="/item/92361/"><div class="bor-6 bor-no-radius" style="background-image:url('/images/items/167.png');background-repeat:no-repeat;background-size:cover;height:55px;width:55px;"> <div style="float:left;margin-top:-5px;"><font color="#ffd166">+25</font></div><div style="float:right;margin-top:-5px;"><img src="/images/runes/rune-3.png" width="20px"></div></div></a><br> <a href="/item/54900/"><div class="bor-5 bor-no-radius" style="background-image:url('/images/items/152.png');background-repeat:no-repeat;background-size:cover;height:55px;width:55px;"> <div style="float:left;margin-top:-5px;"><font color="#ffd166">+25</font></div><div style="float:right;margin-top:-5px;"><img src="/images/runes/rune-3.png" width="20px"></div></div></a><br></td></tr></tbody></table></div><div class="wrapper center">
         <a href="/trofeis/777/"><img src="/images/trofeis/1.png" width="55px"><img src="/images/trofeis/2.png" width="55px"><img src="/images/trofeis/3.png" width="55px"><img src="/images/trofeis/4.png" width="55px"></div></a><div style="background-image:url(/images/invasion/bg2.jpg);background-repeat:no-repeat;background-size:cover;padding:5px;"><table width="100%"><td valign="top" style="text-align:left;"><img src="/images/icon/favorit.png" width="16px"> <font color="#15e3ef">Высшая лига:</font> 13'312<div class="mt5"></div><img src="/images/icon/power.png" width="16px"> Сила: 6'030<div class="mt5"></div><img src="/images/icon/shield.png" width="16px"> Защита: 6'030<div class="mt5"></div><img src="/images/icon/heart.png" width="16px"> Жизнь: 6'030<div class="mt5"></div><img src="/images/icon/krit.png" width="16px"> Ярость: 9%<div class="mt5"></div></td><td style="text-align:right;"><img src="/images/icon/info.png" width="65px"></td></table><hr><table width="100%"><td valign="top" style="text-align:left;"><img src="/images/icon/clan.png" width="16px"> <a href="/clan/12/">Душа Демона</a><div class="mt5"></div><img src="/images/icon/star.png" width="16px"> Звание: <font color="green">Лидер клана</font><div class="mt5"></div><img src="/images/icon/time.png" width="16px"> В клане: 218 д. 23 ч. <div class="mt5"></div><img src="/images/icon/exp.png" width="16px"> Верность клану: 102%<div class="mt5"></div></td><td style="text-align:right;"><img src="/images/clan/gerbs/6.png" width="65px"></td></table></div><div class="line"></div><a href="/contacts/diolog/777/" class="link"><img src="/images/icon/mail.png" width="16px"> Отправить сообщение</a>	<div class="line"></div>
                 <div class="top"></div>
                 <a class="link" href="/user/"><img src="/images/icon/male.png" width="15px"> Мой профиль</a>
                 <a class="link" href="/clan/"><img src="/images/icon/clan.png" width="15px"> Мой клан</a>
                 <a class="link" href="/"><img src="/images/icon/arrow.png" width="15px"> На главную</a>
                 <div class="bottom"></div>
                 <div class="line"></div>
                         <div class="center block" style="margin: 0px 0;">

                 <a href="/forum/">Форум</a> | <a href="/chat/">Чат</a> | <a href="/online/">Онлайн: 3</a>
                 <div class="mt5"></div>
                 <a href="/ref/">Реферальная система</a>
                   <div class="mt5"></div>
                      <a href="/support/">Техническая поддержка</a>
                   <div class="mt5"></div>
                           <a href="/exit/" onclick="return confirm('Вы уверены что хотите сбросить сессию?')">Выйти из игры</a>
                 </div>
                 <div class="line"></div>
                 <div class="fire"></div>
             <div class="mt5"></div>
             <div class="center">
             <div class="mt5"></div>
             </html>
         <?

         ?>
         </head><body>
         <script type="text/javascript" src="/js/diolog.js"></script>
         <div class="line"></div>
         <div class="title center">«Битва Престолов»</div>
         <div class="line"></div>
         <center><body data-feedly-mini="yes"><div class="load"><div class="splash scr1"></div><div class="splash scr2"></div><div class="splash scr3"></div><div class="splash scr4"></div><div class="splash scr5"></div><div class="splash scr6"></div><a href="/start/"><div class="splash scr7"></div></a><a href="?auth"><div class="loginButton">Продолжить</div></a><div class="controls"></div></div>	<div class="line"></div>
                 <div class="fire"></div>
             <div class="mt5"></div>
             <div class="center">
             <div class="mt5"></div>
             <a href="http://statok.net/go/18627"><img src="//statok.net/imageOther/18627" alt="Statok.net" /></a>
             </div>
         <script>
                 var secS = 'с.';
                 var secM = 'с.';
                 var minS = 'м.';
                 var minM = 'м.';
                 var hourS = 'ч.';
                 var hourM = 'ч.';
                 var dayS = 'д.';
                 var dayM = 'д.';
                 var detailOut = false;
                 var readyLink = '0'+(detailOut?secS:' ' + secM);
         </script>
         <script src="/js/t.js" type="text/javascript"></script>
         <br><div style='font-size: xx-small;text-align:center;'><a target='_blank' style='font-size:xx-small;' title='LLC PlayRoom ИНН 7724455887, КПП 772401001, ОГРН 1187746843459'>© LLC PlayRoom</a></div></body>
         </html>

         <?


         */


        /*  echo '<img src="/style/images/body/logo.jpg" class="img"/>';
          echo '<div class="body_list">';
          echo '<div class="menulist">';
          echo '<div class="line_3"></div>
         ';
  */



        $kvest = fch("SELECT * FROM `kvest` WHERE `id_user` = ?", array($user['id']));

        if ($kvest['kolls'] == 0 and $kvest['last'] < time()) {
            qry("UPDATE `kvest` SET `kolls` = ? WHERE `id` = ?", array(2, $kvest['id']));
           // header('Location: ?');
           // exit();
        }

        if (!$kvest) {
            qry("INSERT INTO `kvest` SET `id_user` = ?, `id_monster` = ?, `kolls` = ?", array($user['id'], 1, 2));
            header('Location: ?');
            exit();
        }

        $arena = fch("SELECT * FROM `arena` WHERE `id_user` = ?", array($user['id']));

        if ($arena['kolls'] == 0 and $arena['last'] < time()) {
            qry("UPDATE `arena` SET `kolls` = ? WHERE `id` = ?", array(20, $arena['id']));
          //  header('Location: ?');
          //  exit();
        }

        if(!$arena){
            qry("INSERT INTO `arena` SET `id_user` = ?, `kolls` = ?", array($user['id'], 20));
            header('Location: ?');
            exit();
        }

        echo '<div class="body_list">';
        echo '<div class="menulist">';
        echo '<div class="line_3"></div>
        ';
        echo '<div style="background-repeat: no-repeat;background-image: url(/images/bg_grey.jpg); background-size:cover; position:relative; z-index:2;">
<div class="case center">
<table width="100%">
<td valign="top" class="center" width="33%"><a href="/quest/"><img src="/images/monsters/avatars/' . $kvest['id_monster'] . '.png" width="80px"></a><a href="/quest/"><span class="bold">Квест</span></a>
';

        if ($kvest['kolls'] >= 1) {
            echo '<span>Сражений: ' . $kvest['kolls'] . '</span>';
        } else {
            echo '<span id="time_' . ($kvest['last'] - time()) . '000">' . tl($kvest['last'] - time()) . '</span>';
        }

        echo '<td valign="top" class="center" width="33%"><a href="/arena/"><img src="/images/icon/arena_2.png" width="80px"></a><a href="/arena/"><span class="bold">Арена</span></a>';

        if($arena['kolls'] >= 1){
            echo '<span>Сражений: '.$arena['kolls'].'</span>';
        }else{
            echo '<span id="time_'.($arena['last']-time()).'000">'.tl($arena['last']-time()).'</span>';
        }

echo '</td></table></div></div><div class="line"></div>';

#-Админка-#
        if ($user['prava'] == 1) {
            echo '<li><a class="link" href="/admin"><img src="/style/images/chat/admin.png" alt=""/> Админка</a></li>';
            echo '<div class="line_1"></div>';
        }
#-Если есть права модератора или админа-#
        if ($user['prava'] == 1 or $user['prava'] == 2 or $user['prava'] == 3) {
#-Выборка сколько сообщение не прочтено в беседе-#
            $sel_chat_m = $pdo->prepare("SELECT COUNT(*) FROM `chat_moderator` WHERE `id` > :chat_moder");
            $sel_chat_m->execute(array(':chat_moder' => $user['chat_moder']));
            $amount_m = $sel_chat_m->fetch(PDO::FETCH_LAZY);
            if ($amount_m[0] > 0) {
                echo '<li><a  class="link" href="/chat_moderator"><img src="/style/images/chat/moder.png"> Беседа <span class="green">(+)</span></a></li>';
            } else {
                echo '<li><a class="link" href="/chat_moderator"><img src="/style/images/chat/moder.png"> Беседа</a></li>';
            }

        }

#-Подарок-#
        if ($user['podarok'] != 0) {
#-Ключи-#
            if ($user['type_podarok'] == 1) {
                $img_podarok = '<img src="/style/images/body/key.png" alt=""/>';
            }
#-Серебро-#
            if ($user['type_podarok'] == 2) {
                $img_podarok = '<img src="/style/images/many/silver.png" alt=""/>';
            }
#-Золото-#
            if ($user['type_podarok'] == 3) {
                $img_podarok = '<img src="/style/images/many/gold.png" alt=""/>';
            }
#-Кристаллы-#
            if ($user['type_podarok'] == 4) {
                $img_podarok = '<img src="/style/images/many/crystal.png" alt=""/>';
            }
            echo '<li><a class="link" href="/podarok_act?act=give"><img src="/style/images/body/gift.png" alt=""/> Подарок <span class="white">(Получить: <span class="yellow">' . $img_podarok . '' . $user['podarok'] . '</span>)</span></a></li>';

        } else {
            echo '<li><a class="link" href="/"><img src="/style/images/body/gift.png" alt=""/> Подарок <span class="white">(<img src="/style/images/body/error.png" alt=""/>Подарков нет)</span></a></li>';
        }


          
        #-Хэллоуин-#
       # $halloween_time = $user['celebration_time']-time();
       # echo'<li><a class="link" href="/halloween_battle"><img src="/style/images/body/helloween.png" alt=""/> <span class="purple">Хэллоуин 2019 '.($user['celebration_time'] != 0 ? "(".(int)($halloween_time/3600).":".(int)($halloween_time/60%60).")" : "").'</span></a></li>';
       
        

        echo '<div class="mini-line"></div>';


#-ОХОТА-#
#-Проверяем в бою мы или нет-#
        $sel_hunting_b = $pdo->prepare("SELECT * FROM `hunting_battle` WHERE `user_id` = :user_id AND `statys` = :statys");
        $sel_hunting_b->execute(array(':user_id' => $user['id'], ':statys' => '0'));
        if ($sel_hunting_b->rowCount() == 0) {
#-Проверяем есть группа или нет-#
            $sel_hunting_t = $pdo->prepare("SELECT COUNT(*) FROM `hunting_time` WHERE `user_id` = :user_id");
            $sel_hunting_t->execute(array(':user_id' => $user['id']));
            $hunting_t = $sel_hunting_t->fetch(PDO::FETCH_LAZY);
            $sel_hunting_b = $pdo->prepare("SELECT COUNT(*) FROM `hunting` WHERE `level` <= :user_lvl");
            $sel_hunting_b->execute(array(':user_lvl' => $user['level']));
            $hunting_b = $sel_hunting_b->fetch(PDO::FETCH_LAZY);
            if ($hunting_t[0] != $hunting_b[0]) {
                echo '<li><a class="link" href="/select_location"><img src="/style/images/body/ohota.png" alt=""/> Охота <span class="green">(+)</span></a></li>';
            } else {
                echo '<li><a class="link" href="/select_location"><img src="/style/images/body/ohota.png" alt=""/> Охота </a></li>';
            }
        } else {
            echo '<li><a class="link" href="/hunting_battle"><img src="/style/images/body/ohota.png" alt=""/> Охота <span class="red">(+)</span></a></li>';
        }
#-ДУЭЛИ-#

        if ($user['level'] >= 5) {
            $sel_duel = $pdo->prepare("SELECT * FROM `duel_battle` WHERE `user_id` = :user_id");
            $sel_duel->execute(array(':user_id' => $user['id']));
            if ($sel_duel->rowCount() == 0) {
#-Проверяем онлайн дуэли-#
                $sel_duel_on = $pdo->prepare("SELECT * FROM `duel_online` WHERE (`user_id` = :user_id OR `ank_id` = :user_id) AND `statys` != 0");
                $sel_duel_on->execute(array(':user_id' => $user['id']));
                if ($sel_duel_on->rowCount() == 0) {
                    $user_level = floor($user['level'] / 2);
                    $battle_d = $user_level - $user['duel_b'];
                    echo '<li><a class="link" href="/duel"><img src="/style/images/body/league.png" alt=""/> Дуэли <span style="font-size: 13px;color: #666666;">(Дуэлей - <img src="/style/images/body/league.png">' . $battle_d . ')</span></a></li>';
                } else {
                    echo '<li><a class="link" href="/duel_online"><img src="/style/images/body/league.png" alt=""/> Дуэли <span class="red">(+)</span></a></li>';
                }
            } else {
                echo '<li><a class="link" href="/duel_battle"><img src="/style/images/body/league.png" alt=""/> Дуэли <span class="red">(+)</span></a></li>';
            }
        } else {
            echo '<li><a class="link" href=""><img src="/style/images/body/league.png" alt=""/> <span class="white">Дуэли (Требуется <img src="/style/images/user/level.png" alt=""/>5)</span></a></li>';
        }

#-БОССЫ-#

        if ($user['level'] >= 5) {
#-В бою или нет-#
            $sel_boss_u = $pdo->prepare("SELECT * FROM `boss_users` WHERE `user_id` = :user_id");
            $sel_boss_u->execute(array(':user_id' => $user['id']));
            if ($sel_boss_u->rowCount() == 0) {
#-Проверяем какое количество боссов на отдыхе-#
                $sel_amount_t = $pdo->prepare("SELECT COUNT(DISTINCT(boss_id)) FROM `boss_time` WHERE `user_id` = :user_id AND `type` = 2");
                $sel_amount_t->execute(array(':user_id' => $user['id']));
                $amount_t = $sel_amount_t->fetch(PDO::FETCH_LAZY);
#-Проверяем сколько всего Боссов для нашего лвл-#
                $sel_amount_b = $pdo->prepare("SELECT COUNT(*) FROM `boss` WHERE `level` <= :user_lvl AND `type` != 4"); //type != 4
                $sel_amount_b->execute(array(':user_lvl' => $user['level']));
                $amount_b = $sel_amount_b->fetch(PDO::FETCH_LAZY);
                $battle_o = $amount_b[0] - $amount_t[0];
                echo '<li><a class="link" href="/boss"><img src="/style/images/body/bos.png" alt=""> Боссы <span style="font-size: 13px;color: #666666;">(Боев - <img src="/style/images/body/league.png">' . $battle_o . ')</span> ' . ($user['start'] == 6 ? "<img src='/style/images/body/left_mini.gif' alt=''/>" : "") . '</a></li>';
            } else {
                echo '<li><a class="link" href="/boss_battle"><img src="/style/images/body/bos.png" alt=""> Боссы <span class="red">(+)</span></a></li>';
            }
        } else {
            echo '<li><a class="link" href=""><img src="/style/images/body/bos.png" alt=""> <span class="white">Боссы (Требуется <img src="/style/images/user/level.png" alt=""/>5)</span></a></li>';
        }


        echo '<div class="mini-line"></div>';
        echo '<div class="line_1_v"></div>';
#-ЗАМКИ-#
        if ($user['level'] >= 10) {
            $sel_zamki = $pdo->query("SELECT * FROM `zamki`");
            if ($sel_zamki->rowCount() != 0) {
                $zamki = $sel_zamki->fetch(PDO::FETCH_LAZY);
                $zamki_ost = $zamki['time'] - time();
                $zamki_time = "<span class='white'>(" . ($zamki_ost / 60 % 60) . ":" . ($zamki_ost % 60) . ")</span>";
            }
            echo "<li><a class='link' href='/zamki'><img src='/style/images/body/zamki.png' alt=''/> Замки $zamki_time</a></li>";
        } else {
            echo "<li><a class='link' href=''><img src='/style/images/body/zamki.png' alt=''/> <span class='white'>Замки (Требуется <img src='/style/images/user/level.png' alt=''/>10)</span></a></li>";
        }
#-РЕЙД-#

        if ($user['level'] >= 20) {
#-Время до сражения-#
            $sel_reid_t = $pdo->query("SELECT * FROM `reid_boss`");
            if ($sel_reid_t->rowCount() != 0) {
                $reid_t = $sel_reid_t->fetch(PDO::FETCH_LAZY);
                $reid_ost = $reid_t['time'] - time();
                $reid_time = "<span class='white'>(" . timers_mini($reid_ost) . ")</span>";
            }
            echo "<li><a class='link' href='/reid'><img src='/style/images/body/reid.png' alt=''/> Рейд $reid_time</a></li>";
        } else {
            echo "<li><a class='link' href=''><img src='/style/images/body/reid.png' alt=''/> <span class='white'>Рейд (Требуется <img src='/style/images/user/level.png' alt=''/>20)</span></a></li>";
        }
#-КОЛИЗЕЙ-#

        if ($user['level'] >= 13) {
            echo "<li><a class='link' href='/coliseum'><img src='/style/images/body/coliseum.png' alt=''/> Колизей</a></li>";
        } else {
            echo "<li><a class='link' href=''><img src='/style/images/body/coliseum.png' alt=''/> <span class='white'>Колизей (Требуется <img src='/style/images/user/level.png' alt=''/>13)</span></a></li>";
        }

#-БАШНИ-#
        if ($user['level'] >= 25) {
            echo "<li><a class='link' href='/towers'><img src='/style/images/body/towers.png' alt=''/> Башни</a></li>";
        } else {
            echo "<li><a class='link' href=''><img src='/style/images/body/towers.png' alt=''/> <span class='white'>Башни (Требуется <img src='/style/images/user/level.png' alt=''/>25)</span></a></li>";
        }
#-ПИРАМИДА ТЭПА-#

        echo "<li><a class='link' href='/pyramid'><img src='/style/images/body/pyramid.png' width='13px' height='13px' alt=''/> Пирамида Тэпа</a></li>";
        echo '<div class="line_1"></div>';


#-БОИ ПИТОМЦЕВ-#
        echo '<div class="mini-line"></div>';

        if ($user['level'] >= 20) {
            $sel_pets_me = $pdo->prepare("SELECT * FROM `pets_me` WHERE `user_id` = :user_id");
            $sel_pets_me->execute(array(':user_id' => $user['id']));
            if ($sel_pets_me->rowCount() != 0) {
                echo '<li><a class="link" href="/pets_duel"><img src="/style/images/body/pets_duel.png" alt=""/> Дуэльный поединок <span class="white"></span></a></li>';
            } else {
                echo '<li><a class="link" href=""><img src="/style/images/body/pets_duel.png" alt=""/> <span class="white">Дуэльный поединок (Требуеться <img src="/style/images/body/pets.png" alt=""/>Питомец)</span></a></li>';
            }
        } else {
            echo '<li><a class="link" href=""><img src="/style/images/body/pets_duel.png" alt=""/> <span class="white">Бои питомцев доступны с <img src="/style/images/user/level.png" alt=""/>20 ур.</span></a></li>';
        }
        echo '<div class="line_1"></div>';


        echo '<div class="mini-line"></div>';

#-ТУРНИР ИГРОКОВ-#
        echo '<li><a class="link" href="/tournament_users"><img src="/style/images/body/tournament_users.png" alt=""/> Турнир игроков</a></li>';

#-ТУРНИР КЛАНОВ-#
        if ($user['level'] >= 15) {
            echo '<li><a class="link" href="/tournament_clan"><img src="/style/images/body/tournament_clan.png" alt=""/> Турнир кланов</a></li>';
        } else {
            echo '<li><a class="link" href=""><img src="/style/images/body/tournament_clan.png" alt=""/> <span class="white">Турнир кланов(Требуется <img src="/style/images/user/level.png" alt=""/>15)</span></a></li>';
        }
#-КЛАНЫ-#

        echo "<li><a class='link' href='/clan'><img src='/style/images/body/clan.png' alt=''/> " . ($user['level'] >= 15 ? 'Кланы' : '<span class="white">Кланы (Требуется <img src="/style/images/user/level.png" alt=""/>15)</span>') . "</a></li>";
        echo '<div class="line_1"></div>';


        echo '<div class="mini-line"></div>';

#-Торговая лавка-#
        echo '<li><a class="link" href="/trade_shop"><img src="/style/images/body/torg.png" alt=""> Торговая лавка</a></li>';
#-Кузнец-#

        echo '<li><a class="link" href="/blacksmith"><img src="/style/images/body/blacksmith.png" alt="">  ' . ($user['level'] >= 3 ? 'Кузнец' : '<span class="white">Кузнец (Требуется <img src="/style/images/user/level.png" alt=""/>3)</span>') . '</a></li>';
#-Обменник-#

        if ($user['level'] >= 10) {
            if ($user['obmenik_time'] == 0) {
                echo '<li><a class="link" href="/exchanger"><img src="/style/images/body/obmenik.png" alt=""/> Обменник <span class="green">(+)</span></a></li>';
            } else {
                $obmenik_t = $user['obmenik_time'] - time();
                echo '<li><a class="link" href="/exchanger"><img src="/style/images/body/obmenik.png" alt=""/> Обменник <span class="white">(' . (int)($obmenik_t / 3600) . ':' . (int)($obmenik_t / 60 % 60) . ')</span></a></li>';
            }
        } else {
            echo "<li><a  href=''><img src='/style/images/body/obmenik.png' alt=''/> <span class='white'>Обменник (Требуется <img src='/style/images/user/level.png' alt=''/>10)</span></a></li>";
        }


        echo '<div class="mini-line"></div>';


        #-Ежедневные задания-#
#-Невыполненые задания-#
        $sel_tasks_n = $pdo->prepare("SELECT COUNT(*) FROM `quest` WHERE `user_id` = :user_id");
        $sel_tasks_n->execute(array(':user_id' => $user['id']));
        $amount_n = $sel_tasks_n->fetch(PDO::FETCH_LAZY);
#-Выполненые задания-#
        $glava_quest_count = cnt("SELECT * FROM `quest_glava_user` WHERE `id_user` = ? AND `ok` = ?", array($user['id'], 1));
        $quest_count = cnt("SELECT * FROM `quest_user` WHERE `id_user` = ? AND `ok` = ?", array($user['id'], 1));
        echo '<li><a class="link" href="/tasks"><img src="/style/images/body/daily_tasks.png" alt=""/> Задания '.($glava_quest_count >= 1 || $quest_count >= 1 ? '<font color="green">(+)</font>' : '').'</a></li>';


#-Ежедневные задания-#
#-Невыполненые задания-#
        $sel_tasks_n = $pdo->prepare("SELECT COUNT(*) FROM `daily_tasks` WHERE `user_id` = :user_id");
        $sel_tasks_n->execute(array(':user_id' => $user['id']));
        $amount_n = $sel_tasks_n->fetch(PDO::FETCH_LAZY);
#-Выполненые задания-#
        $sel_tasks_v = $pdo->prepare("SELECT COUNT(*) FROM `daily_tasks` WHERE `user_id` = :user_id AND `statys` = 1");
        $sel_tasks_v->execute(array(':user_id' => $user['id']));
        $amount_v = $sel_tasks_v->fetch(PDO::FETCH_LAZY);
        echo '<li><a class="link" href="/daily_tasks"><img src="/style/images/body/daily_tasks.png" alt=""/> Ежедневные задания <span class="white">(' . $amount_v[0] . '/' . $amount_n[0] . ')</span></a></li>';


#-Зал славы-#

        echo '<li><a class="link" href="/rating"><img src="/style/images/body/rating.png" alt=""/> Зал славы</a></li>';

#-Покупка золота-#

        //echo '<li><a class="link" href="/payment"><img src="/style/images/many/gold_g.png" alt=""/> Получить золото <span class="green">(+)</span></a></li>';
        echo '<li><a class="link" href="/donat/"><img src="/style/images/many/gold_g.png" alt=""/> Получить золото <span style="color:#aa2ad9">(NEW)</span></a></li>';
        echo '</div>';

    }
}

echo '</div>';
echo '</div>';
require_once H . 'system/footer.php';
?>