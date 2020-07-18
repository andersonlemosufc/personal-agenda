<?php
namespace com\andersonlemos\db\dao\mysqli;

require_once __DIR__."/../../DBConnection.php";
require_once __DIR__."/../GenericDAO.php";

use com\andersonlemos\db\DBConnection;
use com\andersonlemos\db\dao\GenericDAO;

abstract class GenericMySQLiDAO implements GenericDAO {

    protected $tableName;
    protected $connection;

    /* constructor
     * Receives the table name to know winch table it is handle and the database connection.
     * If the database connection is NULL, it creates one that is not.
     * */
    public function __construct($tableName, $connection = NULL) {
        $this->tableName = $tableName;
        $this->connection = $this->connection;

        if (is_null($this->connection)) {
            $this->connection = DBConnection::getConnection();
        }
    }

    /* destructor: closes the connection link with the database. */
    public function __destruct() {
        DBConnection::close($this->connection);
    }

    public function insert($element) {
        
    }

    public function update($element) {
        
    }

    public function delete($elementId) {
        
    }

    public function findById($elementId) {
        
    }

    public function findAll() {
        
    }

}

?>
