<?php
namespace com\andersonlemos\endpoints;

require_once __DIR__."/../utils/Constants.php";
require_once __DIR__."/../utils/Helpers.php";

use com\andersonlemos\utils\Constants;
use com\andersonlemos\utils\Helpers;

abstract class GenericEndpoint {

    protected $modelName;
    protected $service;
    protected $urlParameters;

    /* constructor */
    public function __construct($modelName, $service) {
        $this->modelName = $modelName;
        $this->service = $service;
        $this->urlParameters = explode("/", $_GET["url"]);

        switch ($_SERVER["REQUEST_METHOD"]) {
            case Constants::REQUEST_METHOD_TYPE_GET: $this->get(); break;
            case Constants::REQUEST_METHOD_TYPE_POST: $this->post(); break;
            case Constants::REQUEST_METHOD_TYPE_PUT: $this->put(); break;
            case Constants::REQUEST_METHOD_TYPE_DELETE: $this->delete(); break;
        }
    }

    /* Gets the objects from an specific model. */
    public function get() {
        if (isset($this->urlParameters[1]) && $this->urlParameters[1] != "") {
            if (Helpers::isAnIntegerValue($this->urlParameters[1])) {
                $id = intval($this->urlParameters[1]);
                $object = $this->service->get($id);
                if (is_null($object)) {
                    GenericEndpoint::sendResponse(Constants::STATUS_CODE_200_OK, true, "Data not found.");
                } else {
                    GenericEndpoint::sendResponse(Constants::STATUS_CODE_200_OK, true, "Done.", $object->toMap());
                }
            } else {
                GenericEndpoint::sendResponse(Constants::STATUS_CODE_400_BAD_REQUEST, false, "ID parameter must be an integer.");
            }
        } else {
            $objects = $this->service->all();
            $data = array_map(function ($object) {
                return $object->toMap();
            }, $objects);
            GenericEndpoint::sendResponse(Constants::STATUS_CODE_200_OK, true, "Done.", $data);
        }
    }

    /* Inserts objects from an specific model. */
    public function post() {
        $data = json_decode(file_get_contents("php://input"), true);
        $object = $this->createModelInstance();
        $object->fromMap($data);
        $this->service->add($object);
        GenericEndpoint::sendResponse(Constants::STATUS_CODE_200_OK, true, "Created.", array("id" => $object->getId(), "object" => $object->toMap()));
    }

    /* Updates objects from an specific model. */
    public function put() {
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data["id"])) {
            $id = $data["id"];
            $object = $this->service->get($id);
            if (is_null($object)) {
                GenericEndpoint::sendResponse(Constants::STATUS_CODE_200_OK, true, "Object not found. No updates were made.");
            } else {
                $object->fromMap($data);
                $this->service->add($object);
                GenericEndpoint::sendResponse(Constants::STATUS_CODE_200_OK, true, "Updated.", array("object" => $object->toMap()));
            }
        } else {
            GenericEndpoint::sendResponse(Constants::STATUS_CODE_400_BAD_REQUEST, false, "Missing the parameter ID.");
        }
    }

    /* Deletes objects from an specific model. */
    public function delete() {
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data["id"])) {
            $id = $data["id"];
            $response = $this->service->remove($id);
            $message = $response ? "Deletion done." : "ID not found. No deletion has been made.";
            GenericEndpoint::sendResponse(Constants::STATUS_CODE_200_OK, true, $message);
        } else {
            GenericEndpoint::sendResponse(Constants::STATUS_CODE_400_BAD_REQUEST, false, "Missing the parameter ID.");
        }
    }

    /* Sends the requests responses. */
    public static function sendResponse($statusCode, $success, $message, $data = NULL) {
        $response = array(
            "success" => $success,
            "message" => $message
        );

        if (!is_null($data)) {
            $response["data"] = $data;
        }

        http_response_code($statusCode);
        echo(json_encode($response, JSON_UNESCAPED_UNICODE));
    }

    // Creates an instance of the class that the endpoint is handling.
    protected function createModelInstance() {
        require_once __DIR__."/../models/".$this->modelName.".php";
        $className = Constants::MODELS_NAMESPACE.$this->modelName;
        return new $className;
    }

}

?>
