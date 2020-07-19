<?php
namespace com\andersonlemos\db;

require_once __DIR__."/Credentials.php";

class DBConnection {

    private $connection;

    /* constructor: creates a connection link with the database. */
    public function __construct() {
        $this->connection = mysqli_connect(Credentials::HOST, Credentials::USER, Credentials::PASSWORD, Credentials::DATABASE);
        if (mysqli_connect_errno()) {
            $this->connection = NULL;
            echo("Error to connect with the database");
        }
    }

    /* Returns the connection link with the database. */
    public function getConnection() {
        return $this->connection;
    }

    /* destructor: closes the connection link with the database. */
    public function __destruct() {
        if (!is_null($this->connection)) {
            mysqli_close($this->connection);
            $this->connection = NULL;
        }
        // TODO: remove this line
        echo("closing connection<br>");
    }

}

?>
