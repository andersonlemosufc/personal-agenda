<?php
namespace com\andersonlemos\services;

require_once __DIR__."/../db/dao/mysqli/AppointmentMySQLiDAO.php";
require_once __DIR__."/GenericService.php";

use com\andersonlemos\db\dao\mysqli\AppointmentMySQLiDAO;

class AppointmentService extends GenericService {

    /* constructor */
    public function __construct() {
        parent::__construct(new AppointmentMySQLiDAO());
    }

    /* Receives an appointment id and a contact and add that contact to the contacts list of the appointment with this id in the database */
    public function addContact($appointmentId, $contact) {
        $appointment = $this->get($appointmentId);
        if (is_null($appointment->getContact($contact->getId()))) {
            $appointment->addContact($contact);
            $this->update($appointment);
        }
    }

    /* Receives an appointment id and a contact id and removes from the contacts list of the appointment with the passed
     * appointment id, the contact with this contact id.
     * Returns true if the there is a contact with this id in the list (and it was removed) or false if there is not.
     * */
    public function removeContact($appointmentId, $contactId) {
        $appointment = $this->get($appointmentId);
        $response = $appointment->removeContact($contactId);
        $this->update($appointment);
        return $response;
    }

    /* Receives an appointment id and a contact id and returns from the contacts list of the appointment with
     * the appointment id, the contact with this contact id.
     * If the contact is not in the contacts list, the method will return NULL.
     * */
    public function getContact($appointmentId, $contactId) {
        $appointment = $this->get($appointmentId);
        return $appointment->getContact($contactId);
    }

    /* Receives an appointment id and searches in the database for the contacts in the appointment that has this id.
     * Returns the list of contacts that belonging to the appoitment with that id.
     * */
    public function getContacts($appoitmentId) {
        return $this->dao->findContacts($appoitmentId);
    }

    /* Receives an owner id and searches in the database for the appointments of the owner with this id.
     * Returns the list of appointments belonging to the owner with that id.
     * */
    public function getByOwnerId($ownerId) {
        return $this->dao->findByOwnerId($ownerId);
    }

    /* Receives a contact id and searches in the database for the appointments with the contact that has this id.
     * Returns the list of appointments with the contact that the id belongs.
     * */
    public function getByContactId($contactId) {
        return $this->dao->findByContactId($contactId);
    }

}

?>
