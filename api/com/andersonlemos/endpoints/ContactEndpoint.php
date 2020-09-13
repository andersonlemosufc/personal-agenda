<?php

namespace com\andersonlemos\endpoints;

require_once __DIR__."/../services/AddressService.php";
require_once __DIR__."/../services/ContactService.php";
require_once __DIR__."/../utils/Constants.php";
require_once __DIR__."/GenericEndpoint.php";

use com\andersonlemos\services\AddressService;
use com\andersonlemos\services\ContactService;
use com\andersonlemos\utils\Constants;

class ContactEndpoint extends GenericEndpoint {

    /* constructor */
    public function __construct() {
        parent::__construct("Contact", new ContactService());
    }

    /* Inserts objects from the contact model. */
    public function post() {
        $data = json_decode(file_get_contents("php://input"), true);
        $contact = $this->createModelInstance();
        $contact->fromMap($data);
        if (!is_null($contact->getAddress())) {
            $addressService = new AddressService();
            $addressService->add($contact->getAddress());
        }

        $this->service->add($contact);
        GenericEndpoint::sendResponse(Constants::STATUS_CODE_200_OK, true, "Created.", array("id" => $contact->getId(), "object" => $contact->toMap()));
    }

}

?>
