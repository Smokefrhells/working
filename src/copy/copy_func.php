<?php
/**
 * Created by PhpStorm.
 * User: Avenax
 * Date: 24.09.2019
 * Time: 21:42
 */

function fl($m) {
    if (!is_numeric($m)) {
        $m = htmlspecialchars($m);
    } else {
        $m = intval($m);
        $m = abs($m);
    }
    return $m;
} /* FILTER */
/* Работаем с базой данных */
function qry($sql, $params = array()) {
    global $pdo;
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt;
} /* Для обычного запроса */
function cnt($sql, $params = array()) {
    global $pdo;
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $stmt = $stmt->rowCount();
    return $stmt;
} /* Для вывода запроса с подсчетом */
function obj($sql, $params = array()) {
    global $pdo;
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $stmt = $stmt->FetchAll(PDO::FETCH_OBJ);
    return $stmt;
} /* Для вывода объектного массива */
function acc($sql, $params = array()) {
    global $pdo;
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $stmt = $stmt->FetchAll(PDO::FETCH_ASSOC);
    return $stmt;
} /* Для вывода ассоциотивного массива */
function fch($sql, $params = array()) {
    global $pdo;
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $stmt = $stmt->fetch();
    return $stmt;
}
/* ВЫЗОВ ФУНКЦИИ ПРОВЕРКИ ПАРАМЕТРОВ ГЕРОЯ */
function tl($tl){
    $d=3600*24;
    $day=floor($tl/$d);
    $tl=$tl-($d*$day);

    $hour=floor($tl/3600);
    $tl=$tl-(3600*$hour);

    $minute=floor($tl/60);
    $tl=$tl-(60*$minute);

    $second=floor($tl);

    $dayt="".($day>0?"$day д. ":null)."";
    $hourt="".($hour>0?"$hour ч. ":null)."";
    $minutet="".($minute>0?"$minute м. ":null)."";
    $secondt="".($second>0?"$second с. ":null)."";

    if($day>0){
        $minutet=NULL;
        $secondt=NULL;
    }
    if($hour>0 && $day==0){
        $secondt=NULL;
        $dayt=NULL;
    }

    return "$dayt$hourt$minutet$secondt";
}
function get_power($id){
    $get = fch("SELECT `sila_bonus`, `s_sila`, `sila` FROM `users` WHERE `id` = ? LIMIT 1", array($id)); // Выбираем пользователя

    $str = $get['sila'] + $get['s_sila'] + $get['sila_bonus'];
    return $str;
}

function get_block($id){
    $get = fch("SELECT `zashita_bonus`, `s_zashita`, `zashita` FROM `users` WHERE `id` = ? LIMIT 1", array($id)); // Выбираем пользователя

    $str = $get['zashita'] + $get['s_zashita'] + $get['zashita_bonus'];
    return $str;
}
function get_health($id){
    $get = fch("SELECT `health_bonus`, `s_health`, `health` FROM `users` WHERE `id` = ? LIMIT 1", array($id)); // Выбираем пользователя

    $str = $get['health'] + $get['s_health'] + $get['health_bonus'];
    return $str;
}