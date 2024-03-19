<?php
require_once '../../system/system.php';
only_reg();
save();
#-Комментирование в форуме-#
switch ($act) {
    case 'comm':
        if ($user['ban'] == 0 and $user['level'] >= 30) {
            if (isset($_POST['comment']) and isset($_GET['topic']) and isset($_GET['page'])) {
                $comment = check($_POST['comment']); //Комментарий
                $topic_id = check($_GET['topic']); //ID топика
                $page = check($_GET['page']); //Страница
                #-Проверяем существует ли такой топик-#
                $sel_topic = $pdo->prepare("SELECT * FROM `forum_topic` WHERE `id` = :id");
                $sel_topic->execute([':id' => $topic_id]);
                if ($sel_topic->rowCount() != 0) {
                    #-Время обращения-#
                    if (isset($_SESSION["forum_time"])) {
                        $t = ((int)(time() - $_SESSION["forum_time"]));
                        if ($t < 5)
                            $error = 'Не так часто!';
                    }
                    $_SESSION["forum_time"] = time();
                    #-Проверяем данные-#
                    #-Комментарий-#
                    if (preg_match('/[\^\<\>\&\`\$]/', $_POST['comment']))
                        $error = 'Некорректный комментарий!';
                    if (mb_strlen($comment) < 1)
                        $error = 'Пустой комментарий!';
                    if (mb_strlen($comment) > 5000)
                        $error = 'Слишком длинный комментарий!';
                    #-Если нет ошибок-#
                    if (!isset($error)) {
                        $sel_comment_t2 = $pdo->prepare("SELECT COUNT(*) FROM `forum_comment` WHERE `topic_id` = :topic_id");
                        $sel_comment_t2->execute([':topic_id' => $topic_id]);
                        $comment_t2 = $sel_comment_t2->fetch(PDO::FETCH_LAZY);
                        $num = 10;
                        $posts = $comment_t2[0];
                        $total = intval(($posts - 1) / $num) + 1;

                        $ins_forum_com = $pdo->prepare("INSERT INTO `forum_comment` SET `comment` = :comment, `topic_id` = :topic_id, `user_id` = :user_id, `time` = :time");
                        $ins_forum_com->execute([':comment' => $comment, ':topic_id' => $topic_id, ':user_id' => $user['id'], ':time' => time()]);
                        header("Location: /forum_topic/read/$topic_id?page=$total");
                    } else {
                        header("Location: /forum_topic/read/$topic_id");
                        $_SESSION['err'] = $error;
                        exit();
                    }
                } else {
                    header("Location: /forum_razdel");
                    $_SESSION['err'] = 'Топик не найден!';
                    exit();
                }
            } else {
                header("Location: /forum_topic/read/$topic_id");
                $_SESSION['err'] = 'Данные отсутствуют! Обратитесь к администрации.';
                exit();
            }
        } else {
            header('Location: /forum_razdel');
            $_SESSION['err'] = 'Вы не можете создать топик!';
            exit();
        }
}
?>