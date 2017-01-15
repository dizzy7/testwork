<?php

namespace Db;

use Application;
use Exceptions\DbResultCountException;

class Db
{
    /** @var Db */
    private static $instance;

    /** @var \PDO */
    private $connection;

    protected function __construct()
    {
        $this->initConnection();
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Db();
        }

        return self::$instance;
    }

    public function beginTransaction()
    {
        $this->connection->beginTransaction();
    }

    public function commit()
    {
        $this->connection->commit();
    }

    public function query($sql, array $params = [])
    {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function querySingle($sql, array $params = [])
    {
        $result = $this->query($sql, $params);

        if (count($result) !== 1) {
            throw new DbResultCountException();
        }

        return $result[0];
    }

    public function execute($sql, $params)
    {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
    }

    private function initConnection()
    {
        $this->connection = new \PDO(
            sprintf(
                'pgsql:dbname=%s;host=%s',
                Application::$config['db']['database'],
                Application::$config['db']['host']
            ),
            Application::$config['db']['username'],
            Application::$config['db']['password']
        );
    }
}