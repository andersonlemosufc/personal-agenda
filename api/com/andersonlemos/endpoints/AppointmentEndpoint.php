<?php

namespace com\andersonlemos\endpoints;

require_once __DIR__."/../utils/Constants.php";
require_once __DIR__."/../services/AppointmentService.php";
require_once __DIR__."/../services/AddressService.php";
require_once __DIR__."/GenericEndpoint.php";

use com\andersonlemos\utils\Constants;
use com\andersonlemos\services\AppointmentService;
use com\andersonlemos\services\AddressService;

class AppointmentEndpoint extends GenericEndpoint {

    /* constructor */
    public function __construct() {
        parent::__construct("Appointment", new AppointmentService());
    }

    /* Inserts objects from the appointment model. */
    public function post() {
        $data = json_decode(file_get_contents("php://input"), true);
        $appointment = $this->createModelInstance();
        $appointment->fromMap($data);

        if (!is_null($appointment->getPlace())) {
            $addressService = new AddressService();
            $addressService->add($appointment->getPlace());
        }

        $this->service->add($appointment);
        if (isset($data["contacts_ids"])) {
            array_map(function ($contactId) use ($appointment) {
                $this->service->addContact($appointment->getId(), $contactId);
            }, $data["contacts_ids"]);
            $appointment->setContacts($this->service->getContacts($appointment->getId()));
        }

        GenericEndpoint::sendResponse(Constants::STATUS_CODE_200_OK, true, "Created.", array("id" => $appointment->getId(), "object" => $appointment->toMap()));
    }

}

?>
