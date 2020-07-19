<?php
namespace com\andersonlemos\models;

require_once __DIR__."/../db/dao/mysqli/AddressMySQLiDAO.php";
require_once __DIR__."/Bean.php";

use com\andersonlemos\db\dao\mysqli\AddressMySQLiDAO;

abstract class Person extends Bean {

    protected $name;
    protected $dateOfBirth;
    protected $phone;
    protected $email;
    protected $photo;
    protected $address;

    /* constructor (by default, if a argument is not passed, it will be NULL). */
    public function __construct($id = NULL, $name = NULL, $dateOfBirth = NULL, $phone = NULL, $email = NULL, $photo = NULL, $address = NULL) {
        parent::__construct($id);
        $this->name = $name;
        $this->dateOfBirth = $dateOfBirth;
        $this->phone = $phone;
        $this->email = $email;
        $this->photo = $photo;
        $this->address = $address;
    }

    /* gets */

    public function getName() {
        return $this->name;
    }

    public function getDateOfBirth() {
        return $this->dateOfBirth;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPhoto() {
        return $this->photo;
    }

    public function getAddress() {
        /* The address attribute can keep the address object itself or just the id of the address object.
         * It happens when the person (owner or contact) is taken from the database, the intern objects are
         * not loaded. They will be loaded only if necessary (lazy initialization).
         * So, if we have only the address id, we find the address object in the database.
         * */
        if (is_integer($this->address)) {
            $addressDAO = new AddressMySQLiDAO();
            $this->address = $addressDAO->findById($this->address);
        }
        return $this->address;
    }

    /*end gets*/

    /* sets */

    public function setName($name) {
        $this->name = $name;
    }

    public function setDateOfBirth($dateOfBirth) {
        $this->dateOfBirth = $dateOfBirth;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPhoto($photo) {
        $this->photo = $photo;
    }

    public function setAddress($address) {
        $this->address = $address;
    }

    /* end sets */

    /* toString: returns object properties as a string. */
    public function __toString() {
        return "[".
            "id=".$this->id.", ".
            "name=".$this->name.", ".
            "date of birth=".$this->dateOfBirth.", ".
            "phone=".$this->phone.", ".
            "email=".$this->email.", ".
            "address=".$this->address.
        "]";
    }

    /* other methods */

    /* Returns the id of the person's address.
     * The attribute address can keep the id itself (because of the lazy initialization) or the object address
     * */
    public function getAddressId() {
        return is_object($this->address) ? $this->address->getId() : $this->address;
    }
}

?>
