<?php

class Branch
{

    // database connection and table name
    private $conn;
    private $table_name = "branches";

    // object properties
    public $branch_id;
    public $branch_name;

    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    function getBranchesForbranch($branch_id)
    {
        $query = "SELECT
                branch_id, branch_name
            FROM
                " . $this->table_name . " 
            ORDER BY
                branch_name";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }

    function createbranch()
    {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET
                    branch_name= :branch_name";

        $stmt = $this->conn->prepare($query);

        //sanitizing for sql injection
        $this->branch_name = htmlspecialchars(strip_tags($this->branch_name));

        // bind values
        $stmt->bindParam(":branch_name", $this->branch_name);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    function fetchbranch()
    {
        $query = "SELECT branch_id, branch_name 
                        from " . $this->table_name . " 
                        where branch_id = ?
                        LIMIT 0,1";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind id of product to be updated
        $stmt->bindParam(1, $this->branch_id);

        // execute query
        $stmt->execute();

        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set values to object properties
        $this->branch_id = $row['branch_id'];
        $this->branch_name = $row['branch_name'];
    }

    function update()
    {
        $query = "UPDATE " . $this->table_name . "
                    SET
                        branch_name = :branch_name
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