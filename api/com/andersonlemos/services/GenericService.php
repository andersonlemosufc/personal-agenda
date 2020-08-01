<?php
namespace com\andersonlemos\services;

abstract class GenericService {

    protected $dao;

    /* constructor */
    public function __construct($dao) {
        $this->dao = $dao;
    }

    /* Receives an element and inserts it in the database.
     * The element id into the passed element is updated by the created id.
     * Returns the inserted element.
     * */
    public function add($element) {
        return $this->dao->insert($element);
    }

    /* Receives an element and updates it in the database.
     * Returns the updated element.
     * */
    public function update($element) {
        return $this->dao->update($element);
    }

    /* Receives an element id and deletes from the database the element with this id.
     * Returns true if one element was deleted or false if it was not.
     * */
    public function remove($elementId) {
        return $this->dao->delete($elementId);
    }

    /* Receives an element id and searches for an element with this id in the database.
     * Returns the element with this id if there is one, or NULL is there is not.
     * */
    public function get($elementId) {
        return $this->dao->findById($elementId);
    }

    /* Returns all elements from the database table. */
    public function all() {
        return $this->dao->findAll();
    }

}

?>
