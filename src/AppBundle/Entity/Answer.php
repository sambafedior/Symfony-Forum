<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Post;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * Answer
 *
 * @ORM\Table(name="answers")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AnswerRepository")
 * @Gedmo\Loggable(logEntryClass="AppBundle\Entity\Log")
 */
class Answer
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
     * @var Author
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Author", inversedBy="answers" )
     * @Gedmo\Versioned()
     *
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="answer_text", type="text")
     * @Gedmo\Versioned()
     */
    private $text;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var Post
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="answers")
     * @Gedmo\Versioned()
     */
    private $post;


    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Vote", mappedBy="answer")
     */
    private $votes;

    /**
     * @return ArrayCollection
     */
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     * @param ArrayCollection $votes
     * @return Answer
     */
    public function setVotes($votes)
    {
        $this->votes = $votes;
        return $this;
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
     * Set author
     *
     * @param string $author
     *
     * @return Answer
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
     * Set text
     *
     * @param string $text
     *
     * @return Answer
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Answer
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set post
     *
     * @param \AppBundle\Entity\Post $post
     *
     * @return Answer
     */
    public function setPost(\AppBundle\Entity\Post $post = null)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return \AppBundle\Entity\Post
     */
    public function getPost()
    {
        return $this->post;
    }
}
