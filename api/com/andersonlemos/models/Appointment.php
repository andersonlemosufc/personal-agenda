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

    private $date;
    private $startTime;
    private $endTime;
    private $description;
    private $repeat;
    private $address;
    private $owner;
    private $contacts;

    /* constructor (by default, if a argument is not passed, it will be NULL,
     * except the contacts array, initialized with an empty array and repeat, initialized with NO_REPEAT constant).
     * */
    public function __construct($id = NULL, $date = NULL, $startTime = NULL, $endTime = NULL, $description = NULL,
            $repeat = AppointmentRepeat::NO_REPEAT, $address = NULL, $owner = NULL, $contacts = []) {
        parent::__construct($id);
        $this->date = $date;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->description = $description;
        $this->repeat = $repeat;
        $this->address = $address;
        $this->owner = $owner;
        $this->contacts = $contacts;
    }

    /* gets */

    public function getDate() {
        return $this->date;
    }

    public function getStartTime() {
        return $this->startTime;
    }

    public function getEndTime() {
        return $this->endTime;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getRepeat() {
        return $this->repeat;
    }

    public function getAddress() {
        /* The address attribute can keep the address object itself or just the id of the address object.
         * It happens when the appointment is retrieved from the database, the intern objects are
         * not loaded. They will be loaded only if necessary (lazy initialization).
         * So, if we have only the address id, we will find the address object in the database.
         * */
        if (is_integer($this->address)) {
            $addressDAO = new AddressMySQLiDAO();
            $this->address = $addressDAO->findById($this->address);
        }
        return $this->address;
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

    public function setDate($date) {
        $this->date = $date;
    }

    public function setStartTime($startTime) {
        $this->startTime = $startTime;
    }

    public function setEndTime($endTime) {
        $this->endTime = $endTime;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setRepeat($repeat) {
        $this->repeat = $repeat;
    }

    public function setAddress($address) {
        $this->address = $address;
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
        return "[".
            "id=".$this->id.", ".
            "date=".$this->date.", ".
            "startTime=".$this->startTime.", ".
            "endTime=".$this->endTime.", ".
            "description=".$this->description.", ".
            "repeat=".$this->repeat.", ".
            "address=".$this->address.", ".
            "owner=".$ownerStr.
        "]";
    }

    /* other methods */

    /* Returns the id of the address of the appointment.
     * The attribute address can keep the id itself (because of the lazy initialization) or the object address
     * */
    public function getAddressId() {
        return is_object($this->address) ? $this->address->getId() : $this->address;
    }

    /* Returns the id of the owner of the appointment.
     * The attribute owner can keep the id itself (because of the lazy initialization) or the object owner
     * */
    public function getOwnerId() {
        return is_object($this->owner) ? $this->owner->getId() : $this->owner;
    }


    // TODO: remove contacts function
    public function contacts() {
        return $this->contacts;
    }

}

?>
