<?php

namespace Company\Entity;

use Doctrine\ORM\Mapping as ORM,
    Zend\Form\Annotation;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * A state entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="companies")
 * @property int $id
 * @property string $name
 * @property string $address
 * @property int $postcode
 * @property string $city
 * @property int $country_id
 *  @property int $user_id
 * 
 * @Annotation\Name("Company")
 */
class Company {

  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string")
   */
  protected $name;
 /**
   * @ORM\Column(type="string")
   */
  protected $address;
 /**
   * @ORM\Column(type="integer")
   */
  protected $postcode;
/**
   * @ORM\Column(type="string")
   */
  protected $city;
  
  /**
     * @ORM\ManyToOne(targetEntity="Company\Entity\Country", inversedBy="company")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     **/
  protected $country;
  /**
  * @ORM\OneToMany(targetEntity="User\Entity\User", mappedBy="company", cascade={"persist", "remove", "merge"}, orphanRemoval=true)
  **/
  private $user;
  
  /**
  * @ORM\OneToMany(targetEntity="Company\Entity\Department", mappedBy="company", cascade={"persist", "remove", "merge"}, orphanRemoval=true)
  **/
  private $department;
  
  /**
   * Magic getter to expose protected properties.
   *
   * @param string $property
   * @return mixed
   */
  public function __get($property) {
    return $this->$property;
  }

  /**
   * Magic setter to save protected properties.
   *
   * @param string $property
   * @param mixed $value
   */
  public function __set($property, $value) {
    $this->$property = $value;
  }
  public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }
    public function getAddress()
    {
        return $this->address;
    }
    public function getCity()
    {
        return $this->city;
    }
    public function getPostcode()
    {
        return $this->postcode;
    }
    public function setName($name)
    {
        $this->name = $name;
    }
    public function setAddress($address)
    {
        $this->address = $address;
    }
    public function setCity($city)
    {
        $this->city = $city;
    }
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;
    }
   public function getArrayCopy() 
    {
        return get_object_vars($this);
    }
   public function populate($data = array()) 
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->address = $data['address'];
        $this->postcode = $data['postcode'];
        $this->city = $data['city'];
//      $this->country = $data['country'];
        //exit;
    }
    
    public function populateObject($data) 
    {
        $this->id = $data->id;
        $this->name = $data->name;
        $this->address = $data->address;
        $this->postcode = $data->postcode;
        $this->city = $data->city;
      //  $this->country = $data->country;
        //exit;
    }

}
