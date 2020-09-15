<?php

class Bank
{

    // database connection and table name
    private $conn;
    private $table_name = "banks";

    // object properties
    public $bank_id;
    public $bank_name;

    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    function fetchAllBanks()
    {
        $query = "SELECT
                bank_id, bank_name
            FROM
                " . $this->table_name . " 
            ORDER BY
                bank_name";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }

    function createBank()
    {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET
                    bank_name= :bank_name";

        $stmt = $this->conn->prepare($query);

        //sanitizing for sql injection
        $this->bank_name = htmlspecialchars(strip_tags($this->bank_name));

        // bind values
        $stmt->bindParam(":bank_name", $this->bank_name);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    function fetchBank()
    {
        $query = "SELECT bank_id, bank_name 
                        from " . $this->table_name . " 
                        where bank_id = ?
                        LIMIT 0,1";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind id of product to be updated
        $stmt->bindParam(1, $this->bank_id);

        // execute query
        $stmt->execute();

        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set values to object properties
        $this->bank_id = $row['bank_id'];
        $this->bank_name = $row['bank_name'];
    }

    function update()
    {
        $query = "UPDATE " . $this->table_name . "
                    SET
                        bank_name = :bank_name
                    WHERE bank_id = :bank_id";

        $stmt = $this->conn->prepare($query);

        $this->bank_id = htmlspecialchars(strip_tags($this->bank_id));
        $this->bank_name = htmlspecialchars(strip_tags($this->bank_name));

        $stmt->bindParam(':bank_id', $this->bank_id);
        $stmt->bindParam(':bank_name', $this->bank_name);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE bank_id = ?";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->bank_id = htmlspecialchars(strip_tags($this->bank_id));

        // bind id of record to delete
        $stmt->bindParam(1, $this->bank_id);

        // execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}

?>