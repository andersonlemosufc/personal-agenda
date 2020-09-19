<?php
namespace com\andersonlemos\db\dao;

require_once __DIR__."/GenericDAO.php";

interface OwnerDAO extends GenericDAO {
    /* specific methods to owner operations on the database. */

    /* Receives an owner id and searches in the databese for the contacts of the owner with this id.
     * Returns the list of contacts belonging to the owner with that id.
     * */
    public function findContacts($ownerId);

    /* Receives an owner id and searches in the database for the appointments of the owner with this id.
     * Returns the list of appointments belonging to the owner with that id.
     * */
    public function findAppointments($ownerId);

    /* Receives an owner email and returns tha owner that has this email.
     * Returns null if there is no owner with this email.
     * */
    public function findByEmail($email);

}

?>
