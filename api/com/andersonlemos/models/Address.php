<?php
namespace com\andersonlemos\models;

require_once __DIR__."/Bean.php";

class Address extends Bean {

    private $street;
    private $number;
    private $complement;
    private $neighborhood;
    private $postalCode;
    private $city;
    private $state;
    private $country;

    /* constructor (by default, if a argument is not passed, it will be NULL). */
    public function __construct($id = NULL, $street = NULL, $number = NULL, $complement = NULL, $neighborhood = NULL,
            $postalCode = NULL, $city = NULL, $state = NULL, $country = NULL) {
        parent::__construct($id);
        $this->street = $street;
        $this->number = $number;
        $this->complement = $complement;
        $this->neighborhood = $neighborhood;
        $this->postalCode = $postalCode;
        $this->city = $city;
        $this->state = $state;
        $this->country = $country;
    }

    /* gets */

    public function getStreet() {
        return $this->street;
    }

    public function getNumber() {
        return $this->number;
    }

    public function getComplement() {
        return $this->complement;
    }

    public function getNeighborhood() {
        return $this->neighborhood;
    }

    public function getPostalCode() {
        return $this->postalCode;
    }

    public function getCity() {
        return $this->city;
    }

    public function getState() {
        return $this->state;
    }

    public function getCountry() {
        return $this->country;
    }

    /* end gets */

    /* sets */

    public function setStreet($street) {
        $this->street = $street;
    }

    public function setNumber($number) {
        $this->number = $number;
    }

    public function setComplement($complement) {
        $this->complement = $complement;
    }

    public function setNeighborhood($neighborhood) {
        $this->neighborhood = $neighborhood;
    }

    public function setPostalCode($postalCode) {
        $this->postalCode = $postalCode;
    }

    public function setCity($city) {
        $this->city = $city;
    }

    public function setState($state) {
        $this->state = $state;
    }

    public function setCountry($country) {
        $this->country = $country;
    }

    /* end sets */

    /* toString: returns object properties as a string. */
    public function __toString() {
        return "[".
            "id=".$this->id.", ".
            "street=".$this->street.", ".
            "number=".$this->number.", ".
            "complement=".$this->complement.", ".
            "neighborhood=".$this->neighborhood.", ".
            "postal code=".$this->postalCode.", ".
            "city=".$this->city.", ".
            "state=".$this->state.", ".
            "country=".$this->country.
        "]";
    }

}

?>
