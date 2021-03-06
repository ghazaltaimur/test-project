<?php
namespace Session\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class SessionForm extends Form
{
    public function __construct()
    {
        parent::__construct('session');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
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
        
    }
}
