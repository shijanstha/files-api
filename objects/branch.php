<?php

class Branch
{

    // database connection and table name
    private $conn;
    private $table_name = "branches";

    // object properties
    public $id;
    public $branch_name;
    public $address;
    public $bank_id;

    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    function getAllBranchesForBank()
    {
        $query = "SELECT
                id, branch_name, address, bank_id
            FROM
                " . $this->table_name . " 
            WHERE bank_id = :bank_id
            ORDER BY
                branch_name";
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        //sanitizing for sql injection
        $this->bank_id = htmlspecialchars(strip_tags($this->bank_id));

        // bind values
        $stmt->bindParam(":bank_id", $this->bank_id);

        // execute query
        $stmt->execute();

        return $stmt;
    }

    function createbranch()
    {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET
                    branch_name= :branch_name, 
                    bank_id= :bank_id, 
                    address= :address
                    ";

        $stmt = $this->conn->prepare($query);

        //sanitizing for sql injection
        $this->branch_name = htmlspecialchars(strip_tags($this->branch_name));
        $this->bank_id = htmlspecialchars(strip_tags($this->bank_id));
        $this->address = htmlspecialchars(strip_tags($this->address));

        // bind values
        $stmt->bindParam(":branch_name", $this->branch_name);
        $stmt->bindParam(":bank_id", $this->bank_id);
        $stmt->bindParam(":address", $this->address);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    function fetchbranch()
    {
        $query = "SELECT id, branch_name, address, bank_id 
                        from " . $this->table_name . " 
                        where id = ?
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
        $this->bank_id = $row['bank_id'];
        $this->address = $row['address'];
        $this->branch_name = $row['branch_name'];
    }

    function update()
    {
        $query = "UPDATE " . $this->table_name . "
                    SET
                        branch_name = :branch_name,
                        address = :address
                    WHERE branch_id = :branch_id";

        $stmt = $this->conn->prepare($query);

        $this->branch_id = htmlspecialchars(strip_tags($this->branch_id));
        $this->branch_name = htmlspecialchars(strip_tags($this->branch_name));

        $stmt->bindParam(':branch_id', $this->branch_id);
        $stmt->bindParam(':branch_name', $this->branch_name);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE branch_id = ?";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->branch_id = htmlspecialchars(strip_tags($this->branch_id));

        // bind id of record to delete
        $stmt->bindParam(1, $this->branch_id);

        // execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}

?>