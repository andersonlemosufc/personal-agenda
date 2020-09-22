<?php
namespace com\andersonlemos\db\dao\mysqli;

require_once __DIR__."/../../../enums/GenericDAOOperations.php";
require_once __DIR__."/../../../models/Owner.php";
require_once __DIR__."/../../../utils/Helpers.php";
require_once __DIR__."/../OwnerDAO.php";
require_once __DIR__."/GenericMySQLiDAO.php";
require_once __DIR__."/ContactMySQLiDAO.php";
require_once __DIR__."/AppointmentMySQLiDAO.php";

use com\andersonlemos\db\dao\OwnerDAO;
use com\andersonlemos\enums\GenericDAOOperations;
use com\andersonlemos\models\Owner;
use com\andersonlemos\utils\Helpers;

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
        $dateOfBirth = Helpers::dateTimeToDefaultFormat($owner->getDateOfBirth());
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
            $owner = new Owner($id, $name, Helpers::stringToDateTime($dateOfBirth), $phone, $email, $photo, $addressId, $password, NULL, NULL);
        }

        return $owner;
    }

    /* OwnerDAO specific functions */

    public function findContacts($ownerId) {
        $contactDAO = new ContactMySQLiDAO($this->connection);
        return $contactDAO->findByOwnerId($ownerId);
    }

    public function findAppointments($ownerId) {
        $appointmentDAO = new AppointmentMySQLiDAO($this->connection);
        return $appointmentDAO->findByOwnerId($ownerId);
    }

    public function findByEmail($email) {
        $elements = $this->findByField("email", $email, "s");
        $owner = count($elements) == 1 ? $elements[0] : NULL;
        return $owner;
    }

    /* end of OwnerDAO specific functions */

}

?>
