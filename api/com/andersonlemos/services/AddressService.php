<?php
namespace com\andersonlemos\services;

require_once __DIR__."/../db/dao/mysqli/AddressMySQLiDAO.php";
require_once __DIR__."/GenericService.php";

use com\andersonlemos\db\dao\mysqli\AddressMySQLiDAO;

class AddressService extends GenericService {

    /* constructor */
    public function __construct() {
        parent::__construct(new AddressMySQLiDAO());
    }

}

?>
