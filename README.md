    Данный скрипт предназначен для эмулирования бара с произвольным кол-вом посетителей и с произвольным набором любимых жанров.
	В нем вы можете создавать жанры, записывать посетителей с ОБЯЗАТЕЛЬНЫМ выбором жанра, выбирать музыку, которая играет в баре, а также удалять
жанры или посетителей.

    Для старта работы скрипта нужно только внести в файл config.php данные для подключения
к MYSQL. БД и Таблица создаются автоматически при первом запуске скрипта, если таких бд или
таблицы не существует у вас. Если существует, то лучше переименовать бд, а именно переменную
$dbname в config.php.
	
	Для корректной работы скрипта вам следует выполнить следующий порядок шагов:
	1) Создать Жанр(Жанры создаются по одному)
	2) Создать Посетителя(посетители создаются по одному)
	3) Если вы хотите удалить жанры или посетителей, вы можете делать это группами.