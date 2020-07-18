<?php
namespace com\andersonlemos\db\dao;

interface GenericDAO {
    /* generic methods used in database operations for all model classes */

    /* Receives an element and inserts it in the database.
     * The element id into the passed element is updated by the created id.
     * Returns the inserted element.
     * */
    public function insert($element);

    /* Receives an element and updates it in the database.
     * Returns the updated element.
     * */
    public function update($element);

    /* Receives an element id and deletes from the database the element with this id.
     * Returns true if one element was deleted or false if it was not.
     * */
    public function delete($elementId);

    /* Receives an element id and searches for an element with this id in the database.
     * Returns the element with this id if there is one, or NULL is there is not.
     * */
    public function findById($elementId);

    /* Returns all elements from the database table. */
    public function findAll();
}

?>
