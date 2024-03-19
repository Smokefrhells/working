<?php
require_once '../../system/system.php';
echo only_reg();
echo clan_level();
$id = check($_GET['id']);
if (empty($_GET['id']))
    $error = 'Ошибка!';
if (!isset($_GET['id']))
    $error = 'Ошибка!';
if (!isset($error)) {
    $sel_clan = $pdo->prepare("SELECT * FROM `clan` WHERE `id` = :id");
    $sel_clan->execute(array(':id' => $id));
    if ($sel_clan->rowCount() != 0) {
        $clan = $sel_clan->fetch(PDO::FETCH_LAZY);
    } else {
        header('Location: /clan');
        $_SESSION['err'] = 'Клан не найден!';
        exit();
    }
} else {
    header('Location: /clan');
    $_SESSION['err'] = $error;
    exit();
}
$head = 'Клан';
require_once H . 'system/head.php';
#-Выборка основателя клана-#
$sel_osnovatel = $pdo->prepare("SELECT `clan_id`, `user_id`, `prava` FROM `clan_users` WHERE `prava` = 4 AND `clan_id` = :clan_id");
$sel_osnovatel->execute(array(':clan_id' => $clan['id']));
$osnov = $sel_osnovatel->fetch(PDO::FETCH_LAZY);
$sel_users = $pdo->prepare("SELECT `id`, `nick` FROM `users` WHERE `id` = :id");
$sel_users->execute(array(':id' => $osnov['user_id']));
$all = $sel_users->fetch(PDO::FETCH_LAZY);
#-Процент построек-#
$prosent_building = round(((($clan['quatity_user'] + $clan['zashita_lvl'] + $clan['amulet_lvl'] + $clan['treasury_lvl']) / 220) * 100), 1);

#-Опыт клана-#
$level = file(H . "/system/clan_exp.txt");
$exp = trim($level[$clan['level']]);
//echo'<div class="page">';


echo '<div style="background-repeat: no-repeat;background-image: url(/images/bg_grey.jpg); background-position: 50% 50% ; position:relative; z-index:2;">';
echo '<div class="case">';
echo '<table width="100%">';
echo '<div style="white-space: pre-wrap;"><div class="svg_list_m"><font color="wheat">О клане: ' . msg($clan['description']) . '</font></div></div>';
echo '<td class="center" width="40%">';


echo '<img src="' . avatar_clan($clan['avatar']) . '" width="100px">';

echo '</td>';
echo '<td class="center"><span class="r-middle"><font color="goldenrod">' . $clan['name'] . '</font></span><div class="mt5"></div><span class="bolt"><font color="wheat">Основан: ' . vremja($clan['time']) . '</font><div class="mt5"></div><span class="bold"><font color="wheat">Уровень: ' . $clan['level'] . '</font></span><br><font color="wheat"> ';


if ($clan['level'] < 150) {
    echo '<img src="/style/images/user/exp.png" alt=""/>Опыт: ' . num_format($clan['exp']) . '/' . num_format($exp) . '';
} else {
    echo '<img src="/style/images/user/exp.png" alt=""/>Опыт: ' . num_format($clan['exp']) . '/1\'000\'000 [<img src="/style/images/user/figur.png" alt=""/>' . $clan['figur'] . ']<br/>';
}
echo '
</font></td>
</table>';

echo '</div>';
echo '</div>';
echo '<div class="line"></div>';
echo '<div class="top"></div>';

echo '</div>';
echo '</div>';
if ($user['clan_id'] == $clan['id']) {
    echo '<div class="body_list">';
    echo '<div class="menulist">';

#-Казна клана-#
    echo '<div class="line_1_v"></div>';
    echo '<div style="padding-top: 5px;"></div>';
    echo "<a href='/clan/kazna/$clan[id]'><img src='/style/images/clan/kazna.png' alt=''/> Казна: <span class='gray'><img src='/style/images/many/gold.png' alt=''/>" . num_format($clan['gold']) . " <img src='/style/images/many/silver.png' alt=''/>" . num_format($clan['silver']) . "</span></a>";
    echo '<div style="padding-top: 5px;"></div>';
    echo '</div>';
}
echo '<div class="line_pys"></div>';


#-Информационный лист-#
#-Считаем защиту замка-#
$sel_health_c = $pdo->prepare("SELECT SUM(health) as `clan_h` FROM `users` WHERE `clan_id` = :clan_id");
$sel_health_c->execute(array(':clan_id' => $clan['id']));
$health_c = $sel_health_c->fetchColumn();
$sel_healths_c = $pdo->prepare("SELECT SUM(s_health) as `clan_hs` FROM `users` WHERE `clan_id` = :clan_id");
$sel_healths_c->execute(array(':clan_id' => $clan['id']));
$healths_c = $sel_healths_c->fetchColumn();
echo '<div class="svg_list">';
echo "<img src='/style/images/user/user.png' alt=''/> <span class='yellow'>Основатель:</span><a href='/hero/$all[id]' style='display: inline;color: #bfbfbf; text-decoration: underline;'> $all[nick]</a><br/>";
echo '<div style="padding-top: 5px;"></div>';
echo "<img src='/style/images/user/zashita.png' alt=''/> <span class='yellow'>Защита клана:</span> <span class='gray'>" . num_format($health_c + $healths_c + $clan['zashita']) . "</span>";
echo '</div>';
echo '<div class="line_pys"></div>';
echo '</div>';

#-Вступление в клан-#
$sel_clan_u_c = $pdo->prepare("SELECT COUNT(*) FROM `clan_users` WHERE `clan_id` = :clan_id");
$sel_clan_u_c->execute(array(':clan_id' => $clan['id']));
$amount_u = $sel_clan_u_c->fetch(PDO::FETCH_LAZY);
#-Если нет клана и недостаточно игроков то можно вступить-#
if ($amount_u[0] < $clan['quatity_user']) {
    $sel_clan_me = $pdo->prepare("SELECT * FROM `clan_users` WHERE `user_id` = :user_id");
    $sel_clan_me->execute(array(':user_id' => $user['id']));
    if ($sel_clan_me->rowCount() == 0) {
        echo '<div style="padding-top: 5px;"></div>';
#-Есть время или нет-#
        if ($user['clan_time'] == 0) {
#-Подаем заявку или вступаем-#
            if ($clan['close'] == 0) {
                echo "<a href='/clan_join?act=join&clan_id=$clan[id]' class='button_green_a'>Вступить в клан</a>";
                echo '<div style="padding-top: 5px;"></div>';
            } else {
#-Проверяем подавали заявку или нет-#
                $sel_clan_a = $pdo->prepare("SELECT `clan_id`, `user_id` FROM `clan_application` WHERE `clan_id` = :clan_id AND `user_id` = :user_id");
                $sel_clan_a->execute(array(':clan_id' => $clan['id'], ':user_id' => $user['id']));
                if ($sel_clan_a->rowCount() == 0) {
                    echo "<a href='/clan_applic_act?act=add&clan_id=$clan[id]' class='button_green_a'>Подать заявку</a>";
                    echo '<div style="padding-top: 5px;"></div>';
                } else {
                    echo "<a href='/clan_applic_act?act=del&clan_id=$clan[id]' class='button_red_a'>Отозвать</a>";
                    echo '<div style="padding-top: 5px;"></div>';
                }
            }
        } else { //Если стоит лимит
            $exit_time = $user['clan_time'] - time();
#-Ускорение времени отдыха-#
            if (!isset($_GET['accel'])) {
                echo '<center><a href="/clan/view/' . $clan['id'] . '?accel=1" class="button_green_a"><img src="/style/images/body/time.png" alt=""/>' . (int)($exit_time / 3600) . ' час. ' . (int)($exit_time / 60 % 60) . ' мин.</a></center>';
                echo '<div style="padding-top: 5px;"></div>';
            } else {
                echo '<center><a href="/clan_time_act?act=accel&clan_id=' . $clan['id'] . '" class="button_green_a">Ускорить за <img src="/style/images/many/gold.png" alt=""/>1000</a></center>';
                echo '<div style="padding-top: 3px;"></div>';
                echo '<center><a href="/clan/view/' . $clan['id'] . '" class="button_red_a">Отменить</a></center>';
                echo '<div style="padding-top: 5px;"></div>';
            }
        }
        echo '<div class="line_1_v"></div>';
    }
}

echo '<div class="body_list">';
echo '<div class="menulist">';



#-Постройки клана-#
echo '<div class="line_1"></div>';
echo "<li><a href='/clan/building/$clan[id]'><img src='/style/images/clan/building.png' alt=''/> Постройки клана <span class='white'>[$prosent_building%]</span></a></li>";
#-Проверяем состоим в этом клане или нет-#
$sel_clan_u = $pdo->prepare("SELECT * FROM `clan_users` WHERE `clan_id` = :clan_id AND `user_id` = :user_id");
$sel_clan_u->execute(array(':clan_id' => $clan['id'], ':user_id' => $user['id']));
if ($sel_clan_u->rowCount() != 0) {
    $clan_u = $sel_clan_u->fetch(PDO::FETCH_LAZY);
#-Выборка лога-#
    $sel_clan_l = $pdo->prepare("SELECT COUNT(*) FROM `clan_log` WHERE `clan_id` = :clan_id AND `id` > :log_id");
    $sel_clan_l->execute(array(':clan_id' => $clan['id'], ':log_id' => $clan_u['log_id']));
    if ($sel_clan_l->rowCount() != 0) {
        $clan_l = $sel_clan_l->fetch(PDO::FETCH_LAZY);
        if ($clan_l[0] != 0)
            $new_log = "<span class='green'>(+$clan_l[0])</span>";
    }
}
if ($user['clan_id'] == $clan['id']) {
#-Клановые боссы-#
    echo '<div class="line_1"></div>';
    if ($clan['level'] >= 10) {
        echo "<li><a href='/clan_boss'><img src='/style/images/clan/boss.png' alt=''/> Клановые боссы</a></li>";
    } else {
        echo "<li><a href='/clan/view/$clan[id]'><img src='/style/images/clan/boss.png' alt=''/> Клановые боссы <span class='white'>(Требуется <img src='/style/images/user/level.png' alt=''/>10 ур.)</span></a></li>";
    }
}
if ($user['clan_id'] == $clan['id']) {
#-История клана-#
    $sel_clan_l = $pdo->prepare("SELECT COUNT(*) FROM `clan_log` WHERE `clan_id` = :clan_id");
    $sel_clan_l->execute(array(':clan_id' => $clan['id']));
    $clan_l = $sel_clan_l->fetch(PDO::FETCH_LAZY);
    echo '<div class="line_1"></div>';
    echo "<li><a href='/clan/history/$clan[id]'><img src='/style/images/clan/history.png' alt=''/> История клана $new_log <span style='float: right; color: #666666;'>[$clan_l[0]]</span></a></li>";
}
#-Рейтинг опыта-#
echo '<div class="line_pys"></div>';
echo "<li><a href='/clan/tour_exp/$clan[id]'><img src='/style/images/user/exp.png' alt=''/> Рейтинг опыта</a></li>";

#-Форум и чат-#
echo '<div class="line_pys"></div>';
#-Считаем разделы-#
$sel_clan_r = $pdo->prepare("SELECT COUNT(*) FROM `clan_razdel` WHERE `clan_id` = :clan_id");
$sel_clan_r->execute(array(':clan_id' => $clan['id']));
$clan_r = $sel_clan_r->fetch(PDO::FETCH_LAZY);
echo "<li><a href='/clan/razdel/$clan[id]'><img src='/style/images/forum/forum.png' alt=''/> Форум <span style='float: right; color: #666666;'>[$clan_r[0]]</span></a></li>";
if ($sel_clan_u->rowCount() != 0) {
#-Выборка сколько сообщение не прочтено-#
    $sel_clan_c = $pdo->prepare("SELECT COUNT(*) FROM `clan_chat` WHERE `clan_id` = :clan_id AND `id` > :chat_id");
    $sel_clan_c->execute(array(':clan_id' => $clan['id'], ':chat_id' => $clan_u['chat_id']));
    if ($sel_clan_c->rowCount() != 0) {
        $clan_c = $sel_clan_c->fetch(PDO::FETCH_LAZY);
        if ($clan_c[0] != 0)
            $clan_chat = "<span class='green'>(+$clan_c[0])</span>";
    }
    echo '<div class="line_1"></div>';
    echo "<li><a href='/clan/chat/$clan[id]'><img src='/style/images/body/clan_chat.png' alt=''/> Чат $clan_chat</a></li>";


#-Меню для основателя и старейшины-#
    if ($clan_u['prava'] == 4 or $clan_u['prava'] == 3) {
        echo '<div class="line_pys"></div>';
        echo "<li><a href='/clan/ad/$clan[id]'><img src='/style/images/clan/ad.png' alt=''/> Объявление</a></li>";
//echo'<div class="line_1"></div>';
//echo"<li><a href='/clan/download_logo/$clan[id]'><img src='/style/images/clan/download_avatar.png' alt=''/> Загрузить логотип</a></li>";
        echo '<div class="line_1"></div>';
        echo "<li><a href='/clan/edit/$clan[id]'><img src='/style/images/clan/edit.png' alt=''/> Редактировать</a></li>";
    }

#-Меню для основателя, старейшины и ветерана-#
    if ($clan_u['prava'] == 4 or $clan_u['prava'] == 3 or $clan_u['prava'] == 2) {
        $sel_clan_app = $pdo->prepare("SELECT COUNT(*) FROM `clan_application` WHERE `clan_id` = :clan_id");
        $sel_clan_app->execute(array(':clan_id' => $clan['id']));
        $amount_app = $sel_clan_app->fetch(PDO::FETCH_LAZY);
        echo '<div class="line_1"></div>';
        echo "<li><a href='/clan/application/$clan[id]'><img src='/style/images/clan/application.png' alt=''/> Заявки в клан <span class='white'>[$amount_app[0]]</span></a></li>";
    }
#-Покидаем клан-#
    echo '<div class="line_1"></div>';
    echo "<li><a href='/clan/exit/$clan[id]'><img src='/style/images/body/error.png' alt=''/> Покинуть клан</a></li>";
}
echo '<div class="line">';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';
//echo '</div><div class="top"></div><a href="/clan/user/376/" class="link"><span class="right">36k <img src="/images/icon/settings.png" width="16px"></span><img src="/images/icon/male.png" width="15px"> GameR <img src="/images/icon/online.png" width="5px"> - <font color="green">Лидер клана</font></a><div class="bottom"></div><div class="line"></div><div class="block center">';
//echo '</div>';
echo'
<a href="/clan/users/'.$clan[id].'"> <div class="bottom"></div><hr><div class="center">Состав клана:</a> <span class="white">'.$amount_u[0].'/'.$clan[quatity_user].'</div><hr>
<div class="line">';
echo '<div class="body_list">';
echo '<div class="menulist">
';
echo '<div class="line">';
echo '</div>';
#-МЕНЮ КЛАНА-#

echo '<div class="menulist">';
#-Количество участников клана-#
$sel_clan_u_c = $pdo->prepare("SELECT COUNT(*) FROM `clan_users` WHERE `clan_id` = :clan_id");
$sel_clan_u_c->execute(array(':clan_id' => $clan['id']));
$amount = $sel_clan_u_c->fetch(PDO::FETCH_LAZY);
#-Действия постраничной навигации-#
$num = 10;
$page = $_GET['page'];
$posts = $amount[0];
$total = intval(($posts - 1) / $num) + 1;
$page = intval($page);
if (empty($page) or $page < 0)
    $page = 1;
if ($page > $total)
    $page = $total;
$start = $page * $num - $num;

$getClanUsers = $pdo->prepare("SELECT `clan_users`.`prava`, `users`.`id`, `users`.`nick`, `users`.`time_online`, `users`.`pol` FROM clan_users, users WHERE clan_users.clan_id = :clan_id AND clan_users.user_id = users.id ORDER BY clan_users.prava DESC LIMIT $start, $num");
$getClanUsers->execute(array(':clan_id' => $clan['id']));
$showUsers = $getClanUsers->fetchAll(PDO::FETCH_ASSOC);

foreach ($showUsers as $userID) {
    echo '<div>';
    echo '<a href="/hero/' . $userID['id'] . '">' . online($userID['time_online']) . ' ' . htmlspecialchars($userID['nick']) . ' - ' . rankName($userID['prava']) . '</a>';
    echo '</div>';

}
echo '</div>';
if ($posts > $num) {
    echo '<div class="body_list">';
    echo '<div class="line_1"></div>';
    pages($posts, $total, '');
    echo '</div>';
}

echo '<div class="menulist">';

echo '</div>';
echo '</div>';





require_once H . 'system/footer.php';
/*
#-Атаковать клан-#
if($clan['id'] != $user['clan_id']){
#-Проверка прав-#
$sel_clan_u_m = $pdo->prepare("SELECT `id`, `clan_id`, `user_id`, `prava` FROM `clan_users` WHERE `user_id` = :user_id AND `clan_id` = :clan_id AND (`prava` = 4 OR `prava` = 3)");
$sel_clan_u_m->execute(array(':user_id' => $user['id'], ':clan_id' => $user['clan_id']));
if($sel_clan_u_m-> rowCount() != 0){
$min_lvl = $clan['level'] - 10;
$max_lvl = $clan['level'] + 10;
#-Выборка нашего клана-#
$sel_clan_m = $pdo->prepare("SELECT `id`, `level` FROM `clan` WHERE `id` = :clan_id AND `level` >= 10 AND `level` >= :min_lvl AND `level` <= :max_lvl");
$sel_clan_m->execute(array(':clan_id' => $user['clan_id'], ':min_lvl' => $min_lvl, ':max_lvl' => $max_lvl));
if($sel_clan_m-> rowCount() != 0){
if($clan['level'] >= 10){
#-Подтверждение-#
if($_GET['conf'] == 'atk'){
echo'<a href="/" class="button_green_a">Атаковать</a>';	
}else{
echo'<a href="/clan/view/'.$clan['id'].'?conf=atk" class="button_red_a">Атаковать</a>';
}
echo'<div style="padding-top: 5px;"></div>';
}
}
}
}
*/
?>