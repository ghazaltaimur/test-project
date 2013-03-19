<?php
namespace User\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class UserForm extends Form
{
    public function __construct($name = null,$departments = null)
    {
        parent::__construct('user');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'firstname',
            'attributes' => array(
                'type'  => 'text',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'First Name',
            ),
        ));
        $this->add(array(
            'name' => 'lastname',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Last Name',
            ),
        ));
        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type'  => 'text',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Email',
            ),
        ));
        
        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type'  => 'password',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Password',
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
        $Company->setAttribute('required', true);
        $Company->setLabel('Company');
        $Company->setValueOptions($name);
        $this->add($Company);
        
        $Department = new Element\Select('department');
        $Department->setAttribute('required', false);
        $Department->setLabel('Department');
        $Department->setEmptyOption("Select Department","0");
        $Department->setValueOptions($departments);
        $this->add($Department);
        
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
