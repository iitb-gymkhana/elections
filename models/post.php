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
     * @ORM\Column(type="integer")
     */
    protected $mOrder = 0;

    /**
     * Number of NOTA votes
     */
    public $resultNOTA = 0;

    /**
     * Number of neutral votes
     */
    public $resultNeutral = 0;

    /**
     * Detailed result
     */
    public $resultDetail = null;

    /**
     * @ORM\OneToMany(targetEntity="ElectionCandidate", mappedBy="post")
     * @ORM\OrderBy({"mOrder" = "ASC"})
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
        $type = strtoupper($this->type);
        $claim = strtoupper($_SERVER['REDIRECT_OIDC_CLAIM_employeeType']);

        if ($type === 'ALL') return true;
        if ($type === 'UG') return $claim === 'UG' || $claim === 'DD';
        if ($type === 'PG') return $claim !== 'UG' && $claim !== 'DD';

        return false;
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
