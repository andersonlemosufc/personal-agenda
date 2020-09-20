<?php

namespace com\andersonlemos\endpoints;

require_once __DIR__."/../utils/Constants.php";
require_once __DIR__."/../utils/Helpers.php";
require_once __DIR__."/../services/AddressService.php";
require_once __DIR__."/../services/OwnerService.php";
require_once __DIR__."/GenericEndpoint.php";

use com\andersonlemos\services\AddressService;
use com\andersonlemos\services\OwnerService;
use com\andersonlemos\utils\Constants;
use com\andersonlemos\utils\Helpers;

class OwnerEndpoint extends GenericEndpoint {

    /* constructor */
    public function __construct() {
        parent::__construct("Owner", new OwnerService());
    }

    /* Gets the objects from an specific model. */
    public function get() {
        if (isset($this->urlParameters[2]) && $this->urlParameters[2] != "") {
            if ($this->urlParameters[2] != "contacts") {
                GenericEndpoint::sendResponse(Constants::STATUS_CODE_404_NOT_FOUND, false, "Endpoint not found.");
            } elseif ($this->urlParameters[1] == "" || !Helpers::isAnIntegerValue($this->urlParameters[1])) {
                GenericEndpoint::sendResponse(Constants::STATUS_CODE_400_BAD_REQUEST, false, "ID parameter must be an integer.");
            } else {
                $id = intval($this->urlParameters[1]);
                $contacts = $this->service->getContacts($id);
                $data = array_map(function ($contact) {
                    return $contact->toMap();
                }, $contacts);
                GenericEndpoint::sendResponse(Constants::STATUS_CODE_200_OK, true, "Done.", $data);
            }
        } else {
            parent::get();
        }
    }

    /* Inserts objects from the owner model. */
    public function post() {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data["email"]) || empty(trim($data["email"]))) {
            GenericEndpoint::sendResponse(Constants::STATUS_CODE_400_BAD_REQUEST, false, "Missing email.");
        } elseif (!isset($data["password"]) || empty(trim($data["password"]))) {
            GenericEndpoint::sendResponse(Constants::STATUS_CODE_400_BAD_REQUEST, false, "Missing password.");
        } elseif (!is_null($this->service->getByEmail($data["email"]))) {
            GenericEndpoint::sendResponse(Constants::STATUS_CODE_400_BAD_REQUEST, false, "Email already exists.");
        } else {
            $owner = $this->createModelInstance();
            $owner->fromMap($data);
            $owner->setPassword(sha1($owner->getPassword()));

            if (!is_null($owner->getAddress())) {
                $addressService = new AddressService();
                $addressService->add($owner->getAddress());
            }

            $this->service->add($owner);
            GenericEndpoint::sendResponse(Constants::STATUS_CODE_200_OK, true, "Created.", array("id" => $owner->getId(), "object" => $owner->toMap()));
        }
    }

    /* Updates objects from an specific model. */
    public function put() {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data["id"])) {
            GenericEndpoint::sendResponse(Constants::STATUS_CODE_400_BAD_REQUEST, false, "Missing the parameter ID.");
        } elseif (isset($data["email"]) && empty(trim($data["email"]))) {
            GenericEndpoint::sendResponse(Constants::STATUS_CODE_400_BAD_REQUEST, false, "Invalid email.");
        } elseif (isset($data["password"]) && empty(trim($data["password"]))) {
            GenericEndpoint::sendResponse(Constants::STATUS_CODE_400_BAD_REQUEST, false, "Invalid password.");
        } else {
            $id = $data["id"];
            $owner = $this->service->get($id);
            if (is_null($owner)) {
                GenericEndpoint::sendResponse(Constants::STATUS_CODE_200_OK, true, "Object not found. No updates were made.");
            } else {
                $ownerWithPassedEmail = $this->service->getByEmail($data["email"]);
                if (!is_null($ownerWithPassedEmail) && $ownerWithPassedEmail->getId() != $id) {
                    GenericEndpoint::sendResponse(Constants::STATUS_CODE_400_BAD_REQUEST, false, "Email already exists.");
                } else {
                    if (isset($data["password"])) {
                        $data["password"] = sha1($data["password"]);
                    }
                    $owner->fromMap($data);
                    $this->service->update($owner);
                    GenericEndpoint::sendResponse(Constants::STATUS_CODE_200_OK, true, "Updated.", array("object" => $owner->toMap()));
                }
            }
        }
    }

}

?>
