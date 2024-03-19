<?php
function check($msg) {
$msg = htmlspecialchars(stripslashes(trim($msg)));
return $msg;
}
//// функция бб кодов by semdan
function bbcode1($msg){
global $user;
////////////////////////////////// ссылки    ///////////////////////////////////////////////////////////////////////////////////////////////
$msg = preg_replace("#\[image=(.+)\](.+)\[\/image\]#isU","<img src='\\2' width='\\1' alt='' />",$msg);
$msg=preg_replace("/\[site=(.*)\](.*)\[\/site\]/Usi","<a href='\\1'>\\2</a>",$msg);
$msg=preg_replace("/\[cit](.*)\[\/cit\]/Usi","<div class='cit'>\\1</div>",$msg);
$msg=preg_replace("/\[url=(.*)\](.*)\[\/url\]/Usi","<a href='//warsking.mobi\\1'>\\2</a>",$msg);          
$msg=preg_replace("/\[(.*)х(.*)-img=(.*)\]/Usi","<img style='max-width:100%;width:\\1px; height:\\2px;' src='http://warsking.mobi/img.php?url=\\3'>",$msg);  //
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$msg=preg_replace("/\[background=(.*)\](.*)\[\/background\]/Usi","<div style='background-color:\\1; display: block;'>\\2</div>",$msg);
/////////////////////////////////// доп рамки    ///////////////////////////////////////////////////////////////////////////////////////////
$msg=preg_replace("/\[ramkt](.*)\[\/ramkt\]/Usi","<span style='border-style: dashed; border-color:yellowgreen'>\\1</span>",$msg);         //
$msg=preg_replace("/\[ramkt2](.*)\[\/ramkt2\]/Usi","<span style='border-style: dashed; border-color:yellow'>\\1</span>",$msg);            //
$msg=preg_replace("/\[ramkt3](.*)\[\/ramkt3\]/Usi","<span style='border-style: dashed; border-color:LightGreen'>\\1</span>",$msg);        //
$msg=preg_replace("/\[ramkt4](.*)\[\/ramkt4\]/Usi","<span style='border-style: dashed; border-color:SteelBlue'>\\1</span>",$msg);         //
$msg=preg_replace("/\[ramkt5](.*)\[\/ramkt5\]/Usi","<span style='border-style: dashed; border-color:LightPink'>\\1</span>",$msg);         //
$msg=preg_replace("/\[ramkt6](.*)\[\/ramkt6\]/Usi","<span style='border-style: dashed; border-color:Gray'>\\1</span>",$msg);              //
$msg=preg_replace("/\[ramkt7](.*)\[\/ramkt7\]/Usi","<span style='border-style: dashed; border-color:White'>\\1</span>",$msg);             //
$msg=preg_replace("/\[ramkt8](.*)\[\/ramkt8\]/Usi","<span style='border-style: dashed; border-color:MediumSlateBlue'>\\1</span>",$msg);   //
$msg=preg_replace("/\[ramkt9](.*)\[\/ramkt9\]/Usi","<span style='border-style: dashed; border-color:LightSalmon'>\\1</span>",$msg);       //
$msg=preg_replace("/\[ramkt10](.*)\[\/ramkt10\]/Usi","<span style='border-style: dashed; border-color:DarkOrange'>\\1</span>",$msg);      //
$msg=preg_replace("/\[ramkt11](.*)\[\/ramkt11\]/Usi","<span style='border-style: dashed; border-color:SpringGreen'>\\1</span>",$msg);     //
$msg=preg_replace("/\[ramkt12](.*)\[\/ramkt12\]/Usi","<span style='border-style: dashed; border-color:Yellow'>\\1</span>",$msg);          //
$msg=preg_replace("/\[ramkt13](.*)\[\/ramkt13\]/Usi","<span style='border-style: dashed; border-color:Gold'>\\1</span>",$msg);            //
$msg=preg_replace("/\[ramkt14](.*)\[\/ramkt14\]/Usi","<span style='border-style: dashed; border-color:DarkRed'>\\1</span>",$msg);         //
$msg=preg_replace("/\[ramkt15](.*)\[\/ramkt15\]/Usi","<span style='border-style: dashed; border-color:DeepPink'>\\1</span>",$msg);        //
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////// текст ////////////////////////////////////////////////////////////////////////////////////////////////////
$msg=preg_replace("/\[center](.*)\[\/center\]/Usi","<div align='center'>\\1</div>",$msg);                                                 //
$msg=preg_replace("/\[right](.*)\[\/right\]/Usi","<div align='right'>\\1</div>",$msg);                                                    //
$msg=preg_replace("/\[left](.*)\[\/left\]/Usi","<div align='left'>\\1</div>",$msg);                                                       //
$msg=preg_replace("/\[br]/Usi","<br>",$msg);                                                                                              //
$msg=preg_replace("/\[hr]/Usi","<hr>",$msg);                                                                                              //
$msg=preg_replace("/\[big](.*)\[\/big\]/Usi","<span style='font-size:large;'>\\1</span>",$msg);                                           //
$msg=preg_replace("/\[sol](.*)\[\/col\]/Usi","<span style='border:1px solid;'>\\1</span>",$msg);                                          //
$msg=preg_replace("/\[das](.*)\[\/das\]/Usi","<span style='border:1px dashed;'>\\1</span>",$msg);                                         //
$msg=preg_replace("/\[dot](.*)\[\/dot\]/Usi","<span style='border:1px dotted;'>\\1</span>",$msg);                                         //
$msg=preg_replace("/\[ex](.*)\[\/ex\]/Usi","<span style='text-decoration:line-through;'>\\1</span>",$msg);                                //
$msg=preg_replace("/\[dotss](.*)\[\/dotss\]/Usi","<span style='text-decoration:underline'>\\1</span>",$msg);                              //
$msg=preg_replace("/\[up](.*)\[\/up\]/Usi","<span style='text-decoration:overline;'>\\1</span>",$msg);                                    //
$msg=preg_replace("/\[i](.*)\[\/i\]/Usi","<em>\\1</em>",$msg);                                                                            //
$msg=preg_replace("/\[b](.*)\[\/b\]/Usi","\\1",$msg);                                                                              //
                         $msg=preg_replace("/\[small](.*)\[\/small\]/Usi","<small>\\1</small>",$msg);                                     //
$msg=preg_replace("/\[color=(.*)](.*)\[\/color\]/Usi", "<font color='\\1'>\\2</font>", $msg);                                             //
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////// работа с текстом  ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$msg=preg_replace("/\[gradient=(.*),(.*)](.*)\[\/gradient\]/Usi", "<span style='background: linear-gradient(135deg, #\\1 20%, #\\2 70%);-webkit-background-clip: text;-webkit-text-fill-color: transparent;'>\\3</span>", $msg);             //
$msg=preg_replace("/\[gradient3=(.*),(.*),(.*)](.*)\[\/gradient\]/Usi","<span style='background: linear-gradient(135deg, #\\1 20%, #\\2 50%, #\\3);-webkit-background-clip: text;-webkit-text-fill-color: transparent;'>\\4</span>",$msg);   //
$msg=preg_replace("/\[transparent=(.*)](.*)\[\/transparent\]/Usi","<span style='background: #\\1;display: inline-table;color: white;border-radius: 5px;mix-blend-mode: multiply;padding: 5px 10px;'>\\2</span>",$msg);                       //
$msg=preg_replace("/\[transparent3=(.*),(.*),(.*)](.*)\[\/transparent\]/Usi","<span style='background: linear-gradient(135deg, #\\1 20%, #\\2 70%,  #\\3);display: inline-table;color: white;border-radius: 5px;mix-blend-mode: multiply;padding: 5px 10px;'>\\4</span>",$msg);                       //
$msg=preg_replace("/\[transparent2=(.*),(.*)](.*)\[\/transparent\]/Usi","<span style='background: linear-gradient(135deg, #\\1 20%, #\\2 70%);display: inline-table;color: white;border-radius: 5px;mix-blend-mode: multiply;padding: 5px 10px;'>\\3</span>",$msg);                       //

$msg=preg_replace("/\[bit](.*)\[\/bit\]/Usi","<marquee behavior='alternate'>\\1</marquee>",$msg);                                         /////////////////////////////////////////////////////////////////////////////////////////////////////
$msg=preg_replace("/\[textp](.*)\[\/textp\]/Usi","<p align='right'>\\1</p>",$msg);                                                        //
$msg=preg_replace("/\[marq](.*)\[\/marq\]/Usi","<marquee>\\1</marquee>",$msg);                                                            //
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$msg=preg_replace("/\[prlink](.*)\[\/prlink\]/Usi","<blockquote style='border: 3px solid rgb(218, 112, 214); margin: 0px; padding: 5px;'>\\1</blockquote>",$msg);    //
$msg=preg_replace("/\[prlink2](.*)\[\/prlink2\]/Usi","<blockquote style='border: 1px solid rgb(218, 112, 214); margin: 0px; padding: 5px;'>\\1</blockquote>",$msg);        //
$msg=preg_replace("/\[prlink3](.*)\[\/prlink3\]/Usi","<blockquote style='border: 1px solid rgb(218, 112, 214); margin: 1px; padding: 1px;'>\\1</blockquote>",$msg);  //
$msg=preg_replace("/\[prlink4](.*)\[\/prlink4\]/Usi","<blockquote style='border: 1px solid rgb(110, 112, 214); margin: 1px; padding: 1px;'>\\1</blockquote>",$msg);  //
$msg=preg_replace("/\[prlink5](.*)\[\/prlink5\]/Usi","<blockquote style='border: 1px solid rgb(666, 43, 211); margin: 1px; padding: 1px;'>\\1</blockquote>",$msg);   //
$msg=preg_replace("/\[prlink6](.*)\[\/prlink6\]/Usi","<blockquote style='border: 1px solid rgb(777, 43, 666); center: 0px; padding: 1px;'>\\1</blockquote>",$msg);   ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$msg=preg_replace("/\[rams](.*)\[\/rams\]/Usi","<blockquote style='BORDER-LEFT: #F70000 3px solid; padding: 15px; BORDER-TOP: #119931 3px solid; padding: 15px; BORDER-BOTTOM: #E3AF40 3px solid; padding: 15px; BORDER-RIGHT: #924E96 3px solid' padding:='' 15px=''>\\1</blockquote>",$msg); //
$msg=preg_replace("/\[rams2](.*)\[\/rams2\]/Usi","<blockquote style='BORDER-LEFT: #F70000 1px solid; padding: 5px; BORDER-TOP: #119931 1px solid; padding: 3px; BORDER-BOTTOM: red 3px solid; padding: 1px; BORDER-RIGHT: #924E96 3px solid' padding:='' 1px=''>\\1</blockquote>",$msg);       //
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$msg=preg_replace("/\[bDarkOrange](.*)\[\/bDarkOrange\]/Usi","<span style='background-color : DarkOrange'>\\1</span>",$msg);
$msg=preg_replace("/\[bred](.*)\[\/bred\]/Usi","<span style='background-color : red'>\\1</span>",$msg);
$msg=preg_replace("/\[bgreen](.*)\[\/bgreen\]/Usi","<span style='background-color : green'>\\1</span>",$msg);
$msg=preg_replace("/\[bblue](.*)\[\/bblue\]/Usi","<span style='background-color : blue'>\\1</span>",$msg);
$msg=preg_replace("/\[byellow](.*)\[\/byellow\]/Usi","<span style='background-color : yellow'>\\1</span>",$msg);
$msg=preg_replace("/\[bbrown](.*)\[\/bbrown\]/Usi","<span style='background-color : brown'>\\1</span>",$msg);
$msg=preg_replace("/\[borange](.*)\[\/borange\]/Usi","<span style='background-color : orange'>\\1</span>",$msg);
$msg=preg_replace("/\[bDeepPink](.*)\[\/bDeepPink\]/Usi","<span style='background-color : DeepPink'>\\1</span>",$msg);
$msg=preg_replace("/\[bLightSalmon](.*)\[\/bLightSalmon\]/Usi","<span style='background-color : LightSalmon'>\\1</span>",$msg);
$msg=preg_replace("/\[bLime](.*)\[\/bLime\]/Usi","<span style='background-color : Lime'>\\1</span>",$msg);
$msg=preg_replace("/\[dLimeGreen](.*)\[\/dLimeGreen\]/Usi","<span style='background-color : LimeGreen'>\\1</span>",$msg);
$msg=preg_replace("/\[bFireBrick](.*)\[\/bFireBrick\]/Usi","<span style='background-color : FireBrick'>\\1</span>",$msg);
$msg=preg_replace("/\[bOrangeRed](.*)\[\/bOrangeRed\]/Usi","<span style='background-color : OrangeReb'>\\1</span>",$msg);
$msg=preg_replace("/\[bgrey](.*)\[\/bgrey\]/Usi","<span style='background-color : grey'>\\1</span>",$msg);
$msg=preg_replace("/\[bpink](.*)\[\/bpink\]/Usi","<span style='background-color : pink'>\\1</span>",$msg);
$msg=preg_replace("/\[bwhite](.*)\[\/bwhite\]/Usi","<span style='background-color : white'>\\1</span>",$msg);
$msg=preg_replace("/\[scr-w](.*)\[\/scr-w\]/Usi","<span style='background-color:#ffffff;'><span style='color:#ffffff;'><span style='border:1px dashed;'>\\1</span></span></span>",$msg);
$msg=preg_replace("/\[scr-bl](.*)\[\/scr-bl\]/Usi","<span style='background-color:Blue;'><span style='color:Blue;'><span style='border:1px dashed;'>\\1</span></span></span>",$msg);
$msg=preg_replace("/\[scr-r](.*)\[\/scr-r\]/Usi",'<span style="background-color:red;"><span style="color:red;"><span style="border:1px dashed;">\\1</span></span></span>',$msg);
$msg=preg_replace("/\[scr-g](.*)\[\/scr-g\]/Usi",'<span style="background-color:green;"><span style="color:green;"><span style="border:1px dashed;">\\1</span></span></span>',$msg);
$msg=preg_replace("/\[scr-b](.*)\[\/scr-b\]/Usi",'<span style="background-color:#000000;"><span style="color:#000000;"><span style="border:1px dashed;">\\1</span></span></span>',$msg);
$msg=preg_replace("/\[scr-d](.*)\[\/scr-d\]/Usi",'<span style="background-color:DarkMagenta;"><span style="color:DarkMagenta;"><span style="border:1px dashed;">\\1</span></span></span>',$msg);
$msg=preg_replace("/\[feedback](.*)\[\/feedback\]/Usi",'<div class="feedback">\\1</div>',$msg);
$msg=preg_replace("/\[scr-](.*)\[\/scr-\]/Usi",'\\1</span></span></span>',$msg);
$msg=preg_replace("/\[scr-](.*)\[\/scr-\]/Usi",'\\1</span></span></span>',$msg);
$msg=preg_replace("/\[scr-](.*)\[\/scr-\]/Usi",'\\1</span></span></span>',$msg);
$msg=preg_replace("/\[scr-](.*)\[\/scr-\]/Usi",'\\1</span></span></span>',$msg);
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Определяем слава которые нарушают правиладоброзло
$msg = str_replace(array('Кароче херня в другихх марсах. Лудше','Админы за игой следи'), 'флуд', $msg);
$msg = str_replace(array('big mars'), 'лечу в бан', $msg);
$msg = str_replace(array('блядь','блядь','наху','админ жлоб','хуй','гавно','хрень','пидор','пизда','Акуеть','settings?exit','mars-games.ru','bespredel.pro/?ref=535','mars24.online','Привет заходи в новую онлайн игру','mars24.'), 'Спам+Мат', $msg);
$msg = str_replace(array('bigmars','. R U','ФУФЛО','За то время которое игра не работала я бы заработал больше!! И не говорите что это были тех работы! Это в тупую взломали игру','Не играй в это фуфло','ru вот зачётная','Приходи не пожалеешь! Активный колектив, много чего интересного,','Bigmars.ru','Bigmars','бигмарс','','','','','',''), '', $msg);
$msg = str_replace(array('б лять','[R] Admin (13:59:19)','Всем привет, уважаемые игроки.','Прошу прощения с меня хреновый админ....','пишите админу он выдаст всем по 10лямов.','P.S пишите ему что пришли с бизнес Марса!!!
Топик: Открыт','Новости / Переезд','Ещё раз простите!!!!','P.S пишите ему что пришли с бизнес Марса!!!','Топик: Открыт','Я договорился переходите в','н аху','еба н','ху й','w a p m a r s','m a r s','http : //','. r u','p ma rs','га вно','хр ень','пид ор','мудешили','говешки','суходрочки','суходрочка'), '<small>[bwhite][color=gray]лексикон балбеса[/color][/bwhite]</small>', $msg);
$msg = str_replace(array('НОВАЯ ОНЛАЙН ИГРА','ДОБРО ПРОТИВ','wapmars','ссылку нужно вводить','ССЫЛКА- до','доброзло','.де ','ДОБРОЗЛО','.COM','.com ','.xyz','dobrozlo','.дружба..дружба..дружба..дружба..дружба..дружба.'), ' ', $msg);// доброзло
$msg = str_replace(array('НОВ АЯ ОНЛ АЙН ИГРА','ДО БРО ПР ОТИВ ЗЛА','.СОМ','.DE','b i g m a r s','DOBR','ЗАХОДИ ЖДЕМ ТЕБЯ ССЫЛКА ','ПРИГЛАШАЮ В ИГРУ','ДОБРО VS ЗЛО','ROZL','РАЗЧИТАНА ПОД ДОНАТ ','В ИГРЕ ЧАСТО АКЦИИ '), ' ', $msg);// доброзло
$msg = str_replace(array('Пиздец игре онлайн 8 ,хаха.Бан мне','ПИЗДОБОЛ','переходим в игру b i g m a r . r u .кул3. я не обманываю!Убидитесь сами,стабильный онлаин даже ночью','Интересно было - 4980 стало -1860 рубинов . Админ давай мне вечный бан игре конец . Примерно этоже было в одной хорошей игре пираты гроза морей . Бан мне за рекламу другой игры . Все .'), "я люблю эту игру", $msg);
$msg = str_replace(array('http:/ /gold-dragons. ru/?i =7649','http://nvwwc4ttfzww6ytj.cmle.ru/registration?id=8447','Н ОВАЯ ОНЛ АЙН ИГ РА ДОБ РО ПРОТ ИВ З ЛА ссыл ку нуж но вводить в адресную ст року на англий ском ССЫ ЛКА- до бр оз ло .д е'), "я люблю эту игру", $msg);
$msg = str_replace(array('trush.mobi'), 'лечу в бан', $msg);
$msg = str_replace(array('trush. mobi'), 'лечу в бан', $msg);
$msg = str_replace(array('geroi.tk'), 'лечу в бан', $msg);
$msg = str_replace(array('geroi. tk'), 'лечу в бан', $msg);

return $msg;      //
}                 //
////бб коды конец//*/
////////////////////
#-Смайлы-#
function msg($zamena){
$msg = $zamena;
$message = $msg; // Комментарий пользователя
$smiles_kolobok_key = array(":)", ":D", ":-D", ":_D", ":(", ":_(", ":-(", ":P", ":-P", ":a", ":-a", ":]", ":finale", ":haha", ":klass", ":bravo", ":hih", ":cool", ":danc3", ":danc2", ":danc", ":kiss",":love", ":heart2", ":heart", ":friends", ":drink", ":punish", ":stop", ":bee", ":pardon", ":smoke", ":umnik", ":pop", ":read", ":idea", ":gum", ":heat",":secret", ":sweet", ":em", ":nap", ":search", ":swoo", ":dont", ":polling", ":download", ":sorry", ":not_i", ":nea", ":no", ":-no",":bad", ":help", ":mda", ":figa", ":stink", ":meet", ":zol", ":scar", ":computer", ":rd", ":vop", ":med_str", ":pus",":ban", ":red_prv", ":rip", ":k", ":you", ":like", ":dislike", ":pivo"); // Массив с кодами смайлов
$smiles_kolobok_value = array("/style/images/smiles/smiles_kolobok/).png", "/style/images/smiles/smiles_kolobok/D.gif","/style/images/smiles/smiles_kolobok/-D.gif", "/style/images/smiles/smiles_kolobok/_D.gif", "/style/images/smiles/smiles_kolobok/(.gif", "/style/images/smiles/smiles_kolobok/_(.gif", "/style/images/smiles/smiles_kolobok/-(.gif", "/style/images/smiles/smiles_kolobok/P.gif", "/style/images/smiles/smiles_kolobok/-P.gif", "/style/images/smiles/smiles_kolobok/a.gif", "/style/images/smiles/smiles_kolobok/-a.gif",  "/style/images/smiles/smiles_kolobok/].gif", "/style/images/smiles/smiles_kolobok/finale.gif","/style/images/smiles/smiles_kolobok/haha.gif", "/style/images/smiles/smiles_kolobok/klass.gif", "/style/images/smiles/smiles_kolobok/bravo.gif", "/style/images/smiles/smiles_kolobok/hih.gif","/style/images/smiles/smiles_kolobok/cool.gif",  "/style/images/smiles/smiles_kolobok/danc3.gif", "/style/images/smiles/smiles_kolobok/danc2.gif", "/style/images/smiles/smiles_kolobok/danc.gif","/style/images/smiles/smiles_kolobok/kiss.gif", "/style/images/smiles/smiles_kolobok/love.png", "/style/images/smiles/smiles_kolobok/heart2.png", "/style/images/smiles/smiles_kolobok/heart.png", "/style/images/smiles/smiles_kolobok/friends.gif", "/style/images/smiles/smiles_kolobok/drink.gif", "/style/images/smiles/smiles_kolobok/punish.gif", "/style/images/smiles/smiles_kolobok/stop.gif","/style/images/smiles/smiles_kolobok/bee.gif", "/style/images/smiles/smiles_kolobok/pardon.gif", "/style/images/smiles/smiles_kolobok/smoke.gif", "/style/images/smiles/smiles_kolobok/umnik.gif","/style/images/smiles/smiles_kolobok/pop.gif",  "/style/images/smiles/smiles_kolobok/read.gif", "/style/images/smiles/smiles_kolobok/idea.gif", "/style/images/smiles/smiles_kolobok/gum.gif",  "/style/images/smiles/smiles_kolobok/heat.gif", "/style/images/smiles/smiles_kolobok/secret.gif", "/style/images/smiles/smiles_kolobok/sweet.gif", "/style/images/smiles/smiles_kolobok/em.gif", "/style/images/smiles/smiles_kolobok/nap.gif", "/style/images/smiles/smiles_kolobok/search.gif", "/style/images/smiles/smiles_kolobok/swoo.gif", "/style/images/smiles/smiles_kolobok/dont.gif", "/style/images/smiles/smiles_kolobok/polling.gif","/style/images/smiles/smiles_kolobok/download.gif", "/style/images/smiles/smiles_kolobok/sorry.gif", "/style/images/smiles/smiles_kolobok/not_i.gif", "/style/images/smiles/smiles_kolobok/nea.gif", "/style/images/smiles/smiles_kolobok/no.gif","/style/images/smiles/smiles_kolobok/-no.gif", "/style/images/smiles/smiles_kolobok/bad.gif", "/style/images/smiles/smiles_kolobok/help.gif", "/style/images/smiles/smiles_kolobok/mda.gif",  "/style/images/smiles/smiles_kolobok/figa.gif", "/style/images/smiles/smiles_kolobok/stink.gif", "/style/images/smiles/smiles_kolobok/meet.gif", "/style/images/smiles/smiles_kolobok/zol.gif","/style/images/smiles/smiles_kolobok/scar.gif", "/style/images/smiles/smiles_kolobok/computer.png", "/style/images/smiles/smiles_kolobok/rd.png","/style/images/smiles/smiles_kolobok/vop.png","/style/images/smiles/smiles_kolobok/med_str.png", "/style/images/smiles/smiles_kolobok/pus.png", "/style/images/smiles/smiles_kolobok/ban.png", "/style/images/smiles/smiles_kolobok/red_prv.png", "/style/images/smiles/smiles_kolobok/rip.png", "/style/images/smiles/smiles_kolobok/k.png","/style/images/smiles/smiles_kolobok/you.png", "/style/images/smiles/smiles_kolobok/like.png", "/style/images/smiles/smiles_kolobok/dislike.png", "/style/images/smiles/smiles_kolobok/pivo.png"); // Массив с соответствующими путями к изображениям смайлов
for ($i = 0; $i < count($smiles_kolobok_value); $i++)
$smiles_kolobok_value[$i] = "<img src='".$smiles_kolobok_value[$i]."' alt=''/>"; // Делаем тег img на основании пути к изображению
$message = str_replace($smiles_kolobok_key, $smiles_kolobok_value, $message); // Заменяем все коды на теги img со смайлами
$r1_text = preg_replace('/ {2,}/', ' ',  $message);
//$message = preg_replace("/[\r\n]+/m", "\n", $r1_text);
$message=bbcode1($message);
return $message; 
}




#-BBcode-#
function bbcode($zamena){
$message = $zamena;
$bbcode = array(
'/\[yellow\](.+?)\[\/yellow\]/is' => '<span style="color:#ecbc7d;">$1</span>',
'/\[red\](.+?)\[\/red\]/is' => '<span style="color:#ff0000;">$1</span>',
'/\[blue\](.+?)\[\/blue\]/is' => '<span style="color:#2066ce;">$1</span>',
'/\[green\](.+?)\[\/green\]/is' => '<span style="color:#00a800;">$1</span>',
'/\[purple\](.+?)\[\/purple\]/is' => '<span style="color:#dc50ff;">$1</span>',
'/\[black\](.+?)\[\/black\]/is' => '<span style="color:#000000;">$1</span>',
'/\[white\](.+?)\[\/white\]/is' => '<span style="color:#ffffff;">$1</span>',
'/\[(\/?)(b|i|u|s|center)\s*\]/' => "<$1$2>",
'/\[url\](.*?)\[\/url\]/is' => '<a href="$1">$1</a>',
'/\[url\=(.*?)\](.*?)\[\/url\]/is' => '<a href="$1">$2</a>',  
'/\[(\/?)(b|i|u|s|br)\s*\]/' => "<$1$2>",
'/\[img\](.+?)\[\/img\]/is' => '<img src="http://warsking.mobi/img.php?url=$1" alt=""/>');
$message = preg_replace(array_keys($bbcode), array_values($bbcode), $message);
return $message;
}
?>