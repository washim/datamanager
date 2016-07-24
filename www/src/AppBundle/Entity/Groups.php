<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="`groups`")
 */
class Groups
{
	/**
     * @ORM\Column(type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
     private $id;
     
    /**
     *  @ORM\OneToMany(targetEntity="Customers", mappedBy="group")
     */
     private $customers;

	/**
	 * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
	 */
	 private $name;

	/**
	 * @ORM\Column(type="text")
     * @Assert\NotBlank()
	 */
	 private $address;

	/**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
     private $LeaderName;

    /**
     * @ORM\Column(type="string", length=12, nullable=true)
     */
	 private $mobile;
	 
	/**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
	 private $day;
     
    /**
     * Varibales for custom logi and this will not store in database.
     */
     private $purchase;
     private $advance;
	 private $completed;
	 private $pending;
	 
    public function __construct()
    {
        $this->customers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setAddress($address)
    {
        $this->address = $address;
    
        return $this;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setLeaderName($leaderName)
    {
        $this->LeaderName = $leaderName;
    
        return $this;
    }

    public function getLeaderName()
    {
        return $this->LeaderName;
    }

    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    
        return $this;
    }

    public function getMobile()
    {
        return $this->mobile;
    }

    public function addCustomer(\AppBundle\Entity\Customers $customer)
    {
        $this->customers[] = $customer;
    
        return $this;
    }

    public function removeCustomer(\AppBundle\Entity\Customers $customer)
    {
        $this->customers->removeElement($customer);
    }

    public function getCustomers()
    {
        return $this->customers;
    }
    
    public function getPurchase()
	{
	    $balance = 0;
	    foreach ($this->customers as $customer) {
	       $balance += $customer->getProductPrice();
        }
        return $balance;
	}
	
	public function getAdvance()
	{
	    $balance = 0;
	    foreach ($this->customers as $customer) {
	       $balance += $customer->getAdvancePaid();
        }
        return $balance;
	}
	
    public function getCompleted()
	{
	    $balance = 0;
	    foreach ($this->customers as $customer) {
	       foreach ($customer->getEmis() as $emi) {
	           $balance += $emi->getAmount();
	       }
        }
        return $balance;
	}
	
	public function getPending()
	{
	    $balance = 0;
	    foreach ($this->customers as $customer) {
	       $balance += $customer->getProductPrice();
        }
        $balance = $balance - $this->getCompleted() - $this->getAdvance();
        return $balance;
	}
	
	public function setDay($day)
	{
	    $this->day = $day;
	    
	    return $this;
	}
	
	public function getDay()
	{
	    return $this->day;
	}
}
