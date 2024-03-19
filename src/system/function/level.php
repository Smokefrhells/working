<?php
#-Уровень кланов-#
function clan_level() {
global $user;
if($user['level'] < 15){
header('Location: /');
$_SESSION['err'] = 'Кланы доступны с 15 ур.';
exit();
}
}
#-Уровень боссов-#
function boss_level() {
global $user;
if($user['level'] < 5){
header('Location: /');
$_SESSION['err'] = 'Боссы доступны с 5 ур.';
exit();
}
}
#-Уровень дуэлей-#
function duel_level() {
global $user;
if($user['level'] < 5){
header('Location: /');
$_SESSION['err'] = 'Дуэли доступны с 5 ур.';
exit();
}
}
#-Уровень обменника-#
function exchanger_level() {
global $user;
if($user['level'] < 10){
header('Location: /');
$_SESSION['err'] = 'Обменник доступен с 10 ур.';
exit();
}
}
#-Уровень создания форума-#
function forum_level() {
global $user;
if($user['level'] < 30){
header('Location: /');
$_SESSION['err'] = 'Доступно с 30 ур.';
exit();
}
}
#-Уровень чата-#
function chat_level() {
global $user;
if($user['level'] < 15){
header('Location: /');
$_SESSION['err'] = 'Чат доступен с 15 ур.';
exit();
}
}
#-Уровень почты-#
function mail_level() {
global $user;
if($user['level'] < 20){
header('Location: /');
$_SESSION['err'] = 'Почта доступена с 20 ур.';
exit();
}
}
#-Уровень кузнец-#
function blacksmith_level() {
global $user;
if($user['level'] < 3){
header('Location: /');
$_SESSION['err'] = 'Кузнец доступен с 3 ур.';
exit();
}
}
#-Уровень замки-#
function zamki_level() {
global $user;
if($user['level'] < 10){
header('Location: /');
$_SESSION['err'] = 'Замки доступены с 10 ур.';
exit();
}
}
#-Уровень колизей-#
function coliseum_level() {
global $user;
if($user['level'] < 13){
header('Location: /');
$_SESSION['err'] = 'Колизей доступен с 13 ур.';
exit();
}
}
#-Загрузка аватара-#
function avatar_level() {
global $user;
if($user['level'] < 30){
header('Location: /');
$_SESSION['err'] = 'Загрузка аватара доступна с 30 ур.';
exit();
}
}
#-Подарки-#
function gift_level() {
global $user;
if($user['level'] < 10){
header('Location: /');
$_SESSION['err'] = 'Подарки доступны с 10 ур.';
exit();
}
}
#-Рейд-#
function reid_level() {
global $user;
if($user['level'] < 20){
header('Location: /');
$_SESSION['err'] = 'Рейд доступен с 20 ур.';
exit();
}
}
#-Аукцион-#
function auction_level() {
global $user;
if($user['level'] < 10){
header('Location: /');
$_SESSION['err'] = 'Аукцион доступен с 10 ур.';
exit();
}
}
#-Башни-#
function towers_level() {
global $user;
if($user['level'] < 25){
header('Location: /');
$_SESSION['err'] = 'Башни доступены с 25 ур.';
exit();
}
}
#-Питомцы-#
function pets_level() {
global $user;
if($user['level'] < 20){
header('Location: /');
$_SESSION['err'] = 'Питомцы доступены с 25 ур.';
exit();
}
}
#-Передача снаряжения-#
function armor_transfer_level() {
global $user;
if($user['level'] < 60){
header('Location: /');
$_SESSION['err'] = 'Передача сняряжения доступна с 60 ур.';
exit();
}
}



function bon_exp($exp) {
    global $user;

    // % опыта от артефактов
    $exp_bon = 0;
    if (!empty($user['bon_exp'])) {
        $exp_bon = ($exp/ 100) * $user['bon_exp'];
    }
    return floor($exp + $exp_bon);
}

function bon_mon($mon) {
    global $user;

    // % опыта от артефактов
    $mon_bon = 0;
    if (!empty($user['bon_mon'])) {
        $mon_bon = ($mon/ 100) * $user['bon_mon'];
    }
    return floor($mon + $mon_bon);
}

function rankName($rank) {

    $prava = 'Новичок';

    if ($rank == 1) {
        $prava = 'Боец';
    }
    if ($rank == 2) {
        $prava = 'Ветеран';
    }
    if ($rank == 3) {
        $prava = 'Старейшина';
    }
    if ($rank == 4) {
        $prava = 'Основатель';
    }
    return $prava;
}