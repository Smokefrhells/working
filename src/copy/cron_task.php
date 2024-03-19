<?php
require_once '../system/system.php';
require_once H . 'copy/copy_func.php';
qry("UPDATE `quest_user` SET `ok` = ?, `kolls` = ? WHERE `ok` = ?", array(0, 0, 2));
qry("UPDATE `quest_clans` SET `ok` = ?, `kolls` = ? WHERE `ok` = ?", array(0, 0, 2));
echo 'success';
