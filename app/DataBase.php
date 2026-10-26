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

    public function select($order = 'id DESC')
    {
        $stmt = $this->connection->query('SELECT * FROM ' . $this->table . ' ORDER BY ' . $order);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Atualiza registros. Ex.: update('id = 5', ['nome' => 'Maria'])
    public function update($where, $values)
    {
        $fields = array_keys($values);

        $query = 'UPDATE ' . $this->table .
            ' SET ' . implode(' = ?, ', $fields) . ' = ?' .
            ' WHERE ' . $where;

        $stmt = $this->connection->prepare($query);
        $stmt->execute(array_values($values));

        return true;
    }

    // Exclui registros. Ex.: delete('id = 5')
    public function delete($where)
    {
        $query = 'DELETE FROM ' . $this->table . ' WHERE ' . $where;

        $stmt = $this->connection->prepare($query);
        $stmt->execute();

        return true;
    }
} 