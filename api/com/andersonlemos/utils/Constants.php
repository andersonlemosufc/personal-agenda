<?php
namespace com\andersonlemos\utils;

abstract class Constants {

    public const DEFAULT_DATE_FORMAT = "Y-m-d\TH:i:s";

    public const MODELS_NAMESPACE = "\\com\\andersonlemos\\models\\";
    public const SERVICES_NAMESPACE = "\\com\\andersonlemos\\services\\";
    public const ENDPOINTS_NAMESPACE = "\\com\\andersonlemos\\endpoints\\";

    public const STATUS_CODE_200_OK = 200;
    public const STATUS_CODE_201_CREATED = 201;
    public const STATUS_CODE_202_ACCEPTED = 202;
    public const STATUS_CODE_400_BAD_REQUEST = 400;
    public const STATUS_CODE_401_UNAUTHORIZED = 401;
    public const STATUS_CODE_403_FORBIDDEN = 403;
    public const STATUS_CODE_404_NOT_FOUND = 404;
    public const STATUS_CODE_500_INTERNAL_SERVER_ERROR = 500;

    public const REQUEST_METHOD_TYPE_GET = "GET";
    public const REQUEST_METHOD_TYPE_POST = "POST";
    public const REQUEST_METHOD_TYPE_PUT = "PUT";
    public const REQUEST_METHOD_TYPE_DELETE = "DELETE";

}

?>
