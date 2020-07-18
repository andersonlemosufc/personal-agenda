<?php
namespace com\andersonlemos\db\dao\mysqli;

require_once __DIR__."/../OwnerDAO.php";
require_once __DIR__."/GenericMySQLiDAO.php";

use com\andersonlemos\db\dao\OwnerDAO;

class OwnerMySQLiDAO extends GenericMysqliDAO implements OwnerDAO {

    /* construtor: calls the parent constructor passing the table name that this classe handle (owner)
     * and the connection with the database.
     * If the database connection is NULL, the parent class will creates one that is not.
     * */
    public function __construct($connection = NULL) {
        parent::__construct("owner", $connection);
    }

}

?>
