<?php

namespace E03Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Post
 *
 * @ORM\Table(name="post")
 * @ORM\Entity(repositoryClass="E03Bundle\Repository\PostRepository")
 */
class Post
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;


      /**
     * @var int
     *
     * @ORM\Column(name="lik", type="integer", nullable=true)
     */
    private $lik = 0;

     /**
     * @var int
     *
     * @ORM\Column(name="unlik", type="integer", nullable=true)
     */
    private $unlik = 0;

     /**
     * @var int
     * 
     * @ORM\ManyToOne(targetEntity="E01Bundle\Entity\User", inversedBy="post")
     * @ORM\JoinColumn(name="user", referencedColumnName="id", onDelete="CASCADE")
    */
    private $user;

    /**
     * @var int
     * 
     * @ORM\ManyToOne(targetEntity="E01Bundle\Entity\User", inversedBy="lastpost")
     * @ORM\JoinColumn(name="lastuser", referencedColumnName="id", onDelete="CASCADE", nullable=true)
    */
    private $lastuser;

     /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastcreated", type="datetime", nullable=true)
     */
    private $lastcreated;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get lik.
     *
     * @return int
     */
    public function getLik()
    {
        return $this->lik;
    }

    /**
     * Get unlik.
     *
     * @return int
     */
    public function getUnlik()
    {
        return $this->unlik;
    }

       /**
     * Set post.
     *
     * @param user 
     *
     * @return user
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

       /**
     * Get lastuser.
     *
     */
    public function getLastuser()
    {
        return $this->lastuser;
    }

         /**
     * Set lastuser.
     *
     * @param lastuser 
     *
     * @return user
     */
    public function setLastuser($lastuser)
    {
        $this->lastuser = $lastuser;

        return $this;
    }

       /**
     * Get user.
     *
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * Set title.
     *
     * @param string $title
     *
     * @return Post
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

     /**
     * Set like.
     *
     * @param integer $lik
     *
     * @return Post
     */
    public function setLik($lik)
    {
        $this->lik = $lik;

        return $this;
    }

     /**
     * Set unlik.
     *
     * @param integer $unlik
     *
     * @return Post
     */
    public function setUnlik($unlik)
    {
        $this->unlik = $unlik;

        return $this;
    }
    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content.
     *
     * @param string $content
     *
     * @return Post
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set created.
     *
     * @param \DateTime $created
     *
     * @return Post
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created.
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set lastcreated.
     *
     * @param \DateTime $lastcreated
     *
     * @return Post
     */
    public function setLastcreated($lastcreated)
    {
        $this->lastcreated = $lastcreated;

        return $this;
    }

    /**
     * Get lastcreated.
     *
     * @return \DateTime
     */
    public function getLastcreated()
    {
        return $this->lastcreated;
    }
}
