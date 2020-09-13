<?php

namespace com\andersonlemos\endpoints;

require_once __DIR__."/../services/ContactService.php";
require_once __DIR__."/GenericEndpoint.php";

use com\andersonlemos\services\ContactService;

class ContactEndpoint extends GenericEndpoint {

    /* constructor */
    public function __construct() {
        parent::__construct("Contact", new ContactService());
    }

    /* Inserts objects from the contact model. */
    public function post() {}

    /* Updates objects from the contact model. */
    public function put() {}

}

?>
