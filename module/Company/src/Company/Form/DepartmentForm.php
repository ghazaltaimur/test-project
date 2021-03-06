<?php
namespace company\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class DepartmentForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('department');
        $this->setAttribute('method', 'post');
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
            'name' => 'description',
            'attributes' => array(
                'type'  => 'text',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Description',
            ),
        ));
        
        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'status',
             'options' => array(
                     'label' => 'Status',
                     'value_options' => array(
                             '1' => 'Active',
                             '0' => 'Inactive',
                     ),
             )
        ));
        
        
        $Company = new Element\Select('company');
        $Company->setAttribute('required', false);
        $Company->setLabel('Company');
        $Company->setValueOptions($name);
        $this->add($Company);
      
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
