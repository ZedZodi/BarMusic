<?php
    require_once './Classes/DataBase.php';//подключаем класс с объектами для создания db и table
    require_once './Classes/BarClass.php';//подключаем класс с объектами для создания db и table
    require_once './Classes/SelectDeleteDB.php';//подключаем класс с объектами для создания db и table
    require_once './Classes/connection.php';//подключаем конфиг с данными для подключения к mysql

    //приводим пост в нужный вид. Значение в верхнем регистре для удобства
    $arPost = $_POST;

    foreach ($arPost as $KeyPost=>$valuePost)
    {
        $getPost[$KeyPost] = mb_strtoupper ($valuePost);
    }

    $db = new DataBase($username, $password, $host, $dbname);
    $BarClass = new BarClass();
    $SelDelDB = new SelectDeleteDB();

    //вставляем в бд созданных посетителей и жанры
    if($getPost)
    {
        $GenreOld = $SelDelDB->selectGenre();
        if($getPost['Visitor'])
        {
            $GenreVisitorInsert = $SelDelDB->insertNameAndId($getPost);//возвращает id последнего вставленного посетителя или массив существующего
        }
        elseif($getPost['name_Genre'])
        {
            if($GenreOld)
            {
                $key = in_array($getPost['name_Genre'], $GenreOld);
                if(!$key)
                {
                    $GenreInsert = $SelDelDB->insertGenreInDb(ucfirst($getPost['name_Genre']));//возвращает id жанра
                }
            }
            else
            {
                $GenreInsert = $SelDelDB->insertGenreInDb(ucfirst($getPost['name_Genre']));//возвращает id жанра
            }
        }
    }

    //получаем новые жанры для форм
    $Genre = $SelDelDB->selectGenre();

    //удаляем посетителя или жанр
    if($getPost)
    {
        foreach ($getPost as $keyDEl => $valueDEL)
        {
            if(stripos($keyDEl, "DEL") !== false)
            {
                $DeleteDB = $SelDelDB->deleteGanreVisitor($getPost);
            }
        }
    }

    //получаем активный жанр
    if($getPost['genreCheck'])
    {
        $GenrePlay = $getPost['genreCheck'];
    }
    else
    {
        if($Genre)
        {
            $rand_keys = array_rand($Genre);
            $GenrePlay = $Genre[$rand_keys];
        }
    }

    //получаем список посетителей, которые в зависимости от музыки идут либо в бар, либо на танцпол
    if($GenrePlay)
    {
        $arrayVisitorDance = $BarClass->selectBarDance($GenrePlay);
    }

    //получаем список посетителей
    if($arrayVisitorDance)
    {
        foreach($arrayVisitorDance as $ArValueVisitor)
        {
            if($ArValueVisitor)
            {
                foreach($ArValueVisitor as $keyVisitor=>$ValueVisitor)
                {
                    $arVtisior[$keyVisitor] = $keyVisitor;
                }
            }

        }
    }
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>БАР</title>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script src="feedback/js/feedback.js"></script>
        <script src="feedback/js/jquery.arcticmodal.js"></script>
        <script src="feedback/js/scripts.js"></script>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/jquery.arcticmodal.css">
        <link rel="stylesheet" type="text/css" href="css/jquery.jgrowl.css">
        <link rel="stylesheet" type="text/css" href="css/style.css">

    </head>

    <body>
        <div class="container">
            <div class="row">
                <div class="span4">
                    <span class="btn btn-block btn-large btn-success modal_btn">Создать посетителя</span>
                    <span class="btn btn-block btn-large btn-inverse modal_btn3">Удалить посетителя</span>
                </div>
                <div class="span4">
                    <span class="btn btn-block btn-large btn-success modal_btn2">Выбрать Жанр</span>
                </div>
                <div class="span4">
                    <span class="btn btn-block btn-large btn-success modal_btn1">Создать жанр</span>
                    <span class="btn btn-block btn-large btn-inverse modal_btn4"">Удалить жанр</span>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="span4">
                    <div class="well">
                        <legend>Бар</legend>
                        <?php
                            //вывод ситуации на баре
                            if($arrayVisitorDance['Bar'])
                            {
                                foreach($arrayVisitorDance['Bar'] as $keyVisitor => $valVisitor)
                                {
                                    $k=0;
                                    echo "заказывает коктейли $keyVisitor и он/она любит";
                                    foreach($valVisitor as $Ganre)
                                    {
                                        if($k > 0)
                                        {
                                            echo ", $Ganre";
                                        }
                                        else
                                        {
                                            echo " $Ganre";
                                        }
                                        $k++;
                                    }
                                    echo ".<br>";
                                }
                            }
                            else
                            {
                                echo "На баре никого";
                            }
                        ?>
                    </div>
                </div>
                <div class="span4">
                    <div class="well">
                        <legend>Играет сейчас:</legend>
                        <?php
                            echo "$GenrePlay<br>";
                        ?>
                    </div>
                </div>
                <div class="span4">
                    <div class="well">
                        <legend>Танцпол</legend>
                        <?php
                            //вывод ситуации на танцполе
                            if($arrayVisitorDance['Dance'])
                            {
                                foreach($arrayVisitorDance['Dance'] as $keyVisitor => $valVisitor)
                                {
                                    $k=0;
                                    echo "Танцует - $keyVisitor и он/она любит";
                                    foreach($valVisitor as $Ganre)
                                    {
                                        if($k > 0)
                                        {
                                            echo ", $Ganre";
                                        }
                                        else
                                        {
                                            echo " $Ganre";
                                        }
                                        $k++;
                                    }
                                    echo ".<br>";
                                }
                            }
                            else
                            {
                                echo "Никто не танцует";
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div style="display:none;">
            <div class="box-modal" id="small-modal">
                <div class="modal-close arcticmodal-close">X</div>
                <div class="modal-content-box">
                    <form name='formTTT' action="index.php" method="post" name="form" onsubmit='return validate()'>
                        <span class="add-on"><i class="icon-user"></i></span>
                        <input type='text' name='Visitor' placeholder="Имя посетителя"> <span style='color:red' id='namef'></span><br />

                        <?php
                            echo '<div id="checkBoxValidate">';
                            if($Genre)
                            {
                                $columnArray = array_chunk($Genre, ceil(count($Genre)/2), TRUE);
                                foreach ($columnArray as $valueColumn)
                                {
                                    echo '<div class="column">';
                                    foreach($valueColumn as $valueGenreColumn)
                                    {
                                        echo '<label class="checkbox">
                                                  <input type="checkbox" class="checkboxValidate" value="'.$valueGenreColumn.'" name="'.$valueGenreColumn.'-CreateGenre"> '.$valueGenreColumn.'
                                              </label>';
                                    }
                                    echo '</div>';
                                }
                            }
                            echo "</div>";
                        ?>

                        <input class="feedback btn btn-block btn-large btn-success" type="submit" value="Отправить">
                    </form>
                </div>
            </div>
        </div>
        <div style="display:none;">
            <div class="box-modal" id="small-modal2" style="width: 250px;">
                <div class="modal-close arcticmodal-close">X</div>
                <div class="modal-content-box">
                    <form action="index.php" method="post" name="form">
                        <p><b>Выберете жанр или включите рандомную музыку</b></p>
                        <?php
                            if($Genre)
                            {
                                $columnArray = array_chunk($Genre, ceil(count($Genre)/2), TRUE);
                                foreach ($columnArray as $valueColumn)
                                {
                                    echo '<div class="column">';
                                    foreach($valueColumn as $valueGenreColumn)
                                    {
                                        echo '
                                            <label class="checkbox">
                                                <p><input name="genreCheck" type="radio" value="'.$valueGenreColumn.'"> '.$valueGenreColumn.'</p>     
                                            </label>';
                                    }
                                    echo '</div>';
                                }
                                echo '
                                            <label class="checkbox">
                                                <p><input name="genreCheck" type="radio" value="random" checked> РАНДОМНАЯ ПЕСНЯ</p>     
                                            </label>';
                            }
                        ?>
                        <input class="feedback btn btn-block btn-large btn-success" type="submit" value="Отправить">
                    </form>
                </div>
            </div>
        </div>
        <div style="display:none;">
            <div class="box-modal" id="small-modal1" style="width: 250px;">
                <div class="modal-close arcticmodal-close">X</div>
                <div class="modal-content-box">
                    <form action="index.php" method="post" name="form">
                        <div class="input-prepend">
                            <span class="add-on"><i class="icon-music"></i></span>
                            <input class="input-medium focused" name="name_Genre" type="text" placeholder="Название Жанра">
                        </div>
                        <input class="feedback btn btn-block btn-large btn-success" type="submit" value="Отправить">
                    </form>
                </div>
            </div>
        </div>
        <div style="display:none;">
            <div class="box-modal" id="small-modal3" style="width: 250px;">
                <div class="modal-close arcticmodal-close">X</div>
                <div class="modal-content-box">
                    <form action="index.php" method="post" name="form">
                        <p><b>Выберете посетителя, которого вы хотите удалить</b></p>
                        <?php
                            if($arVtisior)
                            {
                                $columnArray = array_chunk($arVtisior, ceil(count($arVtisior)/2), TRUE);

                                foreach ($columnArray as $valueColumn)
                                {
                                    echo '<div class="column">';
                                    foreach($valueColumn as $valueVisitorColumn)
                                    {
                                        echo '
                                                <label class="checkbox">
                                                    <p><input name="VisitorDEL" type="radio" value="'.$valueVisitorColumn.'"> '.$valueVisitorColumn.'</p>     
                                                </label>';
                                    }
                                    echo '</div>';
                                }
                            }
                        ?>
                        <input class="feedback btn btn-block btn-large btn-success" type="submit" value="Удалить">
                    </form>
                </div>
            </div>
        </div>
        <div style="display:none;">
            <div class="box-modal" id="small-modal4" style="width: 250px;">
                <div class="modal-close arcticmodal-close">X</div>
                <div class="modal-content-box">
                    <form action="index.php" method="post" name="form">
                        <p><b>Выберете жанры, которые вы хотите удалить</b></p>
                        <?php
                        if($Genre)
                        {
                            $columnArray = array_chunk($Genre, ceil(count($Genre)/2), TRUE);
                            foreach ($columnArray as $valueColumn)
                            {
                                echo '<div class="column">';
                                foreach($valueColumn as $valueGenreColumn)
                                {
                                    echo '
                                            <label class="checkbox">
                                                <p><input name="GenreDEL" type="radio" value="'.$valueGenreColumn.'"> '.$valueGenreColumn.'</p>     
                                            </label>';
                                }
                                echo '</div>';
                            }
                        }
                        ?>
                        <input class="feedback btn btn-block btn-large btn-success" type="submit" value="Удалить">
                    </form>
                </div>
            </div>
        </div>
        <div style="display:none;">
            <div class="box-modal" id="small-modal3" style="width: 250px;">
                <div class="modal-close arcticmodal-close">X</div>
                <div class="modal-content-box">
                    <form action="index.php" method="post" name="form">
                        <p><b>Выберете посетителя, которого вы хотите удалить</b></p>
                        <?php
                        if($arVtisior)
                        {
                            $columnArray = array_chunk($arVtisior, ceil(count($arVtisior)/2), TRUE);

                            foreach ($columnArray as $valueColumn)
                            {
                                echo '<div class="column">';
                                foreach($valueColumn as $valueVisitorColumn)
                                {
                                    echo '
                                                <label class="checkbox">
                                                    <p><input name="VisitorDEL" type="radio" value="'.$valueVisitorColumn.'"> '.$valueVisitorColumn.'</p>     
                                                </label>';
                                }
                                echo '</div>';
                            }
                        }
                        ?>
                        <input class="feedback btn btn-block btn-large btn-success" type="submit" value="Удалить">
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>

