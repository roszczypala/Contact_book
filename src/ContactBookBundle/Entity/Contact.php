<?php

namespace ContactBookBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Contact
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="ContactBookBundle\Entity\ContactRepository")
 */
class Contact
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\Regex("/^[A-Z]'?[-a-zA-Z]+$/")
     * @Assert\Length(min=1,
     * minMessage="Name should have at least 1 letters")
     * 
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=255)
     * 
     * @Assert\Length(min=1,
     * minMessage="Surname should have at least 1 letters")
     */
    private $surname;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * @Assert\Length(min=1,
     * minMessage="Description should have at least 1 letters")
     */
    private $description;


    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="ContactBookBundle\Entity\Address", mappedBy="contact", cascade={"remove"})
     */
    private $addresses;
    
    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="ContactBookBundle\Entity\Email", mappedBy="contact", cascade={"remove"})
     */
    private $emails;
    
    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="ContactBookBundle\Entity\Phone", mappedBy="contact", cascade={"remove"})
     */
    private $phones;
    
    public function __construct(){
        $this->emails = new ArrayCollection();
        $this->addresses = new ArrayCollection();
        $this->phones = new ArrayCollection();

    }  
    
    /**
     * Add address
     *
     * @param \ContactBookBundle\Entity\Address $address
     */
    public function addAddress(\ContactBookBundle\Entity\Address $address)
    {
        $this->addresses[] = $address;

        return $this;
    }
    
       /**
     * Remove address
     *
     * @param \ContactBookBundle\Entity\Address $address
     */
    public function removeAddress(\ContactBookBundle\Entity\Address $address)
    {
        $this->addresses->removeElement($address);
    }

    /**
     * Get address
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAddresses()
    {
        return $this->addresses;
    }
    
    /**
     * Add email
     *
     * @param \ContactBookBundle\Entity\Address $email
     */
    public function addEmail(\ContactBookBundle\Entity\Email $email)
    {
        $this->emails[] = $email;

        return $this;
    }
    
       /**
     * Remove email
     *
     * @param \ContactBookBundle\Entity\Email $email
     */
    public function removeEmail(\ContactBookBundle\Entity\Email $email)
    {
        $this->emails->removeElement($email);
    }

    /**
     * Get emails
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEmails()
    {
        return $this->emails;
    }
    
   
    /**
     * Add phone
     *
     * @param \ContactBookBundle\Entity\Phone $phone
     */
    public function addPhone(\ContactBookBundle\Entity\Phone $phone)
    {
        $this->phones[] = $phone;

        return $this;
    }
    
       /**
     * Remove phone
     *
     * @param \ContactBookBundle\Entity\Phone $phone
     */
    public function removePhone(\ContactBookBundle\Entity\Phone $phone)
    {
        $this->addresses->removeElement($phone);
    }

    /**
     * Get phone
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPhones()
    {
        return $this->phones;
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Contact
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set surname
     *
     * @param string $surname
     * @return Contact
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string 
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Contact
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }
}
