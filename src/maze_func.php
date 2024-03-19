<?php
/*
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);
*/

$topswap['id']=0;
header('Content-Type: application/json; charset=UTF-8');

include './system/common.php';
include './system/functions.php';
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function vvod($vvod){ return trim(DB::escape($vvod));}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function vivod($vivod){return trim(htmlspecialchars(stripslashes($vivod)));}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function num($chislo){return abs(intval($chislo));}


      $id = vvod(num($_COOKIE['id']));
$password = vvod($_COOKIE['password']);

if($id && $password) {
    
       $q = DB::Query('SELECT * FROM `users` WHERE `id` = "'.$id.'" AND `password` = "'.$password.'"');
    $user = mysqli_fetch_array($q);

  if($user['id']>0) {


if (isset($_GET['m'])) {$mod=vvod($_GET['m']);} else {$mod="";}

switch($mod) 
{

default:
$maze = mysqli_fetch_assoc(DB::Query("SELECT * FROM `maze` WHERE `id_user`='".num($user['id'])."' limit 1"));
if($maze['id']>0){
if(isset($_GET['block'])){$block=num($_GET['block']);}else{$block=1;}if($block>25 or $block<1){$block=1;}
if(isset($_GET['block2'])){$block2=num($_GET['block2']);}else{$block2=1;}if($block2>25 or $block2<1){$block2=1;}
if($user['maze_kluch']>0){


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
if($maze_lovushka[''.$opened_maze[''.$block.'_'.$block2.''].'']==0){
$money=0;$opit=0;$hp=0;

if(rand(1,10)==3){$money=rand($user['level']+50,$user['level']*20+50);}else{
if(rand(1,7)==3){$opit=rand($user['level']*20+25,$user['level']*40+25);}else{
if(rand(1,7)==3){$hp=rand($maze['hp_max']*0.1,$maze['hp_max']*0.2);}
}
}
$lovushka=0;
if(mt_rand(1,7)==4){$lovushka=rand(1,2);}

if(isset($maze_block[''.$opened_maze[''.$block.'_'.$block2.''].''])){
$topswap['maze']='';
$topswap['kol_kluch']=$user['maze_kluch']-1;
DB::Query("UPDATE `users` SET `maze_kluch`=`maze_kluch`-1 WHERE `id`='".num($user['id'])."'  limit 1 ");

DB::Query("INSERT INTO `maze_block`(`id`,`id_user`,`block`,`block2`,`open`,`money`,`opit`,`lovushka`,`hp`)VALUES('','".num($user['id'])."','$block','$block2','1','$money','$opit','$lovushka','$hp')");
$maze_block[''.$block.'_'.$block2.'']=1;$maze_opit[''.$block.'_'.$block2.'']=$opit;$maze_money[''.$block.'_'.$block2.'']=$money;
$maze_lovushka[''.$block.'_'.$block2.'']=$lovushka;$maze_hp[''.$block.'_'.$block2.'']=$hp;

for($i=1;$i<26;$i++){
$block=ceil(($i/5));$block2=$i-($block-1)*5;

if(isset($maze_block[''.$block.'_'.$block2.''])){
if($maze_lovushka[''.$block.'_'.$block2.'']!=0){
$topswap['maze'].='<a  class="block_s p_re" style="'.$opened_maze[''.$block.'_'.$block2.''].'" href="/maze?block='.$block.'&amp;block2='.$block2.'"><img src="/images/maze/open.png" alt="" /><img id="n'.$block.''.$block2.'" class="p_ab" src="/images/maze/lovushka'.$maze_lovushka[''.$block.'_'.$block2.''].'.png" alt="" /></a>';
}else
if($i==$maze['maze']){
$topswap['maze'].='<a  class="block_s p_re" style="'.$opened_maze[''.$block.'_'.$block2.''].'" onclick="lvl_maze('.$block.','.$block2.');return false;"><img src="/images/maze/open.png" alt="" /><img class="p_ab" src="/images/maze/maze_up.png" alt="" /></a>';
}
elseif($i==$maze['kluch']){
$topswap['maze'].='<a  class="block_s p_re" style="'.$opened_maze[''.$block.'_'.$block2.''].'" onclick="kluch_maze('.$block.','.$block2.');return false;"><img src="/images/maze/open.png" alt="" /><img id="maze_kluch" class="p_ab" src="/images/maze/maze_kluch.png" alt="" /></a>';
}elseif($i==$maze['sunduk']){
$topswap['maze'].='<a  class="block_s p_re" style="'.$opened_maze[''.$block.'_'.$block2.''].'" onclick="sunduk_maze('.$block.','.$block2.');return false;"><img src="/images/maze/open.png" alt="" /><img id="maze_kluch" class="p_ab" src="/images/maze/sunduk.png" alt="" /></a>';
}elseif($maze_money[''.$block.'_'.$block2.'']!=0){
$topswap['maze'].='<a  class="block_s p_re" style="'.$opened_maze[''.$block.'_'.$block2.''].'" onclick="n_maze('.$block.','.$block2.');return false;"><img src="/images/maze/open.png" alt="" /><img id="n'.$block.''.$block2.'" class="p_ab" src="/images/maze/money.png" alt="" /></a>';
}elseif($maze_opit[''.$block.'_'.$block2.'']!=0){
$topswap['maze'].='<a  class="block_s p_re" style="'.$opened_maze[''.$block.'_'.$block2.''].'" onclick="n_maze('.$block.','.$block2.');return false;"><img src="/images/maze/open.png" alt="" /><img id="n'.$block.''.$block2.'" class="p_ab" src="/images/maze/opit.png" alt="" /></a>';
}elseif($maze_hp[''.$block.'_'.$block2.'']!=0){
$topswap['maze'].='<a  class="block_s p_re" style="'.$opened_maze[''.$block.'_'.$block2.''].'" onclick="hp_maze('.$block.','.$block2.');return false;"><img src="/images/maze/open.png" alt="" /><img id="n'.$block.''.$block2.'" class="p_ab" src="/images/maze/maze_hp.png" alt="" /></a>';

}else{
$topswap['maze'].='<img class="block_s" src="/images/maze/open.png" alt="" />';
}
}else{

if(isset($maze_block[''.$opened_maze[''.$block.'_'.$block2.''].''])){
if($maze_lovushka[''.$opened_maze[''.$block.'_'.$block2.''].'']==0){
$topswap['maze'].='<a  class="ssilki1" style="'.$opened_maze[''.$block.'_'.$block2.''].'" onclick="open_maze('.$block.','.$block2.');return false;"><img class="block_s" src="/images/maze/closed.png" alt="" /></a>';
}else{
$topswap['maze'].='<span  class="ssilki1 p_re"><img class="block_s" src="/images/maze/closed.png" alt="" /><img id="n'.$block.''.$block2.'" class="p_ab" src="/images/maze/block2.png" alt="" /></span>';
}
}else{
$topswap['maze'].='<img class="block_s" src="/images/maze/block.png" alt="" />';
}

}
}
}
}
}else{$topswap['maze_text']='<span style="color: #FF2400;">У вас закончились ключи</span>';}
}
break;
/////////
case 'lvl':
if(isset($_GET['block'])){$block=num($_GET['block']);}else{$block=1;}if($block>25 or $block<1){$block=1;}
if(isset($_GET['block2'])){$block2=num($_GET['block2']);}else{$block2=1;}if($block2>25 or $block2<1){$block2=1;}
$maze = mysqli_fetch_assoc(DB::Query("SELECT * FROM `maze` WHERE `id_user`='".num($user['id'])."' and `maze`='".num(($block-1)*5+$block2)."' limit 1"));
if($maze['id']>0){

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

$maze_block['3_3']=1;
$q=DB::Query("SELECT SQL_CACHE *  FROM `maze_block` WHERE `id_user`='".num($user['id'])."' ");
while($r = mysqli_fetch_assoc($q))
{
$maze_block[''.$r['block'].'_'.$r['block2'].'']=1;
}

if(isset($maze_block[''.$block.'_'.$block2.''])){
if($maze['kluch']!=0){
$topswap['maze_text']='<span style="color: #FF2400;">У вас нет отмычки</span>';
}else{

$kluch=rand(1,25);$maze_up=rand(1,25);if($maze_up==$kluch){$maze_up=rand(1,25);}if($maze_up==$kluch){$maze_up=rand(1,25);}if($maze_up==$kluch){$maze_up=rand(1,25);}
$tip=mt_rand(1,3);
DB::Query("DELETE FROM `maze_block` WHERE `id_user`='".num($user['id'])."' ");
if($maze['lvl']==4){
DB::Query("UPDATE `maze` SET `sunduk`='$kluch',`maze`='0',`lvl`=`lvl`+'1',`tip`='$tip' WHERE `id`='".num($maze['id'])."'  limit 1 ");
}else{
DB::Query("UPDATE `maze` SET `kluch`='$kluch',`maze`='$maze_up',`lvl`=`lvl`+'1',`tip`='$tip' WHERE `id`='".num($maze['id'])."'  limit 1 ");
}

}

}

}
break;
/////////
case 'kluch':
if(isset($_GET['block'])){$block=num($_GET['block']);}else{$block=1;}if($block>25 or $block<1){$block=1;}
if(isset($_GET['block2'])){$block2=num($_GET['block2']);}else{$block2=1;}if($block2>25 or $block2<1){$block2=1;}
$maze = mysqli_fetch_assoc(DB::Query("SELECT * FROM `maze` WHERE `id_user`='".num($user['id'])."' and `kluch`='".num(($block-1)*5+$block2)."' limit 1"));
if($maze['id']>0){

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

$maze_block['3_3']=1;
$q=DB::Query("SELECT SQL_CACHE *  FROM `maze_block` WHERE `id_user`='".num($user['id'])."' ");
while($r = mysqli_fetch_assoc($q))
{
$maze_block[''.$r['block'].'_'.$r['block2'].'']=1;
}

if(isset($maze_block[''.$block.'_'.$block2.''])){

$topswap['maze_text']='0';
DB::Query("UPDATE `maze` SET `kluch`='0' WHERE `id`='".num($maze['id'])."'  limit 1 ");




}

}
break;
/////////
case 'nagrada':
if(isset($_GET['block'])){$block=num($_GET['block']);}else{$block=1;}if($block>25 or $block<1){$block=1;}
if(isset($_GET['block2'])){$block2=num($_GET['block2']);}else{$block2=1;}if($block2>25 or $block2<1){$block2=1;}
$maze_block = mysqli_fetch_assoc(DB::Query("SELECT * FROM `maze_block` WHERE `id_user`='".num($user['id'])."' and `block`='$block' and `block2`='$block2' limit 1"));
if($maze_block['id']>0){

if($maze_block['money']>0){
$topswap['maze_text']='<span style="color: #7afe4e;">Вы нашли <img src="http://warslord.ru/images/icon/silver.png" alt="" />'.$maze_block['money'].'</span>';
DB::Query("UPDATE `maze_block` SET `money`='0' WHERE `id`='".num($maze_block['id'])."'  limit 1 ");
DB::Query("UPDATE `users` SET `s`=`s`+'".num($maze_block['money'])."' WHERE `id`='".num($user['id'])."'  limit 1 ");
}elseif($maze_block['opit']>0){
$topswap['maze_text']='<span style="color: #7afe4e;">Вы нашли <img src="http://warslord.ru/images/icon/exp.png" alt="" />'.$maze_block['opit'].'</span>';
DB::Query("UPDATE `maze_block` SET `opit`='0' WHERE `id`='".num($maze_block['id'])."'  limit 1 ");
DB::Query("UPDATE `users` SET `exp`=`exp`+'".num($maze_block['opit'])."' WHERE `id`='".num($user['id'])."'  limit 1 ");
}




}

break;
/////////
case 'hp':
if(isset($_GET['block'])){$block=num($_GET['block']);}else{$block=1;}if($block>25 or $block<1){$block=1;}
if(isset($_GET['block2'])){$block2=num($_GET['block2']);}else{$block2=1;}if($block2>25 or $block2<1){$block2=1;}
$maze_block = mysqli_fetch_assoc(DB::Query("SELECT * FROM `maze_block` WHERE `id_user`='".num($user['id'])."' and `block`='$block' and `block2`='$block2' limit 1"));
if($maze_block['id']>0){

if($maze_block['hp']>0){
$maze = mysqli_fetch_assoc(DB::Query("SELECT * FROM `maze` WHERE `id_user`='".num($user['id'])."' limit 1"));
if($maze['hp']+$maze_block['hp']>$maze['hp_max']){$maze_block['hp']=$maze['hp_max']-$maze['hp'];}
$topswap['maze_hp']=$maze_block['hp']+$maze['hp'];
$topswap['maze_text']='<span style="color: #7afe4e;">Вы восстановили <img src="http://warslord.ru/images/icon/health.png" alt="" />'.$maze_block['hp'].'</span>';
DB::Query("UPDATE `maze_block` SET `hp`='0' WHERE `id`='".num($maze_block['id'])."'  limit 1 ");
DB::Query("UPDATE `maze` SET `hp`=`hp`+'".num($maze_block['hp'])."' WHERE `id_user`='".num($user['id'])."'  limit 1 ");
}




}

break;
/////////
/////////
case 'sunduk':
if(isset($_GET['block'])){$block=num($_GET['block']);}else{$block=1;}if($block>25 or $block<1){$block=1;}
if(isset($_GET['block2'])){$block2=num($_GET['block2']);}else{$block2=1;}if($block2>25 or $block2<1){$block2=1;}
$maze = mysqli_fetch_assoc(DB::Query("SELECT * FROM `maze` WHERE `id_user`='".num($user['id'])."' and `sunduk`='".num(($block-1)*5+$block2)."' limit 1"));
if($maze['id']>0){

$money=rand($user['level']*10+500,$user['level']*200+500);
$opit=rand($user['level']*120+250,$user['level']*240+250);
$gold=rand($user['level']*5+25,$user['level']*10+25);

DB::Query("UPDATE `users` SET `exp`=`exp`+'$opit',`s`=`s`+'$money',`g`=`g`+'$gold' WHERE `id`='".num($user['id'])."'  limit 1 ");
$topswap['maze_text']='<span style="color: #7afe4e;">Вы полностью прошли лабиринт<br>Награда:<br><img src="http://warslord.ru/images/icon/gold.png" alt="" />'.$gold.'  <img src="http://warslord.ru/images/icon/exp.png" alt="" />'.$opit.' <img src="http://warslord.ru/images/icon/silver.png" alt="" />'.$money.'</span>';
DB::Query("DELETE FROM `maze_block` WHERE `id_user`='".num($user['id'])."' ");
DB::Query("DELETE FROM `maze` WHERE `id_user`='".num($user['id'])."' ");





}
break;
}




}

}
echo json_encode($topswap); 
?>