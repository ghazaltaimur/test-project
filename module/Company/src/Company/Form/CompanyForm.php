<?php
namespace company\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class companyForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('company');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype','multipart/form-data');
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'type'  => 'text',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Name',
            ),
        ));
        $this->add(array(
            'name' => 'address',
            'attributes' => array(
                'type'  => 'text',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Address',
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Number',
            'name' => 'postcode',
            'options' => array(
                    'label' => 'Postcode'
            )
        ));
        $this->add(array(
            'name' => 'city',
            'attributes' => array(
                'type'  => 'text',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'City',
            ),
        ));
    
        $Country = new Element\Select('country');
        $Country->setAttribute('required', false);
        $Country->setLabel('Country');
        $Country->setValueOptions($name);
        $this->add($Country);
       
        $this->add(array(
            'name' => 'fileupload',
            'attributes' => array(
                'type'  => 'file',
            ),
            'options' => array(
                'label' => 'File Upload',
            ),
        )); 
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
    }
}
