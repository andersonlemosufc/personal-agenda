<?php
namespace com\andersonlemos\db\dao\mysqli;

require_once __DIR__."/../../../enums/GenericDAOOperations.php";
require_once __DIR__."/../../../models/Address.php";
require_once __DIR__."/../AddressDAO.php";
require_once __DIR__."/GenericMySQLiDAO.php";

use com\andersonlemos\db\dao\AddressDAO;
use com\andersonlemos\enums\GenericDAOOperations;
use com\andersonlemos\models\Address;

class AddressMySQLiDAO extends GenericMysqliDAO implements AddressDAO {

    /* construtor: calls the parent constructor passing the table name that this classe handle (address)
     * and the connection with the database.
     * If the database connection is NULL, the parent class will creates one that is not.
     * */
    public function __construct($connection = NULL) {
        parent::__construct("address", $connection);
    }

    /* Returns the sql string for insert and update operations on address table. */
    public function getSQLString($operation){
        switch ($operation) {
            case GenericDAOOperations::INSERT:
                return "INSERT INTO address (street, number, complement, neighborhood, postal_code, city, state, country) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            case GenericDAOOperations::UPDATE:
                return "UPDATE address SET street=?, number=?, complement=?, neighborhood=?, postal_code=?, city=?, state=?, country=? WHERE id=?";
        }
    }

    /* Binds the parameters for execute the statement. */
    public function bindParams($address, $stmt, $operation) {

        $id = $address->getId();
        $street = $address->getStreet();
        $number = $address->getNumber();
        $complement = $address->getComplement();
        $neighborhood = $address->getNeighborhood();
        $postalCode = $address->getPostalCode();
        $city = $address->getCity();
        $state = $address->getState();
        $country = $address->getCountry();

        switch ($operation) {
            case GenericDAOOperations::INSERT:
                $stmt->bind_param("sissssss", $street, $number, $complement, $neighborhood, $postalCode, $city, $state, $country);
                break;
            case GenericDAOOperations::UPDATE:
                $stmt->bind_param("sissssssi", $street, $number, $complement, $neighborhood, $postalCode, $city, $state, $country, $id);
                break;
        }
    }

    /* Creates an address object based on the result of a query from a statement and returns it. */
    public function fillElementFromStatment($stmt) {

        $address = NULL;

        $id = NULL;
        $street = NULL;
        $number = NULL;
        $complement = NULL;
        $neighborhood = NULL;
        $postalCode = NULL;
        $city = NULL;
        $state = NULL;
        $country = NULL;

        $stmt->bind_result($id, $street, $number, $complement, $neighborhood, $postalCode, $city, $state, $country);

        if ($stmt->fetch()) {
            $address = new Address($id, $street, $number, $complement, $neighborhood, $postalCode, $city, $state, $country);
        }

        return $address;
    }

}

?>
