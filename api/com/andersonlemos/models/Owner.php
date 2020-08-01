<?php
namespace com\andersonlemos\models;

require_once __DIR__."/../db/dao/mysqli/OwnerMySQLiDAO.php";
require_once __DIR__."/Person.php";

use com\andersonlemos\db\dao\mysqli\OwnerMySQLiDAO;

class Owner extends Person {

    private $password;
    private $contacts;
    private $appointments;

    /* constructor (by default, if a argument is not passed, it will be NULL,
     * except contacts and appointments arrays, initialized with empty arrays).
     * */
    public function __construct($id = NULL, $name = NULL, $dateOfBirth = NULL, $phone = NULL, $email = NULL, $photo = NULL, $address = NULL,
            $password = NULL, $contacts = [], $appointments = []) {
        parent::__construct($id, $name, $dateOfBirth, $phone, $email, $photo, $address);
        $this->password = $password;
        $this->contacts = $contacts;
        $this->appointments = $appointments;
    }

    /* gets */

    public function getPassword() {
        return $this->password;
    }

    public function getContacts() {
        /* The contacts attribute can keep the list of contacts objects itself or NULL. If it is NULL don't mean
         * the list is empty (in this case, it will be an empty list, []). The NULL value means the list was not
         * loaded from the database yet. It happens when the owner is retrieved from the database, the intern objects are
         * not loaded. They will be loaded only if necessary (lazy initialization).
         * So, if the contacts attribute is NULL, we will find the contacts objects in the database.
         * */
        if (is_null($this->contacts)) {
            $ownerDAO = new OwnerMySQLiDAO();
            $this->contacts = $ownerDAO->findContacts($this->id);
        }
        return $this->contacts;
    }

    public function getAppointments() {
        /* The appointments attribute can keep the list of appointments objects itself or NULL. If it is NULL don't mean
         * the list is empty (in this case, it will be an empty list, []). The NULL value means the list was not
         * loaded from the database yet. It happens when the owner is retrieved from the database, the intern objects are
         * not loaded. They will be loaded only if necessary (lazy initialization).
         * So, if the appointments attribute is NULL, we will find the appointments objects in the database.
         * */
        if (is_null($this->appointments)) {
            $ownerDAO = new OwnerMySQLiDAO();
            $this->appointments = $ownerDAO->findAppointments($this->id);
        }
        return $this->appointments;
    }

    /* end gets */

    /* sets */

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setContacts($contacts) {
        $this->contacts = $contacts;
    }

    public function setAppointments($appointments) {
        $this->appointments = $appointments;
    }

    /* end sets */

    /* toString: returns object properties as a string. */
    public function __toString() {
        return substr(parent::__toString(), 0, -1).", ".
            "password=".$this->password.
        "]";
    }

    /* methods to handle with api requests */

    /* Returns the address in an associative array form */
    public function toMap() {
        return array(
            "id" => $this->id,
        );
    }

    /* Receives a map that is an associative array with the attribuites of an address.
     * Returns an address object with the attributes of the associative array */
    public function fromMap($map) {
        if (!is_null($map) && is_array($map)) {
            $this->id = array_key_exists("id", $map) ? $map["id"] : NULL;
        }
        return $this;
    }

    /* end of methods to api requests */

    /* other methods */

    /* Receives a contact and add it to the contacts list */
    public function addContact($contact) {
        $this->getContacts();
        array_push($this->contacts, $contact);
    }

    /* Receives a contact id and removes from the contacts list the contact with this id.
     * Returns true if there is a contact with this id in the list (and it was removed) or false if there is not.
     * */
    public function removeContact($contactId) {
        $this->getContacts();
        $index = 0;
        foreach ($this->contacts as $contact) {
            if ($contact->getId() === $contactId) {
                array_splice($this->contacts, $index, 1);
                return true;
            }
            $index++;
        }
        return false;
    }

    /* Receives a contact id and returns from the contacts list the contact with this id.
     * If the contact is not in the contacts list, the method will return NULL.
     * */
    public function getContact($contactId) {
        $this->getContacts();
        foreach ($this->contacts as $contact) {
            if ($contact->getId() === $contactId) {
                return $contact;
            }
        }
        return NULL;
    }

    /* Receives a appointment and add it to the appointments list */
    public function addAppointment($appointment) {
        $this->getAppointments();
        array_push($this->appointments, $appointment);
    }

    /* Receives a appointment id and removes from the appointments list the appointment with this id.
     * Returns true if there is a appointment with this id in the list (and it was removed) or false if there is not.
     * */
    public function removeAppointment($appointmentId) {
        $this->getAppointments();
        $index = 0;
        foreach ($this->appointments as $appointment) {
            if ($appointment->getId() === $appointmentId) {
                array_splice($this->appointments, $index, 1);
                return true;
            }
            $index++;
        }
        return false;
    }

    /* Receives a appointment id and returns from the appointments list the appointment with this id.
     * If the appointment is not in the appointments list, the method will return NULL.
     * */
    public function getAppointment($appointmentId) {
        $this->getAppointments();
        foreach ($this->appointments as $appointment) {
            if ($appointment->getId() === $appointmentId) {
                return $appointment;
            }
        }
        return NULL;
    }

}

?>
