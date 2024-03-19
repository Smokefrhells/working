<?php
require_once '../../system/system.php';
$head = 'Смайлы';
echo only_reg();
require_once H.'system/head.php';
echo'<div class="page">';
echo'<div style="padding: 5px;">';
echo''.smiles_not_js().'';
echo'</div>';
echo'</div>';
require_once H.'system/footer.php';
?>