<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueContraint;


/**
* @Entity
*/
class Quote
{
  /**
   * @Column(type="integer")
   * @Id
   * @GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @Column(type="date")
   */
  protected $date;

  /**
   * @Column()
   */
  protected $description;

  /**
   * @Column(type="decimal", precision=8, scale=2)
   */
  protected $shipping;

  /**
   * @ManyToOne(targetEntity="Customer")
   * @JoinColumn(nullable=false)
   */
  private $customer;
}
