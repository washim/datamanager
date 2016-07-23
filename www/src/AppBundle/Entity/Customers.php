<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="customers")
 * @UniqueEntity("bookno")
 */
class Customers
{
	/**
     * @ORM\Column(type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
     private $id;
	 
	/**
     * @ORM\Column(type="bigint", unique=true)
     * @Assert\NotBlank()
     */
     private $bookno;
     
	/**
     * @ORM\Column(type="bigint")
     */
     private $GroupId;
     
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
	 private $gurdian;

	/**
	 * @ORM\Column(type="string", length=12, nullable=true)
	 */
	 private $mobile;
	 
	/**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
	 private $path;
	 
	/**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
     private $totalEmiNo;
     
    /**
     * @ORM\Column(type="decimal", scale=2)
     * @Assert\NotBlank()
     */
     private $emiAmount;
	 
	/**
	 * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
	 */
	 private $ProductNo;
	 
	/**
	 * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
	 */
	 private $ProductName;
	 
	/**
     * @ORM\Column(type="decimal",scale=2)
     * @Assert\NotBlank()
     */
	 private $ProductPrice;
	 
	/**
     * @ORM\Column(type="decimal",scale=2)
     * @Assert\NotBlank()
     */
	 private $AdvancePaid;
	 
	/**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     */
	 private $AdvancePaidDate;
	 
	/**
     * @ORM\ManyToOne(targetEntity="Groups", inversedBy="customers")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     */
     private $group;
     
    /**
     * @ORM\OneToMany(targetEntity="Emis", mappedBy="customer")
     */
     protected $emis;
	 
	/**
	 * Varibales for custom logi and this will not store in database.
	 */
	 private $completed;
	 private $pending;
	 
	public function __construct()
    {
        $this->emis = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setGroupId($groupId)
    {
        $this->GroupId = $groupId;
    
        return $this;
    }

    public function getGroupId()
    {
        return $this->GroupId;
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

    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    
        return $this;
    }

    public function getMobile()
    {
        return $this->mobile;
    }

    public function setGurdian($gurdian)
    {
        $this->gurdian = $gurdian;
    
        return $this;
    }

    public function getGurdian()
    {
        return $this->gurdian;
    }

    public function setGroup(Groups $group)
    {
        $this->group = $group;
    
        return $this;
    }

    public function getGroup()
    {
        return $this->group;
    }

    public function getEmis()
    {
        return $this->emis;
    }

    public function setPath($path)
    {
        $this->path = $path;
    
        return $this;
    }

    public function getPath()
    {
        return $this->path;
    }
	
	public function getProducts()
	{
	    return $this->products;
	}

    public function setBookno($bookno)
    {
        $this->bookno = $bookno;

        return $this;
    }

    public function getBookno()
    {
        return $this->bookno;
    }
	
	public function getCompleted()
	{
	    $this->completed = 0;
	    foreach ($this->emis as $emi) {
	        $this->completed += $emi->getAmount();
        }
        return $this->completed;
	}
	
	public function getPending()
	{
	    $this->pending = $this->getProductPrice() - $this->getCompleted() - $this->getAdvancePaid();
        return $this->pending;
	}

    /**
     * Set productNo
     *
     * @param string $productNo
     *
     * @return Customers
     */
    public function setProductNo($productNo)
    {
        $this->ProductNo = $productNo;

        return $this;
    }

    /**
     * Get productNo
     *
     * @return string
     */
    public function getProductNo()
    {
        return $this->ProductNo;
    }

    /**
     * Set productName
     *
     * @param string $productName
     *
     * @return Customers
     */
    public function setProductName($productName)
    {
        $this->ProductName = $productName;

        return $this;
    }

    /**
     * Get productName
     *
     * @return string
     */
    public function getProductName()
    {
        return $this->ProductName;
    }

    /**
     * Set productPrice
     *
     * @param string $productPrice
     *
     * @return Customers
     */
    public function setProductPrice($productPrice)
    {
        $this->ProductPrice = $productPrice;

        return $this;
    }

    /**
     * Get productPrice
     *
     * @return string
     */
    public function getProductPrice()
    {
        return $this->ProductPrice;
    }

    /**
     * Set advancePaid
     *
     * @param string $advancePaid
     *
     * @return Customers
     */
    public function setAdvancePaid($advancePaid)
    {
        $this->AdvancePaid = $advancePaid;

        return $this;
    }

    /**
     * Get advancePaid
     *
     * @return string
     */
    public function getAdvancePaid()
    {
        return $this->AdvancePaid;
    }

    /**
     * Set advancePaidDate
     *
     * @param \DateTime $advancePaidDate
     *
     * @return Customers
     */
    public function setAdvancePaidDate($advancePaidDate)
    {
        $this->AdvancePaidDate = $advancePaidDate;

        return $this;
    }

    /**
     * Get advancePaidDate
     *
     * @return \DateTime
     */
    public function getAdvancePaidDate()
    {
        return $this->AdvancePaidDate;
    }

    /**
     * Set totalEmiNo
     *
     * @param integer $totalEmiNo
     *
     * @return Customers
     */
    public function setTotalEmiNo($totalEmiNo)
    {
        $this->totalEmiNo = $totalEmiNo;

        return $this;
    }

    /**
     * Get totalEmiNo
     *
     * @return integer
     */
    public function getTotalEmiNo()
    {
        return $this->totalEmiNo;
    }

    /**
     * Set emiAmount
     *
     * @param string $emiAmount
     *
     * @return Customers
     */
    public function setEmiAmount($emiAmount)
    {
        $this->emiAmount = $emiAmount;

        return $this;
    }

    /**
     * Get emiAmount
     *
     * @return string
     */
    public function getEmiAmount()
    {
        return $this->emiAmount;
    }

    /**
     * Add emi
     *
     * @param \AppBundle\Entity\Emis $emi
     *
     * @return Customers
     */
    public function addEmi(\AppBundle\Entity\Emis $emi)
    {
        $this->emis[] = $emi;

        return $this;
    }

    /**
     * Remove emi
     *
     * @param \AppBundle\Entity\Emis $emi
     */
    public function removeEmi(\AppBundle\Entity\Emis $emi)
    {
        $this->emis->removeElement($emi);
    }
}
