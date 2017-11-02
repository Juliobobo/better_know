<?php

namespace BetterknowBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pack
 *
 * @ORM\Table(name="pack")
 * @ORM\Entity(repositoryClass="BetterknowBundle\Repository\PackRepository")
 */
class Pack
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
     * @var string
     *
     * @ORM\Column(name="theme", type="string", length=255, nullable=true)
     */
    private $theme;
    
    /**
     * @ORM\ManyToMany(targetEntity="BetterknowBundle\Entity\Quizz", cascade={"persist"})
     */
    private $quizz;

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
     * Set theme
     *
     * @param string $theme
     *
     * @return Pack
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * Get theme
     *
     * @return string
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->quizz = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add quizz
     *
     * @param \BetterknowBundle\Entity\Quizz $quizz
     *
     * @return Pack
     */
    public function addQuizz(\BetterknowBundle\Entity\Quizz $quizz)
    {
        $this->quizz[] = $quizz;

        return $this;
    }

    /**
     * Remove quizz
     *
     * @param \BetterknowBundle\Entity\Quizz $quizz
     */
    public function removeQuizz(\BetterknowBundle\Entity\Quizz $quizz)
    {
        $this->quizz->removeElement($quizz);
    }

    /**
     * Get quizz
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuizz()
    {
        return $this->quizz;
    }
}
