<?php
namespace com\andersonlemos\models;

require_once __DIR__."/../db/dao/mysqli/AppointmentMySQLiDAO.php";
require_once __DIR__."/../db/dao/mysqli/OwnerMySQLiDAO.php";
require_once __DIR__."/../db/dao/mysqli/AddressMySQLiDAO.php";
require_once __DIR__."/../enums/AppointmentRepeat.php";
require_once __DIR__."/Bean.php";

use com\andersonlemos\enums\AppointmentRepeat;
use com\andersonlemos\db\dao\mysqli\AppointmentMySQLiDAO;
use com\andersonlemos\db\dao\mysqli\OwnerMySQLiDAO;
use com\andersonlemos\db\dao\mysqli\AddressMySQLiDAO;

class Appointment extends Bean {

    private $start;
    private $end;
    private $description;
    private $repeat;
    private $place;
    private $owner;
    private $contacts;

    /* constructor (by default, if a argument is not passed, it will be NULL,
     * except the contacts array, initialized with an empty array and repeat, initialized with NO_REPEAT constant).
     * */
    public function __construct($id = NULL, $start = NULL, $end = NULL, $description = NULL,
        $repeat = AppointmentRepeat::NO_REPEAT, $place = NULL, $owner = NULL, $contacts = []) {
        parent::__construct($id);
        $this->start = $start;
        $this->end = $end;
        $this->description = $description;
        $this->repeat = $repeat;
        $this->place = $place;
        $this->owner = $owner;
        $this->contacts = $contacts;
    }

    /* gets */

    public function getStart() {
        return $this->start;
    }

    public function getEnd() {
        return $this->end;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getRepeat() {
        return $this->repeat;
    }

    public function getPlace() {
        /* The place attribute can keep the address object itself or just the id of the address object.
         * It happens when the appointment is retrieved from the database, the intern objects are
         * not loaded. They will be loaded only if necessary (lazy initialization).
         * So, if we have only the address id, we will find the address object in the database.
         * */
        if (is_integer($this->place)) {
            $addressDAO = new AddressMySQLiDAO();
            $this->place = $addressDAO->findById($this->place);
        }
        return $this->place;
    }

    public function getOwner() {
        /* The owner attribute can keep the owner object itself or just the id of the owner object.
         * It happens when the appointment is retrieved from the database, the intern objects are
         * not loaded. They will be loaded only if necessary (lazy initialization).
         * So, if we have only the owner id, we will find the owner object in the database.
         * */
        if (is_integer($this->owner)) {
            $ownerDAO = new OwnerMySQLiDAO();
            $this->owner = $ownerDAO->findById($this->owner);
        }
        return $this->owner;
    }

    public function getContacts() {
        /* The contacts attribute can keep the list of contacts objects itself or NULL. If it is NULL don't mean
         * the list is empty (in this case, it will be an empty list, []). The NULL value means the list was not
         * loaded from the database yet. It happens when the appointment is retrieved from the database, the intern objects are
         * not loaded. They will be loaded only if necessary (lazy initialization).
         * So, if the contacts attribute is NULL, we will find the contacts objects in the database.
         * */
        if (is_null($this->contacts)) {
            $appointmentDAO = new AppointmentMySQLiDAO();
            $this->contacts = $appointmentDAO->findContacts($this->id);
        }
        return $this->contacts;
    }

    /* end gets */

    /* sets */

    public function setStart($start) {
        $this->start = $start;
    }

    public function setEnd($end) {
        $this->end = $end;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setRepeat($repeat) {
        $this->repeat = $repeat;
    }

    public function setPlace($place) {
        $this->place = $place;
    }

    public function setOwner($owner) {
        $this->owner = $owner;
    }

    public function setContacts($contacts) {
        $this->contacts = $contacts;
    }

    /* end sets */

    /* toString: returns object properties as a string. */
    public function __toString() {
        $ownerStr = $this->owner;
        if (is_object($this->owner)) {
            $ownerStr = "[id=".$this->owner->getId().", name=".$this->owner->getName()."]";
        }

        $startStr = is_null($this->start) ? NULL : $this->start->format("Y-m-d H:i");
        $endStr = is_null($this->end) ? NULL : $this->end->format("Y-m-d H:i");

        return "[".
            "id=".$this->id.", ".
            "start=".$startStr.", ".
            "end=".$endStr.", ".
            "description=".$this->description.", ".
            "repeat=".$this->repeat.", ".
            "place=".$this->place.", ".
            "owner=".$ownerStr.
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

    /* Returns the id of the address (place) of the appointment.
     * The attribute address can keep the id itself (because of the lazy initialization) or the object address
     * */
    public function getPlaceId() {
        return is_object($this->place) ? $this->place->getId() : $this->place;
    }

    /* Returns the id of the owner of the appointment.
     * The attribute owner can keep the id itself (because of the lazy initialization) or the object owner
     * */
    public function getOwnerId() {
        return is_object($this->owner) ? $this->owner->getId() : $this->owner;
    }

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

}

?>
