<?php

namespace BetterknowBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Gem
 *
 * @ORM\Table(name="gem")
 * @ORM\Entity(repositoryClass="BetterknowBundle\Repository\GemRepository")
 */
class Gem
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var bool
     *
     * @ORM\Column(name="gender", type="boolean", unique=false, nullable=true)
     */
    private $gender;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="timeReceive", type="time", unique=false)
     */
    private $timeReceive;
    
    /**
     * A gem belong to a user
     * @ORM\ManyToOne(targetEntity="BetterknowBundle\Entity\User", inversedBy="gems")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $user;
   
    public function __construct()
    {
        $this->timeReceive = new \DateTime();
    }
    
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set gender
     *
     * @param integer $gender
     *
     * @return Gem
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return int
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set timeReceive
     *
     * @param \DateTime $timeReceive
     *
     * @return Gem
     */
    public function setTimeReceive($timeReceive)
    {
        $this->timeReceive = $timeReceive;

        return $this;
    }

    /**
     * Get timeReceive
     *
     * @return \DateTime
     */
    public function getTimeReceive()
    {
        return $this->timeReceive;
    }

    /**
     * Set user
     *
     * @param \BetterknowBundle\Entity\User $user
     *
     * @return Gem
     */
    public function setUser(\BetterknowBundle\Entity\User $user)
    {
        $this->user = $user;
        
        return $this;
    }

    /**
     * Get user
     *
     * @return \BetterknowBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
