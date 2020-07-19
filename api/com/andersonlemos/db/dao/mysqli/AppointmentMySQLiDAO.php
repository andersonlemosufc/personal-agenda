<?php
namespace com\andersonlemos\db\dao\mysqli;

require_once __DIR__."/../../../enums/GenericDAOOperations.php";
require_once __DIR__."/../../../models/Appointment.php";
require_once __DIR__."/../AppointmentDAO.php";
require_once __DIR__."/GenericMySQLiDAO.php";
require_once __DIR__."/ContactMySQLiDAO.php";

use com\andersonlemos\db\dao\AppointmentDAO;
use com\andersonlemos\enums\GenericDAOOperations;
use com\andersonlemos\models\Appointment;

class AppointmentMySQLiDAO extends GenericMysqliDAO implements AppointmentDAO {

    /* construtor: calls the parent constructor passing the table name that this classe handle (appointment)
     * and the connection with the database.
     * If the database connection is NULL, the parent class will creates one that is not.
     * */
    public function __construct($connection = NULL) {
        parent::__construct("appointment", $connection);
    }

    /* Returns the sql string for insert and update operations on appointment table. */
    public function getSQLString($operation){
        // TODO
        return NULL;
    }

    /* Binds the parameters for execute the statement. */
    public function bindParams($appointment, $stmt, $operation) {
        // TODO
    }

    /* Creates an appointment object based on the result of a query from a statement and returns it. */
    public function fillElementFromStatment($stmt) {
        // TODO
        return NULL;
    }

    /* AppointmentDAO specific functions */

    public function findByOwnerId($ownerId) {
        // TODO
        return [];
    }

    public function findByContactId($contactId) {
        // TODO
        return [];
    }

    public function findContacts($appointmentId) {
        $contactDAO = new ContactMySQLiDAO($this->connection);
        return $contactDAO->findByAppointment($appointmentId);
    }

    /* end of AppointmentDAO specific functions */

}

?>
