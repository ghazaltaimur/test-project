<?php
namespace Company\Model;

use Application\Model\Doctrine;
use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import
use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
use Zend\InputFilter\InputFilterInterface;        // <-- Add this import
use Company\Entity\Country as CountryEntity;  

class Country extends Doctrine implements InputFilterAwareInterface 
{
    public $id;
    public $name;
    public $code;
    protected $inputFilter;                       // <-- Add this variable
    public function exchangeArray($data)
    {
        $this->id     = (isset($data['id']))     ? $data['id']     : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
        $this->code = (isset($data['code'])) ? $data['code'] : null;
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

        }

        return $this->inputFilter;
    }

   public function getAll() {
        return $this->entityManager->getRepository('Company\Entity\Country')->findAll();
   }
   
   public function get($id) {
        return  $this->entityManager->getRepository('Company\Entity\Country')->find($id);
   }
}