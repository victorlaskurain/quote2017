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
   * @Column(type="integer")
   */
  protected $numberOfDay;

  /**
   * @Column(type="date")
   */
  protected $date;

  /**
   * @Column()
   */
  protected $description;

  /**
   * @Column(type="decimal", precision=10, scale=3,
   *         nullable=false, options={"default"=0})
   */
  protected $shipping;

  /**
   * @ManyToOne(targetEntity="Customer")
   * @JoinColumn(nullable=false)
   */
  private $customer;

  /**
   * @Column(type="boolean", nullable=false, options={"default"=false})
   */
  private $accepted;

  /**
   * @Column(type="decimal", precision=10, scale=3,
   *         nullable=false, options={"default"=0})
   */
  protected $drill;

  /**
   * @Column(type="decimal", precision=10, scale=3,
   *         nullable=false, options={"default"=0})
   */
  protected $lathe;

  /**
   * @Column(type="decimal", precision=10, scale=3,
   *         nullable=false, options={"default"=0})
   */
  protected $forge;

  /**
   * @Column(type="decimal", precision=10, scale=3,
   *         nullable=false, options={"default"=0})
   */
  protected $saw;

  /**
   * @Column(type="decimal", precision=10, scale=3,
   *         nullable=false, options={"default"=0})
   */
  protected $annealing;

  /**
   * @Column(type="decimal", precision=10, scale=3,
   *         nullable=false, options={"default"=0})
   */
  protected $cementation;

  /**
   * @Column(type="decimal", precision=10, scale=3,
   *         nullable=false, options={"default"=0})
   */
  protected $weight;

  /**
   * @Column(type="decimal", precision=10, scale=3,
   *         nullable=false, options={"default"=0})
   */
  protected $price;

  /**
   * @Column(type="decimal", precision=10, scale=3,
   *         nullable=false, options={"default"=0})
   */
  protected $milling;

  /**
   * @Column(type="decimal", precision=10, scale=3,
   *         nullable=false, options={"default"=0})
   */
  protected $threading;

  /**
   * @Column(type="decimal", precision=10, scale=3,
   *         nullable=false, options={"default"=0})
   */
  protected $commissions;

  /**
   * @Column(type="decimal", precision=10, scale=3,
   *         nullable=false, options={"default"=0})
   */
  protected $grinding;

  /**
   * @Column(type="decimal", precision=10, scale=3,
   *         nullable=false, options={"default"=0})
   */
  protected $hardening;

}
