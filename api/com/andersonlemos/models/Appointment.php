<?php
namespace com\andersonlemos\models;

require_once __DIR__."/../enums/AppointmentRepeat.php";
require_once __DIR__."/Bean.php";

use com\andersonlemos\enums\AppointmentRepeat;

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
     * except the contacts array, initialized with an empty array and repeat, initialized with NO_REPEAT constant). */
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
        // TODO: lazy initialization
        return $this->address;
    }

    public function getOwner() {
        // TODO: lazy initialization
        return $this->owner;
    }

    public function getContacts() {
        // TODO: lazy initialization
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
}

?>
