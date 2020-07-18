<?php
namespace com\andersonlemos\models;

class Bean {

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
}

?>
