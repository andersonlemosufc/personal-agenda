<?php
namespace com\andersonlemos\models;

require_once __DIR__."/Person.php";

class Contact extends Person {

    private $comments;
    private $favorite;
    private $photo;
    private $owner;
    private $appointments;

    /* constructor (by default, if a argument is not passed, it will be NULL,
     * except the appointments array, initialized with an empty array). */
    public function __construct($id = NULL, $name = NULL, $dateOfBirth = NULL, $phone = NULL, $address = NULL,
            $comments = NULL, $favorite = NULL, $photo = NULL, $owner = NULL, $appointments = []) {
        parent::__construct($id, $name, $dateOfBirth, $phone, $address);
        $this->comments = $comments;
        $this->favorite = $favorite;
        $this->photo = $photo;
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

    public function getPhoto() {
        return $this->photo;
    }

    public function getOwner() {
        // TODO: lazy initialization
        return $this->owner;
    }

    public function getAppointments() {
        // TODO: lazy initialization
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

    public function setPhoto($photo) {
        $this->photo = $photo;
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
}

?>
