<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vote
 *
 * @ORM\Table(name="vote")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VoteRepository")
 */
class Vote
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
     * @var Answer
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Answer", inversedBy="votes")
     */
    private $answer;

    /**
     * @var Author
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Author", inversedBy="votes")
     */
    private $author;

    /**
     * @var integer
     * @ORM\Column(name="vote", type="integer", length=255)
     */
    private $vote;



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
     * Set Answer
     *
     * @param string $answer
     *
     * @return Vote
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get Answer
     *
     * @return string
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set author
     *
     * @param string $author
     *
     * @return Vote
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set vote
     *
     * @param string $vote
     *
     * @return Vote
     */
    public function setVote($vote)
    {
        $this->vote = $vote;

        return $this;
    }

    /**
     * Get vote
     *
     * @return string
     */
    public function getVote()
    {
        return $this->vote;
    }
}

