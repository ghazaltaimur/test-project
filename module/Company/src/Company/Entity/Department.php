<?php

namespace Company\Entity;

use Doctrine\ORM\Mapping as ORM,
    Zend\Form\Annotation;

/**
 * A department entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="departments")
 * @property int $id
 * @property string $name
 * @property text $description
 * @property int $status
 * @property date $created_at
 * @property int $company_id
 */
class Department {

  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

 /**
 * @ORM\Column(type="string")
 * 
 * @Annotation\Attributes({"type":"text"})
 * @Annotation\Options({"label":"Name:"})
 * @Annotation\Filter({"name":"StringTrim"})
 * @Annotation\Filter({"name":"StripTags"})
 */
  protected $name;
/**
 * @ORM\Column(type="text")
 * 
 * @Annotation\Attributes({"type":"text"})
 * @Annotation\Options({"label":"Description:"})
 * @Annotation\Filter({"name":"StringTrim"})
 * @Annotation\Filter({"name":"StripTags"})
 */
  protected $description;
 /**
 * @ORM\Column(type="integer")
 * 
 * @Annotation\Type("Zend\Form\Element\Select")
 * @Annotation\Options({"label":"Status:",
 * "value_options" : {"1":"Active","0":"Inactive"}})
 */
  protected $status;
/**
 * @ORM\Column(type="datetime")
 * 
 * @Annotation\Required(false)
 */
    protected $created_at;
 /**
 * @ORM\ManyToOne(targetEntity="Company\Entity\Company", inversedBy="department")
 * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
 * @Annotation\Type("Zend\Form\Element\Select")
 * @Annotation\Options({"label":"Company:"}) 
 * @Annotation\Required(true)
 **/
 protected $company;
 
 /**
  * @ORM\OneToMany(targetEntity="User\Entity\User", mappedBy="department", cascade={"persist", "remove", "merge"}, orphanRemoval=true)
  **/
  private $user;

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
  public function getArrayCopy() 
    {
        return get_object_vars($this);
    }
   public function populate($data = array()) 
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->description = $data['description'];
        $this->status = $data['status'];
    }

}
