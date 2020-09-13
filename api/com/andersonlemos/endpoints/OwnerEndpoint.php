<?php

namespace com\andersonlemos\endpoints;

require_once __DIR__."/../services/OwnerService.php";
require_once __DIR__."/GenericEndpoint.php";

use com\andersonlemos\services\OwnerService;

class OwnerEndpoint extends GenericEndpoint {

    /* constructor */
    public function __construct() {
        parent::__construct("Owner", new OwnerService());
    }

    /* Inserts objects from the owner model. */
    public function post() {}

    /* Updates objects from the owner model. */
    public function put() {}

}

?>
