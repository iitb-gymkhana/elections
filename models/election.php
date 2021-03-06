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
    protected $suspended;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $ended;

    /**
     * @ORM\OneToMany(targetEntity="ElectionPost", mappedBy="election")
     * @ORM\OrderBy({"mOrder" = "ASC"})
     */
    protected $posts;

    /**
     * @ORM\OneToMany(targetEntity="ElectionVoterList", mappedBy="election")
     */
    protected $voterLists;

    /**
     * @ORM\Column(type="string")
     */
    protected $creator;

    /**
     * @ORM\Column(type="integer")
     */
    protected $time;

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

    /**
     * Get the value of creator
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * Set the value of creator
     *
     * @return  self
     */
    public function setCreator($creator)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get the value of suspended
     */
    public function getSuspended()
    {
        return $this->suspended;
    }

    /**
     * Set the value of suspended
     *
     * @return  self
     */
    public function setSuspended($suspended)
    {
        $this->suspended = $suspended;

        return $this;
    }

    /**
     * Get the value of time
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set the value of time
     *
     * @return  self
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }
}
