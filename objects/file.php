<?php

class File
{

    // database connection and table name
    private $conn;
    private $table_name = "files";

    // object properties
    public $id;
    public $name;
    public $size;
    public $downloads;
    public $file_path;
    public $bank_id;
    public $branch_id;

    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    function fetchAllFiles()
    {
        $query = "SELECT
                f.id as id, f.name as name, f.size as size, f.downloads as downloads, f.file_path as file_path, f.bank_id as bank_id, 
                b.bank_name as bank_name, f.branch_id as branch_id, br.branch_name as branch_name
            FROM
                " . $this->table_name . " f
                LEFT JOIN
                    banks b
                        ON f.bank_id = b.bank_id
                LEFT JOIN
                    branches br
                        ON f.branch_id = br.id
            ORDER BY
                id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function creatFile()
    {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET
                    name= :name, size= :size, downloads = 0, file_path = :file_path, bank_id = :bank_id, branch_id = :branch_id";

        $stmt = $this->conn->prepare($query);

        //sanitizing for sql injection
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->size = htmlspecialchars(strip_tags($this->size));
        $this->file_path = htmlspecialchars(strip_tags($this->file_path));
        $this->bank_id = htmlspecialchars(strip_tags($this->bank_id));
        $this->branch_id = htmlspecialchars(strip_tags($this->branch_id));

        // bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":size", $this->size);
        $stmt->bindParam(":file_path", $this->file_path);
        $stmt->bindParam(":bank_id", $this->bank_id);
        $stmt->bindParam(":branch_id", $this->branch_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    function fetchFile()
    {
        $query = "SELECT f.id, f.name, f.size, f.downloads, f.file_path, f.bank_id, b.bank_name, f.branch_id, br.branch_name 
                        from " . $this->table_name . " f 
                        LEFT JOIN banks b 
                        ON f.bank_id = b.bank_id
                        LEFT JOIN branches br
                        ON f.branch_id = br.id
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
        $this->size = $row['size'];
        $this->downloads = $row['downloads'];
        $this->file_path = $row['file_path'];
        $this->bank_id = $row['bank_id'];
        $this->bank_name = $row['bank_name'];
        $this->branch_id = $row['branch_id'];
        $this->branch_name = $row['branch_name'];

        return $row;
    }

    function update()
    {
        $query = "UPDATE " . $this->table_name . "
                    SET
                        name = :name,
                        size = :size,
                        downloads = :downloads,
                        file_path = :file_path,
                        bank_id = :bank_id,
                        branch_id = :branch_id
                    WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->size = htmlspecialchars(strip_tags($this->size));
        $this->file_path = htmlspecialchars(strip_tags($this->file_path));
        $this->downloads = htmlspecialchars(strip_tags($this->downloads));
        $this->bank_id = htmlspecialchars(strip_tags($this->bank_id));
        $this->branch_id = htmlspecialchars(strip_tags($this->branch_id));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':size', $this->size);
        $stmt->bindParam(':downloads', $this->downloads);
        $stmt->bindParam(':file_path', $this->file_path);
        $stmt->bindParam(':bank_id', $this->bank_id);
        $stmt->bindParam(':branch_id', $this->branch_id);
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