    $(document).ready(function(){
        $("#dialog").hide(); //скрываем окно при загрузке страница
    });

    function opendialog(){
        $("#dialog").fadeIn(); //плавное появление блока
    }

    function closedialog(){
        $("#dialog").fadeOut(); //плавное исчезание блока
    }