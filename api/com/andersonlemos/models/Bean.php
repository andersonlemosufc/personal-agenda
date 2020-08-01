<?php
namespace com\andersonlemos\models;

abstract class Bean {

    protected $id;

    /* constructor (by default, if a argument is not passed, it will be NULL). */
    public function __construct($id = NULL) {
        $this->id = $id;
    }

    /* gets */

    public function getId() {
        return $this->id;
    }

    /* end gets */

    /* sets */

    public function setId($id) {
        $this->id = $id;
    }

    /* end sets*/

    /* methods to handle with api requests */

    /* Returns the bean object in an associative array form. */
    public abstract function toMap();

    /* Receives a map that is an associative array with the attribuites of a bean object.
     * Fills the bean object with the attributes of the associative array and returns the object itself. */
    public abstract function fromMap($map);

    /* Returns the bean object in a json form. */
    public function toJSON() {
        return json_encode($this->toMap(), JSON_UNESCAPED_UNICODE);
    }

    /* Receives a json with the attribuites of a bean object.
     * Fills the bean object with the attributes of json and returns the object itself. */
    public function fromJSON($json) {
        return $this->fromMap(json_decode($json, true));
    }

    /* end of methods to api requests */
}

?>
