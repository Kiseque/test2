<?php

namespace app;

require_once __DIR__ . '/dbConst.php';
use \PDO;
class dbConnect
{
    private $link;

    /**
     * Функция определяет тип данных для записи подготавливаемого запроса
     * @param $typeParam string тип данного введенного пользователем
     * @return int
     */
    private function typeParameter($typeParam)
    {
        switch ($typeParam) {
            case 'int' :
                return 1;
                break;
            case 'string' :
                return 2;
                break;
            case 'bool' :
                return 5;
                break;
        }
    }


    public function __construct()
    {
        $this->connect();
    }

    private function connect()
    {
        $this->link = new \PDO('mysql:host=' . HOST . ';dbname=' . DB_NAME . ';charset=' . CHARSET, USER, PASSWORD);

        return $this;
    }

    /**
     * функция выполняет sql запрос и принимает значение выполненного sql запроса
     */
    public function query($sql, $params = [])
    {
        $sqlQuery = $this->link->prepare($sql);
        if ((isset($params)) && (is_array($params))) {
            foreach ($params as $i => $value) {
                $sqlQuery->bindParam($i + 1, $value[0], $this->typeParameter($value[1]));
            }
        }

        $sqlQuery->execute();

        return $sqlQuery->fetchAll(PDO::FETCH_ASSOC);;
    }

    /**
     * функция подготавливает запрос к транзакции
     */
    public function beginTransaction()
    {
        $this->link->beginTransaction();
    }

    /**
     * Функция записывает изменения в таблице в базе данных
     */
    public function commit()
    {
        $this->link->commit();
    }

    /**
     * Функция откатывает изменения в таблице в случае какой-либо ошибки
     */
    public function rollBack()
    {
        $this->link->rollBack();
    }

}