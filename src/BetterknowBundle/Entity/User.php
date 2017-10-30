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
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255, unique=false, nullable=false)
     */
    private $firstName;
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255, unique=false, nullable=false)
     */
    private $lastName;
    
    /**
     * @var int
     *
     * @ORM\Column(name="age", type="integer", unique=false, nullable=false)
     */
    private $age;
    
    /**
     * @var bool
     *
     * @ORM\Column(name="gender", type="boolean", unique=false, nullable=true)
     */
    private $gender;
    
    /**
     * @ORM\OneToMany(targetEntity="BetterknowBundle\Entity\Gem", mappedBy="user")
     */
    private $gems;
    
    /**
     * @ORM\OneToMany(targetEntity="BetterknowBundle\Entity\Friend", mappedBy="user")
     */
    private $friends;

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
        $this->friends = new ArrayCollection();
    }
    
      // ----------------- //
     // --- Functions --- //
    // ----------------- //
    
    /*
     * isFriend
     */
    public function isFriend(User $pFriend){
        foreach ($this->friends as $myFriend) {
            if($myFriend->getId() == $pFriend->getId()){
                return true;
            } else {
                return false;
            }
        }
    }
    //------------------------//
    
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

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set age
     *
     * @param integer $age
     *
     * @return User
     */
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * Get age
     *
     * @return integer
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set gender
     *
     * @param boolean $gender
     *
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return boolean
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Add friend
     *
     * @param \BetterknowBundle\Entity\Friend $friend
     *
     * @return User
     */
    public function addFriend(\BetterknowBundle\Entity\Friend $friend)
    {
        $this->friends[] = $friend;

        return $this;
    }

    /**
     * Remove friend
     *
     * @param \BetterknowBundle\Entity\Friend $friend
     */
    public function removeFriend(\BetterknowBundle\Entity\Friend $friend)
    {
        $this->friends->removeElement($friend);
    }

    /**
     * Get friends
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFriends()
    {
        return $this->friends;
    }
}
