<?php
namespace com\andersonlemos\db\dao\mysqli;

require_once __DIR__."/../../../enums/GenericDAOOperations.php";
require_once __DIR__."/../../../models/Appointment.php";
require_once __DIR__."/../../../utils/Helpers.php";
require_once __DIR__."/../AppointmentDAO.php";
require_once __DIR__."/GenericMySQLiDAO.php";
require_once __DIR__."/ContactMySQLiDAO.php";

use com\andersonlemos\db\dao\AppointmentDAO;
use com\andersonlemos\enums\GenericDAOOperations;
use com\andersonlemos\models\Appointment;
use com\andersonlemos\utils\Helpers;

class AppointmentMySQLiDAO extends GenericMysqliDAO implements AppointmentDAO {

    /* construtor: calls the parent constructor passing the table name that this classe handle (appointment)
     * and the connection with the database.
     * If the database connection is NULL, the parent class will creates one that is not.
     * */
    public function __construct($connection = NULL) {
        parent::__construct("appointment", $connection);
    }

    /* The method insert will be overridden to insert also the contacts in the appointment. */
    public function insert($appointment) {
        parent::insert($appointment);

        $contacts = $appointment->getContacts();
        foreach ($contacts as $contact) {
            $this->insertContact($appointment->getId(), $contact->getId());
        }

        return $appointment;
    }

    /* The method update will be overridden to update also the contacts in the appointment. */
    public function update($appointment) {
        parent::update($appointment);

        $contacts = $appointment->getContacts();
        $currentContactsIds = $this->findContactsIds($appointment->getId());

        // inserting the contacts that was not in the appointment
        foreach ($contacts as $contact) {
            $index = array_search($contact->getId(), $currentContactsIds);
            if ($index === false) {
                $this->insertContact($appointment->getId(), $contact->getId());
            } else {
                array_splice($currentContactsIds, $index, 1);
            }
        }

        // removing the contact that was not in the appointment
        foreach ($currentContactsIds as $contactId) {
            $this->deleteContact($appointment->getId(), $contactId);
        }
        return $appointment;
    }

    /* Receives an appointment id and returns the ids of contacts in this appointment */
    private function findContactsIds($appointmentId) {
        $sql = "SELECT contact_id FROM contact_appointment WHERE appointment_id=?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $appointmentId);
        $stmt->execute();
        $ids = [];
        $id = NULL;
        $stmt->bind_result($id);
        while ($stmt->fetch()) {
            array_push($ids, $id);
        }
        return $ids;
    }

    /* Receives an appointment id and a contact id and inserts this contact to that appointment */
    private function insertContact($appointmentId, $contactId) {
        $sql = "INSERT INTO contact_appointment (contact_id, appointment_id) VALUES (?, ?)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ii", $contactId, $appointmentId);
        $stmt->execute();
    }

    /* Receives an appointment id and a contact id and deletes this contact from that appointment */
    private function deleteContact($appointmentId, $contactId) {
        $sql = "DELETE FROM contact_appointment WHERE contact_id=? AND appointment_id=?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ii", $contactId, $appointmentId);
        $stmt->execute();
    }

    /* Returns the sql string for insert and update operations on appointment table. */
    public function getSQLString($operation){
        switch ($operation) {
            case GenericDAOOperations::INSERT:
                return "INSERT INTO appointment (start, end, description, repeat_type, place_id, owner_id) VALUES (?, ?, ?, ?, ?, ?)";
            case GenericDAOOperations::UPDATE:
                return "UPDATE appointment SET start=?, end=?, description=?, repeat_type=?, place_id=?, owner_id=? WHERE id=?";
        }
    }

    /* Binds the parameters for execute the statement. */
    public function bindParams($appointment, $stmt, $operation) {

        $id = $appointment->getId();
        $start = Helpers::dateTimeToDefaultFormat($appointment->getStart());
        $end = Helpers::dateTimeToDefaultFormat($appointment->getEnd());
        $description = $appointment->getDescription();
        $repeat = $appointment->getRepeat();
        $placeId = $appointment->getPlaceId();
        $ownerId = $appointment->getOwnerId();

        switch ($operation) {
            case GenericDAOOperations::INSERT:
                $stmt->bind_param("sssiii", $start, $end, $description, $repeat, $placeId, $ownerId);
                break;
            case GenericDAOOperations::UPDATE:
                $stmt->bind_param("sssiiii", $start, $end, $description, $repeat, $placeId, $ownerId, $id);
                break;
        }
    }

    /* Creates an appointment object based on the result of a query from a statement and returns it. */
    public function fillElementFromStatment($stmt) {

        $appointment = NULL;

        $id = NULL;
        $start = NULL;
        $end = NULL;
        $description = NULL;
        $repeat = NULL;
        $placeId = NULL;
        $ownerId = NULL;

        $stmt->bind_result($id, $start, $end, $description, $repeat, $placeId, $ownerId);

        if ($stmt->fetch()) {
            $appointment = new Appointment($id, Helpers::stringToDateTime($start), Helpers::stringToDateTime($end), $description, $repeat, $placeId, $ownerId, NULL);
        }

        return $appointment;
    }

    /* AppointmentDAO specific functions */

    public function findByOwnerId($ownerId) {
        return $this->findByField("owner_id", $ownerId, "i");
    }

    public function findByContactId($contactId) {
        $sql = "SELECT appointment.* FROM appointment, contact_appointment WHERE appointment.id=contact_appointment.appointment_id AND contact_appointment.contact_id=?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $contactId);
        return $this->executeQuery($stmt);
    }

    public function findContacts($appointmentId) {
        $contactDAO = new ContactMySQLiDAO($this->connection);
        return $contactDAO->findByAppointmentId($appointmentId);
    }

    /* end of AppointmentDAO specific functions */

}

?>
