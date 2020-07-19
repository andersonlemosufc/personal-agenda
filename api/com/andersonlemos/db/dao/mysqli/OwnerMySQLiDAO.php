<?php
namespace com\andersonlemos\db\dao\mysqli;

require_once __DIR__."/../../../enums/GenericDAOOperations.php";
require_once __DIR__."/../../../models/Owner.php";
require_once __DIR__."/../OwnerDAO.php";
require_once __DIR__."/GenericMySQLiDAO.php";

use com\andersonlemos\db\dao\OwnerDAO;
use com\andersonlemos\enums\GenericDAOOperations;
use com\andersonlemos\models\Owner;

class OwnerMySQLiDAO extends GenericMysqliDAO implements OwnerDAO {

    /* construtor: calls the parent constructor passing the table name that this classe handle (owner)
     * and the connection with the database.
     * If the database connection is NULL, the parent class will creates one that is not.
     * */
    public function __construct($connection = NULL) {
        parent::__construct("owner", $connection);
    }

    /* Returns the sql string for insert and update operations on owner table. */
    public function getSQLString($operation){
        switch ($operation) {
            case GenericDAOOperations::INSERT:
                return "INSERT INTO owner (name, date_of_birth, phone, email, photo, address_id, password) VALUES (?, ?, ?, ?, ?, ?, ?)";
            case GenericDAOOperations::UPDATE:
                return "UPDATE owner SET name=?, date_of_birth=?, phone=?, email=?, photo=?, address_id=?, password=? WHERE id=?";
        }
    }

    /* Binds the parameters for execute the statement. */
    public function bindParams($owner, $stmt, $operation) {

        $id = $owner->getId();
        $name = $owner->getName();
        $dateOfBirth = $owner->getDateOfBirth();
        $phone = $owner->getPhone();
        $email = $owner->getEmail();
        $photo = $owner->getPhoto();
        $addressId = $owner->getAddressId();
        $password = $owner->getPassword();

        switch ($operation) {
            case GenericDAOOperations::INSERT:
                $stmt->bind_param("sssssis", $name, $dateOfBirth, $phone, $email, $photo, $addressId, $password);
                break;
            case GenericDAOOperations::UPDATE:
                $stmt->bind_param("sssssisi", $name, $dateOfBirth, $phone, $email, $photo, $addressId, $password, $id);
                break;
        }
    }

    /* Creates an owner object based on the result of a query from a statement and returns it. */
    public function fillElementFromStatment($stmt) {

        $owner = NULL;

        $id = NULL;
        $name = NULL;
        $dateOfBirth = NULL;
        $phone = NULL;
        $email = NULL;
        $photo = NULL;
        $addressId = NULL;
        $password = NULL;

        $stmt->bind_result($id, $name, $dateOfBirth, $phone, $email, $photo, $addressId, $password);

        if ($stmt->fetch()) {
            $owner = new Owner($id, $name, $dateOfBirth, $phone, $email, $photo, $addressId, $password, NULL, NULL);
        }

        return $owner;
    }

    /* OwnerDAO specific functions */

    public function findContacts($ownerId) {
        // TODO
        return [];
    }

    public function findAppointments($ownerId) {
        // TODO
        return [];
    }

    /* end of OwnerDAO specific functions */

}

?>
