<?php
namespace com\andersonlemos\db\dao\mysqli;

require_once __DIR__."/../../../enums/GenericDAOOperations.php";
require_once __DIR__."/../../DBConnection.php";
require_once __DIR__."/../GenericDAO.php";

use com\andersonlemos\db\DBConnection;
use com\andersonlemos\db\dao\GenericDAO;
use com\andersonlemos\enums\GenericDAOOperations;

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
        // TODO: remove this line
        echo("fechando ".$this->tableName." ");
        DBConnection::close($this->connection);
    }

    /* abstract methods that must be implemented by the children classes. */

    /* Receives the operation that will be done and returns the sql string to do that operation.
     * The $operation parameter must be a GenericDAOOperation.
     * That is necessary because the sql string for insert or update, for example, is different
     * for each table.
     * */
    public abstract function getSQLString($operation);

    /* Binds the parameters from an object to the statement, depending on the operation.
     * The $operation parameter must be a GenericDAOOperation.
     * This is necessary because the sql string for insert or update, for example, is different
     * for each table.
     * */
    public abstract function bindParams($element, $stmt, $operation);

    /* Fills an object with the result of a select query and returns this object.
     * This is necessary because the result and values to be bound are different
     * for each table.
     * */
    public abstract function fillElementFromStatment($stmt);

    /* end of the abstract methods */

    /* DAO default functions */

    public function insert($element) {
        return $this->save($element);
    }

    public function update($element) {
        return $this->save($element);
    }

    public function delete($elementId) {
        $sql = "DELETE FROM ".$this->tableName." WHERE id=?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $elementId);
        $stmt->execute();
        $result = ($this->connection->affected_rows > 0);
        $stmt->close();
        return $result;
    }

    public function findById($elementId) {
        $sql = "SELECT * FROM ".$this->tableName." WHERE id=?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $elementId);
        $stmt->execute();
        $element = $this->fillElementFromStatment($stmt);
        $stmt->close();
        return $element;
    }

    public function findAll() {
        $sql = "SELECT * FROM ".$this->tableName;
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $elements = [];
        while ($element = $this->fillElementFromStatment($stmt)) {
            array_push($elements, $element);
        }
        $stmt->close();
        return $elements;
    }

    /* end of DAO default functions */

    /* other functions */

    /* Saves an object in the database.
     * If the object has id, it means the object is already in the database, so it will be just updated.
     * Otherwise, the object is not in the database, so it will be inserted.
     * */
    protected function save($element) {
        $operation = is_null($element->getId()) ? GenericDAOOperations::INSERT : GenericDAOOperations::UPDATE;
        $sql = $this->getSQLString($operation);
        $stmt = $this->connection->prepare($sql);
        $this->bindParams($element, $stmt, $operation);
        $stmt->execute();
        if ($operation === GenericDAOOperations::INSERT) {
            $element->setId($this->connection->insert_id);
        }
        $stmt->close();
        return $element;
    }

}

?>
