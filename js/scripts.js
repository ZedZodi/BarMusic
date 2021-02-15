    //Pop-up для формы "Создать посетителя"
    $(document).ready(function() {
        $(document).on('click', '.modal_btn', function(){
            $('#small-modal').arcticmodal();
        });
    });

    //Pop-up для формы "Создать жанр"
    $(document).ready(function() {
        $(document).on('click', '.modal_btn1', function(){
            $('#small-modal1').arcticmodal();
        });
    });

    //Pop-up для формы "Выбрать Жанр"
    $(document).ready(function() {
        $(document).on('click', '.modal_btn2', function(){
            $('#small-modal2').arcticmodal();
        });
    });

    //Pop-up для формы "Удалить посетителя"
    $(document).ready(function() {
        $(document).on('click', '.modal_btn3', function(){
            $('#small-modal3').arcticmodal();
        });
    });

    //Pop-up для формы "Удалить жанр"
    $(document).ready(function() {
        $(document).on('click', '.modal_btn4', function(){
            $('#small-modal4').arcticmodal();
        });
    });

    //Отмена повторной отправки формы
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }


    function validate()
    {
        //Считаем значения из полей name в переменные x
        var xName=document.forms['formTTT']['Visitor'].value;
        //Если поле name пустое выведем сообщение и предотвратим отправку формы
        if (xName.length==0){
            document.getElementById('namef').innerHTML='*данное поле обязательно для заполнения';
            return false;
        }

        //Проверяем checkbox на заполнение
        var ch = document.getElementById('checkBoxValidate').getElementsByClassName('checkboxValidate');
        var error = 1;
        for (var i=0; i<ch.length; i++)
        {
            if (ch[i].checked) { error = 0; break; }
        }
        if (error)  {
            document.getElementById('namef').innerHTML='*Выберете CheckBox';
            return false;
        }
    }
