<?php

class File
{

    // database connection and table name
    private $conn;
    private $table_name = "files";

    // object properties
    public $id;
    public $name;
    public $file_path;
    public $bank_id;
    public $upload_date;
    
    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    function fetchAllFiles()
    {
        $query = "SELECT
                f.id as id, f.name as name, f.file_path as file_path, f.bank_id as bank_id, f.upload_date as upload_date, 
                b.bank_name as bank_name
            FROM
                " . $this->table_name . " f
                LEFT JOIN
                    banks b
                        ON f.bank_id = b.bank_id
            ORDER BY
                id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function createFile()
    {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET
                    name= :name, file_path = :file_path, bank_id = :bank_id";

        $stmt = $this->conn->prepare($query);

        //sanitizing for sql injection
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->file_path = htmlspecialchars(strip_tags($this->file_path));
        $this->bank_id = htmlspecialchars(strip_tags($this->bank_id));

        // bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":file_path", $this->file_path);
        $stmt->bindParam(":bank_id", $this->bank_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    function fetchFile()
    {
        $query = "SELECT f.id, f.name, f.file_path, f.bank_id, f.upload_date, b.bank_name 
                        from " . $this->table_name . " f 
                        LEFT JOIN banks b 
                        ON f.bank_id = b.bank_id
                        where f.id = ?
                        LIMIT 0,1";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->id);

        $stmt->execute();

        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set values to object properties
        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->file_path = $row['file_path'];
        $this->upload_date = $row['upload_date'];
        $this->bank_id = $row['bank_id'];
        $this->bank_name = $row['bank_name'];

        return $row;
    }

    function update()
    {
        $query = "UPDATE " . $this->table_name . "
                    SET
                        name = :name,
                        file_path = :file_path,
                        bank_id = :bank_id
                    WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->file_path = htmlspecialchars(strip_tags($this->file_path));
        $this->bank_id = htmlspecialchars(strip_tags($this->bank_id));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':file_path', $this->file_path);
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

?>