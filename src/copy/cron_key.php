<?php
require_once '../system/system.php';
require_once H . 'copy/copy_func.php';
$cluch = fch("SELECT * FROM `cluch` ORDER BY `id` DESC LIMIT 1");
$time = strtotime("Sunday 20:00");
$date = date('d.m.y');

if (!$cluch) {
    qry("INSERT INTO `cluch` SET `last` = ?", array($time));
    die('Запись в базу создана!');
}
if ($cluch['last'] - 60 * 5 <= time()) {
    $q = acc("SELECT * FROM `users` WHERE `kluch` >= ? ORDER BY `kluch` DESC", array(0));
    $message = '[center][color=goldenrod][b]Турнир по ключам окончен![/b]
    
    [b]Победители:[/b][/center][/color]';

    if (is_array($q)) {
        $i = 0;
        foreach ($q as $u) {
            $i++;
            $silver = ($u['kluch'] * 500);
            $text = 'Турнир по [image=16px]/images/icon/kluch.png[/image] ключам окончен! Вы заняли ' . $i . ' место, награда: [image=16px]/images/icon/silver.png[/image] ' . $silver . ' серебра!';
            if ($i <= 10) {
                if ($i == 1)
                    $gold = 1000;
                if ($i == 2)
                    $gold = 900;
                if ($i == 3)
                    $gold = 800;
                if ($i == 4)
                    $gold = 700;
                if ($i == 5)
                    $gold = 600;
                if ($i == 6)
                    $gold = 500;
                if ($i == 7)
                    $gold = 400;
                if ($i == 8)
                    $gold = 300;
                if ($i == 9)
                    $gold = 200;
                if ($i == 10)
                    $gold = 100;
                $message .= ''.$i.'.[url=/hero/'.$u['id'].'/]'.$u['nick'].'[/url] ([image=16px]/images/icon/kluch.png[/image] ' . $u['kluch'] . ') - [image=16px]/images/icon/gold.png[/image] ' . $gold . ' золота
    			';
                $text = 'Турнир по [image=16px]/images/icon/kluch.png[/image] ключам окончен! Вы заняли ' . $i . ' место, награда: [image=16px]/images/icon/gold.png[/image] ' . $gold . ' золота и [image=16px]/images/icon/silver.png[/image] ' . $silver . ' серебра!';
                qry("UPDATE `users` SET `gold` = ? WHERE `id` = ?", array($u['gold'] + $gold, $u['id']));
            }
            qry("UPDATE `users` SET `silver` = ? WHERE `id` = ?", array($u['silver'] + $silver, $u['id']));
        }
        $message .= '[color=goldenrod]Остальные игроки получили по 500 серебра за [image=16px]/images/icon/kluch.png[/image] ключ[/color]';
    }
    $name = 'Еженедельный турнир от ' . $date . '';
    $ins_news = $pdo->prepare("INSERT INTO `news` SET `title` = :title, `msg` = :msg, `user_id` = :user_id, `time` = :time");
    $ins_news->execute(array(':title' => $name, ':msg' => $message, ':user_id' => 2, ':time' => time()));

    qry("UPDATE `users` SET `news_read` = ?, `kluch` = ?", array(1, 0));
    qry("INSERT INTO `cluch` SET `last` = ?", array($time));
    echo bb_code($message, 3);
} else {
    echo 'Еще рано';
}