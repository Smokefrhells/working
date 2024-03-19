<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="HandheldFriendly" content="True">
    <meta name="description"
          content="Битва Королевств - это увлекательная мобильная игра в которая можно поиграть практически с любого устройства."/>
    <meta name="keywords"
          content="Битва Королевств, warheros, warsking, mmorpg, wap игры, игры на телефон, текстовые игры"/>
    <meta name="theme-color" content="#1e1d1d"/>
    <title><?= $head ?></title>
    <link type="text/css" rel="stylesheet" href="/style/style.v1.6.css?v=3"/>
    <link rel="stylesheet" type="text/css" media="all" href="/images/style/mrush.css?v=8"/>
    <link rel="shortcut icon" href="/style/icon.ico">
    <script type="text/javascript" src="/js/jquery-2.2.4.min.js"></script>
    <script type="text/javascript" src="/js/handler_v1.0.js"></script>
   <link rel="stylesheet" href="/style/default.css?ver=2.3">
</head>
<body>
<?php
//	<link rel="stylesheet" href="/style/bootstrap.min.css">
//    <link rel="stylesheet" href="/style/default.css?ver=2.3">
//    <link rel="stylesheet" href="/style/diolog.css?ver=4.3">
//	<script type="text/javascript" src="/style/jquery.js"></script>

//<link rel="stylesheet" type="text/css" media="all" href="http://144.76.127.94/view/style/index.css?3.78" />
#-Только если авторизованы-#
if (isset($user['id'])) {
#-Онлайн-#
    if ($user['time_online'] < time() + 1100) {
        $upd_users = $pdo->prepare("UPDATE `users` SET `time_online` = :time_online WHERE `id` = :id LIMIT 1");
        $upd_users->execute(array(':time_online' => time(), ':id' => $user['id']));
    }
#-IP адрес-#
    $upd_users = $pdo->prepare("UPDATE `users` SET `ip` = :ip WHERE `id` = :id LIMIT 1");
    $upd_users->execute(array(':ip' => $_SERVER['REMOTE_ADDR'], ':id' => $user['id']));

#-Параметры-#
    $param = $user['sila'] + $user['zashita'] + $user['health'] + $user['s_sila'] + $user['s_zashita'] + $user['s_health'] + $user['sila_bonus'] + $user['zashita_bonus'] + $user['health_bonus'];
    if ($param != $user['param']) {
        $upd_users = $pdo->prepare("UPDATE `users` SET `param` = :param WHERE `id` = :id LIMIT 1");
        $upd_users->execute(array(':param' => $param, ':id' => $user['id']));
    }
#-Записываем ID клана-#
    if ($user['clan_id'] == 0) {
        $sel_clan_me = $pdo->prepare("SELECT `id`, `clan_id`, `user_id` FROM `clan_users` WHERE `user_id` = :user_id");
        $sel_clan_me->execute(array(':user_id' => $user['id']));
        if ($sel_clan_me->rowCount() != 0) {
            $clan_me = $sel_clan_me->fetch(PDO::FETCH_LAZY);
            $upd_u = $pdo->prepare("UPDATE `users` SET `clan_id` = :clan_id WHERE `id` = :id LIMIT 1");
            $upd_u->execute(array(':clan_id' => $clan_me['clan_id'], ':id' => $user['id']));
        }
    }

    require_once 'time_delete.php'; //Удаление записей по времени
    require_once 'game_setting.php'; //Игровые настройки
    require_once 'clan_exp.php'; //Клановый опыт
    require_once H . ('system/game/reid.php'); //Рейд
    require_once H . ('system/game/coliseum.php'); //Колизей
    require_once H . ('system/game/zamki.php'); //Замки
    require_once H . ('system/game/towers.php'); //Башни
    require_once H . ('system/game/pets_duel.php'); //Дуэльный поединок


#-Новая новость-#
    $news_r = false;
    if ($user['news_read'] == 1) {
        $news_r = '<a href="/news"><img src="/style/images/news/news.png" alt=""/><span class="green">Новости</span></a>';
    }
#-Новое сообщение-#
    $mail = false;
    $sel_mail_k = $pdo->prepare("SELECT * FROM `mail_kont` WHERE `user_id` = :user_id AND `new` = :new LIMIT 1");
    $sel_mail_k->execute(array(':user_id' => $user['id'], ':new' => 1));
    if ($sel_mail_k->rowCount() != 0) {
        $mail = '<a href="/mail"><img src="/style/images/user/mail.png" alt=""/></a>';
    }

    $hunting_ostatok = false;
#-Проверка что герой участвует в бою-#
    $sel_hunting_b = $pdo->prepare("SELECT * FROM `hunting_battle_u` WHERE `user_id` = :user_id");
    $sel_hunting_b->execute(array(':user_id' => $user['id']));
    if ($sel_hunting_b->rowCount() != 0) {
        $hunting_b = $sel_hunting_b->fetch(PDO::FETCH_LAZY);
#-Время отображения захвата локации-#
        $sel_hunting_t = $pdo->prepare("SELECT * FROM `hunting` WHERE `time_battle` > 0 AND `id` = :location");
        $sel_hunting_t->execute(array(':location' => $hunting_b['location']));
        if ($sel_hunting_t->rowCount() != 0) {
            $time_hunting = $sel_hunting_t->fetch(PDO::FETCH_LAZY);
            $hunting_ostatok = $time_hunting['time_battle'] - time();
            $hunting_ostatok = '<a href="/hunting_battle_u"><img src="/style/images/body/league.png" alt=""/><span class="red">' . (int)($hunting_ostatok / 3600) . ':' . (int)($hunting_ostatok / 60 % 60) . '</span></a>';
        }
    }

#-Замки-#
    $zamki_ostatok = false;
    $sel_zamki_u = $pdo->prepare("SELECT `id`, `user_id` FROM `zamki_users` WHERE `user_id` = :user_id");
    $sel_zamki_u->execute(array(':user_id' => $user['id']));
    if ($sel_zamki_u->rowCount() != 0) {
#-Выборка времени-#
        $sel_zamki_t = $pdo->query("SELECT `id`, `statys`, `time` FROM `zamki`");
        $zamki_t = $sel_zamki_t->fetch(PDO::FETCH_LAZY);
        $ost_zamki = $zamki_t['time'] - time();
        $zamki_ostatok = '<a href="/zamki_battle">' . ($zamki_t['statys'] == 1 ? '<span class="red">' : '<span class="green">') . '<img src="/style/images/body/zamki.png" alt=""/>' . (int)($ost_zamki / 60 % 60) . ':' . ($ost_zamki % 60) . '</span></a>';
    }

#-Рейд-#
    $reid_ostatok = false;
    $sel_reid_u = $pdo->prepare("SELECT * FROM `reid_users` WHERE `user_id` = :user_id");
    $sel_reid_u->execute(array(':user_id' => $user['id']));
    if ($sel_reid_u->rowCount() != 0) {
        $sel_reid_t = $pdo->query("SELECT * FROM `reid_boss`");
        $reid_t = $sel_reid_t->fetch(PDO::FETCH_LAZY);
        $ost_reid = $reid_t['time'] - time();
        $reid_ostatok = '<a href="/reid"><img src="/style/images/body/reid.png" alt=""/>' . ($reid['statys'] == 1 ? '<span class="red">' : '<span class="green">') . '' . timers_mini($ost_reid) . '</span></a>';
    }

#-Время отображения дуэлей-#
    $oshered_duel = false;
    $sel_time_duel = $pdo->prepare("SELECT * FROM `duel_online` WHERE `user_id` = :user_id OR `ank_id` = :user_id");
    $sel_time_duel->execute(array(':user_id' => $user['id']));
    if ($sel_time_duel->rowCount() != 0) {
        $time_duel = $sel_time_duel->fetch(PDO::FETCH_LAZY);
        if ($time_duel['time'] >= time()) {
            $oshered_time = $time_duel['time'] - time();
            if ($time_duel['statys'] == 0) {
                $oshered_duel = '<img src="/style/images/body/league.png" alt=""/>' . (int)($oshered_time / 60 % 60) . ':' . ($oshered_time % 60) . '';
            } else {
                $oshered_duel = '<a href="/duel_online"><img src="/style/images/body/league.png" alt=""/><span class="red">' . ($oshered_time % 60) . ' сек.</span></a>';
            }
        } else {
            $oshered_duel = '<a href="/duel_online"><img src="/style/images/body/league.png" alt=""/><span class="red">Дуэль</span></a>';
            $upd_duel_on = $pdo->prepare("UPDATE `duel_online` SET `statys` = 2 WHERE `id` = :id");
            $upd_duel_on->execute(array(':id' => $time_duel['id']));
        }
    }

#-Колизей-#
    $coliseum_s = false;
    $sel_coliseum_s = $pdo->prepare("SELECT `id`, `user_id`, `statys` FROM `coliseum` WHERE `user_id` = :user_id AND (`statys` = 1 OR `statys` = 2)");
    $sel_coliseum_s->execute(array(':user_id' => $user['id']));
    if ($sel_coliseum_s->rowCount() != 0) {
        $coliseum_s = '<a href="/coliseum"><img src="/style/images/body/coliseum.png" alt=""/></a>';
    }

#-Башни-#
    $towers_s = false;
    $sel_towers_s = $pdo->prepare("SELECT `id`, `user_id`, `statys` FROM `towers` WHERE `user_id` = :user_id AND (`statys` = 1 OR `statys` = 2)");
    $sel_towers_s->execute(array(':user_id' => $user['id']));
    if ($sel_towers_s->rowCount() != 0) {
        $towers_s = '<a href="/towers"><img src="/style/images/body/towers.png" alt=""/></a>';
    }

#-Дуэльный поединок питомцев-#
    $pets_duel_s = false;
    $sel_pets_duel = $pdo->prepare("SELECT `id`, `user_id`, `statys` FROM `pets_duel` WHERE `user_id` = :user_id AND (`statys` = 1 OR `statys` = 2)");
    $sel_pets_duel->execute(array(':user_id' => $user['id']));
    if ($sel_pets_duel->rowCount() != 0) {
        $pets_duel_s = '<a href="/pets_duel"><img src="/style/images/body/pets.png" alt=""/></a>';
    }

#-Клановые боссы-#
    $sel_clan_boss_s = $pdo->prepare("SELECT `id`, `clan_id` FROM `clan_boss_battle` WHERE `clan_id` = :clan_id");
    $sel_clan_boss_s->execute(array(':clan_id' => $user['clan_id']));
    if ($sel_clan_boss_s->rowCount() != 0) {
        $clan_boss_s = '<a href="/clan_boss"><img src="/style/images/clan/boss.png" alt=""/></a>';
    }



    $level = file(H . "/system/exp.txt");
    $exp = trim($level[$user['level'] + 1]);
    $gold_l = (4 * $user['level']) / 2;
    $silver_l = (1000 * $user['level']) / 2;
    $level_l = $user['level'] + 1;
  
 echo ' <div id="header"><div class="cntr small lorange mt5 mb5" style="position: relative">
	<img class="icon" src="/style/images/ico/strength.png" /> '.($user['sila']+$user['s_sila']+$user['sila_bonus']).'	<img class="icon" src="/style/images/ico/health.png" /> '.($user['health']+$user['s_health']+$user['health_bonus']).'	<img class="icon" src="/style/images/ico/defense.png" /> '.($user['zashita']+$user['s_zashita']+$user['zashita_bonus']).'

<div id="mail_counter" style="position: absolute; top: -1px; right: 0; display: block">
        <div style="display: none; float: left"><a class="fr" href=""><img class="icon" src="' . $mail . '" /></a></div>
                    <span style="width: 0; visibility: hidden"></span>
                </div>

 <div style="display: inline-block; float: left"><a class="fr" href=""></a></div>    

    <div id="mail_counter" style="position: absolute; top: -1px; right: 0; display: block">
        <div style="display: inline-block; float: left"><a class="fr" href="">' . $mail . '</a></div>
                    <span style="width: 0; visibility: hidden"></span>
                </div>




</div>
';  

     
    echo '<div class="head">';
    echo '<div class="panel_head">' . $hunting_ostatok . ' ' . $zamki_ostatok . ' ' . $reid_ostatok . ' ' . $oshered_duel . ' ' . $news_r . '  ' . $coliseum_s . ' ' . $towers_s . ' ' . $pets_duel_s . ' </div>';
    echo '<div class="location_head">' . $head . '</div>';
    echo '</div>';
   

#-EXP BAR-#
    if ($user['level'] < 100) {
        $procent = round(100 / ($exp / ($user['exp'] + 1)));
        if ($procent > 100) {
            $procent = 100;
        }
        echo "<div class='exp_bar'><div class='progress' style='width:" . $procent . "%'></div></div>";
    } else {
        echo "<div class='exp_bar'><div class='progress' style='width:" . round(100 / 1000) . "%'></div></div>";
    }

#-Получение нового уровня-#
    if ($user['exp'] >= $exp AND $user['level'] < 100) {
        $rand = rand(1, 100);
        if ($rand < 90) {
            $chest = '<img src="/style/images/body/chest.png" alt=""/>Обычный сундук';
            $type = 1;
        } else {
            $chest = '<img src="/style/images/body/chest.png" alt=""/><span class="green">Древний сундук</span>';
            $type = 2;
        }
        $exp_ost2 = $user['exp'] - $exp;
        $upd_users = $pdo->prepare("UPDATE `users` SET `level` = :level, `exp` = :exp_ost, `gold` = :gold, `silver` = :silver, `new_event` = 1 WHERE `id` = :id LIMIT 1");
        $upd_users->execute(array(':level' => $user['level'] + 1, ':exp_ost' => $exp_ost2, ':gold' => $user['gold'] + $gold_l, ':silver' => $user['silver'] + $silver_l, ':id' => $user['id']));
#-Если есть клан то обнуляем время-#
        if ($user['clan_id'] != 0) {
            $upd_clan_l = $pdo->prepare("UPDATE `clan_users` SET `silver_time` = 0, `silver_t` = 0, `gold_time` = 0, `gold_t` = 0 WHERE `user_id` = :user_id LIMIT 1");
            $upd_clan_l->execute(array(':user_id' => $user['id']));
        }
#-Добавляем сундук-#
        $ins_chest = $pdo->prepare("INSERT INTO `chest` SET `type` = :type, `user_id` = :user_id, `time` = :time");
        $ins_chest->execute(array(':type' => $type, ':user_id' => $user['id'], ':time' => time()));
        $_SESSION['not2'] = "<center><div style='font-size: 15px;color: #cb862c;font-weight: bold;padding-bottom:3px;'><span class='line_notif'><img src='/style/images/user/level.png' alt=''/>Новый уровень<img src='/style/images/user/level.png' alt=''/></span></div><img src='/style/images/many/silver.png' alt=''/>$silver_l <img src='/style/images/many/gold.png' alt=''/>$gold_l<br/><a href='/chest'>$chest</a></center>";

#-Конкурс рефералов-#
        if ($user['level'] == 15 and $user['ref_id'] != 0) {
            $upd_users_r = $pdo->prepare("UPDATE `users` SET `ref_comp` = `ref_comp` + 1 WHERE `id` = :ref_id LIMIT 1");
            $upd_users_r->execute(array(':ref_id' => $user['ref_id']));
        }

#-Обучение-#
        if ($user['level'] > 1 and $user['start'] == 2) {
            $upd_users = $pdo->prepare("UPDATE `users` SET `start` = 3 WHERE `id` = :user_id LIMIT 1");
            $upd_users->execute(array(':user_id' => $user['id']));
        }
    }

#-Статуэтки-#
    if ($user['level'] == 100 and $user['exp'] >= 500000) {
        $exp_ost = $user['exp'] - 500000;
        $upd_users = $pdo->prepare("UPDATE `users` SET `figur` = :figur, `exp` = :exp_ost WHERE `id` = :id LIMIT 1");
        $upd_users->execute(array(':figur' => $user['figur'] + 1, ':exp_ost' => $exp_ost, ':id' => $user['id']));
    }

} else {
    echo '<div class="head">';
    echo '<a href="/">' . $text_location . '</a>';
    echo '</div>';
    echo '<div class="line_1"></div>';
}

#-Тех обслуживание-#
$sel_close = $pdo->query("SELECT * FROM `close` WHERE `close` = 1");
if ($sel_close->rowCount() != 0 and $user['prava'] != 1 and $user['prava'] != 2) {
    $close = $sel_close->fetch(PDO::FETCH_LAZY);
    $ostatok = time() - $close['time'];
    echo '<img src="/style/images/body/logo.jpg" class="img"/>';
    echo '<div class="body_list">';
    echo '<div style="padding-top: 5px;"></div>';
  //  echo '<center><span class="yellow">Всем привет! Всем привет! Сегодня открытие в 17:00 ПО МСК Ждем всех!  Все подробности в нашей группе ВК:  https://vk.com/warsking.mobi </span><br/></center>';
echo'<span class="gray">На сервере проводяться технические работы. Зайдите пожалуйста позже :(</span><br/>';
echo'<center><span class="yellow">Прошло времени: <img src="/style/images/body/time.png" alt=""/>'.timers($ostatok).'</span></center>';
    echo '<div style="padding-top: 5px;"></div>';
    echo '</div>';
    require_once H . 'system/footer.php';
    exit();
}
#-Тех обслуживание-#
$sel_close = $pdo->query("SELECT * FROM `close` WHERE `close` = 1");
if ($sel_close->rowCount() != 0 and $user['prava'] != 1 and $user['prava'] != 2) {
    $close = $sel_close->fetch(PDO::FETCH_LAZY);
    $ostatok = time() - $close['time'];
    echo '<img src="/style/images/body/logo.jpg" class="img"/>';
    echo '<div class="body_list">';
    echo '<div style="padding-top: 5px;"></div>';
  //  echo '<center><span class="yellow">Всем привет! Сегодня открытие в 17:00 ПО МСК Ждем всех! Все подробности в нашей группе ВК:  https://vk.com/warsking.mobi </span><br/></center>';
echo'<span class="gray">На сервере проводяться технические работы. Зайдите пожалуйста позже :(</span><br/>';
echo'<center><span class="yellow">Прошло времени: <img src="/style/images/body/time.png" alt=""/>'.timers($ostatok).'</span></center>';
    echo '<div style="padding-top: 5px;"></div>';
    echo '</div>';
    require_once H . 'system/footer.php';
    exit();
}

#-Блок-#
if ($user['block'] != 0) {
    echo '<img src="/style/images/body/logo.jpg" class="img"/>';
    echo '<div class="page">';
    echo '<center>';
    echo '<div style="padding-top: 5px;"></div>';
    echo '<img src="/style/images/body/error.png"><span class="gray">Вы заблокированы до: ' . vremja($user['block']) . '</span><br/>';
    echo '<div style="padding-top: 5px;"></div>';
    echo '</center>';
    echo '</div>';
    require_once H . 'system/footer.php';
    exit();
}

#-Блок IP-#
$sel_ip_block = $pdo->prepare("SELECT * FROM `ip_block` WHERE `ip` = :ip");
$sel_ip_block->execute(array(':ip' => $_SERVER['REMOTE_ADDR']));
if ($sel_ip_block->rowCount() != 0) {
    echo '<img src="/style/images/body/logo.jpg" class="img"/>';
    echo '<div class="page">';
    echo '<center>';
    echo '<div style="padding-top: 5px;"></div>';
    echo '<img src="/style/images/body/error.png"><span class="red">Доступ к игре для вас запрещен!</span><br/>';
    echo '<div style="padding-top: 5px;"></div>';
    echo '</center>';
    echo '</div>';
    require_once H . 'system/footer.php';
    exit();
}

$redicet = $_SERVER['REQUEST_URI'];
#-Вывод ответа обработчиков форм-#
if (isset($_SESSION['err'])) {
    echo '<div class="not_e">';
    echo "<div class='error'><img src='/style/images/body/error.png' alt=''/>$_SESSION[err]<div class='not_exit'><a href='?' id='not_exit'><img src='/style/images/body/cross.png'/></a></div></div>";
    echo '<div class="line_3"></div>';
    echo '</div>';
    $_SESSION['err'] = NULL;
}
if (isset($_SESSION['ok'])) {
    echo '<div class="not_e">';
    echo "<div class='ok'><img src='/style/images/body/ok.png' alt=''/>$_SESSION[ok]<div class='not_exit'><a href='?' id='not_exit'><img src='/style/images/body/cross.png'/></a></div></div>";
    echo '<div class="line_3"></div>';
    echo '</div>';
    $_SESSION['ok'] = NULL;
}
if (isset($_SESSION['not2'])) {
    echo "<div class='notif'>$_SESSION[not2]</div>";
    $_SESSION['not2'] = NULL;
}
if (isset($_SESSION['notif'])) {
    echo "<div class='notif'>$_SESSION[notif]</div>";
    $_SESSION['notif'] = NULL;
}

#-Игровые уведомления-#
if (isset($user['id'])) {
#-Вывод уведомлений-#
    $sel_event_l = $pdo->prepare("SELECT * FROM `event_log` WHERE `user_id` = :user_id AND (`type` = 1 OR `type` = 2 OR `type`  = 5 OR `type` = 6 OR `type` = 7 OR `type` = 8 OR `type` = 9 OR `type` = 10 OR `type` = 11) ORDER BY `time`");
    $sel_event_l->execute(array(':user_id' => $user['id']));
#-Если есть записи-#
    if ($sel_event_l->rowCount() != 0) {
        echo '<div class="notif">';
        while ($event = $sel_event_l->fetch(PDO::FETCH_LAZY)) {
#-Дуэли-#
            if ($event['type'] == 1) {
                $img = '<img src="/style/images/body/league.png" alt=""/>';
                echo "<div style='padding: 2px;'>$img<span class='yellow'>$event[log]</span></div>";
                echo "<img src='/style/images/body/ok.png' alt=''><a href='/duel_act?act=agree_duel&event_id=$event[id]'><span class='green'>Согласиться</span></a>  <img src='/style/images/body/error.png' alt=''><a href='/event_act?act=del&event_id=$event[id]&redicet=$redicet'><span class='red'>Отказать</span></a>";
            }
#-Помощь-#
            if ($event['type'] == 2) {
                $img = '<img src="/style/images/user/user.png" alt=""/>';
                echo "<div style='padding: 2px;'>$img<span class='yellow'>$event[log]</span></div>";
                echo "<img src='/style/images/body/ok.png' alt=''><a href='/boss_join?act=join&battle_id=$event[ev_id]&event_id=$event[id]'><span class='green'>Помочь</span></a>  <img src='/style/images/body/error.png' alt=''><a href='/event_act?act=del&event_id=$event[id]&redicet=$redicet'><span class='red'>Отказать</span></a>";
            }
#-Приглашение в клан-#
            if ($event['type'] == 5) {
                $img = '<img src="/style/images/body/clan.png" alt=""/>';
                echo "<div style='padding: 2px;'>$img<span class='yellow'>$event[log]</span></div>";
                echo "<img src='/style/images/body/ok.png' alt=''><a href='/clan_inv_join?act=join&clan_id=$event[ev_id]&event_id=$event[id]'><span class='green'>Вступить</span></a>  <img src='/style/images/body/error.png' alt=''><a href='/event_act?act=del&event_id=$event[id]&redicet=$redicet'><span class='red'>Отказаться</span></a>";
            }
#-Приглашение в друзья-#
            if ($event['type'] == 6) {
                $img = '<img src="/style/images/body/friends.png" alt=""/>';
                echo "<div style='padding: 2px;'>$img<span class='yellow'>$event[log]</span></div>";
                echo "<img src='/style/images/body/ok.png' alt=''><a href='/friends_act?act=get&event_id=$event[id]&redicet=$redicet'><span class='green'>Дружить</span></a>  <img src='/style/images/body/error.png' alt=''><a href='/event_act?act=del&event_id=$event[id]&redicet=$redicet'><span class='red'>Отказать</span></a>";
            }
#-Удалил из друзей-#
            if ($event['type'] == 7) {
                $img = '<img src="/style/images/body/clan.png" alt=""/>';
                echo "<div style='padding: 2px;'>$img<span class='yellow'>$event[log]</span></div>";
                echo "<img src='/style/images/body/error.png' alt=''><a href='/event_act?act=del&event_id=$event[id]&redicet=$redicet'><span class='red'>Скрыть</span></a>";
            }
#-Покупка золота и получение золота-#
            if ($event['type'] == 8) {
                $img = '<img src="/style/images/body/ok.png" alt=""/>';
                echo "<div style='padding: 2px;'>$img<span class='yellow'>$event[log]</span></div>";
                echo "<img src='/style/images/body/error.png'><a href='/event_act?act=del&event_id=$event[id]&redicet=$redicet'><span class='red'>Скрыть</span></a>";
            }
#-Замки-#
            if ($event['type'] == 9) {
                $img = '<img src="/style/images/body/zamki.png" alt=""/>';
                echo "<div style='padding: 2px;'>$img<span class='yellow'>$event[log]</span></div>";
                echo "<img src='/style/images/body/error.png'><a href='/event_act?act=del&event_id=$event[id]&redicet=$redicet'><span class='red'>Скрыть</span></a>";
            }
#-Подарок-#
            if ($event['type'] == 10) {
                $img = '<img src="/style/images/body/gift.png" alt=""/>';
                echo "<div style='padding: 2px;'>$img<span class='yellow'>$event[log]</span></div>";
                echo "<img src='/style/images/body/error.png'><a href='/event_act?act=del&event_id=$event[id]&redicet=$redicet'><span class='red'>Скрыть</span></a>";
            }
#-Аукцион-#
            if ($event['type'] == 11) {
                $img = '<img src="/style/images/body/auction.png" alt=""/>';
                echo "<div style='padding: 2px;'>$img<span class='yellow'>$event[log]</span></div>";
                echo "<img src='/style/images/body/error.png'><a href='/event_act?act=del&event_id=$event[id]&redicet=$redicet'><span class='red'>Скрыть</span></a>";
            }
        }
        echo '</div>';
    }

#-Клановое объявление-#
    if ($user['clan_ad'] == 1 and $user['clan_id'] != 0) {
        $sel_clan_ad = $pdo->prepare("SELECT `id`, `ad`, `ad_time` FROM `clan` WHERE `id` = :clan_id");
        $sel_clan_ad->execute(array(':clan_id' => $user['clan_id']));
        $clan_ad = $sel_clan_ad->fetch(PDO::FETCH_LAZY);
        if ($clan_ad['ad'] != '') {
            //echo '<div class="notif">';
            //echo "<div style='padding: 2px;'><img src='/style/images/clan/ad.png' alt=''/> <span class='yellow'>$clan_ad[ad]<br/><img src='/style/images/body/time.png' alt=''/>" . vremja($clan_ad['ad_time']) . "</span></div>";
           // echo "<img src='/style/images/body/error.png' alt=''><a href='/event_act?act=del_ad&redicet=$redicet'><span class='red'>Скрыть</span></a>";
            echo '
</div><div class="block center"><span class="r-middle">Объявление клана</span><div class="mt5"></div>' .$clan_ad[ad]. '<div class="mt5"></div> <img src="/style/images/body/time.png" alt=""/> - ' . vremja($clan_ad['ad_time']) . ' <div class="mt5"></div><a href="/event_act?act=del_ad&redicet='.$redicet.'">Скрыть объявление</a></div>
            </div>';
        }
    }

#-Обучение-#
    if ($user['start'] < 8) {
        echo '<div class="body_list">';
        echo '<div class="line_1_v"></div>';
        echo '<div class="menulist">';
#-Охота-#
        if ($user['start'] == 1) {
            echo '<li><a href="/hunting_battle"><img src="/style/images/start/grey.png" class="menulitl_img" width="30" height="30" alt=""/><div class="menulitl_block"><span class="menulitl_name"><b>Грей:</b></span> <div class="menulitl_param">Одержи победу над монстром!</div></div></a></li>';
        }
#-3 уровень-#
        if ($user['start'] == 2) {
            echo '<li><a href="/hunting_battle"><img src="/style/images/start/grey.png" class="menulitl_img" width="30" height="30" alt=""/><div class="menulitl_block"><span class="menulitl_name"><b>Грей:</b></span> <div class="menulitl_param">Неплохо ты его... Теперь достигни 3 уровня.</div></div></a></li>';
        }
#-Покупка снаряжения-#
        if ($user['start'] == 3) {
            echo '<li><a href="/armors"><img src="/style/images/start/grey.png" class="menulitl_img" width="30" height="30" alt=""/><div class="menulitl_block"><span class="menulitl_name"><b>Грей:</b></span> <div class="menulitl_param">Купи любую вещь своего уровня.</div></div></a></li>';
        }
#-Надевание снаряжение-#
        if ($user['start'] == 4) {
            echo '<li><a href="/bag"><img src="/style/images/start/grey.png" class="menulitl_img" width="30" height="30" alt=""/><div class="menulitl_block"><span class="menulitl_name"><b>Грей:</b></span> <div class="menulitl_param">Хороший выбор. Теперь перейди в сумку и надень её.</div></div></a></li>';
        }
#-Тренировка-#
        if ($user['start'] == 5) {
            echo '<li><a href="/training"><img src="/style/images/start/grey.png" class="menulitl_img" width="30" height="30" alt=""/><div class="menulitl_block"><span class="menulitl_name"><b>Грей:</b></span> <div class="menulitl_param">Чтобы побеждать сильных монстров необходимо повышать параметры. Прокачать их можно в <span class="yellow">Тренировке</span>.</div></div></a></li>';
        }
#-Боссы-#
        if ($user['start'] == 6) {
            if ($user['level'] >= 5) {
                echo '<li><a href="/boss"><img src="/style/images/start/grey.png" class="menulitl_img" width="30" height="30" alt=""/><div class="menulitl_block"><span class="menulitl_name"><b>Грей:</b></span> <div class="menulitl_param">Ты стал(а) сильнее и теперь можешь сразиться с боссом <span class="yellow">Огненная змея</span>.</div></div></a></li>';
            } else {
                echo '<li><a href="/"><img src="/style/images/start/grey.png" class="menulitl_img" width="30" height="30" alt=""/><div class="menulitl_block"><span class="menulitl_name"><b>Грей:</b></span> <div class="menulitl_param">Чтобы продолжить путь достигни 5 уровня.</div></div></a></li>';
            }
        }
#-Сохранение-#
        if ($user['start'] == 7) {
            echo '<li><a href="/save"><img src="/style/images/start/grey.png" class="menulitl_img" width="30" height="30" alt=""/><div class="menulitl_block"><span class="menulitl_name"><b>Грей:</b></span> <div class="menulitl_param">Твое обучение окончено. Все должны знать имя будущего героя, для этого сохрани игрока.</div></div></a></li>';
        }

        echo '</div>';
        echo '<div class="line_1"></div>';
        echo '</div>';
    }
}
?>