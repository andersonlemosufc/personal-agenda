<?php
namespace com\andersonlemos\db\dao\mysqli;

require_once __DIR__."/../../../enums/GenericDAOOperations.php";
require_once __DIR__."/../../../models/Contact.php";
require_once __DIR__."/../ContactDAO.php";
require_once __DIR__."/GenericMySQLiDAO.php";

use com\andersonlemos\db\dao\ContactDAO;
use com\andersonlemos\enums\GenericDAOOperations;
use com\andersonlemos\models\Contact;

class ContactMySQLiDAO extends GenericMysqliDAO implements ContactDAO {

    /* construtor: calls the parent constructor passing the table name that this classe handle (contact)
     * and the connection with the database.
     * If the database connection is NULL, the parent class will creates one that is not.
     * */
    public function __construct($connection = NULL) {
        parent::__construct("contact", $connection);
    }

    /* Returns the sql string for insert and update operations on contact table. */
    public function getSQLString($operation){
        // TODO
        return NULL;
    }

    /* Binds the parameters for execute the statement. */
    public function bindParams($contact, $stmt, $operation) {
        // TODO
    }

    /* Creates an contact object based on the result of a query from a statement and returns it. */
    public function fillElementFromStatment($stmt) {
        // TODO
        return NULL;
    }

    /* ContactDAO specific functions */

    public function findByOwnerId($ownerId) {
        // TODO
        return [];
    }

    public function findByAppointment($appointmentId) {
        // TODO
        return [];
    }

    public function findAppointments($contactId) {
        // TODO
        return [];
    }

    /* end of ContactDAO specific functions */

}

?>
