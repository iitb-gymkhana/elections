<?php

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="posts")
 */
class ElectionPost
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
     * @ORM\ManyToOne(targetEntity="Election", inversedBy="posts")
     * @ORM\JoinColumn(name="election_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $election;

    /**
     * @ORM\Column(type="integer", options={"default" : 1})
     */
    protected $number;

    /**
     * @ORM\Column(type="string", options={"default" : "ALL"})
     */
    protected $type;

    /**
     * Number of NOTA votes
     */
    public $resultNOTA = 0;

    /**
     * Number of neutral votes
     */
    public $resultNeutral = 0;

    /**
     * @ORM\OneToMany(targetEntity="ElectionCandidate", mappedBy="post")
     */
    protected $candidates;

    public function __construct()
    {
        $this->candidates = new ArrayCollection();
    }

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
     * Get the value of election
     */
    public function getElection()
    {
        return $this->election;
    }

    /**
     * Set the value of election
     *
     * @return  self
     */
    public function setElection($election)
    {
        $this->election = $election;

        return $this;
    }

    /**
     * Get the value of number
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set the value of number
     *
     * @return  self
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get the value of type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @return  self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of candidates
     */
    public function getCandidates()
    {
        return $this->candidates;
    }

    /**
     * Check if yes-no-neutral election
     */
    public function isYNN() {
        return ($this->number > 1) || ($this->candidates->count() == 1);
    }

    /**
     * Check if current user can vote
     */
    public function canVote() {
        if ($this->type === 'ALL') {
            return true;
        }

        return $this->type === strtoupper($_SERVER['OIDC_CLAIM_employeeType']);
    }
}