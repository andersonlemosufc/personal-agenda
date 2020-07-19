<?php
namespace com\andersonlemos\models;

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
        // TODO: lazy initialization
        return $this->contacts;
    }

    public function getAppointments() {
        // TODO: lazy initialization
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


    // TODO: remove contacts and appointments functions
    public function contacts() {
        return $this->contacts;
    }

    public function appointments() {
        return $this->appointments;
    }
}

?>
