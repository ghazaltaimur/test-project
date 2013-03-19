<?php
namespace Company\Model;

use Application\Model\Doctrine;
use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import
use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
use Zend\InputFilter\InputFilterInterface;        // <-- Add this import 
use Company\Controller\CompanyController; 

class Company extends Doctrine implements InputFilterAwareInterface 
{
    public $id;
    public $name;
    public $address;
    public $postcode;
    public $city;
    public $country;
    protected $inputFilter;                       // <-- Add this variable
    public function exchangeArray($data)
    {
        $this->id     = (isset($data['id']))     ? $data['id']     : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
        $this->address  = (isset($data['address']))  ? $data['address']  : null;
        $this->postcode = (isset($data['postcode'])) ? $data['postcode'] : null;
        $this->city  = (isset($data['city']))  ? $data['city']  : null;
        $this->country = (isset($data['country'])) ? $data['country'] : null;
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
                'name'     => 'address',
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
                'name'     => 'postcode',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
            )));
             $inputFilter->add($factory->createInput(array(
                'name'     => 'city',
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
                
            $inputFilter->add(
                $factory->createInput(array(
                    'name'     => 'fileupload',
                    'required' => false,
                ))
            );
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
        $dql = "SELECT b, e FROM Company\Entity\Company b LEFT OUTER JOIN b.country e".$order_by;
        $query = $this->entityManager->createQuery($dql);
  
        return $query;
      }
      
   public function get($id) {
        return $this->entityManager->getRepository('Company\Entity\Company')->find($id);
   }
   
   public function getAll() {
        return $this->entityManager->getRepository('Company\Entity\Company')->findAll();
   }
   public function create($company) {
      $this->entityManager->persist($company);
      $this->entityManager->flush();
      return $company;
    }
   public function remove($company) {
      $this->entityManager->remove($company);
      $this->entityManager->flush();
      return $company;
    }
}