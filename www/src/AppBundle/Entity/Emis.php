<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Customers;
use AppBundle\Entity\Users;

/**
 * @ORM\Entity
 * @ORM\Table(name="`emis`")
 */
class Emis
{
	/**
     * @ORM\Column(type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
     private $id;
	 
	/**
     * @ORM\Column(type="bigint")
     */
	 private $cid;
	 
	/**
     * @ORM\ManyToOne(targetEntity="Customers", inversedBy="emis")
	 * @ORM\JoinColumn(name="cid", referencedColumnName="id")
     */
	 private $customer;
	 
	/**
     * @ORM\Column(type="decimal",scale=2)
     */
	 private $amount;
	
	/**
     * @ORM\Column(type="date")
     */
	 private $dueDt;
    
    public function getId()
    {
        return $this->id;
    }

    public function setCid($cid)
    {
        $this->cid = $cid;
    
        return $this;
    }

    public function getCid()
    {
        return $this->cid;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    
        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setDueDt($dueDt)
    {
        $this->dueDt = $dueDt;
    
        return $this;
    }

    public function getDueDt()
    {
        return $this->dueDt;
    }

    public function setCustomer(Customers $customer)
    {
        $this->customer = $customer;
    
        return $this;
    }

    public function getCustomer()
    {
        return $this->customer;
    }
}
