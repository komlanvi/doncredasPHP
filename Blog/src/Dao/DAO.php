<?php
/**
 * Created by PhpStorm.
 * User: doncredas
 * Date: 29/03/15
 * Time: 23.48
 */

namespace Blog\Dao;

use \Doctrine\DBAL\Connection;


abstract class DAO {

    /**
     * @var Connection
     */
    protected $db;

    /**
     * @param Connection $db
     */
    public function __construct(Connection $db) {
        $this->db = $db;
    }

    /**
     * @return Connection
     */
    protected function getDB() {
        return $this->db;
    }

    /**
     * @param array $row
     */
    protected abstract function buildDomainObject(array $row);

}