<?php
require_once '../../system/system.php';
only_reg();
if (isset($_GET['id'])) {
    $razdel_id = check($_GET['id']);
    $sel_f_razdel = $pdo->prepare("SELECT * FROM `forum_razdel` WHERE `id` = :razdel_id");
    $sel_f_razdel->execute([':razdel_id' => $razdel_id]);
    $razdel = $sel_f_razdel->fetch(PDO::FETCH_LAZY);
    #-Есть ли раздел-#
    if ($sel_f_razdel->rowCount() != 0) {
        $head = $razdel['name'];
    } else {
        header('Location: /forum_razdel');
        exit();
    }
} else {
    header('Location: /forum_razdel');
    exit();
}
require_once H . 'system/head.php';
require_once H . 'avenax/Base.php';

$forPage = new Base();
echo '<div class="body_list">';
echo '<div class="menulist">';

#-Навигация по форуму-#
$sel_f_nav = $pdo->prepare("SELECT * FROM `forum_navigation` WHERE `user_id` = :user_id");
$sel_f_nav->execute([':user_id' => $user['id']]);
if ($sel_f_nav->rowCount() != 0) {
    $nav_f = $sel_f_nav->fetch(PDO::FETCH_LAZY);
}


#-Выборка разделов форума-#
$sel_forum = $pdo->prepare("SELECT * FROM `forum_razdel` WHERE `pod` = :pod");
$sel_forum->execute([':pod' => $razdel_id]);
#-Если есть записи-#
if ($sel_forum->rowCount() != 0) {
    #-Выводим разделы форума-#
    while ($forum = $sel_forum->fetch(PDO::FETCH_LAZY)) {
        $f_num = 0; //Начальное кол-во тем
        #-Считаем топики данного раздела-#
        $sel_c_topic = $pdo->prepare("SELECT COUNT(*) FROM `forum_topic` WHERE `razdel_id` = :pod");
        $sel_c_topic->execute([':pod' => $forum['id']]);
        $c_topic = $sel_c_topic->fetch(PDO::FETCH_LAZY);
        #-Если есть топики-#
        if ($c_topic[0] > 0) {
            for ($i = 0; $i <= $c_topic[0]; $i++) {
                $sel_topic = $pdo->prepare("SELECT `id`, `razdel_id` FROM `forum_topic` WHERE `razdel_id` = :razdel_id ORDER BY `id` LIMIT $i, 1");
                $sel_topic->execute([':razdel_id' => $forum['id']]);
                $topic = $sel_topic->fetch(PDO::FETCH_LAZY);
                if (preg_match('/\{' . $topic['id'] . '\}/', $nav_f['topic'])) {
                    $f_num = $f_num + 1;
                }
            }
        }
        #-Если кол-во топиков одинаковое, то все прочитаны-#
        if ($f_num == $c_topic[0]) {
            $f_inf = $c_topic[0];
        } else {
            $f_inf = "<span class='green'>+" . ($c_topic[0] - $f_num) . "</span>";
        }
        echo '<div class="line_1"></div>';
        echo "<li><a href='/forum_topic/" . $forum['id'] . "'><img src='/style/images/forum/forum.png' alt=''/> <span class='gray'>" . $forum['name'] . "</span> <div style='float: right; color:#666666;'>$f_inf</div></a></li>";
    }
}

#-Считаем количество топиков которые есть в разделе-#
$sel_c_forum_t = $pdo->prepare("SELECT COUNT(*) FROM `forum_topic` WHERE `razdel_id` = :razdel_id");
$sel_c_forum_t->execute([':razdel_id' => $razdel_id]);
$amount = $sel_c_forum_t->fetch(PDO::FETCH_LAZY);
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

#-Закрепленные топики-#
$sel_forum_t1 = $pdo->prepare("SELECT * FROM `forum_topic` WHERE `razdel_id` = :razdel_id AND `verh` = 1 ORDER BY `time` DESC LIMIT $start, $num");
$sel_forum_t1->execute([':razdel_id' => $razdel_id]);
while ($forum_t1 = $sel_forum_t1->fetch(PDO::FETCH_LAZY)) {
    #-Считаем комментарии топика-#
    $sel_comment_t1 = $pdo->prepare("SELECT COUNT(*) FROM `forum_comment` WHERE `topic_id` = :topic_id");
    $sel_comment_t1->execute([':topic_id' => $forum_t1['id']]);
    if ($sel_comment_t1->rowCount() != 0) {
        $comment_t1 = $sel_comment_t1->fetch(PDO::FETCH_LAZY);
    }

    $total = $forPage->getPage($user['id'], $forum_t1['id']);
    #-Читали этот топик или нет-#
    if (preg_match('/\{' . $forum_t1['id'] . '\}/', $nav_f['topic'])) {
        $color = 'gray';
    } else {
        $color = 'green';
    }
    echo '<div class="line_1"></div>';
    echo "<li><a href='/forum_topic/read/$forum_t1[id]?page={$total}'><img src='/style/images/forum/topic_attach.png' alt=''/> <span class='$color'><b>$forum_t1[title]</b></span><div style='float: right; color:#666666;'>$comment_t1[0]</div></a></li>";
}

#-Обычные топики-#
$sel_forum_t2 = $pdo->prepare("SELECT * FROM `forum_topic` WHERE `razdel_id` = :razdel_id AND `verh` = 0 ORDER BY `time` DESC LIMIT $start, $num");
$sel_forum_t2->execute([':razdel_id' => $razdel_id]);
while ($forum_t2 = $sel_forum_t2->fetch(PDO::FETCH_LAZY)) {
    #-Считаем комментарии топика-#
    $sel_comment_t2 = $pdo->prepare("SELECT COUNT(*) FROM `forum_comment` WHERE `topic_id` = :topic_id");
    $sel_comment_t2->execute([':topic_id' => $forum_t2['id']]);
    if ($sel_comment_t2->rowCount() != 0) {
        $comment_t2 = $sel_comment_t2->fetch(PDO::FETCH_LAZY);
    }
    $total = $forPage->getPage($user['id'], $forum_t2['id']);
    #-Читали этот топик или нет-#
    if (preg_match('/\{' . $forum_t2['id'] . '\}/', $nav_f['topic'])) {
        $color = 'gray';
    } else {
        $color = 'green';
    }
    #-Закрыт или нет-#
    if ($forum_t2['close'] == 0) {
        $img = '/style/images/forum/topic_open.png';
    } else {
        $img = '/style/images/forum/topic_close.png';
    }
    echo '<div class="line_1"></div>';
    echo "<li><a href='/forum_topic/read/$forum_t2[id]?page={$total}'><img src='$img' alt=''/> <span class='$color'>$forum_t2[title]</span><div style='float: right; color:#666666;'>$comment_t2[0]</div></a></li>";
}

#-Отмечаем все как прочитаное-#
echo '<div class="line_1"></div>';
echo "<li><a href='/forum_act?act=read_all&razdel=$razdel[id]'><img src='/style/images/body/ok.png' alt=''/> <span class='yellow'>Отметить всё как прочитанное</span></a></li>";
#-Если нет бана то можем создавать топики-#
if ($user['ban'] == 0 and $user['level'] >= 30) {
    echo '<div class="line_1"></div>';
    if ($user['topic_time'] == 0 || $user['prava'] > 0) {
        echo "<li><a href='/topic_add?razdel=$razdel_id'><img src='/style/images/forum/topic_add.png' alt=''/> <span class='yellow'>Создать топик</span></a></li>";

    } else {
        $topic_ost = $user['topic_time'] - time();
        echo "<li><a href='/forum_topic/$razdel_id'><img src='/style/images/body/time.png' alt=''/> <span class='gray'>" . (int)($topic_ost / 60 % 60) . " мин. " . (int)($topic_ost % 60) . " сек.</span></a></li>";
    }
}
if ($user['prava'] > 0) {
    echo "<li><a href='/razdel_add?razdel=$razdel_id'><img src='/style/images/forum/topic_add.png' alt=''/> <span class='yellow'>Создать раздел</span></a></li>";
}
echo '<div class="line_1"></div>';
echo "<li><a href='/forum_razdel'><img src='/style/images/body/back.png' alt=''/> Разделы</a></li>";
echo '</div>';
#-Отображение постраничной навигации-#
if ($posts > $num) {
    $action = '';
    echo '<div class="line_1"></div>';
    pages($posts, $total, $action);
}
echo '</div>';
require_once H . 'system/footer.php';
?>