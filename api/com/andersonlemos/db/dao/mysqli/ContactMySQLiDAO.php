<?php
namespace com\andersonlemos\db\dao\mysqli;

require_once __DIR__."/../ContactDAO.php";
require_once __DIR__."/GenericMySQLiDAO.php";

use com\andersonlemos\db\dao\ContactDAO;

class ContactMySQLiDAO extends GenericMysqliDAO implements ContactDAO {

    /* construtor: calls the parent constructor passing the table name that this classe handle (contact)
     * and the connection with the database.
     * If the database connection is NULL, the parent class will creates one that is not.
     * */
    public function __construct($connection = NULL) {
        parent::__construct("contact", $connection);
    }

}

?>
