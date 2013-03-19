<?php

namespace Company\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A country entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="countries")
 * @property int $id
 * @property string $name
 * @property string $code
 */
class Country {

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
  protected $code;
  /**
  * @ORM\OneToMany(targetEntity="Company\Entity\Company", mappedBy="country", cascade={"persist", "remove", "merge"}, orphanRemoval=true)
  **/
  private $company;

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
        $this->code = $data['code'];
        //exit;
    }

}
