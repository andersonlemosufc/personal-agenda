<?php

require_once __DIR__."/com/andersonlemos/utils/Constants.php";
require_once __DIR__."/com/andersonlemos/endpoints/GenericEndpoint.php";

use com\andersonlemos\utils\Constants;
use com\andersonlemos\endpoints\GenericEndpoint;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With");

$availableEndpoints = [
    "AddressEndpoint",
    "AppointmentEndpoint",
    "ContactEndpoint",
    "OwnerEndpoint",
    "LoginEndpoint"
];

try {
    if (isset($_GET["url"])) {
        $urlParameters = explode("/", $_GET["url"]);
        $endpointName = ucwords(strtolower($urlParameters[0]))."Endpoint";

        if (in_array($endpointName, $availableEndpoints)) {
            require_once __DIR__."/com/andersonlemos/endpoints/".$endpointName.".php";
            $endpointClass = Constants::ENDPOINTS_NAMESPACE.$endpointName;
            new $endpointClass;
        } else {
            GenericEndpoint::sendResponse(Constants::STATUS_CODE_404_NOT_FOUND, false, "Endpoint not found.");
        }
    } else {
        GenericEndpoint::sendResponse(Constants::STATUS_CODE_404_NOT_FOUND, false, "Endpoint not found.");
    }
} catch (Exception $exception) {
    GenericEndpoint::sendResponse(Constants::STATUS_CODE_500_INTERNAL_SERVER_ERROR, false, "Internal error.");
}

?>
