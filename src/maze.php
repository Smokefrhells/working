<?php
require_once '../../system/system.php';
$head = 'Пирамида Тэпа';
echo only_reg();
require_once H.'system/head.php';
echo'<div class="page">';


?>
<style>
.p_re{position:relative;}
.p_ab{position:absolute;left:0;right:0;}
.block_s{width: 63px; height: 63px;}
.maze {width: 315px;height: 315px;margin: 0 auto;}
</style>



<script type="text/javascript" language="javascript">
function open_maze(block,block2) {

var xhr = new XMLHttpRequest();
xhr.open('GET','/maze_func.php?m=open&block='+block+'&block2='+block2, true);
xhr.send();
xhr.onreadystatechange = function() { 
  if (xhr.readyState == 4) {
if (xhr.status != 200) {
location.reload();
} else {
stats = JSON.parse(xhr.responseText);
if(typeof(stats.maze) != "undefined" && stats.maze !== null) {
document.getElementById('maze').innerHTML =stats.maze;
document.getElementById('kol_kluch').innerHTML =stats.kol_kluch;
}
if(typeof(stats.maze_text) != "undefined" && stats.maze_text !== null) {
document.getElementById('error').innerHTML =stats.maze_text;
}

}
}
}
}

function lvl_maze(block,block2) {

var xhr = new XMLHttpRequest();
xhr.open('GET','/maze_func.php?m=lvl&block='+block+'&block2='+block2, true);
xhr.send();
xhr.onreadystatechange = function() { 
  if (xhr.readyState == 4) {
if (xhr.status != 200) {
location.reload();
} else {
stats = JSON.parse(xhr.responseText);
if(typeof(stats.maze_text) != "undefined" && stats.maze_text !== null) {
document.getElementById('error').innerHTML =stats.maze_text;
} else {
location.reload();
}


}
}
}
}


function kluch_maze(block,block2) {

var xhr = new XMLHttpRequest();
xhr.open('GET','/maze_func.php?m=kluch&block='+block+'&block2='+block2, true);
xhr.send();
xhr.onreadystatechange = function() { 
  if (xhr.readyState == 4) {
if (xhr.status != 200) {
location.reload();
} else {
stats = JSON.parse(xhr.responseText);
if(typeof(stats.maze_text) != "undefined" && stats.maze_text !== null) {
document.getElementById('maze_kl').innerHTML =', Ключ: 1';
document.getElementById('maze_kluch').style.display ='none';
} 

}
}
}
}

function n_maze(block,block2) {

var xhr = new XMLHttpRequest();
xhr.open('GET','/maze_func.php?m=nagrada&block='+block+'&block2='+block2, true);
xhr.send();
xhr.onreadystatechange = function() { 
  if (xhr.readyState == 4) {
if (xhr.status != 200) {
location.reload();
} else {
stats = JSON.parse(xhr.responseText);
if(typeof(stats.maze_text) != "undefined" && stats.maze_text !== null) {
document.getElementById('error').innerHTML =stats.maze_text;
document.getElementById('n'+block+block2).style.display ='none';
}


}
}
}
}


function sunduk_maze(block,block2) {

var xhr = new XMLHttpRequest();
xhr.open('GET','/maze_func.php?m=sunduk&block='+block+'&block2='+block2, true);
xhr.send();
xhr.onreadystatechange = function() { 
  if (xhr.readyState == 4) {
if (xhr.status != 200) {
location.reload();
} else {
stats = JSON.parse(xhr.responseText);
if(typeof(stats.maze_text) != "undefined" && stats.maze_text !== null) {
document.getElementById('maze').innerHTML =stats.maze_text;
}


}
}
}
}

function hp_maze(block,block2) {

var xhr = new XMLHttpRequest();
xhr.open('GET','/maze_func.php?m=hp&block='+block+'&block2='+block2, true);
xhr.send();
xhr.onreadystatechange = function() { 
  if (xhr.readyState == 4) {
if (xhr.status != 200) {
location.reload();
} else {
stats = JSON.parse(xhr.responseText);
if(typeof(stats.maze_text) != "undefined" && stats.maze_text !== null) {
document.getElementById('error').innerHTML =stats.maze_text;
document.getElementById('n'+block+block2).style.display ='none';
document.getElementById('kol_hp').innerHTML =stats.maze_hp;
}


}
}
}
}

</script>
<?
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function vvod($vvod){ return trim(DB::escape($vvod));}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function vivod($vivod){return trim(htmlspecialchars(stripslashes($vivod)));}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function num($chislo){return abs(intval($chislo));}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function go($url){header("Location: ".$url); exit;}



$maze = mysqli_fetch_assoc(DB::Query("SELECT * FROM `maze` WHERE `id_user`='".num($user['id'])."' limit 1"));
if($maze['id']==0){
if(!isset($_GET['ok'])){
echo'
<div class="center block_light">
<img src="/images/maze/maze.png" alt="" /><br/>
<a href="/maze?ok" class="btns _g">Войти</a>
</div>   ';
}else{
$kluch=rand(1,25);$maze_up=rand(1,25);if($maze_up==$kluch){$maze_up=rand(1,25);}if($maze_up==$kluch){$maze_up=rand(1,25);}if($maze_up==$kluch){$maze_up=rand(1,25);}
$tip=mt_rand(1,3);
DB::Query("INSERT INTO `maze`(`id`,`id_user`,`hp`,`hp_max`,`kluch`,`maze`,`tip`)VALUES('','".num($user['id'])."','".num($user['vit'])."','".num($user['vit'])."','$kluch','$maze_up','tip')");
$open=rand(2,3);$open2=rand(2,3);


go("/maze");
}
}else{
if($maze['hp']>0){
if(!isset($_GET['block']) or !isset($_GET['block2'])){
if($maze['tip']==1){
$opened_maze = array( 
'1_1' => '1_2','1_2' => '2_2','1_3' => '2_3','1_4' => '1_5','1_5' => '2_5','2_1' => '3_1','2_2' => '3_2','2_3' => '2_2','2_4' => '3_4','2_5' => '3_5'
,'3_1' => '4_1','3_2' => '3_3','3_3' => '0','3_4' => '3_3','3_5' => '3_4','4_1' => '4_2','4_2' => '3_2','4_3' => '3_3','4_4' => '3_4','4_5' => '3_5'
,'5_1' => '4_1','5_2' => '5_1','5_3' => '4_3','5_4' => '4_4','5_5' => '5_4'

        );
}elseif($maze['tip']==2){
$opened_maze = array( 
'1_1' => '2_1','1_2' => '2_2','1_3' => '1_2','1_4' => '2_4','1_5' => '1_4','2_1' => '2_2','2_2' => '3_2','2_3' => '2_4','2_4' => '3_4','2_5' => '3_5',
'3_1' => '2_1','3_2' => '3_3','3_3' => '0','3_4' => '3_3','3_5' => '3_4','4_1' => '4_2','4_2' => '4_3','4_3' => '3_3','4_4' => '3_4','4_5' => '4_4',
'5_1' => '5_2','5_2' => '4_2','5_3' => '5_2','5_4' => '5_5','5_5' => '4_5'

 );

}else{
$opened_maze = array( 
'1_1' => '2_1','1_2' => '2_2','1_3' => '2_3','1_4' => '2_4','1_5' => '2_5','2_1' => '2_2','2_2' => '3_2','2_3' => '2_2','2_4' => '2_5','2_5' => '3_5',
'3_1' => '3_2','3_2' => '3_3','3_3' => '0','3_4' => '4_4','3_5' => '3_4','4_1' => '4_2','4_2' => '3_2','4_3' => '4_2','4_4' => '4_3','4_5' => '3_5',
'5_1' => '4_1','5_2' => '5_3','5_3' => '4_3','5_4' => '5_3','5_5' => '5_4'


 );

}

$maze_block['3_3']=1;$maze_opit['3_3']=0;$maze_money['3_3']=0;$maze_lovushka['3_3']=0;$maze_hp['3_3']=0;
$q=DB::Query("SELECT SQL_CACHE *  FROM `maze_block` WHERE `id_user`='".num($user['id'])."' ");
while($r = mysqli_fetch_assoc($q))
{
$maze_block[''.$r['block'].'_'.$r['block2'].'']=1;$maze_opit[''.$r['block'].'_'.$r['block2'].'']=$r['opit'];
$maze_money[''.$r['block'].'_'.$r['block2'].'']=$r['money'];$maze_lovushka[''.$r['block'].'_'.$r['block2'].'']=$r['lovushka'];
$maze_hp[''.$r['block'].'_'.$r['block2'].'']=$r['hp'];
}



echo'<div class="center"> Ключей <span id="kol_kluch">'.$user['maze_kluch'].'</span>, Осталось <span id="kol_hp">'.$maze['hp'].'</span> <span id="maze_kl"></span>'; if($maze['kluch']==0){ echo', Ключ: 1';}
echo'</div>
<div class="block_light">
<div id="error" class="center"></div>
<div class="center">Этаж '.$maze['lvl'].'</div><br/>
<div id="maze" class="center maze">';

for($i=1;$i<26;$i++){
$block=ceil(($i/5));$block2=$i-($block-1)*5;
/////if($block2==0){$block2=5;}

/*
$maze_block = mysqli_fetch_assoc(DB::Query("SELECT * FROM `maze_block` WHERE `id_user`='".num($user['id'])."' and `block`='$block' and `block2`='$block2'  limit 1"));
if($maze_block['id']==0){
echo'<img class="block_s" src="/images/maze/block.png" alt="" />';
}else{

if($maze_block['open']==1){echo'<img class="block_s" src="/images/maze/open.png" alt="" />';}
else{echo'<a  class="ssilki1" onclick="open_maze('.$block.','.$block2.');return false;"><img class="block_s" src="/images/maze/closed.png" alt="" /></a>';}

}
*/

if(isset($maze_block[''.$block.'_'.$block2.''])){
if($maze_lovushka[''.$block.'_'.$block2.'']!=0){
echo'<a  class="block_s p_re" style="'.$opened_maze[''.$block.'_'.$block2.''].'" href="/maze?block='.$block.'&amp;block2='.$block2.'"><img src="/images/maze/open.png" alt="" /><img id="n'.$block.''.$block2.'" class="p_ab" src="/images/maze/lovushka'.$maze_lovushka[''.$block.'_'.$block2.''].'.png" alt="" /></a>';
}elseif($i==$maze['maze']){
echo'<a  class="block_s p_re" style="'.$opened_maze[''.$block.'_'.$block2.''].'" onclick="lvl_maze('.$block.','.$block2.');return false;"><img src="/images/maze/open.png" alt="" /><img class="p_ab" src="/images/maze/maze_up.png" alt="" /></a>';
}
elseif($i==$maze['kluch']){
echo'<a  class="block_s p_re" style="'.$opened_maze[''.$block.'_'.$block2.''].'" onclick="kluch_maze('.$block.','.$block2.');return false;"><img src="/images/maze/open.png" alt="" /><img id="maze_kluch" class="p_ab" src="/images/maze/maze_kluch.png" alt="" /></a>';
}elseif($i==$maze['sunduk']){
echo'<a  class="block_s p_re" style="'.$opened_maze[''.$block.'_'.$block2.''].'" onclick="sunduk_maze('.$block.','.$block2.');return false;"><img src="/images/maze/open.png" alt="" /><img id="maze_kluch" class="p_ab" src="/images/maze/sunduk.png" alt="" /></a>';
}elseif($maze_money[''.$block.'_'.$block2.'']!=0){
echo'<a  class="block_s p_re" style="'.$opened_maze[''.$block.'_'.$block2.''].'" onclick="n_maze('.$block.','.$block2.');return false;"><img src="/images/maze/open.png" alt="" /><img id="n'.$block.''.$block2.'" class="p_ab" src="/images/maze/money.png" alt="" /></a>';
}
elseif($maze_opit[''.$block.'_'.$block2.'']!=0){
echo'<a  class="block_s p_re" style="'.$opened_maze[''.$block.'_'.$block2.''].'" onclick="n_maze('.$block.','.$block2.');return false;"><img src="/images/maze/open.png" alt="" /><img id="n'.$block.''.$block2.'" class="p_ab" src="/images/maze/opit.png" alt="" /></a>';
}elseif($maze_hp[''.$block.'_'.$block2.'']!=0){
echo'<a  class="block_s p_re" style="'.$opened_maze[''.$block.'_'.$block2.''].'" onclick="hp_maze('.$block.','.$block2.');return false;"><img src="/images/maze/open.png" alt="" /><img id="n'.$block.''.$block2.'" class="p_ab" src="/images/maze/maze_hp.png" alt="" /></a>';

}else{
echo'<img class="block_s" src="/images/maze/open.png" alt="" />';
}
}else{

if(isset($maze_block[''.$opened_maze[''.$block.'_'.$block2.''].''])){
if($maze_lovushka[''.$opened_maze[''.$block.'_'.$block2.''].'']==0){
echo'<a  class="ssilki1"  onclick="open_maze('.$block.','.$block2.');return false;"><img class="block_s" src="/images/maze/closed.png" alt="" /></a>';
}else{
echo'<span  class="ssilki1 p_re"><img class="block_s" src="/images/maze/closed.png" alt="" /><img id="n'.$block.''.$block2.'" class="p_ab" src="/images/maze/block2.png" alt="" /></span>';
}
}else{
echo'<img class="block_s" src="/images/maze/block.png" alt="" />';
}

}


}


echo'</div></div>';
}else{

if(isset($_GET['block'])){$block=num($_GET['block']);}else{$block=1;}if($block>25 or $block<1){$block=1;}
if(isset($_GET['block2'])){$block2=num($_GET['block2']);}else{$block2=1;}if($block2>25 or $block2<1){$block2=1;}
$maze_block = mysqli_fetch_assoc(DB::Query("SELECT * FROM `maze_block` WHERE `id_user`='".num($user['id'])."' and `block`='$block' and `block2`='$block2' and `lovushka`>0 limit 1"));
if($maze_block['id']>0){
if(!isset($_GET['t'])){
echo'
<div class="center block_light">
<img src="/images/maze/lovushka'.$maze_block['lovushka'].'_big.png" alt="" /><br/><br/>
<a href="/maze?block='.$block.'&amp;block2='.$block2.'&amp;t=1" ><img src="/images/maze/lovushka_left.png" alt="" /></a>
<a href="/maze?block='.$block.'&amp;block2='.$block2.'&amp;t=2" ><img src="/images/maze/lovushka_center.png" alt="" /></a>
<a href="/maze?block='.$block.'&amp;block2='.$block2.'&amp;t=3" ><img src="/images/maze/lovushka_right.png" alt="" /></a>
</div>   ';
}else{

echo'
<div class="center block_light">
<img src="/images/maze/lovushka'.$maze_block['lovushka'].'_big.png" alt="" /><br/><br/>';
DB::Query("UPDATE `maze_block` SET `lovushka`='0' WHERE `id`='".num($maze_block['id'])."'  limit 1 ");

if(rand(1,3)==$_GET['t']){
echo'Вы успешно прошли ловушку';
}else{
$uron=rand(1,3);
if($uron==1){$uron=num($maze['hp_max']*0.2);}
elseif($uron==2){$uron=num($maze['hp_max']*0.4);}
elseif($uron==3){$uron=num($maze['hp_max']*0.6);}
if($uron>$maze['hp']){$uron=$maze['hp'];}
DB::Query("UPDATE `maze` SET `hp`=`hp`-'$uron' WHERE `id`='".num($maze['id'])."'  limit 1 ");

echo'Ловушка вам нанесла <img src="http://warslord.ru/images/icon/health.png" alt="" />'.$uron.'';
}


echo'<br/><a href="/maze?ok" class="btns _g">Далее</a>
</div>   ';


}

}else{go("/maze");}
}
}else{
DB::Query("DELETE FROM `maze_block` WHERE `id_user`='".num($user['id'])."' ");
DB::Query("DELETE FROM `maze` WHERE `id_user`='".num($user['id'])."' ");
echo'
<div class="center block_light">
<img src="/images/maze/maze.png" alt="" /><br/>
К сожалению вы погибли
<br/><a href="/maze" class="btns _g">Далее</a>
</div>   ';
}


}

ini_set('display_errors', 0);

require_once H . 'system/footer.php';
?>