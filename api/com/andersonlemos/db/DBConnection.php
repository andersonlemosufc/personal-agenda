<?php
namespace com\andersonlemos\db;

require_once __DIR__."/Credentials.php";

abstract class DBConnection {

    /* Creates a connection link with the database.
     * Returns the connection link with the database
     * */
    public static function getConnection() {
        $connection = mysqli_connect(Credentials::HOST, Credentials::USER, Credentials::PASSWORD, Credentials::DATABASE);
        if (mysqli_connect_errno()) {
            $connection = NULL;
            echo("Error to connect with the database");
        }
        return $connection;
    }

    /* Closes the connection link with the database. */
    public static function close($connection) {
        mysqli_close($connection);

        // TODO: remove this line
        echo("closing connection<br>");
    }

}

?>
