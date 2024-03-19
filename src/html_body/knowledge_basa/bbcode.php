<?php
require_once '../../system/system.php';
$head = 'BBCode';
echo only_reg();
require_once H.'system/head.php';
///////////////////////////////////////////////////////////////
if($_GET['act']=='1'){
	?><a class="home-s" href="/bbcode">В начало</a>
<div class="sform"><input type="text" value="[img=/img/start_logo.jpg]"> Картинка Пример : <img src="/img/login.png"></div>
<div class="sform"><input type="text" value="[75х75-img=/img/start_logo.jpg]"> Картинка c размером 75х75 Пример : <img style="width:75px;heaght:75px;" src="/img/login.png"></div>
<div class="sform"><input type="text" value="[url=http://адрес]название[/url]"><a href="/bbcode/?act=1">Ссылка</a></div>

</div>
        <?
	require_once H.'system/footer.php';
	exit();
}
////////////////////////////////////////////////////////////////
if($_GET['act']=='2'){
	?>
	<a class="home-s" href="/bbcode">В начало</a>
<div class="sform"><input type="text" value="[big]ваш текст[/big]"><span style="font-size:large;">Большой жирный</span></div>
<div class="sform"><input type="text" value="[b]ваш текст[/b]"><strong>Жирный средний</strong></div>
<div class="sform"><input type="text" value="[i]ваш текст[/i]"><em>Курсив</em></div>
<div class="sform"><input type="text" value="[sol]ваш текст[/sol]"><span style="border:1px solid;">обведённый</span></div>
<div class="sform"><input type="text" value="[das]ваш текст[/das]"><span style="border:1px dashed;">обведённый2</span></div>
<div class="sform"><input type="text" value="[dot]ваш текст[/dot]"><span style="border:1px dotted;">обведённый3</span></div>
<div class="sform"><input type="text" value="[dou]ваш текст[/dot]"><span style="border:3px double #E1E1E4;">обведённый4</span></div>
<div class="sform"><input type="text" value="[ex]ваш текст[/ex]"><span style="text-decoration:line-through;">зачеркнутый текст</span></div>
<div class="sform"><input type="text" value="[dotss]ваш текст[/dotss]"><span style="text-decoration:underline">Подчеркнутый</span></div>
<div class="sform"><input type="text" value="[up]ваш текст[/up]"><span style="text-decoration:overline;">надчеркнутый</span></div>
        <?
	require_once H.'system/footer.php';
	exit();
}
////////////////////////////////////////////////////////////////
if($_GET['act']=='3'){
	?>
<a class="home-s" href="/bbcode">В начало</a>
<div class="sform"><input type="text" value="[color=008080]Вводим текст[/color]"> 	<span style="color:#008080;">Цвет Текста</span></div>
<div class="sform"><input type="text" value="[gradient=008080,FF4500]Вводим текст[/gradient]"> 	<span style="background: linear-gradient(135deg, #008080 20%, #FF4500 70%);-webkit-background-clip: text;-webkit-text-fill-color: transparent;">Двойной Градиент</span></div>
<div class="sform"><input type="text" value="[gradient3=008080,FF4500,4B0082]Вводим текст[/gradient]"> 	<span style="background: linear-gradient(135deg, #008080 20%, #FF4500 50%, #4B0082);-webkit-background-clip: text;-webkit-text-fill-color: transparent;">Тройной Градиент</span></div>
<div class="sform"><input type="text" value="[transparent=008080]Вводим текст[/transparent]"> 	<span style="background: #008080;display: inline-table;color: white;border-radius: 5px;mix-blend-mode: multiply;padding: 5px 10px;">Прозрачный Текст с 1 градиентом</span></div>
<div class="sform"><input type="text" value="[transparent2=008080,FF4500]Вводим текст[/transparent]"> 	<span style="background: linear-gradient(135deg, #008080 20%, #FF4500 70%);display: inline-table;color: white;border-radius: 5px;mix-blend-mode: multiply;padding: 5px 10px;">Прозрачный Текст с 2ым	 градиентом</span></div>
<div class="sform"><input type="text" value="[transparent3=008080,FF4500,4B0082]Вводим текст[/transparent]"> 	<span style="background: linear-gradient(135deg, #008080 20%, #FF4500 70%,  #4B0082);display: inline-table;color: white;border-radius: 5px;mix-blend-mode: multiply;padding: 5px 10px;">Прозрачный Текст с 3ым	 градиентом</span></div>
<div class="sform"><input type="text" value="[bit]ваш текст[/bit]"><marquee behavior="alternate">Текст бьется о стенки экрана</marquee></div>
<div class="sform"><input type="text" value="[center]ваш текст[/center]"><center>текст в центре</center></div>
<div class="sform"><input type="text" value="[textp]ваш текст[/textp]"> <p align="right">Выравнивание текста в право</p></div>
<div class="sform"><input type="text" value="[marq]ваш текст[/marq]"><marquee>движущийся текст</marquee></div>
        <?
	require_once H.'system/footer.php';
	exit();
}
////////////////////////////////////////////////////////////////
if($_GET['act']=='4'){
	?>
	<a class="home-s" href="/bbcode">В начало</a>
<div class="sform"><input type="text" value="[bDarkOrange]ваш текст[/bDarkOrange]"><span style="background-color : DarkOrange">DarkOrange
 </span></div>
<div class="sform"><input type="text" value="[bred]ваш текст[/bred]"><span style="background-color : red">red
</span></div>
<div class="sform"><input type="text" value="[bgreen]ваш текст[/bgreen]"><span style="background-color : green">green
</span></div>
<div class="sform"><input type="text" value="[bblue]ваш текст[/bblue]"><span style="background-color : blue">blue
</span></div>
<div class="sform"><input type="text" value="[byellow]ваш текст[/byellow]"><span style="background-color : yellow">yellow
</span></div>
<div class="sform"><input type="text" value="[bbrown]ваш текст[/bbrown]"><span style="background-color : brown">brown
</span></div>
<div class="sform"><input type="text" value="[borange]ваш текст[/borange]"><span style="background-color : orange">orange
</span></div>
<div class="sform"><input type="text" value="[bDeepPink]ваш текст[/bDeepPink]"><span style="background-color : DeepPink">DeepPink
 </span></div>
<div class="sform"><input type="text" value="[bLightSalmon]ваш текст[/bLightSalmon]"><span style="background-color : LightSalmon">LightSalmon
 </span></div>
<div class="sform"><input type="text" value="[bLime]ваш текст[/bLime]"><span style="background-color : Lime">Lime
 </span></div>
<div class="sform"><input type="text" value="[bDarkOrange]ваш текст[/bDarkOrange]"><span style="background-color : DarkOrange">DarkOrange
 </span></div>
<div class="sform"><input type="text" value="[dLimeGreen]ваш текст[/dLimeGreen]"><span style="background-color : LimeGreen">Yellow
 </span></div>
<div class="sform"><input type="text" value="[bFireBrick]ваш текст[/bFireBrick]"><span style="background-color : FireBrick">FireBrick
 </span></div>
<div class="sform"><input type="text" value="[bOrangeRed]ваш текст[/bOrangeRed]"><span style="background-color : OrangeRed">OrangeRed
 </span></div>
<div class="sform"><input type="text" value="[bGoldenrod]ваш текст[/bGoldenrod]"><span style="background-color : Goldenrod">Goldenrod
 </span></div>
<div class="sform"><input type="text" value="[bTurquoise]ваш текст[/bTurquoise]"><span style="background-color : Turquoise">Turquoise
 </span> </div>
<div class="sform"><input type="text" value="[bblack]ваш текст[/bblack]"><span style="background-color : black">black
</span></div>
<div class="sform"><input type="text" value="[bgrey]ваш текст[/bgrey]"><span style="background-color : grey">grey
</span></div>
<div class="sform"><input type="text" value="[bpink]ваш текст[/bpink]"><span style="background-color : pink">pink
</span></div>
<div class="sform"><input type="text" value="[bviolet]ваш текст[/bviolet]"><span style="background-color : violet">violet
</span></div>
<div class="sform"><input type="text" value="[bwhite]ваш текст[/bwhite]"><span style="background-color : white">white
</span></div>
        <?
	require_once H.'system/footer.php';
	exit();
}
////////////////////////////////////////////////////////////////
if($_GET['act']=='5'){
	?>
	<a class="home-s" href="/bbcode">В начало</a>
<div class="sform"><input type="text" value="[scr-w]ваш текст[/scr-w]"><span style="background-color:#ffffff;"><span style="color:#ffffff;"><span style="border:1px dashed;">скрытый текст</span></span></span> Cкрытый текст</div>
<div class="sform"><input type="text" value="[scr-bl]ваш текст[/scr-bl]"><span style="background-color:Blue;"><span style="color:Blue;"><span style="border:1px dashed;">скрытый текст</span></span></span> Cкрытый текст</div>
<div class="sform"><input type="text" value="[scr-r]ваш текст[/scr-r]"><span style="background-color:red;"><span style="color:red;"><span style="border:1px dashed;">скрытый текст</span></span></span> Cкрытый текст</div>
<div class="sform"><input type="text" value="[scr-g]ваш текст[/scr-g]"><span style="background-color:green;"><span style="color:green;"><span style="border:1px dashed;">скрытый текст</span></span></span> Cкрытый текст</div>
<div class="sform"><input type="text" value="[scr-b]ваш текст[/scr-b]"><span style="background-color:#000000;"><span style="color:#000000;"><span style="border:1px dashed;">скрытый текст</span></span></span> Cкрытый текст</div>
<div class="sform"><input type="text" value="[scr-d]ваш текст[/scr-d]"><span style="background-color:DarkMagenta;"><span style="color:DarkMagenta;"><span style="border:1px dashed;">скрытый текст</span></span></span> Cкрытый текст</div>
<div class="sform"><input type="text" value="[scr-c]ваш текст[/scr-c]"><span style="background-color:Crimson;"><span style="color:Crimson;"><span style="border:1px dashed;">скрытый текст</span></span></span> Cкрытый текст</div>
<div class="sform"><input type="text" value="[scr-bu]ваш текст[/scr-bu]"><span style="background-color:Burlywood;"><span style="color:Burlywood;"><span style="border:1px dashed;">скрытый текст</span></span></span> Cкрытый текст</div>
<div class="sform"><input type="text" value="[scr-k]ваш текст[/scr-k]"><span style="background-color:Khaki;"><span style="color:Khaki;"><span style="border:1px dashed;">скрытый текст</span></span></span> Cкрытый текст</div>
<div class="sform"><input type="text" value="[scr-1]ваш текст[/scr-1]"><span style="background-color:Peru;"><span style="color:LightPink;"><span style="border:1px dashed;">скрытый текст</span></span></span> Cкрытый текст</div>
<div class="sform"><input type="text" value="[ramk-1]ваш текст[/ramk-1]"><br><span style="background-color:LightGreen;"><span style="color:LightPink;"><span style="border:1px dashed;"> В  рамке искаженный волнами </span></span></span></div>
<div class="sform"><input type="text" value="[ramk-2]ваш текст[/ramk-2]"><br><span style="background-color:MediumSeaGreen;"><span style="color:LightPink;"><span style="border:1px dashed;"> В  рамке искаженный волнами </span></span></span></div>
<div class="sform"><input type="text" value="[ramk-3]ваш текст[/ramk-3]"><br><span style="background-color:ForestGreen;"><span style="color:LightPink;"><span style="border:1px dashed;"> В  рамке искаженный волнами </span></span></span></div>
<div class="sform"><input type="text" value="[ramk-4]ваш текст[/ramk-4]"><br><span style="background-color:PaleTurquoise;"><span style="color:LightPink;"><span style="border:1px dashed;"> В  рамке искаженный волнами </span></span></span></div>
<div class="sform"><input type="text" value="[ramk-5]ваш текст[/ramk-5]"><br><span style="background-color:SteelBlue;"><span style="color:LightPink;"><span style="border:1px dashed;"> В  рамке искаженный волнами </span></span></span></div>
<div class="sform"><input type="text" value="[ramk-6]ваш текст[/ramk-6]"><br><span style="background-color:Orchid;"><span style="color:LightPink;"><span style="border:1px dashed;"> В  рамке искаженный волнами </span></span></span> </div>
<div class="sform"><input type="text" value="[ramk-7]ваш текст[/ramk-7]"><br><span style="background-color:DimGray;"><span style="color:LightPink;"><span style="border:1px dashed;"> В  рамке искаженный волнами </span></span></span></div>

        <?
         require_once H.'system/footer.php';
	exit();
}
////////////////////////////////////////////////////////////////
if($_GET['act']=='6'){
	?>
<a class="home-s" href="/bbcode">В начало</a>
<div class="sform"><input type="text" value="[prlink]ваш текст[/prlink]"><blockquote style="border: 3px solid rgb(218, 112, 214); margin: 0px; padding: 5px;">Простая рамка</blockquote></div>
<div class="sform"><input type="text" value="[prlink2]ваш текст[/prlink2]"><blockquote style="border: 1px solid rgb(218, 112, 214); margin: 0px; padding: 5px;">Простая рамка маленькая</blockquote></div>
<div class="sform"><input type="text" value="[prlink3]ваш текст[/prlink3]"><blockquote style="border: 1px solid rgb(218, 112, 214); margin: 1px; padding: 1px;">Простая рамка еще меньше</blockquote></div>
<div class="sform"><input type="text" value="[prlink4]ваш текст[/prlink4]"><blockquote style="border: 1px solid rgb(110, 112, 214); margin: 1px; padding: 1px;">Простая рамка еще меньше (Голубая)</blockquote></div>
<div class="sform"><input type="text" value="[prlink5]ваш текст[/prlink5]"><blockquote style="border: 1px solid rgb(666, 43, 211); margin: 1px; padding: 1px;">Простая рамка еще меньше (Розавая)</blockquote></div>
<div class="sform"><input type="text" value="[prlink6]ваш текст[/prlink6]"><blockquote style="border: 1px solid rgb(777, 43, 666); center: 0px; padding: 1px;">Простая рамка еще меньше по центру(Светло-озавая)</blockquote></div>
<div class="sform"><input type="text" value="[rams]ваш текст[/rams]"><blockquote style="BORDER-LEFT: #F70000 3px solid; padding: 15px; BORDER-TOP: #119931 3px solid; padding: 15px; BORDER-BOTTOM: #E3AF40 3px solid; padding: 15px; BORDER-RIGHT: #924E96 3px solid" padding:="" 15px="">ваш текст</blockquote></div>
<div class="sform"><input type="text" value="[rams2]ваш текст[/rams2]"><blockquote style="BORDER-LEFT: #F70000 1px solid; padding: 5px; BORDER-TOP: #119931 1px solid; padding: 3px; BORDER-BOTTOM: red 3px solid; padding: 1px; BORDER-RIGHT: #924E96 3px solid" padding:="" 1px="">ваш текст</blockquote></div>

        <?
	 require_once H.'system/footer.php';
	exit();
}
/////// акт восьмой, доп рамки /////////////////////////////////
if($_GET['act']=='7'){
	?>
	<a class="home-s" href="/bbcode">В начало</a>
	<div class="sform"><input type="text" value="[ramkt]ваш текст[/ramkt]"><span style="border-style: dashed; border-color:yellowgreen">В толстой рамке с пробелами</span></div>
        <div class="sform"><input type="text" value="[ramkt2]ваш текст[/ramkt2]"><span style="border-style: dashed; border-color:yellow">В толстой рамке с пробелами</span></div>
        <div class="sform"><input type="text" value="[ramkt3]ваш текст[/ramkt3]"><span style="border-style: dashed; border-color:LightGreen">В толстой рамке с пробелами</span></div>
        <div class="sform"><input type="text" value="[ramkt4]ваш текст[/ramkt4]"><span style="border-style: dashed; border-color:SteelBlue">В толстой рамке с пробелами</span></div>
        <div class="sform"><input type="text" value="[ramkt5]ваш текст[/ramkt5]"><span style="border-style: dashed; border-color:LightPink">В толстой рамке с пробелами</span></div>
        <div class="sform"><input type="text" value="[ramkt6]ваш текст[/ramkt6]"><span style="border-style: dashed; border-color:Gray">В толстой рамке с пробелами</span></div>
        <div class="sform"><input type="text" value="[ramkt7]ваш текст[/ramkt7]"><span style="border-style: dashed; border-color:White">В толстой рамке с пробелами</span></div>
        <div class="sform"><input type="text" value="[ramkt8]ваш текст[/ramkt8]"><span style="border-style: dashed; border-color:MediumSlateBlue">В толстой рамке с пробелами</span> </div>
        <div class="sform"><input type="text" value="[ramkt9]ваш текст[/ramkt9]"><span style="border-style: dashed; border-color:LightSalmon">В толстой рамке с пробелами</span></div>
        <div class="sform"><input type="text" value="[ramkt10]ваш текст[/ramkt10]"><span style="border-style: dashed; border-color:DarkOrange">В толстой рамке с пробелами</span></div>
        <div class="sform"><input type="text" value="[ramkt11]ваш текст[/ramkt11]"><span style="border-style: dashed; border-color:SpringGreen">В толстой рамке с пробелами</span></div>
        <div class="sform"><input type="text" value="[ramkt12]ваш текст[/ramkt12]"><span style="border-style: dashed; border-color:Yellow">В толстой рамке с пробелами</span></div>
        <div class="sform"><input type="text" value="[ramkt13]ваш текст[/ramkt13]"><span style="border-style: dashed; border-color:Gold">В толстой рамке с пробелами</span></div>
        <div class="sform"><input type="text" value="[ramkt14]ваш текст[/ramkt14]"><span style="border-style: dashed; border-color:DarkRed">В толстой рамке с пробелами</span></div>
        <div class="sform"><input type="text" value="[ramkt15]ваш текст[/ramkt15]"><span style="border-style: dashed; border-color:DeepPink">В толстой рамке с пробелами</span></div>
	<?
	require_once H.'system/footer.php';
	exit();
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if($_GET['act']=='8'){
	?>
	<a class="home-s" href="/bbcode">В начало</a>
	
	<p>В таблице перечислены названия цветов (которые можно использовать в качестве значения), поддерживаемые всеми браузерами, и их шестнадцатеричные значения.</p>
<table class="wid">
					<tbody><tr>
						<th style="width: 30%;">Название цвета</th>
						<th style="width: 30%;">HEX</th>
						<th>Цвет</th>
					</tr>

					<tr>
						<td>Black</td><td>#000000</td><td style="background-color:#000000">&nbsp;</td>
					</tr>

					<tr>
						<td>Navy</td><td>#000080</td><td style="background-color:#000080">&nbsp;</td>
					</tr>

					<tr>
						<td>DarkBlue</td><td>#00008B</td><td style="background-color:#00008B">&nbsp;</td>
					</tr>

					<tr>
						<td>MediumBlue</td><td>#0000CD</td><td style="background-color:#0000CD">&nbsp;</td>
					</tr>

					<tr>
						<td>Blue</td><td>#0000FF</td><td style="background-color:#0000FF">&nbsp;</td>
					</tr>

					<tr>
						<td>DarkGreen</td><td>#006400</td><td style="background-color:#006400">&nbsp;</td>
					</tr>

					<tr>
						<td>Green</td><td>#008000</td><td style="background-color:#008000">&nbsp;</td>
					</tr>

					<tr>
						<td>Teal</td><td>#008080</td><td style="background-color:#008080">&nbsp;</td>
					</tr>

					<tr>
						<td>DarkCyan</td><td>#008B8B</td><td style="background-color:#008B8B">&nbsp;</td>
					</tr>

					<tr>
						<td>DeepSkyBlue</td><td>#00BFFF</td><td style="background-color:#00BFFF">&nbsp;</td>
					</tr>

					<tr>
						<td>DarkTurquoise</td><td>#00CED1</td><td style="background-color:#00CED1">&nbsp;</td>
					</tr>

					<tr>
						<td>MediumSpringGreen</td><td>#00FA9A</td><td style="background-color:#00FA9A">&nbsp;</td>
					</tr>

					<tr>
						<td>Lime</td><td>#00FF00</td><td style="background-color:#00FF00">&nbsp;</td>
					</tr>

					<tr>
						<td>SpringGreen</td><td>#00FF7F</td><td style="background-color:#00FF7F">&nbsp;</td>
					</tr>

					<tr>
						<td>Aqua</td><td>#00FFFF</td><td style="background-color:#00FFFF">&nbsp;</td>
					</tr>

					<tr>
						<td>Cyan</td><td>#00FFFF</td><td style="background-color:#00FFFF">&nbsp;</td>
					</tr>

					<tr>
						<td>MidnightBlue</td><td>#191970</td><td style="background-color:#191970">&nbsp;</td>
					</tr>

					<tr>
						<td>DodgerBlue</td><td>#1E90FF</td><td style="background-color:#1E90FF">&nbsp;</td>
					</tr>

					<tr>
						<td>LightSeaGreen</td><td>#20B2AA</td><td style="background-color:#20B2AA">&nbsp;</td>
					</tr>

					<tr>
						<td>ForestGreen</td><td>#228B22</td><td style="background-color:#228B22">&nbsp;</td>
					</tr>

					<tr>
						<td>SeaGreen</td><td>#2E8B57</td><td style="background-color:#2E8B57">&nbsp;</td>
					</tr>

					<tr>
						<td>DarkSlateGray</td><td>#2F4F4F</td><td style="background-color:#2F4F4F">&nbsp;</td>
					</tr>

					<tr>
						<td>LimeGreen</td><td>#32CD32</td><td style="background-color:#32CD32">&nbsp;</td>
					</tr>

					<tr>
						<td>MediumSeaGreen</td><td>#3CB371</td><td style="background-color:#3CB371">&nbsp;</td>
					</tr>

					<tr>
						<td>Turquoise</td><td>#40E0D0</td><td style="background-color:#40E0D0">&nbsp;</td>
					</tr>

					<tr>
						<td>RoyalBlue</td><td>#4169E1</td><td style="background-color:#4169E1">&nbsp;</td>
					</tr>

					<tr>
						<td>SteelBlue</td><td>#4682B4</td><td style="background-color:#4682B4">&nbsp;</td>
					</tr>

					<tr>
						<td>DarkSlateBlue</td><td>#483D8B</td><td style="background-color:#483D8B">&nbsp;</td>
					</tr>

					<tr>
						<td>MediumTurquoise</td><td>#48D1CC</td><td style="background-color:#48D1CC">&nbsp;</td>
					</tr>

					<tr>
						<td>Indigo</td><td>#4B0082</td><td style="background-color:#4B0082">&nbsp;</td>
					</tr>

					<tr>
						<td>DarkOliveGreen</td><td>#556B2F</td><td style="background-color:#556B2F">&nbsp;</td>
					</tr>

					<tr>
						<td>CadetBlue</td><td>#5F9EA0</td><td style="background-color:#5F9EA0">&nbsp;</td>
					</tr>

					<tr>
						<td>CornflowerBlue</td><td>#6495ED</td><td style="background-color:#6495ED">&nbsp;</td>
					</tr>

					<tr>
						<td>MediumAquaMarine</td><td>#66CDAA</td><td style="background-color:#66CDAA">&nbsp;</td>
					</tr>

					<tr>
						<td>DimGray</td><td>#696969</td><td style="background-color:#696969">&nbsp;</td>
					</tr>

					<tr>
						<td>SlateBlue</td><td>#6A5ACD</td><td style="background-color:#6A5ACD">&nbsp;</td>
					</tr>

					<tr>
						<td>OliveDrab</td><td>#6B8E23</td><td style="background-color:#6B8E23">&nbsp;</td>
					</tr>

					<tr>
						<td>SlateGray</td><td>#708090</td><td style="background-color:#708090">&nbsp;</td>
					</tr>

					<tr>
						<td>LightSlateGray</td><td>#778899</td><td style="background-color:#778899">&nbsp;</td>
					</tr>

					<tr>
						<td>MediumSlateBlue</td><td>#7B68EE</td><td style="background-color:#7B68EE">&nbsp;</td>
					</tr>

					<tr>
						<td>LawnGreen</td><td>#7CFC00</td><td style="background-color:#7CFC00">&nbsp;</td>
					</tr>

					<tr>
						<td>Chartreuse</td><td>#7FFF00</td><td style="background-color:#7FFF00">&nbsp;</td>
					</tr>

					<tr>
						<td>Aquamarine</td><td>#7FFFD4</td><td style="background-color:#7FFFD4">&nbsp;</td>
					</tr>

					<tr>
						<td>Maroon</td><td>#800000</td><td style="background-color:#800000">&nbsp;</td>
					</tr>

					<tr>
						<td>Purple</td><td>#800080</td><td style="background-color:#800080">&nbsp;</td>
					</tr>

					<tr>
						<td>Olive</td><td>#808000</td><td style="background-color:#808000">&nbsp;</td>
					</tr>

					<tr>
						<td>Gray</td><td>#808080</td><td style="background-color:#808080">&nbsp;</td>
					</tr>

					<tr>
						<td>SkyBlue</td><td>#87CEEB</td><td style="background-color:#87CEEB">&nbsp;</td>
					</tr>

					<tr>
						<td>LightSkyBlue</td><td>#87CEFA</td><td style="background-color:#87CEFA">&nbsp;</td>
					</tr>

					<tr>
						<td>BlueViolet</td><td>#8A2BE2</td><td style="background-color:#8A2BE2">&nbsp;</td>
					</tr>

					<tr>
						<td>DarkRed</td><td>#8B0000</td><td style="background-color:#8B0000">&nbsp;</td>
					</tr>

					<tr>
						<td>DarkMagenta</td><td>#8B008B</td><td style="background-color:#8B008B">&nbsp;</td>
					</tr>

					<tr>
						<td>SaddleBrown</td><td>#8B4513</td><td style="background-color:#8B4513">&nbsp;</td>
					</tr>

					<tr>
						<td>DarkSeaGreen</td><td>#8FBC8F</td><td style="background-color:#8FBC8F">&nbsp;</td>
					</tr>

					<tr>
						<td>LightGreen</td><td>#90EE90</td><td style="background-color:#90EE90">&nbsp;</td>
					</tr>

					<tr>
						<td>MediumPurple</td><td>#9370D8</td><td style="background-color:#9370D8">&nbsp;</td>
					</tr>

					<tr>
						<td>DarkViolet</td><td>#9400D3</td><td style="background-color:#9400D3">&nbsp;</td>
					</tr>

					<tr>
						<td>PaleGreen</td><td>#98FB98</td><td style="background-color:#98FB98">&nbsp;</td>
					</tr>

					<tr>
						<td>DarkOrchid</td><td>#9932CC</td><td style="background-color:#9932CC">&nbsp;</td>
					</tr>

					<tr>
						<td>YellowGreen</td><td>#9ACD32</td><td style="background-color:#9ACD32">&nbsp;</td>
					</tr>

					<tr>
						<td>Sienna</td><td>#A0522D</td><td style="background-color:#A0522D">&nbsp;</td>
					</tr>

					<tr>
						<td>Brown</td><td>#A52A2A</td><td style="background-color:#A52A2A">&nbsp;</td>
					</tr>

					<tr>
						<td>DarkGray</td><td>#A9A9A9</td><td style="background-color:#A9A9A9">&nbsp;</td>
					</tr>

					<tr>
						<td>LightBlue</td><td>#ADD8E6</td><td style="background-color:#ADD8E6">&nbsp;</td>
					</tr>

					<tr>
						<td>GreenYellow</td><td>#ADFF2F</td><td style="background-color:#ADFF2F">&nbsp;</td>
					</tr>

					<tr>
						<td>PaleTurquoise</td><td>#AFEEEE</td><td style="background-color:#AFEEEE">&nbsp;</td>
					</tr>

					<tr>
						<td>LightSteelBlue</td><td>#B0C4DE</td><td style="background-color:#B0C4DE">&nbsp;</td>
					</tr>

					<tr>
						<td>PowderBlue</td><td>#B0E0E6</td><td style="background-color:#B0E0E6">&nbsp;</td>
					</tr>

					<tr>
						<td>FireBrick</td><td>#B22222</td><td style="background-color:#B22222">&nbsp;</td>
					</tr>

					<tr>
						<td>DarkGoldenRod</td><td>#B8860B</td><td style="background-color:#B8860B">&nbsp;</td>
					</tr>

					<tr>
						<td>MediumOrchid</td><td>#BA55D3</td><td style="background-color:#BA55D3">&nbsp;</td>
					</tr>

					<tr>
						<td>RosyBrown</td><td>#BC8F8F</td><td style="background-color:#BC8F8F">&nbsp;</td>
					</tr>

					<tr>
						<td>DarkKhaki</td><td>#BDB76B</td><td style="background-color:#BDB76B">&nbsp;</td>
					</tr>

					<tr>
						<td>Silver</td><td>#C0C0C0</td><td style="background-color:#C0C0C0">&nbsp;</td>
					</tr>

					<tr>
						<td>MediumVioletRed</td><td>#C71585</td><td style="background-color:#C71585">&nbsp;</td>
					</tr>

					<tr>
						<td>IndianRed</td><td>#CD5C5C</td><td style="background-color:#CD5C5C">&nbsp;</td>
					</tr>

					<tr>
						<td>Peru</td><td>#CD853F</td><td style="background-color:#CD853F">&nbsp;</td>
					</tr>

					<tr>
						<td>Chocolate</td><td>#D2691E</td><td style="background-color:#D2691E">&nbsp;</td>
					</tr>

					<tr>
						<td>Tan</td><td>#D2B48C</td><td style="background-color:#D2B48C">&nbsp;</td>
					</tr>

					<tr>
						<td>LightGray</td><td>#D3D3D3</td><td style="background-color:#D3D3D3">&nbsp;</td>
					</tr>

					<tr>
						<td>PaleVioletRed</td><td>#D87093</td><td style="background-color:#D87093">&nbsp;</td>
					</tr>

					<tr>
						<td>Thistle</td><td>#D8BFD8</td><td style="background-color:#D8BFD8">&nbsp;</td>
					</tr>

					<tr>
						<td>Orchid</td><td>#DA70D6</td><td style="background-color:#DA70D6">&nbsp;</td>
					</tr>

					<tr>
						<td>GoldenRod</td><td>#DAA520</td><td style="background-color:#DAA520">&nbsp;</td>
					</tr>

					<tr>
						<td>Crimson</td><td>#DC143C</td><td style="background-color:#DC143C">&nbsp;</td>
					</tr>

					<tr>
						<td>Gainsboro</td><td>#DCDCDC</td><td style="background-color:#DCDCDC">&nbsp;</td>
					</tr>

					<tr>
						<td>Plum</td><td>#DDA0DD</td><td style="background-color:#DDA0DD">&nbsp;</td>
					</tr>

					<tr>
						<td>BurlyWood</td><td>#DEB887</td><td style="background-color:#DEB887">&nbsp;</td>
					</tr>

					<tr>
						<td>LightCyan</td><td>#E0FFFF</td><td style="background-color:#E0FFFF">&nbsp;</td>
					</tr>

					<tr>
						<td>Lavender</td><td>#E6E6FA</td><td style="background-color:#E6E6FA">&nbsp;</td>
					</tr>

					<tr>
						<td>DarkSalmon</td><td>#E9967A</td><td style="background-color:#E9967A">&nbsp;</td>
					</tr>

					<tr>
						<td>Violet</td><td>#EE82EE</td><td style="background-color:#EE82EE">&nbsp;</td>
					</tr>

					<tr>
						<td>PaleGoldenRod</td><td>#EEE8AA</td><td style="background-color:#EEE8AA">&nbsp;</td>
					</tr>

					<tr>
						<td>LightCoral</td><td>#F08080</td><td style="background-color:#F08080">&nbsp;</td>
					</tr>

					<tr>
						<td>Khaki</td><td>#F0E68C</td><td style="background-color:#F0E68C">&nbsp;</td>
					</tr>

					<tr>
						<td>AliceBlue</td><td>#F0F8FF</td><td style="background-color:#F0F8FF">&nbsp;</td>
					</tr>

					<tr>
						<td>HoneyDew</td><td>#F0FFF0</td><td style="background-color:#F0FFF0">&nbsp;</td>
					</tr>

					<tr>
						<td>Azure</td><td>#F0FFFF</td><td style="background-color:#F0FFFF">&nbsp;</td>
					</tr>

					<tr>
						<td>SandyBrown</td><td>#F4A460</td><td style="background-color:#F4A460">&nbsp;</td>
					</tr>

					<tr>
						<td>Wheat</td><td>#F5DEB3</td><td style="background-color:#F5DEB3">&nbsp;</td>
					</tr>

					<tr>
						<td>Beige</td><td>#F5F5DC</td><td style="background-color:#F5F5DC">&nbsp;</td>
					</tr>

					<tr>
						<td>WhiteSmoke</td><td>#F5F5F5</td><td style="background-color:#F5F5F5">&nbsp;</td>
					</tr>

					<tr>
						<td>MintCream</td><td>#F5FFFA</td><td style="background-color:#F5FFFA">&nbsp;</td>
					</tr>

					<tr>
						<td>GhostWhite</td><td>#F8F8FF</td><td style="background-color:#F8F8FF">&nbsp;</td>
					</tr>

					<tr>
						<td>Salmon</td><td>#FA8072</td><td style="background-color:#FA8072">&nbsp;</td>
					</tr>

					<tr>
						<td>AntiqueWhite</td><td>#FAEBD7</td><td style="background-color:#FAEBD7">&nbsp;</td>
					</tr>

					<tr>
						<td>Linen</td><td>#FAF0E6</td><td style="background-color:#FAF0E6">&nbsp;</td>
					</tr>

					<tr>
						<td>LightGoldenRodYellow</td><td>#FAFAD2</td><td style="background-color:#FAFAD2">&nbsp;</td>
					</tr>

					<tr>
						<td>OldLace</td><td>#FDF5E6</td><td style="background-color:#FDF5E6">&nbsp;</td>
					</tr>

					<tr>
						<td>Red</td><td>#FF0000</td><td style="background-color:#FF0000">&nbsp;</td>
					</tr>

					<tr>
						<td>Fuchsia</td><td>#FF00FF</td><td style="background-color:#FF00FF">&nbsp;</td>
					</tr>

					<tr>
						<td>Magenta</td><td>#FF00FF</td><td style="background-color:#FF00FF">&nbsp;</td>
					</tr>

					<tr>
						<td>DeepPink</td><td>#FF1493</td><td style="background-color:#FF1493">&nbsp;</td>
					</tr>

					<tr>
						<td>OrangeRed</td><td>#FF4500</td><td style="background-color:#FF4500">&nbsp;</td>
					</tr>

					<tr>
						<td>Tomato</td><td>#FF6347</td><td style="background-color:#FF6347">&nbsp;</td>
					</tr>

					<tr>
						<td>HotPink</td><td>#FF69B4</td><td style="background-color:#FF69B4">&nbsp;</td>
					</tr>

					<tr>
						<td>Coral</td><td>#FF7F50</td><td style="background-color:#FF7F50">&nbsp;</td>
					</tr>

					<tr>
						<td>Darkorange</td><td>#FF8C00</td><td style="background-color:#FF8C00">&nbsp;</td>
					</tr>

					<tr>
						<td>LightSalmon</td><td>#FFA07A</td><td style="background-color:#FFA07A">&nbsp;</td>
					</tr>

					<tr>
						<td>Orange</td><td>#FFA500</td><td style="background-color:#FFA500">&nbsp;</td>
					</tr>

					<tr>
						<td>LightPink</td><td>#FFB6C1</td><td style="background-color:#FFB6C1">&nbsp;</td>
					</tr>

					<tr>
						<td>Pink</td><td>#FFC0CB</td><td style="background-color:#FFC0CB">&nbsp;</td>
					</tr>

					<tr>
						<td>Gold</td><td>#FFD700</td><td style="background-color:#FFD700">&nbsp;</td>
					</tr>

					<tr>
						<td>PeachPuff</td><td>#FFDAB9</td><td style="background-color:#FFDAB9">&nbsp;</td>
					</tr>

					<tr>
						<td>NavajoWhite</td><td>#FFDEAD</td><td style="background-color:#FFDEAD">&nbsp;</td>
					</tr>

					<tr>
						<td>Moccasin</td><td>#FFE4B5</td><td style="background-color:#FFE4B5">&nbsp;</td>
					</tr>

					<tr>
						<td>Bisque</td><td>#FFE4C4</td><td style="background-color:#FFE4C4">&nbsp;</td>
					</tr>

					<tr>
						<td>MistyRose</td><td>#FFE4E1</td><td style="background-color:#FFE4E1">&nbsp;</td>
					</tr>

					<tr>
						<td>BlanchedAlmond</td><td>#FFEBCD</td><td style="background-color:#FFEBCD">&nbsp;</td>
					</tr>

					<tr>
						<td>PapayaWhip</td><td>#FFEFD5</td><td style="background-color:#FFEFD5">&nbsp;</td>
					</tr>

					<tr>
						<td>LavenderBlush</td><td>#FFF0F5</td><td style="background-color:#FFF0F5">&nbsp;</td>
					</tr>

					<tr>
						<td>SeaShell</td><td>#FFF5EE</td><td style="background-color:#FFF5EE">&nbsp;</td>
					</tr>

					<tr>
						<td>Cornsilk</td><td>#FFF8DC</td><td style="background-color:#FFF8DC">&nbsp;</td>
					</tr>

					<tr>
						<td>LemonChiffon</td><td>#FFFACD</td><td style="background-color:#FFFACD">&nbsp;</td>
					</tr>

					<tr>
						<td>FloralWhite</td><td>#FFFAF0</td><td style="background-color:#FFFAF0">&nbsp;</td>
					</tr>

					<tr>
						<td>Snow</td><td>#FFFAFA</td><td style="background-color:#FFFAFA">&nbsp;</td>
					</tr>

					<tr>
						<td>Yellow</td><td>#FFFF00</td><td style="background-color:#FFFF00">&nbsp;</td>
					</tr>

					<tr>
						<td>LightYellow</td><td>#FFFFE0</td><td style="background-color:#FFFFE0">&nbsp;</td>
					</tr>

					<tr>
						<td>Ivory</td><td>#FFFFF0</td><td style="background-color:#FFFFF0">&nbsp;</td>
					</tr>

					<tr>
						<td>White</td><td>#FFFFFF</td><td style="background-color:#FFFFFF">&nbsp;</td>
					</tr>

					</tbody></table>
					<?
	require_once H.'system/footer.php';
	exit();
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////// страница тегов ////////////////////////////////////////////
?>
<div class="background_place">
<div class="main_place">
<a class="home-s" href="?act=1">Ссылки </a>
<a class="home-s" href="?act=2">Tекст</a> 
<a class="home-s" href="?act=3">Работа с текстом</a>  
<a class="home-s" href="?act=4">Фоны</a>
<a class="home-s" href="?act=5">Заливки</a>
<a class="home-s" href="?act=6">Рамки</a>
<a class="home-s" href="?act=7">Доп.рамки</a>
<a class="home-s" href="?act=8">обозначение цветов</a>
</div></div>
<?
require_once H.'system/footer.php';
?>




