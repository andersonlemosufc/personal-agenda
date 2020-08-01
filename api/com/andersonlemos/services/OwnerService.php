<?php
namespace com\andersonlemos\services;

require_once __DIR__."/../db/dao/mysqli/OwnerMySQLiDAO.php";
require_once __DIR__."/GenericService.php";

use com\andersonlemos\db\dao\mysqli\OwnerMySQLiDAO;

class OwnerService extends GenericService {

    /* constructor */
    public function __construct() {
        parent::__construct(new OwnerMySQLiDAO());
    }

}

?>
