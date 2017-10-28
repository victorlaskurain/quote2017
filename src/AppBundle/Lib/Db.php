<?php

namespace AppBundle\Lib;

use Doctrine\ORM\EntityManager;

class Db
{
    private $em;
    private $conn;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
        $this->conn = $entityManager->getConnection();
    }

    private function addRecord($table, $record)
    {
        $conn = $this->conn;
        $columns      = array();
        $placeholders = array();
        foreach ($record as $column => $value) {
            $columns[]      = $conn->quoteIdentifier($column);
            $placeholders[] = '?';
        }
        $columns      = implode(', ', $columns);
        $placeholders = implode(', ', $placeholders);
        $sql = "
INSERT INTO $table ($columns)
            VALUES ($placeholders)
";
        $this->conn->executeUpdate($sql, array_values($record));
        return $this->conn->lastInsertId();
    }

    public function addQuoteGenericConcept($concept)
    {
        return $this->addRecord('quote_generic_concept', $concept);
    }

    public function addCustomer($customer)
    {
        return $this->addRecord('customer', $customer);
    }

    public function addQuote($quote)
    {
        return $this->addRecord('quote', $quote);
    }

    public function deleteGenericConceptsByQuoteId($id)
    {
        $sql = 'DELETE FROM quote_generic_concept WHERE quote_id = ?';
        $this->conn->executeUpdate($sql, array($id));
    }

    private static function filterToMySqlQuery($conn, $filter)
    {
        $orderBy = '';
        if (isset($filter['sortField'])) {
            $orderBy = $conn->quoteIdentifier($filter['sortField']);
            unset($filter['sortField']);
        }
        if (isset($filter['sortOrder'])) {
            if ('desc' == $filter['sortOrder']) {
                $orderBy .= ' DESC';
            }
            unset($filter['sortOrder']);
        }
        $limit = '';
        $pageSize = 0;
        if (isset($filter['pageSize'])) {
            $pageSize = (int)$filter['pageSize'];
            unset($filter['pageSize']);
        }
        $pageIndex = 0;
        if (isset($filter['pageIndex'])) {
            $pageIndex = (int)$filter['pageIndex'];
            unset($filter['pageIndex']);
        }
        if ($pageSize && $pageIndex) {
            $limit = 'LIMIT ' . $pageSize * ($pageIndex - 1) . ', ' . $pageSize;
        }
        $whereArray = array('TRUE');
        $vars  = array();
        foreach ($filter as $col => $val) {
            $col = $conn->quoteIdentifier($col);
            if (is_array($val)) {
                for ($i = 0; $i < count($val); $i += 2) {
                    $op    = $val[$i];
                    $value = $val[$i + 1];
                    $var   = 'filterVar_' . count($vars) . "_$i";
                    assert(in_array(
                        $op,
                        array('<', '<=', '=', '>=', '>', '<>', 'LIKE')));
                    $whereArray[] = "$col $op :$var";
                    $vars[$var]   = $value;
                }
            } else {
                $var           = 'filterVar_' . count($vars);
                $whereArray[] = "$col LIKE :$var";
                $vars[$var]   = $val;
            }
        }
        $where = 'WHERE ' . implode(' AND ', $whereArray);
        if (!empty($orderBy)) {
            $orderBy = "ORDER BY $orderBy";
        }
        return array(
            $where,
            $orderBy,
            $limit,
            $vars);
    }

    public function getCustomerById($id)
    {
        $conn = $this->conn;
        $sql = 'SELECT * FROM customer WHERE id = :id';
        $params = array(
            'id' => $id
        );
        return $conn->fetchAssoc($sql, $params);
    }

    public function getCustomersFilter($filter)
    {
        $conn = $this->conn;
        foreach ($filter as $key => $val) {
            if ($val === '') {
                unset($filter[$key]);
            }
        }
        list($where, $order, $limit, $vars) =
            self::filterToMySqlQuery($conn, $filter);
        $sql      = "SELECT *        FROM customer $where $order $limit";
        $sqlCount = "SELECT COUNT(*) FROM customer $where $order";
        return array(
            'data'       => $conn->fetchAll   ($sql     , $vars),
            'itemsCount' => $conn->fetchColumn($sqlCount, $vars)
        );
    }

    public function getQuoteById($id)
    {
        $conn = $this->conn;
        $sql = 'SELECT * FROM quote WHERE id = :id';
        $params = array(
            'id' => $id
        );
        return $conn->fetchAssoc($sql, $params);
    }

    public function getQuoteGenericConcepts($quoteId)
    {
        $sql = '
SELECT *
FROM quote_generic_concept
WHERE quote_id = ?
ORDER BY `order`';
        return $this->conn->fetchAll($sql, array($quoteId));
    }

    public function getQuotesFilter($filter)
    {
        $conn = $this->conn;
        foreach ($filter as $key => $val) {
            if ('' === $val) {
                unset($filter[$key]);
            }
            if ('id' === $key) {
                $filter['q.id'] = $val;
                unset($filter[$key]);
            }
        }
        list($where, $order, $limit, $vars) =
            self::filterToMySqlQuery($conn, $filter);
        $sql      = "
SELECT q.*,
       COALESCE(SUM(g.amount), 0) +
       shipping + drill + lathe + forge + saw +
       annealing + cementation + weight * price +
       milling + threading + commissions +
       grinding + hardening + zinc_plating AS total
FROM      quote                 q
LEFT JOIN quote_generic_concept g
       ON q.id = g.quote_id
$where
GROUP BY q.id
$order
$limit
";
        $sqlCount = "
SELECT COUNT(*),
       0 AS total
FROM quote q
$where $order
";
        $data = $conn->fetchAll($sql, $vars);
        foreach ($data as &$d) {
            $d['accepted'] = (bool)$d['accepted'];
        }
        return array(
            'data'       => $data,
            'itemsCount' => $conn->fetchColumn($sqlCount, $vars)
        );
    }

    public function transactional($f)
    {
        return $this->em->transactional($f);
    }

    public function updateCustomer($customer)
    {
        return $this->updateRecord('customer', $customer);
    }

    public function updateQuote($quote)
    {
        return $this->updateRecord('quote', $quote);
    }

    private function updateRecord($table, $record, $keyCols = array('id'))
    {
        $conn = $this->conn;
        $setExp = array();
        foreach ($record as $column => $value) {
            $setExp[] = $conn->quoteIdentifier($column) . ' = ?';
        }
        $vars = array_values($record);
        $whereExp = array();
        foreach ($keyCols as $column) {
            $whereExp[] = $conn->quoteIdentifier($column) . ' = ?';
            $vars[]     = $record[$column];
        }
        $table    = $conn->quoteIdentifier($table);
        $setExp   = implode(', '   , $setExp);
        $whereExp = implode(' AND ', $whereExp);
        $sql = "
UPDATE $table
SET    $setExp
WHERE  $whereExp
";
        return $this->conn->executeUpdate($sql, $vars);
    }
}