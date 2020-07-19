<?php
namespace com\andersonlemos\models;

require_once __DIR__."/../db/dao/mysqli/ContactMySQLiDAO.php";
require_once __DIR__."/../db/dao/mysqli/OwnerMySQLiDAO.php";
require_once __DIR__."/Person.php";

use com\andersonlemos\db\dao\mysqli\ContactMySQLiDAO;
use com\andersonlemos\db\dao\mysqli\OwnerMySQLiDAO;

class Contact extends Person {

    private $comments;
    private $favorite;
    private $owner;
    private $appointments;

    /* constructor (by default, if a argument is not passed, it will be NULL,
     * except the appointments array, initialized with an empty array).
     * */
    public function __construct($id = NULL, $name = NULL, $dateOfBirth = NULL, $phone = NULL, $email = NULL, $photo = NULL, $address = NULL,
            $comments = NULL, $favorite = NULL, $owner = NULL, $appointments = []) {
        parent::__construct($id, $name, $dateOfBirth, $phone, $email, $photo, $address);
        $this->comments = $comments;
        $this->favorite = $favorite;
        $this->owner = $owner;
        $this->appointments = $appointments;
    }

    /* gets */

    public function getComments() {
        return $this->comments;
    }

    public function isFavorite() {
        return $this->favorite;
    }

    public function getOwner() {
        /* The owner attribute can keep the owner object itself or just the id of the owner object.
         * It happens when the contact is retrieved from the database, the intern objects are
         * not loaded. They will be loaded only if necessary (lazy initialization).
         * So, if we have only the owner id, we will find the owner object in the database.
         * */
        if (is_integer($this->owner)) {
            $ownerDAO = new OwnerMySQLiDAO();
            $this->owner = $ownerDAO->findById($this->owner);
        }
        return $this->owner;
    }

    public function getAppointments() {
        /* The appointments attribute can keep the list of appointments objects itself or NULL. If it is NULL don't mean
         * the list is empty (in this case, it will be an empty list, []). The NULL value means the list was not
         * loaded from the database yet. It happens when the contact is retrieved from the database, the intern objects are
         * not loaded. They will be loaded only if necessary (lazy initialization).
         * So, if the appointments attribute is NULL, we will find the appointments objects in the database.
         * */
        if (is_null($this->appointments)) {
            $contactDAO = new ContactMySQLiDAO();
            $this->appointments = $contactDAO->findAppointments($this->id);
        }
        return $this->appointments;
    }

    /* end gets */

    /* sets */

    public function setComments($comments) {
        $this->comments = $comments;
    }

    public function setFavorite($favorite) {
        $this->favorite = $favorite;
    }

    public function setOwner($owner) {
        $this->owner = $owner;
    }

    public function setAppointments($appointments) {
        $this->appointments = $appointments;
    }

    /* end sets */

    /* toString: returns object properties as a string. */
    public function __toString() {
        $ownerStr = $this->owner;
        if (is_object($this->owner)) {
            $ownerStr = "[id=".$this->owner->getId().", name=".$this->owner->getName()."]";
        }
        return substr(parent::__toString(), 0, -1).", ".
            "comments=".$this->comments.", ".
            "favorite=".$this->favorite.", ".
            "owner=".$ownerStr.
        "]";
    }

    /* other methods */

    /* Returns the id of the contact's owner.
     * The attribute owner can keep the id itself (because of the lazy initialization) or the object owner
     * */
    public function getOwnerId() {
        return is_object($this->owner) ? $this->owner->getId() : $this->owner;
    }

    /* Receives a appointment and add it to the appointments list */
    public function addAppointment($appointment) {
        $appointments = $this->getAppointments();
        array_push($appointments, $appointment);
    }

    /* Receives a appointment id and returns from the appointments list the appointment with this id.
     * If the appointment is not in the appointments list, the method will return NULL.
     * */
    public function removeAppointment($appointmentId) {
        $appointments = $this->getAppointments();
        $index = 0;
        foreach ($appointments as $appointment) {
            if ($appointment->getId() === $appointmentId) {
                array_splice($appointments, $index, 1);
                return true;
            }
            $index++;
        }
        return false;
    }

    /* Receives a appointment id and removes from the appointments list the appointment with this id.
     * Returns true if the there is a appointment with this id in the list (and it was removed) or false if there is not.
     * */
    public function getAppointment($appointmentId) {
        $appointments = $this->getAppointments();
        foreach ($appointments as $appointment) {
            if ($appointment->getId() === $appointmentId) {
                return $appointment;
            }
        }
        return NULL;
    }

    // TODO: remove appointments function
    public function appointments() {
        return $this->appointments;
    }

}

?>
