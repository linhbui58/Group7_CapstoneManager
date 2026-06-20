<?php

class Database {

    private static $instance = null;

    private $conn;

    private $host = DB_HOST;
    private $dbname = DB_NAME;
    private $username = DB_USER;
    private $password = DB_PASS;

    private function __construct(){

        try{

            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4",
                $this->username,
                $this->password
            );

            $this->conn->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

            $this->conn->setAttribute(
                PDO::ATTR_DEFAULT_FETCH_MODE,
                PDO::FETCH_ASSOC
            );

        }catch(PDOException $e){

            die("Database Error: " . $e->getMessage());
        }
    }

    public static function getInstance(){

        if(self::$instance == null){

            self::$instance = new Database();
        }

        return self::$instance;
    }

    public function getConnection(){

        return $this->conn;
    }
}