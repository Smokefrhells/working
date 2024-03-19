<?php
/**
 * Created by PhpStorm.
 * User: A.Gorbunov
 * Date: 28.10.2019
 * Time: 13:02
 */

class Base {

    public $countTimeNewPlayer = 30;
    private $countPlayers = 5;
    private $countTimeAddBot = 20;
    private $woman = 2;

    public function db() {
        global $pdo;
        return $pdo;
    }

    public function infoBots(array $user) {
        $this->woman = $user['pol'];
        return [

            'avatar' => '',
            'time_online' => time(),
            'pol' => $this->woman,
            'nick' => 'Бот',
            'level' => $user['level'],
            'sila' => $this->percent($user['sila']),
            's_sila' => $this->percent($user['s_sila']),
            'sila_bonus' => $user['sila_bonus'],
            'zashita' => $this->percent($user['zashita']),
            's_zashita' => $this->percent($user['s_zashita']),
            'zashita_bonus' => $user['zashita_bonus'],
            'health' => $this->percent($user['health']),
            's_health' => $this->percent($user['s_health']),
            'health_bonus' => $user['health_bonus'],
            'type' => true
        ];
    }

    public function randDamage($health_uron, $sex) {
        $this->woman = $sex;
        return $this->percent($health_uron);
    }

    private function percent($num) {
        if ($this->woman == 1) {
            return floor($num + ($num / 100 * 10));
        }
        return floor($num - ($num / 100 * 10));
    }

    public function checkUserColiseum(int $id, array $user) {
        $pdo = $this->db();
        $sel_coliseum_me = $pdo->prepare("SELECT COUNT(*) FROM `coliseum` WHERE `battle_id` = :battle_id");
        $sel_coliseum_me->execute([':battle_id' => $id]);
        $coliseum_me = $sel_coliseum_me->fetchColumn();
        $this->woman = $user['pol'];

        $check = $pdo->query("SELECT * FROM `coliseum` WHERE `battle_id` = '" . $id . "' ORDER BY `id` DESC LIMIT 1")->fetch();

        if ($coliseum_me < $this->countPlayers && !empty($check) && $check['last_add'] < time()) {
            $userAllHealth = $this->percent($user['health']) + $this->percent($user['s_health']) + $user['health_bonus']; //Здоровье игрока

            $ins_coliseum = $pdo->prepare("INSERT INTO `coliseum` SET `level` = :level, `user_health` = :user_health, `user_t_health` = :user_health, `user_id` = :user_id, `battle_id` = :battle_id, `statys` = :statys, `last_add` = :last_add");
            $ins_coliseum->execute([
                ':level'       => $user['level'],
                ':user_health' => $userAllHealth,
                ':user_id'     => time(),
                ':battle_id'   => $id,
                ':statys'      => 0,
                ':last_add'    => time() + $this->countTimeAddBot
            ]);
            return true;
        }
        return false;

    }

    /**
     * записываем значение страницы
     * @param $data
     * @param $topic_id
     */
    public function setPage($data, $topic_id) {
        $pdo = $this->db();
        $page = $this->getCurrentPage();
        if (empty($data->num_page)) {
            $arrJson = json_encode([
                $topic_id => $page
            ]);
        } else {
            $arrJson = json_decode($data->num_page, true);
            $arrJson[$topic_id] = $page;
            $arrJson = json_encode($arrJson);
        }

        $updPage = $pdo->prepare("UPDATE `forum_navigation` SET `num_page` = :num_page WHERE `user_id` = :user_id");
        $updPage->execute([':num_page' => $arrJson, ':user_id' => $data->user_id]);
    }

    public function getPage($user, $topic_id) {
        $pdo = $this->db();
        $sel_f_nav = $pdo->prepare("SELECT `num_page` FROM `forum_navigation` WHERE `user_id` = :user_id");
        $sel_f_nav->execute([':user_id' => $user]);
        $data = $sel_f_nav->fetch(PDO::FETCH_LAZY);


        $data = json_decode($data->num_page, true);
        if (empty($data[$topic_id])) {
            return 1;
        }
        return $data[$topic_id];
    }

    public function getCurrentPage() {
        return !empty($_GET['page']) ? intval($_GET['page']) : 0;
    }
}