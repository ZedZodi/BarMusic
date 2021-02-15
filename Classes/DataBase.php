<?php

class DataBase {
    public $isConn;
    protected $datab;
    protected static $tableGenre = "genre_table";
    protected static $tableVisitor = "visitor_table";
    protected static $tableGenreVisitor = "genreVisitor_table";

    // подключение к бд
    public function __construct($username = "root", $password = "", $host = "localhost", $dbname = "bar_db", $options = [])
    {
        $this->isConn = TRUE;
        try
        {
            $this->datab = new PDO("mysql:host=$host;charset=utf8;dbname=$dbname", $username, $password, $options);
        }
        catch (PDOException $e)
        {
            if($e->getCode() == 1049)
            {

                $this->datab = new PDO("mysql:host=$host;charset=utf8", $username, $password, $options);
                //осуществление запроса на создание бд, если ее нет
                $this->datab->exec("CREATE DATABASE IF NOT EXISTS `$dbname`;
                        CREATE USER '$username'@'localhost' IDENTIFIED BY '$password';
                        GRANT ALL ON `$dbname`.* TO '$username'@'localhost';
                        FLUSH PRIVILEGES;")
                or die(print_r($this->datab->errorInfo(), true));

                $this->datab->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->datab->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

                $this->datab = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

                $sqlGenre = "CREATE TABLE IF NOT EXISTS ".self::$tableGenre." (
                        `genre_ID` INT(11) NOT NULL AUTO_INCREMENT,
                        `name_Genre` VARCHAR(255) NOT NULL,
                        PRIMARY KEY (`genre_ID`)
                    )";

                $sqlVisitor = "CREATE TABLE IF NOT EXISTS ".self::$tableVisitor." (
                        `visitor_ID` INT(11) NOT NULL AUTO_INCREMENT,
                        `name_Visitor` VARCHAR(255) NOT NULL,
                        PRIMARY KEY (`visitor_ID`)
                    )";

                $genreVisitor = "CREATE TABLE IF NOT EXISTS ".self::$tableGenreVisitor." (
                        `genre_ID` INT(11) NOT NULL,
                        `visitor_ID` INT(11) NOT NULL,
                        PRIMARY KEY (`genre_ID`, `visitor_ID`),
                        INDEX `genre_ID` (`genre_ID`),
                        INDEX `visitor_ID` (`visitor_ID`),
                        CONSTRAINT `FK_".self::$tableVisitor."` FOREIGN KEY (`visitor_ID`) 
                            REFERENCES `".self::$tableVisitor."` (`visitor_ID`) ON DELETE CASCADE,
                        CONSTRAINT `FK_".self::$tableGenre."` FOREIGN KEY (`genre_ID`) 
                            REFERENCES `".self::$tableGenre."` (`genre_ID`) ON DELETE CASCADE
                    )";

                // осуществление запроса
                $this->datab->exec($sqlGenre);
                $this->datab->exec($sqlVisitor);
                $this->datab->exec($genreVisitor);
            }
            else
            {
                throw new Exception($e->getMessage());
            }
        }
    }
    //отключение от бд
    public function Disconnect()
    {
        $this->datab = NULL;
        $this-> isConn = FALSE;
    }
}
?>