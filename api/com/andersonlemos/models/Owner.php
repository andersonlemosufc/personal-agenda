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


    // TODO: remove contacts and appointments functions
    public function contacts() {
        return $this->contacts;
    }

    public function appointments() {
        return $this->appointments;
    }
}

?>
