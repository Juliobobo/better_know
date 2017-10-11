<?php

namespace BetterknowBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Friend
 *
 * @ORM\Table(name="friend")
 * @ORM\Entity(repositoryClass="BetterknowBundle\Repository\FriendRepository")
 */
class Friend
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
     * @ORM\Column(name="state", type="boolean", nullable=true)
     */
    private $state;
    
    /**
     * @ORM\ManyToOne(targetEntity="BetterknowBundle\Entity\User")
     */
    private $user;
    
    /**
     * @ORM\ManyToOne(targetEntity="BetterknowBundle\Entity\User")
     */
    private $friend;
    
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
     * Set idFriend
     *
     * @param integer $idFriend
     *
     * @return Friend
     */
    public function setIdFriend($idFriend)
    {
        $this->idFriend = $idFriend;

        return $this;
    }

    /**
     * Get idFriend
     *
     * @return int
     */
    public function getIdFriend()
    {
        return $this->idFriend;
    }

    /**
     * Set state
     *
     * @param boolean $state
     *
     * @return Friend
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return boolean
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set idUser
     *
     * @param \BetterknowBundle\Entity\User $idUser
     *
     * @return Friend
     */
    public function setIdUser(\BetterknowBundle\Entity\User $idUser = null)
    {
        $this->id_user = $idUser;

        return $this;
    }

    /**
     * Get idUser
     *
     * @return \BetterknowBundle\Entity\User
     */
    public function getIdUser()
    {
        return $this->id_user;
    }

    /**
     * Set user
     *
     * @param \BetterknowBundle\Entity\User $user
     *
     * @return Friend
     */
    public function setUser(\BetterknowBundle\Entity\User $user = null)
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

    /**
     * Set friend
     *
     * @param \BetterknowBundle\Entity\User $friend
     *
     * @return Friend
     */
    public function setFriend(\BetterknowBundle\Entity\User $friend = null)
    {
        $this->friend = $friend;

        return $this;
    }

    /**
     * Get friend
     *
     * @return \BetterknowBundle\Entity\User
     */
    public function getFriend()
    {
        return $this->friend;
    }
}
