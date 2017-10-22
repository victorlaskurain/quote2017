<?php

namespace AppBundle\Lib;

use Doctrine\ORM\EntityManager;

class Logic
{
    private $db;

    public function __construct(Db $db)
    {
        $this->db = $db;
    }

    public function addQuoteGenericConcepts($id, $genericConcepts)
    {
        $this->db->transactional(function($em) use ($id, $genericConcepts) {
            $db = $this->db;
            foreach ($genericConcepts as $concept) {
                $concept['quote_id'] = $id;
                $db->addQuoteGenericConcept($concept);
            }
        });
    }

    public function addUpdateCustomer($customer)
    {
        $id = null;
        $this->db->transactional(function($em) use ($customer, &$id) {
            $db              = $this->db;
            if (isset($customer['id'])) {
                $id = $customer['id'];
                // ensure customer to update exists
                $dbCustomer = $db->getCustomerById($id);
                $db->updateCustomer($customer);
            } else {
                $id = $db->addCustomer($customer);
            }
        });
        return $id;
    }

    public function addUpdateQuote($quote)
    {
        $id = null;
        $this->db->transactional(function($em) use ($quote, &$id) {
            $db              = $this->db;
            $genericConcepts = array();
            if (isset($quote['generic_concepts'])) {
                $genericConcepts = $quote['generic_concepts'];
                unset($quote['generic_concepts']);
            }
            if (isset($quote['id'])) {
                $id = $quote['id'];
                // ensure quote to update exists
                $dbQuote = $db->getQuoteById($id);
                $db->updateQuote($quote);
                $db->deleteGenericConceptsByQuoteId($id);
            } else {
                $id = $db->addQuote($quote);
            }
            foreach ($genericConcepts as $i => &$gc) {
                $gc['order'] = 10 * ($i + 1);
            }
            $this->addQuoteGenericConcepts($id, $genericConcepts);
        });
        return $id;
    }

    public function getQuoteById($id)
    {
        $quote = null;
        $this->db->transactional(function($em) use (&$quote, $id) {
            $db = $this->db;
            $quote = $db->getQuoteById($id);
            $quote['generic_concepts'] = $db->getQuoteGenericConcepts($id);
        });
        return $quote;
    }
}