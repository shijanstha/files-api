<?php

class Admin
{

    // database connection and table name
    private $conn;
    private $table_name = "admin";

    // object properties
    public $id;
    public $user_name;
    public $password;

    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    function getAllUsers()
    {
        $query = "SELECT * FROM
                " . $this->table_name . " 
            ORDER BY
                id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }

    function createUser()
    {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET
                    username = :user_name, 
                    password = :password
                    ";

        $stmt = $this->conn->prepare($query);

        //sanitizing for sql injection
        $this->user_name = htmlspecialchars(strip_tags($this->user_name));
        $this->password = htmlspecialchars(strip_tags($this->password));

        // bind values
        $stmt->bindParam(":user_name", $this->user_name);
        $stmt->bindParam(":password", $this->password);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    function fetchUser()
    {
        $query = "SELECT * FROM " . $this->table_name . " 
                        where id = ?
                        LIMIT 0,1";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->id);

        // execute query
        $stmt->execute();

        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set values to object properties
        $this->user_name = $row['user_name'];
        $this->password = $row['password'];
        $this->id = $row['id'];
    }

    function update()
    {
        $query = "UPDATE " . $this->table_name . "
                    SET
                        user_name = :user_name,
                        password = :password
                    WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->user_name = htmlspecialchars(strip_tags($this->user_name));
        $this->password = htmlspecialchars(strip_tags($this->password));

        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':user_name', $this->user_name);
        $stmt->bindParam(':password', $this->password);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id = htmlspecialchars(strip_tags($this->id));

        // bind id of record to delete
        $stmt->bindParam(1, $this->id);

        // execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    function checkLogin()
    {
        $query = "SELECT * FROM
                " . $this->table_name . " 
                WHERE 
                    user_name = :user_name 
                    and 
                    password = :password";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        //sanitizing for sql injection
        $this->user_name = htmlspecialchars(strip_tags($this->user_name));
        $this->password = htmlspecialchars(strip_tags($this->password));

        // bind values
        $stmt->bindParam(":user_name", $this->user_name);
        $stmt->bindParam(":password", $this->password);

        // execute query
        $stmt->execute();

        return $stmt;
    }
}
