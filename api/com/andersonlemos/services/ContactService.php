<?php
namespace com\andersonlemos\services;

require_once __DIR__."/../db/dao/mysqli/ContactMySQLiDAO.php";
require_once __DIR__."/GenericService.php";

use com\andersonlemos\db\dao\mysqli\ContactMySQLiDAO;

class ContactService extends GenericService {

    /* constructor */
    public function __construct() {
        parent::__construct(new ContactMySQLiDAO());
    }

}

?>
