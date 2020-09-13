<?php

namespace com\andersonlemos\endpoints;

require_once __DIR__."/../utils/Constants.php";
require_once __DIR__."/../services/OwnerService.php";
require_once __DIR__."/GenericEndpoint.php";

use com\andersonlemos\utils\Constants;
use com\andersonlemos\services\OwnerService;

class LoginEndpoint extends GenericEndpoint {

    /* constructor */
    public function __construct() {
        parent::__construct("Owner", new OwnerService());
    }

    /* Does the login verification of an owner. */
    public function post() {}

    /* Overriding the parent class method to do nothing (invalid request for Login Endpoint). */
    public function get() {
        GenericEndpoint::sendResponse(Constants::STATUS_CODE_400_BAD_REQUEST, false, "Method not allowed for Login Endpoint");
    }

    /* Overriding the parent class method to do nothing (invalid request for Login Endpoint). */
    public function put() {
        GenericEndpoint::sendResponse(Constants::STATUS_CODE_400_BAD_REQUEST, false, "Method not allowed for Login Endpoint");
    }

    /* Overriding the parent class method to do nothing (invalid request for Login Endpoint). */
    public function delete() {
        GenericEndpoint::sendResponse(Constants::STATUS_CODE_400_BAD_REQUEST, false, "Method not allowed for Login Endpoint");
    }

}

?>
