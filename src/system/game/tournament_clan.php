<?php
require_once H.'system/system.php';
#-Место клана в турнире-#

#-Статуэтки-#
$sel_count_f = $pdo->prepare("SELECT COUNT(*) FROM `clan` WHERE `figur` >= :figur AND `level` = 150 AND `id` != :clan_id");
$sel_count_f->execute(array(':figur' => $clan_figur, ':clan_id' => $clan_id));
$amount_f = $sel_count_f->fetch(PDO::FETCH_LAZY);
$sel_count_fm = $pdo->prepare("SELECT COUNT(*) FROM `clan` WHERE `figur` = :figur AND `id` > :clan_id AND `level` = 150 AND `id` != :clan_id");
$sel_count_fm->execute(array(':figur' => $clan_figur, ':clan_id' => $clan_id));
$amount_fm = $sel_count_fm->fetch(PDO::FETCH_LAZY);
$amount_1 = $amount_f[0]-$amount_fm[0];
if($clan_figur > 0){
$mesto_figur = $amount_1+1;
}else{
$mesto_figur = 0;
}
?>