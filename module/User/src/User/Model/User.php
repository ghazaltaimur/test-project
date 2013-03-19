<?php
namespace User\Model;

use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import
use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
use Zend\InputFilter\InputFilterInterface;        // <-- Add this import
use Zend\Authentication\Storage\Session;
use Application\Model\Doctrine;

class User extends Doctrine implements InputFilterAwareInterface
{
    public $id;
    public $name;
    public $email;
    public $password;
    protected $inputFilter;                       // <-- Add this variable

    public function exchangeArray($data)
    {
        $this->id     = (isset($data['id']))     ? $data['id']     : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
        $this->email     = (isset($data['email']))     ? $data['email']     : null;
        $this->password = (isset($data['email'])) ? $data['email'] : null;
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    // Add content to this method:
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name'     => 'id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));


            $inputFilter->add($factory->createInput(array(
                'name'     => 'email',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'EmailAddress',
                        'options' => array(
                            'message' => 'This is not a valid email address'
                        ),
                    ),
                ),
            )));
            
            $inputFilter->add($factory->createInput(array(
                'name'     => 'department',
                'required' => false,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));
        
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
    
     public function getAdapter($column, $order) {
        if($column != ""){
           $order_by = " ORDER BY b.$column $order";
        }
        else
           $order_by = ""; 
        $dql = "SELECT b, e,a FROM User\Entity\User b INNER JOIN b.company e LEFT OUTER JOIN b.department a ".$order_by;
   //     $dql = "SELECT b, e,a FROM User\Entity\User b LEFT OUTER JOIN b.company e LEFT OUTER JOIN b.department a ".$order_by;
        $query = $this->entityManager->createQuery($dql);
        return $query;
      }
   public function get($id) {
        return $this->entityManager->getRepository('User\Entity\User')->find($id);
   }
   
   public function getAll() {
        return $this->entityManager->getRepository('User\Entity\User')->findAll();
   }  
   public function create($user) {
      $this->entityManager->persist($user);
      $this->entityManager->flush();
      return $user;
    }
   public function remove($user) {
      $this->entityManager->remove($user);
      $this->entityManager->flush();
      return $user;
    }
}
