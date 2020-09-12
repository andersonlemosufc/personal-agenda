<?php
namespace com\andersonlemos\models;

require_once __DIR__."/../db/dao/mysqli/ContactMySQLiDAO.php";
require_once __DIR__."/../db/dao/mysqli/OwnerMySQLiDAO.php";
require_once __DIR__."/../utils/Helpers.php";
require_once __DIR__."/Person.php";

use DateTime;
use com\andersonlemos\utils\Helpers;
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

    /* methods to handle with api requests */

    /* Returns the contact object in an associative array form without the intern objects (just their ids). */
    protected function toShallowMap() {

        $appointmentsIds = !is_array($this->appointments) ? NULL : array_map(function ($appointment) {
            return $appointment->getId();
        }, $this->appointments);

        return array(
            "id" => $this->id,
            "name" => $this->name,
            "date_of_birth" => Helpers::dateTimeToDefaultFormat($this->dateOfBirth),
            "phone" => $this->phone,
            "email" => $this->email,
            "photo" => $this->photo,
            "address_id" => $this->getAddressId(),
            "comments" => $this->comments,
            "favorite" => $this->favorite,
            "owner_id" => $this->getOwnerId(),
            "appointments_ids" => $appointmentsIds
        );
    }

    /* Returns the contact in an associative array form */
    public function toMap() {
        $map = $this->toShallowMap();
        $map["address"] = $this->address ? $this->address->toShallowMap() : NULL;
        $map["owner"] = $this->owner ? $this->owner->toShallowMap() : NULL;

        $map["appointments"] = !is_array($this->appointments) ? NULL : array_map(function ($appointment) {
            return $appointment->toShallowMap();
        }, $this->appointments);

        return $map;
    }

    /* Receives a map that is an associative array with the attribuites of a contact.
     * Returns a contact object with the attributes of the associative array */
    public function fromMap($map) {
        if (!is_null($map) && is_array($map)) {
            $this->id = array_key_exists("id", $map) ? $map["id"] : NULL;
            $this->name = array_key_exists("name", $map) ? $map["name"] : NULL;
            $this->dateOfBirth = array_key_exists("date_of_birth", $map) ? new DateTime($map["date_of_birth"]) : NULL;
            $this->phone = array_key_exists("phone", $map) ? $map["phone"] : NULL;
            $this->email = array_key_exists("email", $map) ? $map["email"] : NULL;
            $this->photo = array_key_exists("photo", $map) ? $map["photo"] : NULL;
            $this->comments = array_key_exists("comments", $map) ? $map["comments"] : NULL;
            $this->favorite = array_key_exists("favorite", $map) ? $map["favorite"] : NULL;
            $this->address = NULL;
            $this->owner = NULL;
            $this->appointments = NULL;

            if (array_key_exists("address", $map) && is_array($map["address"])) {
                $this->address = (new Address())->fromMap($map["address"]);
            } elseif (array_key_exists("address_id", $map)) {
                $this->address = $map["address_id"];
            }

            if (array_key_exists("owner", $map) && is_array($map["owner"])) {
                $this->owner = (new Owner())->fromMap($map["owner"]);
            } elseif (array_key_exists("owner_id", $map)) {
                $this->owner = $map["owner_id"];
            }

            if (array_key_exists("appointments", $map) && is_array($map["appointments"])) {
                $this->appointments = array_map(function ($appointmentMap) {
                    return (new Appointment())->fromMap($appointmentMap);
                }, $map["appointments"]);
            }
        }
        return $this;
    }

    /* end of methods to api requests */

    /* other methods */

    /* Returns the id of the contact's owner.
     * The attribute owner can keep the id itself (because of the lazy initialization) or the object owner
     * */
    public function getOwnerId() {
        return is_object($this->owner) ? $this->owner->getId() : $this->owner;
    }

    /* Receives an appointment and add it to the appointments list */
    public function addAppointment($appointment) {
        $this->getAppointments();
        array_push($this->appointments, $appointment);
    }

    /* Receives an appointment id and removes from the appointments list the appointment with this id.
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

    /* Receives an appointment id and returns from the appointments list the appointment with this id.
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
