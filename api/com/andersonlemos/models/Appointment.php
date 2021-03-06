<?php
namespace com\andersonlemos\models;

require_once __DIR__."/../db/dao/mysqli/AppointmentMySQLiDAO.php";
require_once __DIR__."/../db/dao/mysqli/OwnerMySQLiDAO.php";
require_once __DIR__."/../db/dao/mysqli/AddressMySQLiDAO.php";
require_once __DIR__."/../db/dao/mysqli/ContactMySQLiDAO.php";
require_once __DIR__."/../enums/AppointmentRepeat.php";
require_once __DIR__."/../utils/Helpers.php";
require_once __DIR__."/Bean.php";

use com\andersonlemos\utils\Helpers;
use com\andersonlemos\enums\AppointmentRepeat;
use com\andersonlemos\db\dao\mysqli\AppointmentMySQLiDAO;
use com\andersonlemos\db\dao\mysqli\OwnerMySQLiDAO;
use com\andersonlemos\db\dao\mysqli\AddressMySQLiDAO;
use com\andersonlemos\db\dao\mysqli\ContactMySQLiDAO;

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

    /* Returns the appointment object in an associative array form without the intern objects (just their ids). */
    protected function toShallowMap() {

        $contactsIds = array_map(function ($contact) {
            return $contact->getId();
        }, $this->getContacts());

        return array(
            "id" => $this->id,
            "start" => Helpers::dateTimeToDefaultFormat($this->start),
            "end" => Helpers::dateTimeToDefaultFormat($this->end),
            "description" => $this->description,
            "repeat" => $this->repeat,
            "place_id" => $this->getPlaceId(),
            "owner_id" => $this->getOwnerId(),
            "contacts_ids" => $contactsIds
        );
    }

    /* Returns the appointment in an associative array form */
    public function toMap() {
        $map = $this->toShallowMap();
        $map["place"] = $this->getPlace() ? $this->place->toShallowMap() : NULL;
        $map["owner"] = $this->getOwner() ? $this->owner->toShallowMap() : NULL;

        $map["contacts"] = array_map(function ($contact) {
            return $contact->toShallowMap();
        }, $this->getContacts());

        return $map;
    }

    /* Receives a map that is an associative array with the attribuites of an appointment.
     * Returns an appointment object with the attributes of the associative array */
    public function fromMap($map) {
        if (!is_null($map) && is_array($map)) {
            $this->id = array_key_exists("id", $map) ? $map["id"] : $this->id;
            $this->start = array_key_exists("start", $map) ? Helpers::stringToDateTime($map["start"]) : $this->start;
            $this->end = array_key_exists("end", $map) ? Helpers::stringToDateTime($map["end"]) : $this->end;
            $this->description = array_key_exists("description", $map) ? $map["description"] : $this->description;
            $this->repeat = array_key_exists("repeat", $map) ? $map["repeat"] : $this->repeat;

            if (array_key_exists("place", $map) && is_array($map["place"])) {
                $this->place = (new Address())->fromMap($map["place"]);
            } elseif (array_key_exists("place_id", $map)) {
                $this->place = $map["place_id"];
            }

            if (array_key_exists("owner", $map) && is_array($map["owner"])) {
                $this->owner = (new Owner())->fromMap($map["owner"]);
            } elseif (array_key_exists("owner_id", $map)) {
                $this->owner = $map["owner_id"];
            }

            if (array_key_exists("contacts", $map) && is_array($map["contacts"])) {
                $this->contacts = array_map(function ($contactMap) {
                    return (new Contact())->fromMap($contactMap);
                }, $map["contacts"]);
            } elseif (array_key_exists("contacts_ids", $map) && is_array($map["contacts_ids"])) {
                $contactDAO = new ContactMySQLiDAO();
                $this->contacts = array_map(function ($contactId) use ($contactDAO) {
                    return $contactDAO->findById($contactId);
                }, $map["contacts_ids"]);
            }
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
