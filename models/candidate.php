<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="candidates")
 */
class ElectionCandidate
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;
    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="ElectionPost", inversedBy="candidates")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $post;

    /**
     * @ORM\Column(type="string")
     */
    protected $photo;

    /**
     * @ORM\Column(type="string")
     */
    protected $manifesto;

    /**
     * @ORM\Column(type="integer")
     */
    protected $mOrder = 0;

    /**
     * Number of yes votes
     */
    public $resultYes = 0;
    /**
     * Number of no votes
     */
    public $resultNo = 0;
    /**
     * Number of neutral votes
     */
    public $resultNeutral = 0;

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get first name
     */
    public function getFirstName()
    {
        return explode(' ', trim($this->name))[0];
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Set the value of post
     *
     * @return  self
     */
    public function setPost($post)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get the value of photo
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set the value of photo
     *
     * @return  self
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get the value of manifesto
     */
    public function getManifesto()
    {
        return $this->manifesto;
    }

    /**
     * Set the value of manifesto
     *
     * @return  self
     */
    public function setManifesto($manifesto)
    {
        $this->manifesto = $manifesto;

        return $this;
    }

    /**
     * Get the value of mOrder
     */
    public function getMOrder()
    {
        return $this->mOrder;
    }

    /**
     * Set the value of mOrder
     *
     * @return  self
     */
    public function setMOrder($mOrder)
    {
        $this->mOrder = $mOrder;

        return $this;
    }
}
