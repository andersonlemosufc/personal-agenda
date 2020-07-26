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
    private $dbConnection;

    /* constructor
     * Receives the table name to know winch table it is handle and the database connection.
     * If the database connection is NULL, it creates one that is not.
     * */
    public function __construct($tableName, $connection = NULL) {
        $this->tableName = $tableName;
        $this->connection = $this->connection;

        if (is_null($this->connection)) {
            $this->dbConnection = new DBConnection();
            $this->connection = $this->dbConnection->getConnection();
        }
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
        $elements = $this->findByField("id", $elementId, "i");
        return (count($elements) === 1) ? $elements[0] : NULL;
    }

    public function findAll() {
        return $this->findByField();
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

    /* Receives a field, a value, and a string type and returns all elements from the table that has the property
     * in the column field equals to the value (column[field]=value).
     * The string type is the initial character of the string type of the passed value (used on the bind_param).
     * If one of these parameters is not passed, the method will return all the elements in the table.
     * */
    protected function findByField($field = NULL, $value = NULL, $stringType = NULL) {
        $hasWhereClause = boolval($field && $value && $stringType);
        $sql = "SELECT * FROM ".$this->tableName;
        if ($hasWhereClause) {
            $sql = $sql." WHERE ".$field."=?";
        }
        $stmt = $this->connection->prepare($sql);
        if ($hasWhereClause) {
            $stmt->bind_param($stringType, $value);
        }
        return $this->executeQuery($stmt);
    }

    /* Receives a query statement, execute it and returns the list of elements resulted from the query */
    protected function executeQuery($stmt) {
        $stmt->execute();
        $elements = [];
        while ($element = $this->fillElementFromStatment($stmt)) {
            array_push($elements, $element);
        }
        $stmt->close();
        return $elements;
    }

}

?>
