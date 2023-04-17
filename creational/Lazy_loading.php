<?php

// ?????????????????

class DbConnection
{
    private $conneciton = null;
    private $parameters;

    public function __construct($parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * Выполняет запрос и возвращает результаты его выполнения
     */
    public function query($query)
    {
        $arrayResult = array();
        $queryResult = $this->getConnection()->query($query);

        if (!empty($queryResult)) {
            while ($row = $queryResult->fetch_array(MYSQLI_ASSOC)) {
                $arrayResult[] = $row;
            }
            $queryResult->close();
        }
        return $arrayResult;
    }

    /**
     * Возвращает соединеие с базой данных, если оно уже было создано
     * Если нет, то создаёт его.
     *
     * @return mysqli
     */
    private function getConnection()
    {
        if ($this->conneciton === null) {
            // 'host' - как пример
            $this->conneciton = new mysqli('host');
        }
        return $this->conneciton;
    }
}

$dbConnection = new DbConnection('domnikl:1234@localhost:3306');
var_dump($dbConnection->query('SELECT * FROM `user` LIMIT 2'));

// ----------- 2 -----------

trait Lazy
{
    private $_lazyProperties = [];

    private function getPropertyValue($propertyName)
    {
        if (isset($this->_lazyProperties[$propertyName])) {
            return $this->_lazyProperties[$propertyName];
        }

        if (!isset($this->_propertyLoaders[$propertyName])) {
            throw new Exception("Property $propertyName does not have loader!");
        }

        $propertyValue = $this->_propertyLoaders[$propertyName]();
        $this->_lazyProperties[$propertyName] = $propertyValue;
        return $propertyValue;
    }

    public function __call($methodName, $arguments)
    {
        if (strpos($methodName, 'get') !== 0) {
            throw new Exception("Method $methodName is not implemented!");
        }

        $propertyName = substr($methodName, 3);
        if (isset($this->_lazyProperties[$propertyName])) {
            return $this->_lazyProperties[$propertyName];
        }

        $propertyInializerName = 'lazy' . $propertyName;
        $propertyValue = $this->$propertyInializerName();
        $this->_lazyProperties[$propertyName] = $propertyValue;
        return $propertyValue;
    }
}


class Test
{
    use Lazy;

    protected function lazyX()
    {
        echo("Initalizer called.\r\n");
        return "X THE METHOD";
    }
}

$t = new Test;
echo $t->getX() . "\n";
echo $t->getX() . "\n";
// Initalizer called.
// X THE METHOD
// X THE METHOD
