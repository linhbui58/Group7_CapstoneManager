<?php

class User {

    private $conn;

    public function __construct(){

        $this->conn = Database::getInstance()->getConnection();
    }

    /*
    |--------------------------------------------------------------------------
    | GET ALL USERS
    |--------------------------------------------------------------------------
    */

    public function getAll(){

        $sql = "SELECT *
                FROM users
                ORDER BY id DESC";

        return $this->conn
            ->query($sql)
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | FIND USER BY ID
    |--------------------------------------------------------------------------
    */

    public function find($id){

        $stmt = $this->conn->prepare(
            "SELECT *
             FROM users
             WHERE id=?"
        );

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | FIND USER BY EMAIL
    |--------------------------------------------------------------------------
    */

    public function findByEmail($email){

        $stmt = $this->conn->prepare(
            "SELECT *
             FROM users
             WHERE email=?
             LIMIT 1"
        );

        $stmt->execute([$email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    public function login($email){

        $stmt = $this->conn->prepare(
            "SELECT *
             FROM users
             WHERE email=?
             LIMIT 1"
        );

        $stmt->execute([$email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK EMAIL EXIST
    |--------------------------------------------------------------------------
    */

    public function emailExists($email){

        $stmt = $this->conn->prepare(
            "SELECT id
             FROM users
             WHERE email=?"
        );

        $stmt->execute([$email]);

        return $stmt->rowCount() > 0;
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE USER
    |--------------------------------------------------------------------------
    */

    public function create($data){

        $stmt = $this->conn->prepare(
            "INSERT INTO users
            (
                email,
                password,
                role,
                status
            )
            VALUES(?,?,?,?)"
        );

        $stmt->execute([

            $data['email'],

            password_hash(
                $data['password'],
                PASSWORD_DEFAULT
            ),

            $data['role'],

            'active'
        ]);

        return $this->conn->lastInsertId();
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE USER
    |--------------------------------------------------------------------------
    */

    public function update($id,$data){

        $stmt = $this->conn->prepare(
            "UPDATE users
             SET
                email=?,
                role=?,
                status=?
             WHERE id=?"
        );

        return $stmt->execute([

            $data['email'],
            $data['role'],
            $data['status'] ?? 'active',
            $id
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE PASSWORD
    |--------------------------------------------------------------------------
    */

    public function updatePassword($id,$password){

        $stmt = $this->conn->prepare(
            "UPDATE users
             SET password=?
             WHERE id=?"
        );

        return $stmt->execute([

            password_hash(
                $password,
                PASSWORD_DEFAULT
            ),

            $id
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE USER
    |--------------------------------------------------------------------------
    */

    public function delete($id){

        $stmt = $this->conn->prepare(
            "DELETE FROM users
             WHERE id=?"
        );

        return $stmt->execute([$id]);
    }

    /*
    |--------------------------------------------------------------------------
    | LOCK USER
    |--------------------------------------------------------------------------
    */

    public function lock($id){

        $stmt = $this->conn->prepare(
            "UPDATE users
             SET status='locked'
             WHERE id=?"
        );

        return $stmt->execute([$id]);
    }

    /*
    |--------------------------------------------------------------------------
    | UNLOCK USER
    |--------------------------------------------------------------------------
    */

    public function unlock($id){

        $stmt = $this->conn->prepare(
            "UPDATE users
             SET status='active'
             WHERE id=?"
        );

        return $stmt->execute([$id]);
    }

    /*
    |--------------------------------------------------------------------------
    | TOTAL USERS
    |--------------------------------------------------------------------------
    */

    public function countAll(){

        $stmt = $this->conn->query(
            "SELECT COUNT(*) AS total
             FROM users"
        );

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['total'];
    }

    /*
    |--------------------------------------------------------------------------
    | TOTAL STUDENTS
    |--------------------------------------------------------------------------
    */

    public function countStudents(){

        $stmt = $this->conn->query(
            "SELECT COUNT(*) AS total
             FROM users
             WHERE role='student'"
        );

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['total'];
    }

    /*
    |--------------------------------------------------------------------------
    | TOTAL LECTURERS
    |--------------------------------------------------------------------------
    */

    public function countLecturers(){

        $stmt = $this->conn->query(
            "SELECT COUNT(*) AS total
             FROM users
             WHERE role='lecturer'"
        );

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['total'];
    }

    /*
    |--------------------------------------------------------------------------
    | SEARCH USERS
    |--------------------------------------------------------------------------
    */

    public function search($keyword){

        $stmt = $this->conn->prepare(
            "SELECT *
             FROM users
             WHERE
                email LIKE ?
                OR role LIKE ?
             ORDER BY id DESC"
        );

        $search = "%$keyword%";

        $stmt->execute([

            $search,
            $search
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}