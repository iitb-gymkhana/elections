<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="votes")
 */
class ElectionVote
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
    protected $vote;

    /**
     * @ORM\ManyToOne(targetEntity="ElectionCandidate")
     * @ORM\JoinColumn(name="candidate_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $candidate;

    /**
     * @ORM\Column(type="string")
     */
    private $voterListName;

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of vote
     */
    public function getVote()
    {
        return $this->vote;
    }

    /**
     * Set the value of vote
     *
     * @return  self
     */
    public function setVote($vote)
    {
        $this->vote = $vote;

        return $this;
    }

    /**
     * Get the value of candidate
     */
    public function getCandidate()
    {
        return $this->candidate;
    }

    /**
     * Set the value of candidate
     *
     * @return  self
     */
    public function setCandidate($candidate)
    {
        $this->candidate = $candidate;

        return $this;
    }

    /**
     * Get the value of voterListName
     */
    public function getVoterListName()
    {
        return $this->voterListName;
    }

    /**
     * Set the value of voterListName
     *
     * @return  self
     */
    public function setVoterListName($voterListName)
    {
        $this->voterListName = $voterListName;

        return $this;
    }
}
