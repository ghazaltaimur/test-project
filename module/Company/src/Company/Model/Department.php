<?php
namespace Company\Model;

use Application\Model\Doctrine;
use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import
use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
use Zend\InputFilter\InputFilterInterface;        // <-- Add this import

class Department extends Doctrine implements InputFilterAwareInterface 
{
    public $id;
    public $name;
    public $description;
    public $status;
    public $created_at;
    public $company;
    protected $inputFilter;                       // <-- Add this variable
    public function exchangeArray($data)
    {
        $this->id     = (isset($data['id']))     ? $data['id']     : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
        $this->description  = (isset($data['description']))  ? $data['description']  : null;
        $this->status = (isset($data['status'])) ? $data['status'] : null;
        $this->created_at  = (isset($data['created_at']))  ? $data['created_at']  : null;
        $this->company = (isset($data['company'])) ? $data['company'] : null;
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
                'name'     => 'name',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'description',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                    ),
                ),
            )));
            $inputFilter->add($factory->createInput(array(
                'name'     => 'status',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
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
        $dql = "SELECT b, e FROM Company\Entity\Department b LEFT OUTER JOIN b.company e".$order_by;
        $query = $this->entityManager->createQuery($dql);
        return $query;
      }
      
   public function get($id) {
        return $this->entityManager->getRepository('Company\Entity\Department')->find($id);
   }
   
   public function getAll() {
        return $this->entityManager->getRepository('Company\Entity\Department')->findAll();
   }
   public function create($department) {
      $this->entityManager->persist($department);
      $this->entityManager->flush();
      return $department;
    }
   public function remove($department) {
      $this->entityManager->remove($department);
      $this->entityManager->flush();
      return $department;
    }
}