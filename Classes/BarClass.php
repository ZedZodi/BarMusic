<?php

    class BarClass extends DataBase
    {
        //получаем список посетителей и их предпочтения
        public function selectBarDance($genreName)
        {

            $nameExist = $this->selectVisitorId($genreName);//получаем visitor_ID,name_Visitor


            foreach ($nameExist as $keyGenre=>$valueGenre)
            {

                $arrayVisitorDance[$valueGenre['visitor_ID']] = $valueGenre['name_Visitor'];
            }


            $allVisitor = $this->selectVisitorIdName();//Получаем таблицу по Посетителям и их любимым жанрам


            foreach ($allVisitor as $keyVisitorGanre => $valueVisitorGenre)
            {
                $arrayVisitorGenre[$valueVisitorGenre['name_Visitor']][] = $valueVisitorGenre['name_Genre'];
            }


            if($arrayVisitorGenre)
            {
                foreach ($arrayVisitorGenre as $keyGenre=>$valueGenre)
                {
                    $arrayAllVisitor[]= $keyGenre;
                }
            }



            if($arrayVisitorDance)
            {
                foreach($arrayVisitorDance as $valueDance)
                {
                    foreach ($arrayVisitorGenre as $keyisVitorGenre => $valueVitorGenre)
                    {
                        if($valueDance == $keyisVitorGenre)
                        {
                            $arrayDanceBar['Dance'][$keyisVitorGenre] = $valueVitorGenre;
                        }
                    }
                }

                $arrayDanceBar['Bar'] = array_diff_key($arrayVisitorGenre, $arrayDanceBar['Dance']);
            }
            else
            {
                $arrayDanceBar['Bar'] = $arrayVisitorGenre;
            }
            return $arrayDanceBar;
        }

        //получаем visitor_ID,name_Visitor
        protected function selectVisitorId($genreName)
        {
            $query = "SELECT ".self::$tableGenreVisitor.".visitor_ID,name_Visitor FROM ".self::$tableGenreVisitor." LEFT JOIN ".self::$tableVisitor." ON
            ".self::$tableGenreVisitor.".visitor_ID = ".self::$tableVisitor.".visitor_ID WHERE ".self::$tableGenreVisitor.".genre_ID = 
            (SELECT genre_ID FROM `genre_table` WHERE name_Genre = '".$genreName."' )";

            $sth = $this->datab->query($query);
            $GenreVisitor = $sth->fetchAll();

            return $GenreVisitor;
        }

        //Получаем таблицу по Посетителям и их любимым жанрам
        protected function selectVisitorIdName()
        {
            $queryVisitor = "SELECT ".self::$tableGenreVisitor.".visitor_ID,name_Visitor, name_Genre FROM ".self::$tableGenreVisitor." 
            LEFT JOIN ".self::$tableVisitor." ON ".self::$tableGenreVisitor.".visitor_ID = ".self::$tableVisitor.".visitor_ID 
            LEFT JOIN ".self::$tableGenre." ON ".self::$tableGenreVisitor.".genre_ID = ".self::$tableGenre.".genre_ID ";

            $sthVisitor = $this->datab->query($queryVisitor);
            $allVisitor = $sthVisitor->fetchAll();

            return $allVisitor;
        }
    }
