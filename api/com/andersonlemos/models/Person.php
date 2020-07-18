<?php
namespace com\andersonlemos\models;

require_once __DIR__."/Bean.php";

abstract class Person extends Bean {

    protected $name;
    protected $dateOfBirth;
    protected $phone;
    protected $address;

    /* constructor (by default, if a argument is not passed, it will be NULL). */
    public function __construct($id = NULL, $name = NULL, $dateOfBirth = NULL, $phone = NULL, $address = NULL) {
        parent::__construct($id);
        $this->name = $name;
        $this->dateOfBirth = $dateOfBirth;
        $this->phone = $phone;
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

    public function getAddress() {
        // TODO: lazy initialization
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
