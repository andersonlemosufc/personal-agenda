<?php
namespace com\andersonlemos\services;

require_once __DIR__."/../db/dao/mysqli/ContactMySQLiDAO.php";
require_once __DIR__."/GenericService.php";
require_once __DIR__."/AppointmentService.php";

use com\andersonlemos\db\dao\mysqli\ContactMySQLiDAO;

class ContactService extends GenericService {

    /* constructor */
    public function __construct() {
        parent::__construct(new ContactMySQLiDAO());
    }

    /* Receives a contact id and an appointment id and add that appointment to the appointments list of the contact with this id in the database */
    public function addAppointment($contactId, $appointmentId) {
        $appointmentService = new AppointmentService();
        return $appointmentService->addContact($appointmentId, $contactId);
    }

    /* Receives a contact id and an appointment id and removes from the appointments list of the contact with the passed
     * contact id, the appointment with this appointment id.
     * Returns true if there is a appointment with this id in the list (and it was removed) or false if there is not.
     * */
    public function removeAppointment($contactId, $appointmentId) {
        $appointmentService = new AppointmentService();
        return $appointmentService->removeContact($appointmentId, $contactId);
    }

    /* Receives a contact id and an appointment id and returns from the appointments list of the contact with
     * the contact id, the appointment with this appointment id.
     * If the appointment is not in the appointments list, the method will return NULL.
     * */
    public function getAppointment($contactId, $appointmentId) {
        $contact = $this->get($contactId);
        return $contact->getAppointment($appointmentId);
    }

    /* Receives a contact id and searches in the database for the appointments with the contact that has this id.
     * Returns the list of appointments with the contact that the id belongs.
     * */
    public function getAppointments($contactId) {
        return $this->dao->findAppointments($contactId);
    }

    /* Receives an owner id and searches in the database for the contacts of the owner with this id.
     * Returns the list of contacts belonging to the owner with that id.
     * */
    public function getByOwnerId($ownerId) {
        return $this->dao->findByOwnerId($ownerId);
    }

    /* Receives an appointment id and searches in the database for the contacts in the appointment that has this id.
     * Returns the list of contacts that belonging to the appoitment with that id.
     * */
    public function getByAppointmentId($appointmentId) {
        return $this->dao->findByAppointmentId($appointmentId);
    }

}

?>
