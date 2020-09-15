<?php

class User
{

    // database connection and table name
    private $conn;
    private $table_name = "users";

    // object properties
    public $id;
    public $fname;
    public $lname;
    public $user_name;
    public $password;
    public $contactno;
    public $posting_date;
    public $bank_id;
    public $branch_id;
    public $role;
    public $bank_name;
    public $branch_name;

    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    function fetchAllUsers()
    {
        $query = "SELECT
                u.id as id, u.fname as fname, u.lname as lname, u.user_name as user_name, u.posting_date as posting_date,
                u.password as password, u.contactno as contactno, u.bank_id as bank_id, b.bank_name as bank_name, 
                u.branch_id, u.role, br.branch_name
            FROM
                " . $this->table_name . " u
                LEFT JOIN
                    banks b
                        ON u.bank_id = b.bank_id
                LEFT JOIN
                    branches br
                        ON u.branch_id = br.id
            ORDER BY
                u.fname";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function authUser()
    {

        // select all query
        $query = "SELECT * FROM " . $this->table_name . " where user_name = :user_name and password = :password";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitization
        $this->user_name = htmlspecialchars(strip_tags($this->user_name));
        $this->password = htmlspecialchars(strip_tags($this->password));

        // binding
        $stmt->bindParam(":user_name", $this->user_name);
        $stmt->bindParam(":password", $this->password);

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
                    fname= :fname, lname= :lname, user_name = :user_name, password = :password, 
                    contactno = :contactno, bank_id = :bank_id, branch_id = :branch_id, role = : role";

        $stmt = $this->conn->prepare($query);

        //sanitizing for sql injection
        $this->fname = htmlspecialchars(strip_tags($this->fname));
        $this->lname = htmlspecialchars(strip_tags($this->lname));
        $this->user_name = htmlspecialchars(strip_tags($this->user_name));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->contactno = htmlspecialchars(strip_tags($this->contactno));
        $this->bank_id = htmlspecialchars(strip_tags($this->bank_id));
        $this->branch_id = htmlspecialchars(strip_tags($this->branch_id));
        $this->role = htmlspecialchars(strip_tags($this->role));

        // bind values
        $stmt->bindParam(":fname", $this->fname);
        $stmt->bindParam(":lname", $this->lname);
        $stmt->bindParam(":user_name", $this->user_name);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":contactno", $this->contactno);
        $stmt->bindParam(":bank_id", $this->bank_id);
        $stmt->bindParam(":branch_id", $this->branch_id);
        $stmt->bindParam(":role", $this->role);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    function fetchUser()
    {
        $query = "SELECT u.fname as first_name, u.lname as last_name, u.user_name as user_name,
                            u.password as password, u.contactno as contactno, u.bank_id as bank_id, b.bank_name as bank_name,
                            u.branch_id, br.branch_name, u.role       
                        from " . $this->table_name . " u 
                        left join banks b on u.bank_id = b.bank_id
                        left join branches br on u.branch_id = br.id
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
        $this->fname = $row['first_name'];
        $this->lname = $row['last_name'];
        $this->user_name = $row['user_name'];
        $this->password = $row['password'];
        $this->contactno = $row['contactno'];
        $this->bank_id = $row['bank_id'];
        $this->bank_name = $row['bank_name'];
        $this->branch_id = $row['branch_id'];
        $this->branch_name = $row['branch_name'];
        $this->role = $row['role'];
    }

    function update()
    {
        $query = "UPDATE " . $this->table_name . "
                    SET
                        fname = :fname,
                        lname = :lname,
                        user_name = :user_name,
                        password = :password,
                        contactno = :contactno,
                        bank_id = :bank_id,
                        branch_id = :branch_id,
                        role = : role
                    WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $this->fname = htmlspecialchars(strip_tags($this->fname));
        $this->lname = htmlspecialchars(strip_tags($this->lname));
        $this->user_name = htmlspecialchars(strip_tags($this->user_name));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->contactno = htmlspecialchars(strip_tags($this->contactno));
        $this->bank_id = htmlspecialchars(strip_tags($this->bank_id));
        $this->branch_id = htmlspecialchars(strip_tags($this->branch_id));
        $this->role = htmlspecialchars(strip_tags($this->role));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':fname', $this->fname);
        $stmt->bindParam(':lname', $this->lname);
        $stmt->bindParam(':user_name', $this->user_name);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':contactno', $this->contactno);
        $stmt->bindParam(':bank_id', $this->bank_id);
        $stmt->bindParam(':branch_id', $this->branch_id);
        $stmt->bindParam(':role', $this->role);
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