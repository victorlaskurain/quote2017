<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;


/**
* @Entity
* @Table(uniqueConstraints={@UniqueConstraint(columns={"quote_id", "order"})})
*/
class QuoteGenericConcept
{
  /**
   * @Column(type="integer")
   * @Id
   * @GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @Column(type="integer")
   */
  protected $order;

  /**
   * @Column()
   */
  protected $description;

  /**
   * @Column(type="decimal",
   *         precision=10,
   *         scale=3,
   *         nullable=false,
   *         options={"default" = 0})
   */
  protected $amount;

  /**
   * @ManyToOne(targetEntity="Quote")
   * @JoinColumn(nullable=false)
   */
  private $quote;
}
