<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping           as ORM;
use Doctrine\ORM\Mapping\ManyToOne as ManyToOne;
use FOS\UserBundle\Entity\User     as BaseUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
    }
}