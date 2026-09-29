<?php
namespace App;

use PDO;

class DataBase
{
    const HOST     = 'localhost';
    const USER     = 'root';
    const PASSWORD = '123';
    const DBNAME   = 'consultorio';

    private $connection;
    private $table;

    public function __construct($table = null)
    {
        $this->setConnection();
        $this->table = $table;
    }

    private function setConnection()
    {
        $this->connection = new PDO(
            'mysql:host=' . self::HOST . ';dbname=' . self::DBNAME . ';charset=utf8mb4',
            self::USER,
            self::PASSWORD
        );
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function insert($array)
    {
        $fields = array_keys($array);
        $binds  = array_pad([], count($fields), '?');

        $query = 'INSERT INTO ' . $this->table .
                 ' (' . implode(', ', $fields) . ')' .
                 ' VALUES (' . implode(', ', $binds) . ')';

        $stmt = $this->connection->prepare($query);
        $stmt->execute(array_values($array));

        return $this->connection->lastInsertId();
    }

    public function select()
    {
        $stmt = $this->connection->query('SELECT * FROM ' . $this->table . ' ORDER BY id DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}