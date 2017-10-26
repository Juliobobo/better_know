<?php

namespace BetterknowBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="BetterknowBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\OneToMany(targetEntity="BetterknowBundle\Entity\Gem", mappedBy="user")
     */
    protected $gems;

    /**
     * @ORM\ManyToMany(targetEntity="BetterknowBundle\Entity\Pack", cascade={"persist"})
     */
    private $packs;
    
    /**
     * @ORM\ManyToMany(targetEntity="BetterknowBundle\Entity\Unit", cascade={"persist"})
     */
    private $units;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->packs = new ArrayCollection();
        $this->units = new ArrayCollection();
        $this->gems = new ArrayCollection();
    }

    /**
     * Add pack
     *
     * @param \BetterknowBundle\Entity\Pack $pack
     *
     * @return User
     */
    public function addPack(\BetterknowBundle\Entity\Pack $pack)
    {
        $this->packs[] = $pack;

        return $this;
    }

    /**
     * Remove pack
     *
     * @param \BetterknowBundle\Entity\Pack $pack
     */
    public function removePack(\BetterknowBundle\Entity\Pack $pack)
    {
        $this->packs->removeElement($pack);
    }

    /**
     * Get packs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPacks()
    {
        return $this->packs;
    }

    /**
     * Add unit
     *
     * @param \BetterknowBundle\Entity\Unit $unit
     *
     * @return User
     */
    public function addUnit(\BetterknowBundle\Entity\Unit $unit)
    {
        $this->units[] = $unit;

        return $this;
    }

    /**
     * Remove unit
     *
     * @param \BetterknowBundle\Entity\Unit $unit
     */
    public function removeUnit(\BetterknowBundle\Entity\Unit $unit)
    {
        $this->units->removeElement($unit);
    }

    /**
     * Get units
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUnits()
    {
        return $this->units;
    }

    /**
     * Add gem
     *
     * @param \BetterknowBundle\Entity\Gem $gem
     *
     * @return User
     */
    public function addGem(\BetterknowBundle\Entity\Gem $gem)
    {
        $this->gems[] = $gem;

        return $this;
    }

    /**
     * Remove gem
     *
     * @param \BetterknowBundle\Entity\Gem $gem
     */
    public function removeGem(\BetterknowBundle\Entity\Gem $gem)
    {
        $this->gems->removeElement($gem);
    }

    /**
     * Get gems
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGems()
    {
        return $this->gems;
    }
}
