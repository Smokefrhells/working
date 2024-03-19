<?php
if (isset($user['id'])) {

    echo '<div class="mb10">';
      echo '</div>';

    
echo '<a class="link" href="/hero/'.$user[id].'"><img class="icon" src="http://144.76.127.94/view/image/icons/hero.png" /> Мой Герой</a>';
     
     
#-Кланы-#
    if ($user['level'] >= 15) {
#-Проверка есть ли клан-#
        $sel_clan_u = $pdo->prepare("SELECT `id`, `clan_id`, `user_id`, `chat_id`, `log_id` FROM `clan_users` WHERE `user_id` = :user_id");
        $sel_clan_u->execute(array(':user_id' => $user['id']));
        if ($sel_clan_u->rowCount() != 0) {
            $clan_u = $sel_clan_u->fetch(PDO::FETCH_LAZY);
#-Выборка данных клана-#
            $sel_clan = $pdo->prepare("SELECT `id` FROM `clan` WHERE `id` = :clan_id");
            $sel_clan->execute(array(':clan_id' => $clan_u['clan_id']));
            if ($sel_clan->rowCount() != 0) {
                $clan = $sel_clan->fetch(PDO::FETCH_LAZY);
#-Есть ли не прочитаные сообщения-#
                $sel_clan_c = $pdo->prepare("SELECT COUNT(*) FROM `clan_chat` WHERE `clan_id` = :clan_id AND `id` > :chat_id");
                $sel_clan_c->execute(array(':clan_id' => $clan['id'], ':chat_id' => $clan_u['chat_id']));
                $clan_c = $sel_clan_c->fetch(PDO::FETCH_LAZY);
#-История клана-#
                $sel_clan_l = $pdo->prepare("SELECT COUNT(*) FROM `clan_log` WHERE `clan_id` = :clan_id AND `id` > :log_id");
                $sel_clan_l->execute(array(':clan_id' => $clan['id'], ':log_id' => $clan_u['log_id']));
                $clan_l = $sel_clan_l->fetch(PDO::FETCH_LAZY);
                if ($clan_c[0] != 0 or $clan_l[0] != 0) {
                    $clan_m = '<img src="/style/images/body/clan_m_new.png" alt=""/>';
                } else {
                    $clan_m = '<a class="link" href="/clan"><img src="http://144.76.127.94/view/image/icons/clan.png" class="icon"/> Мой Клан</a>';
                }
                echo "<a href='/clan/view/$clan[id]'><span> $clan_m</span></a>";
            }
        } else {
            echo '<a class="link" href="/clan"><img src="http://144.76.127.94/view/image/icons/clan.png" class="icon"/> Мой Клан</a>';
        }
    } else {
        echo "";
    }

 echo '<a class="link" href="/"><img class="icon" src="http://144.76.127.94/view/image/icons/home.png" />  Главная</a>';
        echo '<div class="head">

<div class="svg_list">';
    echo '    
<div class="cntr"><div class="cntr lorange small"><img class="icon" src="http://144.76.127.94/view/image/icons/gold.png" />29 | <img class="icon" src="http://144.76.127.94/view/image/icons/silver.png" />28.9k</div></div>
  ';
          echo '</div>';
    echo '</div>';
    echo '</div>';
    

    echo '</div>';
    echo '<div class="line_1"></div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '<center>';
    echo '<div style="padding-top: 5px;"></div>';
#-Есть новости или нет-#

    echo '<div class="head">

<div class="svg_list">
';
//line_1 mbtn mb2 cntr svg_list
    if ($user['news_read'] == 1) {
        echo '<a class="lblue" href="/news">Новости <span class="green">(+)</span></a>';
    } else {
        echo '<a class="lblue"  href="/news">Новости</a>';
    }
    echo '<span class="gray"> | </span>';
#-Выборка сколько сообщение не прочтено в обычном чате-#
    $sel_chat_c = $pdo->prepare("SELECT COUNT(*) FROM `chat` WHERE (`type` = 1 AND `id` > :chat_id) OR (`type` = 2 AND `id` > :chat_war_id)");
    $sel_chat_c->execute(array(':chat_id' => $user['chat_id'], ':chat_war_id' => $user['chat_war_id']));
    $amount_c = $sel_chat_c->fetch(PDO::FETCH_LAZY);
    if ($amount_c[0] > 0) {
        echo '<a class="lblue"  href="/chat">Чат <span class="green">(+)</span></a>';
    } else {
        echo '<a class="lblue"  href="/chat">Чат</a>';
    }
    echo '<span class="gray"> | </span>';
    echo '<a class="lblue"  href="/forum_razdel">Форум</a><br/>';
    echo '</center>

';


    #-Игровые акции и скидки-#

    $sel_stock = $pdo->query("SELECT * FROM `stock` ORDER BY `type`");
    if ($sel_stock->rowCount() != 0) {
        while ($stock = $sel_stock->fetch(PDO::FETCH_LAZY)) {
            $stock_t = $stock['time'] - time();
          
            if ($stock['type'] == 1) {
                echo '
<a class="lwhite nd small bold" href="/payment">
<div class="mt5 mb2 cntr"><img class="icon" src="/images/ico/art/blick-action.png?2"/></div>
<div class="mb5 cntr">Акция! Помощь клану.</div>
</a><div class="cntr small mb5" style="color: #b280ff">До конца: ' . (int)($stock_t / 3600) . ' час. ' . ($stock_t / 60 % 60) . ' мин.<div><div><div><div>';
            }
            if ($stock['type'] == 2) {
                echo '<a class="lwhite nd small bold" href="/donat/">
<div class="mt5 mb2 cntr"><img class="icon" src="/images/ico/art/blick-action.png?2"/></div>
<div class="mb5 cntr">Акция! В 2 раза больше золота.</div>
</a><div class="cntr small mb5" style="color: #b280ff">До конца: ' . (int)($stock_t / 3600) . ' час. ' . ($stock_t / 60 % 60) . ' мин.<div><div><div><div>';
            }
            if ($stock['type'] == 3) {
                echo '<a class="lwhite nd small bold" href="/payment">
<div class="mt5 mb2 cntr"><img class="icon" src="/images/ico/art/blick-action.png?2"/></div>
<div class="mb5 cntr">Акция! В 3 раза больше золота.</div>
</a><div class="cntr small mb5" style="color: #b280ff">До конца: ' . (int)($stock_t / 3600) . ' час. ' . ($stock_t / 60 % 60) . ' мин.<div><div><div><div>';
            }
if($stock['type'] == 4){
echo'<a class="lwhite nd small bold" href="/training">
<div class="mt5 mb2 cntr"><img class="icon" src="/images/ico/art/blick-action.png?2"/></div>
<div class="mb5 cntr">Скидка '.$stock['prosent'].'% на тренировку.</div>
</a><div class="cntr small mb5" style="color: #b280ff">До конца: '.(int)($stock_t/3600).' час. '.($stock_t/60%60).' мин.<div><div><div><div>';}
    
  /*  
    
            if ($stock['type'] == 4) {
                echo '<a class="lwhite nd small bold" href="/actions_list">
<div class="mt5 mb2 cntr"><img class="icon" src="/images/ico/art/blick-action.png?2"/></div>
<div class="mb5 cntr">Мега акция!</div>
</a><div class="cntr small mb5" style="color: #b280ff">Акция действует до 11 октября включительно! <div><div><div><div>
';
            }
   
   ' . (int)($stock_t / 3600) . ' час. ' . ($stock_t / 60 % 60) . ' мин. 
 */    
            if ($stock['type'] == 5) {
                echo '
<a class="lwhite nd small bold" href="/blacksmith">
<div class="mt5 mb2 cntr"><img class="icon" src="/images/ico/art/blick-action.png?2"/></div>
<div class="mb5 cntr">Скидка ' . $stock['prosent'] . '% на заточку вещей.</div>
</a><div class="cntr small mb5" style="color: #b280ff">До конца: ' . (int)($stock_t / 3600) . ' час. ' . ($stock_t / 60 % 60) . ' мин.<div><div><div><div>';
            }
            if ($stock['type'] == 6) {
                echo '
<a class="lwhite nd small bold" href="/premium">
<div class="mt5 mb2 cntr"><img class="icon" src="/images/ico/art/blick-action.png?2"/></div>
<div class="mb5 cntr">Скидка ' . $stock['prosent'] . '% на премиум.</div>
</a><div class="cntr small mb5" style="color: #b280ff">До конца: ' . (int)($stock_t / 3600) . ' час. ' . ($stock_t / 60 % 60) . ' мин.<div><div><div><div>
';
            }
            if ($stock['type'] == 7) {
                echo '<a class="lwhite nd small bold" href="/exchanger">
<div class="mt5 mb2 cntr"><img class="icon" src="/images/ico/art/blick-action.png?2"/></div>
<div class="mb5 cntr">Обменник на ' . $stock['prosent'] . '% выгоднее.</div>
</a><div class="cntr small mb5" style="color: #b280ff">До конца: ' . (int)($stock_t / 3600) . ' час. ' . ($stock_t / 60 % 60) . ' мин.<div><div><div><div>';

            }
            if ($stock['type'] == 8) {
                echo '
<a class="lwhite nd small bold" href="/payment">
<div class="mt5 mb2 cntr"><img class="icon" src="/images/ico/art/blick-action.png?2"/></div>
<div class="mb5 cntr">Питомец в подарок! [Только сегодня]</div>
</a><div class="cntr small mb5" style="color: #b280ff">До конца: ' . timers($stock_t) . ' <div><div><div><div>';




            }
        }
    }


    /*
    ?>

       <a class="lwhite nd small bold" href="?">
       <div class="mt5 mb2 cntr"><img class="icon" src="http://144.76.127.94/view/image/art/blick-action.png?2"/></div>
       <div class="mb5 cntr">Акция на Руны, и Золото!</div>
       </a><div class="cntr small mb5" style="color: #b280ff">Скоро!</div><div class="hr_g mb2"><div><div></div></div></div>

    <?
    */

    echo '
<br>
<div>
<div class="head">';


    ?>

    <?
#-Сужба поддержки-#
    ?>


    <?
    echo '<center>';
    echo '<a href="/support">Служба поддержки</a><br/>';
    echo '</center>';
}
echo '<center>';
if (!isset($user['id'])) {
    echo '<div class="line_1"></div>';
    echo '<div style="padding-top:3px;"></div>';
}
echo '<a href="/knowledge_basa">База знаний</a><br/>';
echo '<a href="/rules">Правила</a>';
echo '</center>';

#-Конкурс рефералов-#
echo '<center><a href="/referal_comp"><span class="yellow">Конкурс рефералов!</span></a></center>';


#-Делаем выборку количества онлайн-#
$time = time() - 3600;
$sel_users = $pdo->prepare("SELECT * FROM `users` WHERE `time_online` > :time");
$sel_users->execute(array(':time' => $time));
$online = $sel_users->rowCount();
echo '<div class="foot">' . date("H:i:s") . ', <a href="/online">Онлайн:</a> ' . $online . '<br/>Битва Королевств © 2019, 14+<br/>';
if (isset($user['id'])) {
    echo '<a href="/exit">Выход</a></div>';
}
echo '<center><a href="https://m.vk.com/warsking.mobi"><img src="/style/images/body/vkontakte.png" width="20px" height="20" alt=""/></a> <a href="?"><img src="/style/images/body/odnoklassniki.png" width="20px" height="20" alt=""/></a></center>
<div><div><div><div>';
?>

<a href="https://statok.net/go/20327"><img src="https//statok.net/image/20327" alt="Statok.net" /></a>
<script type="text/javascript" src="https://mobtop.ru/c/122448.js"></script><noscript><a href="https://mobtop.ru/in/122448"><img src="https://mobtop.ru/122448.gif" alt="MobTop.Ru - Рейтинг и статистика мобильных сайтов"/></a></noscript>



                </center>
                </body>
                </html>
