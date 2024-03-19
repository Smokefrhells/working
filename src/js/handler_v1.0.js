/*Добавление смайлов*/
$(document).on('click','.smiles',function(){
var str = $(this).attr('src');
var str2 = str.split('.')[0];
var smile = str2.split('/')[5];
if(typeof(smile != undefined)){
var tex = $('#mail').val();
$('#mail').val(tex+':'+smile);
}
return false;
});
/*Открытие и закрытие панели смайлов*/
$(document).on('click','.smiles_button',function(){
$('#panel_smiles').toggleClass('enabled');
return false;
});
/*Открытие и закрытие панели смайлов в клане*/
$(document).on('click','.smiles_button_t',function(){
$('#panel_smiles').toggleClass('enabled');
return false;
});
/*Открытие и закрытие панели редактирования форума*/
$(document).on('click','#button_p_f',function(){
$('#panel_forum').toggleClass('enabled');
return false;
});
/*Добавление в форму ника*/
$(document).on('click','#otv',function(){
var data = $(this).attr('class');
var nick = data.split('/')[0];
var id = data.split('/')[1];
$('#mail').val(nick+', ');
$("input[name='ank_id']").val(id);
return false;
});

/*Скрытие ошибки или успешного ответа*/
$(document).on('click','#not_exit',function(){
$('.not_e').remove();
return false;
});

$(document).on('click','#arrow',function(){
var img = $(".arrow").attr("src");
if(img == '/style/images/mail/arrow_bot.png'){
$(".arrow").attr("src", "/style/images/mail/arrow_up.png");
}else{
$(".arrow").attr("src", "/style/images/mail/arrow_bot.png");	
}
$('#panel').toggleClass('enabled');
return false;
});