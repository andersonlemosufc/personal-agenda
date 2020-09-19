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
    public function post() {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data["email"]) || empty(trim($data["email"]))) {
            GenericEndpoint::sendResponse(Constants::STATUS_CODE_400_BAD_REQUEST, false, "Invalid email.");
        } elseif (!isset($data["password"]) || empty(trim($data["password"]))) {
            GenericEndpoint::sendResponse(Constants::STATUS_CODE_400_BAD_REQUEST, false, "Invalid password.");
        } else {
            $email = $data["email"];
            $password = sha1($data["password"]);
            $owner = $this->service->getByEmail($email);

            if (is_null($owner)) {
                GenericEndpoint::sendResponse(Constants::STATUS_CODE_401_UNAUTHORIZED, false, "No owner found with email ".$email.".");
            } else if ($owner->getPassword() != $password) {
                GenericEndpoint::sendResponse(Constants::STATUS_CODE_401_UNAUTHORIZED, false, "Incorrect password.");
            } else {
                GenericEndpoint::sendResponse(Constants::STATUS_CODE_200_OK, true, "Authentication done.", array("owner" => $owner->toMap()));
            }
        }
    }

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
