<?php
namespace com\andersonlemos\db;

require_once __DIR__."/Credentials.php";

class DBConnection {

    private $connection;

    /* contructor: creates a connection link with the database */
    public function __construct() {
        $this->connection = mysqli_connect(Credentials::HOST, Credentials::USER, Credentials::PASSWORD, Credentials::DATABASE);
        if (mysqli_connect_errno()) {
            $this->connection = NULL;
            echo("Error to connect with the database");
        }
    }

    /* destructor: closes the connection link with the database */
    public function __destruct() {
        mysqli_close($this->connection);

        // TODO: remove this line
        echo("closing connection");
    }

    /* Returns the connection link with the database */
    public function getConnection() {
        return $this->connection;
    }

}

?>
