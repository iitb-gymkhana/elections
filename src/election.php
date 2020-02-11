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
}
