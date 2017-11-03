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
     * @ORM\ManyToMany(targetEntity="BetterknowBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinTable(name="quizz_responses")
     */
    private $responses;
    
    /**
     * @ORM\ManyToMany(targetEntity="BetterknowBundle\Entity\Category", cascade={"persist"})
     */
    private $categories;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->responses = new \Doctrine\Common\Collections\ArrayCollection();
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
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
     * Add response
     *
     * @param \BetterknowBundle\Entity\User $response
     *
     * @return Quizz
     */
    public function addResponse(\BetterknowBundle\Entity\User $response)
    {
        $this->responses[] = $response;

        return $this;
    }

    /**
     * Remove response
     *
     * @param \BetterknowBundle\Entity\User $response
     */
    public function removeResponse(\BetterknowBundle\Entity\User $response)
    {
        $this->responses->removeElement($response);
    }

    /**
     * Get responses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getResponses()
    {
        return $this->responses;
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
