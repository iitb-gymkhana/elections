<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="voterlists")
 */
class ElectionVoterList
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
     * @ORM\ManyToOne(targetEntity="Election", inversedBy="voterLists")
     * @ORM\JoinColumn(name="election_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $election;
    /**
     * @ORM\Column(type="string")
     */
    protected $boothIPs;
    /**
     * @ORM\Column(type="boolean")
     */
    protected $requireCode;

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
     * Get the value of boothIPs
     */
    public function getBoothIPs()
    {
        return $this->boothIPs;
    }

    /**
     * Set the value of boothIPs
     *
     * @return  self
     */
    public function setBoothIPs($boothIPs)
    {
        $this->boothIPs = $boothIPs;

        return $this;
    }

    /**
     * Get the value of requireCode
     */
    public function getRequireCode()
    {
        return $this->requireCode;
    }

    /**
     * Set the value of requireCode
     *
     * @return  self
     */
    public function setRequireCode($requireCode)
    {
        $this->requireCode = $requireCode;

        return $this;
    }
}

/**
 * @ORM\Entity
 * @ORM\Table(name="voters", indexes={@ORM\Index(name="rn", columns={"rollNo"}),
 *                                    @ORM\Index(name="rnxe", columns={"rollNo", "election_id"})})
 */
class ElectionVoter
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
    protected $rollNo;
    /**
     * @ORM\Column(type="boolean")
     */
    protected $voted;

    /**
     * @ORM\ManyToOne(targetEntity="Election")
     * @ORM\JoinColumn(name="election_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $election;

    /**
     * @ORM\ManyToOne(targetEntity="ElectionVoterList")
     * @ORM\JoinColumn(name="voterlist_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $voterList;

    /**
     * @ORM\Column(type="string")
     */
    protected $code;

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of rollNo
     */
    public function getRollNo()
    {
        return $this->rollNo;
    }

    /**
     * Set the value of rollNo
     *
     * @return  self
     */
    public function setRollNo($rollNo)
    {
        $this->rollNo = $rollNo;

        return $this;
    }

    /**
     * Get the value of voted
     */
    public function getVoted()
    {
        return $this->voted;
    }

    /**
     * Set the value of voted
     *
     * @return  self
     */
    public function setVoted($voted)
    {
        $this->voted = $voted;

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
     * Get the value of voterList
     */
    public function getVoterList()
    {
        return $this->voterList;
    }

    /**
     * Set the value of voterList
     *
     * @return  self
     */
    public function setVoterList($voterList)
    {
        $this->voterList = $voterList;

        return $this;
    }

    /**
     * Get the value of code
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set the value of code
     *
     * @return  self
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }
}
