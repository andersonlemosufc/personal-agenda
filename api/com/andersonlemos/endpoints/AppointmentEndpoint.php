<?php

namespace com\andersonlemos\endpoints;

require_once __DIR__."/../utils/Constants.php";
require_once __DIR__."/../services/AppointmentService.php";
require_once __DIR__."/GenericEndpoint.php";

use com\andersonlemos\services\AppointmentService;

class AppointmentEndpoint extends GenericEndpoint {

    /* constructor */
    public function __construct() {
        parent::__construct("Appointment", new AppointmentService());
    }

    /* Inserts objects from the appointment model. */
    public function post() {}

    /* Updates objects from the appointment model. */
    public function put() {}

}

?>
