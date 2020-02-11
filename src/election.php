<?php

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="elections")
 */
class Election
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
     * @ORM\Column(type="boolean")
     */
    protected $active;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $ended;

    /**
     * @ORM\OneToMany(targetEntity="ElectionPost", mappedBy="election")
     */
    protected $posts;

    /**
     * @ORM\OneToMany(targetEntity="ElectionVoterList", mappedBy="election")
     */
    protected $voterLists;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->voterLists = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get the value of posts
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Get the value of voterLists
     */
    public function getVoterLists()
    {
        return $this->voterLists;
    }

    /**
     * Get the value of active
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set the value of active
     *
     * @return  self
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get the value of ended
     */
    public function getEnded()
    {
        return $this->ended;
    }

    /**
     * Set the value of ended
     *
     * @return  self
     */
    public function setEnded($ended)
    {
        $this->ended = $ended;

        return $this;
    }
}
