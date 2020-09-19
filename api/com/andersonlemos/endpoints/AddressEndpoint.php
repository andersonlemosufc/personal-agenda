<?php

namespace com\andersonlemos\endpoints;

require_once __DIR__."/../services/AddressService.php";
require_once __DIR__."/GenericEndpoint.php";

use com\andersonlemos\services\AddressService;

class AddressEndpoint extends GenericEndpoint {

    /* constructor */
    public function __construct() {
        parent::__construct("Address", new AddressService());
    }

}

?>
