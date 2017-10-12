<?php

namespace BetterknowBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Quizz
 *
 * @ORM\Table(name="quizz")
 * @ORM\Entity(repositoryClass="BetterknowBundle\Repository\QuizzRepository")
 */
class Quizz
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
     * @ORM\Column(name="question", type="string", length=255)
     */
    private $question;
    
    /**
     * A quizz belong to a pack
     * @ORM\ManyToOne(targetEntity="BetterknowBundle\Entity\Pack")
     * @ORM\JoinColumn(nullable=true)
     */
    private $pack;
    
    /**
     * A quizz have an answer
     * @ORM\ManyToOne(targetEntity="BetterknowBundle\Entity\Answer")
     * @ORM\JoinColumn(nullable=true)
     */
    private $answer;
    
    /**
     * @ORM\ManyToMany(targetEntity="BetterknowBundle\Entity\Category", cascade={"persist"})
     */
    private $categories;
    
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
     * Set question
     *
     * @param string $question
     *
     * @return Quizz
     */
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set pack
     *
     * @param \BetterknowBundle\Entity\Pack $pack
     *
     * @return Quizz
     */
    public function setPack(\BetterknowBundle\Entity\Pack $pack)
    {
        $this->pack = $pack;

        return $this;
    }

    /**
     * Get pack
     *
     * @return \BetterknowBundle\Entity\Pack
     */
    public function getPack()
    {
        return $this->pack;
    }

    /**
     * Set answer
     *
     * @param \BetterknowBundle\Entity\Answer $answer
     *
     * @return Quizz
     */
    public function setAnswer(\BetterknowBundle\Entity\Answer $answer)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get answer
     *
     * @return \BetterknowBundle\Entity\Answer
     */
    public function getAnswer()
    {
        return $this->answer;
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add category
     *
     * @param \BetterknowBundle\Entity\Category $category
     *
     * @return Quizz
     */
    public function addCategory(\BetterknowBundle\Entity\Category $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \BetterknowBundle\Entity\Category $category
     */
    public function removeCategory(\BetterknowBundle\Entity\Category $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }
}
