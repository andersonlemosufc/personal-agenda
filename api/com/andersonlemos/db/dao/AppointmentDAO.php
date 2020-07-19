<?php
namespace com\andersonlemos\db\dao;

require_once __DIR__."/GenericDAO.php";

interface AppointmentDAO extends GenericDAO {
    /* specific methods to appointment operations on the database. */

    /* Receives an owner id and searches in the database for the appointments of the owner with this id.
     * Returns the list of appointments belonging to the owner with that id.
     * */
    public function findByOwnerId($ownerId);

    /* Receives a contact id and searches in the database for the appointments with the contact that has this id.
     * Returns the list of appointments with the contact that the id belongs.
     * */
    public function findByContactId($contactId);

    /* Receives an appointment id and searches in the database for the contacts in the appointment that has this id.
     * Returns the list of contacts that belonging to the appoitment with that id.
     * */
    public function findContacts($appoitmentId);

}

?>
