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

    public function addUpdateQuote($quote)
    {
        $id = null;
        $this->db->transactional(function($em) use ($quote, &$id) {
            $db = $this->db;
            if (isset($quote['id'])) {
                $id = $quote['id'];
                // ensure quote to update exists
                $dbQuote = $db->getQuoteById($id);
                $db->updateQuote($quote);
            } else {
                $id = $db->addQuote($quote);
            }
        });
        return $id;
    }
}