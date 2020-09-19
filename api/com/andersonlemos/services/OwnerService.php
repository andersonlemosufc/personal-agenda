<?php
namespace com\andersonlemos\services;

require_once __DIR__."/../db/dao/mysqli/OwnerMySQLiDAO.php";
require_once __DIR__."/GenericService.php";
require_once __DIR__."/ContactService.php";
require_once __DIR__."/AppointmentService.php";

use com\andersonlemos\db\dao\mysqli\OwnerMySQLiDAO;

class OwnerService extends GenericService {

    /* constructor */
    public function __construct() {
        parent::__construct(new OwnerMySQLiDAO());
    }

    /* Receives an owner id and a contact and add that contact to the contacts list of the owner with this id in the database */
    public function addContact($ownerId, $contact) {
        $owner = $this->get($ownerId);
        if (is_null($owner->getContact($contact->getId()))) {
            $contact->setOwner($owner);
            $contactService = new ContactService();
            $contactService->add($contact);
        }
    }

    /* Receives an owner id and a contact id and removes from the contacts list the contact of the owner with the passed
     * owner id, the contact with this contact id.
     * Returns true if there is a contact with this id in the list (and it was removed) or false if there is not.
     * */
    public function removeContact($ownerId, $contactId) {
        $owner = $this->get($ownerId);
        $response = $owner->removeContact($contactId);
        if ($response) {
            $contactService = new ContactService();
            $contactService->remove($contactId);
        }
        return $response;
    }

    /* Receives an owner id and a contact id and returns from the contacts list of the owner with
     * the owner id, the contact with this contact id.
     * If the contact is not in the contacts list, the method will return NULL.
     * */
    public function getContact($ownerId, $contactId) {
        $owner = $this->get($ownerId);
        return $owner->getContact($contactId);
    }

    /* Receives an owner id and searches in the databese for the contacts of the owner with this id.
     * Returns the list of contacts belonging to the owner with that id.
     * */
    public function getContacts($ownerId) {
        return $this->dao->findContacts($ownerId);
    }

    /* Receives an owner id and a appointment and add that appointment to the appointments list of
     * the owner with this id in the database */
    public function addAppointment($ownerId, $appointment) {
        $owner = $this->get($ownerId);
        if (is_null($owner->getAppointment($appointment->getId()))) {
            $appointment->setOwner($owner);
            $appointmentService = new AppointmentService();
            $appointmentService->add($appointment);
        }
    }

    /* Receives an owner id and a appointment id and removes from the appointments list of the owner with the passed
     * owner id, the appointment with this appointment id.
     * Returns true if there is a appointment with this id in the list (and it was removed) or false if there is not.
     * */
    public function removeAppointment($ownerId, $appointmentId) {
        $owner = $this->get($ownerId);
        $response = $owner->removeAppointment($appointmentId);
        if ($response) {
            $appointmentService = new AppointmentService();
            $appointmentService->remove($appointmentId);
        }
        return $response;
    }

    /* Receives an owner id and a appointment id and returns from the appointments list of the owner with
     * the owner id, the appointment with this appointment id.
     * If the appointment is not in the appointments list, the method will return NULL.
     * */
    public function getAppointment($ownerId, $appointmentId) {
        $owner = $this->get($ownerId);
        return $owner->getAppointment($appointmentId);
    }

    /* Receives an owner id and searches in the database for the appointments of the owner with this id.
     * Returns the list of appointments belonging to the owner with that id.
     * */
    public function getAppointments($ownerId) {
        return $this->dao->findAppointments($ownerId);
    }

    /* Receives an owner email and returns tha owner that has this email.
     * Returns null if there is no owner with this email.
     * */
    public function getByEmail($email) {
        return $this->dao->findByEmail($email);
    }

}

?>
