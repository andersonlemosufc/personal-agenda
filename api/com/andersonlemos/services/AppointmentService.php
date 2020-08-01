<?php
namespace com\andersonlemos\services;

require_once __DIR__."/../db/dao/mysqli/AppointmentMySQLiDAO.php";
require_once __DIR__."/GenericService.php";

use com\andersonlemos\db\dao\mysqli\AppointmentMySQLiDAO;

class AppointmentService extends GenericService {

    /* constructor */
    public function __construct() {
        parent::__construct(new AppointmentMySQLiDAO());
    }

}

?>
