<?php


    class SelectDeleteDB extends DataBase
    {
        //вставляем в genre_table и visitor_table данные
        public function insertNameAndId($nameGenre)
        {
            $arrID = array();
            foreach ($nameGenre as $key=>$value)
            {
                if(stripos($key, "CreateGenre") !== false)
                {
                    $id = "genre_ID";
                    $columnName = "name_Genre";
                    $table = self::$tableGenre;
                    $nameExist = $this->nameExistsInDb(ucfirst ($value), $table, $id, $columnName);
                    if ($nameExist == false)
                    {
                        $nameExist = $this->insertNameInDb(ucfirst ($value), $table, $columnName);
                    }
                    $arrID[$key] = $nameExist;
                }
                elseif ($key == "Visitor")
                {
                    $id = "visitor_ID";
                    $columnName = "name_Visitor";
                    $table = self::$tableVisitor;
                    $nameExist = $this->nameExistsInDb(ucfirst ($value), $table, $id, $columnName);
                    if ($nameExist == false)
                    {
                        $nameExist = $this->insertNameInDb(ucfirst ($value), $table, $columnName);
                    }
                    $arrID[$key] = $nameExist;
                }
            }

            foreach ($arrID as $keyCon=>$idCon)
            {
                if(stripos($keyCon, "CreateGenre") !== false)
                {
                    $visitorID = $arrID['Visitor'];
                    $genreID = $idCon;

                    $tableConnect = self::$tableGenreVisitor;
                    $nameExistConnect = $this->nameExistsInDbConnect($tableConnect, $visitorID, $genreID);
                    if ($nameExistConnect == false)
                    {
                        $nameExistConnect = $this->insertIdInDbConnection($tableConnect, $visitorID, $genreID);
                    }
                }
            }
            return $nameExistConnect;
        }

        //проверка на то, есть ЖАНР уже в таблице и получаем id
        protected function nameExistsInDb($value, $table,$id, $columnName)
        {
            $query = "SELECT * FROM ".$table." WHERE $columnName = '$value'";
            $stmt = $this->datab->prepare($query);
            $stmt -> execute($params);
            $result = $stmt->fetch();

            return (empty($result)) ? false : $result[$id];
        }

        //получаем id Жанра при создании записи
        protected function insertNameInDb($value, $table, $columnName)
        {
            $query = "INSERT INTO ".$table." ($columnName) " .
                " VALUES (:$columnName)";
            $stmnt = $this->datab->prepare($query);
            $params = array(
                "$columnName" => $value,
            );
            $stmnt->execute($params);

            return $this->datab->lastInsertId();
        }

        //Возвращает id последнего вставленного
        public function insertGenreInDb($value)
        {
            $query = "INSERT INTO " . self::$tableGenre .
                " (name_Genre) " .
                " VALUES (:name_Genre)";
            $stmnt = $this->datab->prepare($query);
            $params = array(
                "name_Genre" => $value,
            );
            $stmnt->execute($params);

            return $this->datab->lastInsertId();
        }

        //вставляем в бд genreVisitor_table данные
        protected function insertIdInDbConnection($tableConnect, $visitorID, $genreID)
        {
            $query = 'INSERT INTO '.$tableConnect.' (genre_ID, visitor_ID) VALUES(
            '.$genreID.','.$visitorID.')';

            $stmnt = $this->datab->prepare($query);
            $params = array(
                "genre_ID" => $genreID,
                "visitor_ID" => $visitorID,
            );
            $stmnt->execute($params);

            return $this->datab->lastInsertId();
        }

        //проверяем есть ли данные в таблице genreVisitor_table
        protected function nameExistsInDbConnect($tableConnect, $visitorID, $genreID)
        {
            $query = "SELECT * FROM ".$tableConnect." 
            WHERE genre_ID = '".$genreID."' 
            and visitor_ID = '".$visitorID."'";
            $stmt = $this->datab->prepare($query);
            $stmt -> execute($params);
            $result = $stmt->fetch();
            return (empty($result)) ? false : $result;
        }

        //Получаем все жанры
        public function selectGenre()
        {
            $query = "SELECT name_Genre FROM ".self::$tableGenre."";
            $sth = $this->datab->query($query);
            $Genre = $sth->fetchAll();
            foreach ($Genre as $keyGenre=>$valueGenre)
            {
                $arrayAllGenre[$keyGenre]= $valueGenre[0];
            }
            return $arrayAllGenre;
        }

        //Удалить посетителя или жанр
        public function deleteGanreVisitor($getPost)
        {

            foreach($getPost as $delKey=>$delGEnreVisitor)
            {

                if(stripos($delKey,'VisitorDEL')!==FALSE)
                {
                    $queryDel = "DELETE FROM `".SELF::$tableVisitor."` WHERE `".SELF::$tableVisitor."`.`name_Visitor` = '".$delGEnreVisitor."'";
                }
                if(stripos($delKey,'GenreDEL')!==FALSE)
                {
                    $queryDel = "DELETE FROM `".SELF::$tableGenre."` WHERE `".SELF::$tableGenre."`.`name_Genre` = '".$delGEnreVisitor."'";
                }

                $sthVisitorGenre = $this->datab->query($queryDel);
                $delVisitor = $sthVisitorGenre->fetchAll();
                return $delVisitor;
            }


        }
    }