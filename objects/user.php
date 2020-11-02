<?php

class User
{

    // database connection and table name
    private $conn;
    private $table_name = "users";

    // object properties
    public $id;
    public $name;
    public $address;
    public $user_name;
    public $password;
    public $contactno;
    public $posting_date;
    public $bank_id;
    public $bank_name;

    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    function fetchAllUsers()
    {
        $query = "SELECT
                u.id as id, u.name as name, u.address as address, u.user_name as user_name, u.posting_date as posting_date,
                u.password as password, u.contactno as contactno, u.bank_id as bank_id, b.bank_name as bank_name 
            FROM
                " . $this->table_name . " u
                LEFT JOIN
                    banks b
                        ON u.bank_id = b.bank_id
            ORDER BY
                u.name";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function authUser()
    {

        // select all query
        $query = "SELECT * FROM " . $this->table_name . " where user_name = :username and password = :pwd";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitization
        $this->user_name = htmlspecialchars(strip_tags($this->user_name));
        $this->password = htmlspecialchars(strip_tags($this->password));

        // binding
        $stmt->bindParam(":username", $this->user_name);
        $stmt->bindParam(":pwd", $this->password);
        // execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    function creatUser()
    {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET
                    name= :name, address= :address, user_name = :user_name, password = :password, 
                    contactno = :contactno, bank_id = :bank_id";

        $stmt = $this->conn->prepare($query);

        //sanitizing for sql injection
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->user_name = htmlspecialchars(strip_tags($this->user_name));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->contactno = htmlspecialchars(strip_tags($this->contactno));
        $this->bank_id = htmlspecialchars(strip_tags($this->bank_id));

        // bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":address", $this->address);
        $stmt->bindParam(":user_name", $this->user_name);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":contactno", $this->contactno);
        $stmt->bindParam(":bank_id", $this->bank_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    function fetchUser()
    {
        $query = "SELECT u.name as first_name, u.address as last_name, u.user_name as user_name,
                            u.password as password, u.contactno as contactno, u.bank_id as bank_id, b.bank_name as bank_name      
                        from " . $this->table_name . " u 
                        left join banks b on u.bank_id = b.bank_id
                        where u.id = ?
                        LIMIT 0,1";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind id of product to be updated
        $stmt->bindParam(1, $this->id);

        // execute query
        $stmt->execute();

        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set values to object properties
        $this->name = $row['first_name'];
        $this->address = $row['last_name'];
        $this->user_name = $row['user_name'];
        $this->password = $row['password'];
        $this->contactno = $row['contactno'];
        $this->bank_id = $row['bank_id'];
        $this->bank_name = $row['bank_name'];
    }

    function update()
    {
        $query = "UPDATE " . $this->table_name . "
                    SET
                        name = :name,
                        address = :address,
                        user_name = :user_name,
                        password = :password,
                        contactno = :contactno,
                        bank_id = :bank_id
                    WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->user_name = htmlspecialchars(strip_tags($this->user_name));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->contactno = htmlspecialchars(strip_tags($this->contactno));
        $this->bank_id = htmlspecialchars(strip_tags($this->bank_id));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':user_name', $this->user_name);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':contactno', $this->contactno);
        $stmt->bindParam(':bank_id', $this->bank_id);
        $stmt->bindParam(':id', $this->id);

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
}
