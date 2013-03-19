<?php

namespace User\Entity;

use Doctrine\ORM\Mapping as ORM,
    Zend\Form\Annotation;

/**
 * A state entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="users")
 * @property int $id
 * @property string $firstname
 * @property string $lastname
 * @property int $email
 * @property string $password
 * @property int $status
 * @property int $company_id
 * 
 * @Annotation\Name("User")
 */
class User {
   /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

 /**
 * @ORM\Column(type="string")
 * 
 * @Annotation\Attributes({"type":"text", "required":"true"})
 * @Annotation\Options({"label":"Firstname:"})
 * @Annotation\Filter({"name":"StringTrim"})
 * @Annotation\Filter({"name":"StripTags"})
 */
  protected $firstname;
 /**
 * @ORM\Column(type="string")
 * 
 * @Annotation\Attributes({"type":"text", "required":"false"})
 * @Annotation\Options({"label":"Lastname:"})
 * @Annotation\Filter({"name":"StringTrim"})
 * @Annotation\Filter({"name":"StripTags"})
 */
  protected $lastname;
/**
 * @ORM\Column(name="email", type="string")
 * 
 * @Annotation\Attributes({"type":"email", "required":"true" })
 * @Annotation\Options({"label":"Email:"})
 * @Annotation\Filter({"name":"StringTrim"})
 * @Annotation\Filter({"name":"StripTags"})
 * @Annotation\Validator({"name":"Regex", "options":{"pattern":"/[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})/", "messages":{"regexInvalid":"Invalid Regular Expression","regexNotMatch": "Invalid Email Address","regexErrorous": "Internal error"}}})
 */
 protected $email;
/**
 * @ORM\Column(type="string")
 * 
 * @Annotation\Attributes({"type":"password" })
 * @Annotation\Options({"label":"Password:"})
 * @Annotation\Filter({"name":"StringTrim"})
 * @Annotation\Filter({"name":"StripTags"})
 * @Annotation\Required(true)
 */
 protected $password;
/**
 * @ORM\Column(type="integer")
 * 
 * @Annotation\Type("Zend\Form\Element\Select")
 * @Annotation\Options({"label":"Status:",
 * "value_options" : {"1":"Active","0":"Inactive"}})
 */
  protected $status;
/**
 * @ORM\ManyToOne(targetEntity="Company\Entity\Company", inversedBy="user")
 * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
 * @Annotation\Type("Zend\Form\Element\Select")
 * @Annotation\Options({"label":"Company:"}) 
 * @Annotation\Required(true)
 **/
 protected $company;
 /**
 * @ORM\ManyToOne(targetEntity="Company\Entity\Department", inversedBy="user")
 * @ORM\JoinColumn(name="department_id", referencedColumnName="id")
 * @Annotation\Type("Zend\Form\Element\Select")
 * @Annotation\Options({"label":"Department:"}) 
 * @Annotation\Required(false)
 **/
 protected $department;
 
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

    public function getFirstname()
    {
        return $this->firstname;
    }
    public function getLastname()
    {
        return $this->lastname;
    }
    public function getPassword()
    {
        return $this->password;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getStatus()
    {
        return $this->status;
    }
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }
    public function setPassword($password)
    {
        $this->password = $password;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function setStatus($status)
    {
        $this->status = $status;
    }
   public function getArrayCopy() 
    {
        return get_object_vars($this);
    }
   public function populate($data = array()) 
    {
        $this->id = $data['id'];
        $this->firstname = $data['firstname'];
        $this->lastname = $data['lastname'];
        $this->email = $data['email'];
        $this->password = md5($data['password']);
        $this->status = $data['status'];
        //exit;
    }
    
    public function populateObject($data) 
    {
        $this->id = $data->id;
        $this->firstname = $data->firstname;
        $this->lastname = $data->lastname;
        $this->email = $data->email;
        $this->password = $data->password;
        $this->email = $data->email;
        $this->company = $data->company;
        //exit;
    }


    public static function hashPassword($identity, $plaintext) {
        //echo $identity->password;
       // print_r($identity);
        return md5($plaintext . $identity->password);
    }
    public function getSessionRepresentation() {
        $output['id'] = $this->id;
        $output['name'] = $this->name;
        $output['email'] = $this->email;
        return $output;
    }
}
