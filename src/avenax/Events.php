<?php
/**
 * Created by PhpStorm.
 * User: A.Gorbunov
 * Date: 28.10.2019
 * Time: 12:55
 */

include_once 'RandomProbability.php';
include_once 'Base.php';

class Events extends Base {


    /**
     * В арене за убийство выпадение 1-3 хрестика рандомно
     * В колизее, башнях, замках, 1-5 за проиграш, 5-10 за выиграш
     * В боссах 1-3 рандомно
     * А шанс на выпадение 60% везде
     * @param $type
     * @param $id
     * @param string $status
     * @return bool
     */
    public static function randomItem($type, $id, $status = 'lose') {

        $randomProbabilty = new RandomProbability();
        $randomProbabilty->add('yes', 0);
        $randomProbabilty->add('no', 100);
        if ($randomProbabilty->getResult() == 'no') {
            return false;
        }

        $count = 0;
        switch ($type) {
            case 'arena':
            case 'boss':
            $count = mt_rand(1, 3);
                break;
            case 'other':
                $count = mt_rand(1, 5);
                if ($status == 'win') {
                    $count = mt_rand(5, 10);
                }
                break;
        }
        self::updateItemEvents($count, $id);
        return true;
    }

    public static function updateItemEvents($count, $id) {

        $base = new Base();
        $db = $base->db();

        $smtp = $db->prepare("UPDATE `users` SET `eventItem` = `eventItem` + :eventItem WHERE `id` = :id LIMIT 1");
        $smtp->execute([':eventItem' => $count,  ':id' => $id]);
        $item = self::setEvents();
        $_SESSION['ok'] = "{$item['name']}. Вы успешно получили +{$count} <img width='18' src='{$item['img']}'>";
    }


    public static function getConvert($type, $id) {
        $base = new Base();
        $db = $base->db();
        $need = self::setShop();
        $event = self::setEvents();

        if ($type == 'gold') {
            $smtp = $db->prepare("UPDATE `users` SET `eventItem` = `eventItem` - :eventItem, `gold` = `gold` + :gold WHERE `id` = :id");
            $smtp->execute([':eventItem' => $need['gold']['need'], ':gold' => $need['gold']['get'], ':id' => $id]);
            $_SESSION['ok'] = "Награда: <img src='/style/images/many/gold.png' alt=''>+{$need['gold']['get']}, <img width='16' src='{$event['img']}' alt=''>-{$need['gold']['need']}";
            header('Location: /shopEvent');
            exit();
        }
        $getItems = $db->prepare("SELECT * FROM `weapon` WHERE `no_magaz` = :no_magaz");
        $getItems->execute([':no_magaz' => 15]);
        $list = $getItems->fetchAll(PDO::FETCH_ASSOC);

        $arr = [];
        foreach ($list as $value) {
            $arr[$value['id']] = $value;
        }

        if (!empty($_GET['buy']) && array_key_exists($_GET['buy'], $arr)) {
            $smtp = $db->prepare("UPDATE `users` SET `eventItem` = `eventItem` - :eventItem WHERE `id` = :id");
            $smtp->execute([':eventItem' => $need['item']['need'], ':id' => $id]);
            $_SESSION['ok'] = 'Успешная покупка!';

            $itemUser = $arr[$_GET['buy']];
            $setItem = $db->prepare("INSERT INTO `weapon_me` (`user_id`, `weapon_id`, `state`, `time`, `b_sila`, `b_zashita`, `b_health`, `type`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $setItem->execute([
                $id, $itemUser['id'], 0, time(), $itemUser['sila'], $itemUser['zashita'], $itemUser['health'], $itemUser['type']
            ]);

            header('Location: /shopEvent');
            exit();
        }



        foreach ($arr as $item) {
            ?>
            <div class="page">
                <div class="img_weapon"><img src="<?= $item['images']; ?>" class="weapon_1" alt=""></div>
                <div class="weapon_setting"><span style="color: #bfbfbf;"><b><?= $item['name']; ?></b> [<?= $item['level']; ?> ур.]</span><br>
                    <div class="weapon_param"><img src="/style/images/user/sila.png" alt=""><?= $item['sila']; ?> <img
                            src="/style/images/user/zashita.png" alt=""><?= $item['zashita']; ?> <img src="/style/images/user/health.png"
                                                                                  alt=""><?= $item['health']; ?>
                    </div>
                </div>
                <div style="padding-top: 20px;"></div>
                <a href="/shopEvent?act=item&buy=<?= $item['id']; ?>" class="button_green_a">Купить</a>
                <div style="padding-top: 5px;"></div>
            </div>
            <?php
        }

    }

    /**
     * На артефакты и золото
     * 1000 хрестиков(или как то по своему назови) обмен на артефакт
     * 5000 хрестиков обмен на 10000 золота
     */
    public static function setShop() {
        return [
            'item' => [
                'need' => 1000
            ],
            'gold' => [
                'need' => 5000,
                'get' => 10000
            ]
        ];
    }

    public static function printr() {
        $event = self::setEvents();
        $need = self::setShop();
        return [
            1 => '<a href="/shopEvent?act=gold"><img src="/style/images/body/obmenik.png" alt=""> Обменять <img src="' . $event['img'] . '" alt=""> ' . $need['gold']['need'] . ' на <img src="/style/images/many/gold.png" alt="">' . $need['gold']['get'] . '</a>',
            2 => '<a href="/shopEvent?act=item"><img src="/style/images/body/obmenik.png" alt=""> Обменять <img src="' . $event['img'] . '" alt=""> ' . $need['item']['need'] . ' на артефакт</a>',
        ];
    }

    public static function setEvents() {
        return [
            'name' => 'Хэллоуин',
            'item' => 'Тыквы',
            'img'  => '/style/images/body/helloween.png'
        ];
    }
}