<?php
namespace com\andersonlemos\db\dao\mysqli;

require_once __DIR__."/../../../enums/GenericDAOOperations.php";
require_once __DIR__."/../../../models/Contact.php";
require_once __DIR__."/../ContactDAO.php";
require_once __DIR__."/GenericMySQLiDAO.php";
require_once __DIR__."/AppointmentMySQLiDAO.php";

use com\andersonlemos\db\dao\ContactDAO;
use com\andersonlemos\enums\GenericDAOOperations;
use com\andersonlemos\models\Contact;

class ContactMySQLiDAO extends GenericMysqliDAO implements ContactDAO {

    /* construtor: calls the parent constructor passing the table name that this classe handle (contact)
     * and the connection with the database.
     * If the database connection is NULL, the parent class will creates one that is not.
     * */
    public function __construct($connection = NULL) {
        parent::__construct("contact", $connection);
    }

    /* Returns the sql string for insert and update operations on contact table. */
    public function getSQLString($operation){
        switch ($operation) {
            case GenericDAOOperations::INSERT:
                return "INSERT INTO contact (name, date_of_birth, phone, email, photo, address_id, comments, favorite, owner_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            case GenericDAOOperations::UPDATE:
                return "UPDATE contact SET name=?, date_of_birth=?, phone=?, email=?, photo=?, address_id=?, comments=?, favorite=?, owner_id=? WHERE id=?";
        }
    }

    /* Binds the parameters for execute the statement. */
    public function bindParams($contact, $stmt, $operation) {

        $id = $contact->getId();
        $name = $contact->getName();
        $dateOfBirth = $contact->getDateOfBirth();
        $phone = $contact->getPhone();
        $email = $contact->getEmail();
        $photo = $contact->getPhoto();
        $addressId = $contact->getAddressId();
        $comments = $contact->getComments();
        $favorite = $contact->isFavorite();
        $ownerId = $contact->getOwnerId();

        switch ($operation) {
            case GenericDAOOperations::INSERT:
                $stmt->bind_param("sssssisii", $name, $dateOfBirth, $phone, $email, $photo, $addressId, $comments, $favorite, $ownerId);
                break;
            case GenericDAOOperations::UPDATE:
                $stmt->bind_param("sssssisiii", $name, $dateOfBirth, $phone, $email, $photo, $addressId, $comments, $favorite, $ownerId, $id);
                break;
        }
    }

    /* Creates an contact object based on the result of a query from a statement and returns it. */
    public function fillElementFromStatment($stmt) {
        $contact = NULL;

        $id = NULL;
        $name = NULL;
        $dateOfBirth = NULL;
        $phone = NULL;
        $email = NULL;
        $photo = NULL;
        $addressId = NULL;
        $comments = NULL;
        $favorite = NULL;
        $ownerId = NULL;

        $stmt->bind_result($id, $name, $dateOfBirth, $phone, $email, $photo, $addressId, $comments, $favorite, $ownerId);

        if ($stmt->fetch()) {
            $contact = new Contact($id, $name, $dateOfBirth, $phone, $email, $photo, $addressId, $comments, $favorite, $ownerId, NULL);
        }

        return $contact;
    }

    /* ContactDAO specific functions */

    public function findByOwnerId($ownerId) {
        return $this->findByField("owner_id", $ownerId, "i");
    }

    public function findByAppointmentId($appointmentId) {
        $sql = "SELECT contact.* FROM contact, contact_appointment WHERE contact.id=contact_appointment.contact_id AND contact_appointment.id=?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $appointmentId);
        return $this->executeQuery($stmt);
    }

    public function findAppointments($contactId) {
        $appointmentDAO = new AppointmentMySQLiDAO($this->connection);
        return $appointmentDAO->findByContactId($contactId);
    }

    /* end of ContactDAO specific functions */

}

?>
