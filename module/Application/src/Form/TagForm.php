<?php
/**
 * @link        https://publogger.khaleb.dev
 * @copyright   Copyright (c) 2021 Publogger
 * @license     MIT License    
 */

declare(strict_types=1);

namespace Application\Form;

use Laminas\Form\Form;
use Laminas\InputFilter\InputFilter;

/**
 * This form is used to validate tag data.
 */
class TagForm extends Form
{
    public function __construct()
    {
        parent::__construct('tag-form');
        $this->setAttribute('method', 'POST');
           
        $this->addElements();
        $this->addInputFilter(); 
    }

    protected function addElements()
    {
        // Add "name" field
        $this->add([
            'type'  => 'text',
            'name' => 'name',
            'options' => [
                'label' => 'Name',
            ],
        ]);

        // Add "description" field
        $this->add([
            'type'  => 'text',
            'name' => 'description',
            'options' => [
                'label' => 'description',
            ],
        ]);

        // Add "update" field
        $this->add([
            'type'  => 'text',
            'name' => 'update',
            'options' => [
                'label' => 'update',
            ],
        ]);
    }

    private function addInputFilter() 
    {
        $inputFilter = new InputFilter();        
        $this->setInputFilter($inputFilter);
        
        // Filter "name" field
        $inputFilter->add([
            'name'     => 'name',
            'required' => true,
            'filters'  => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => 'StripNewlines'],
            ],                
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => 2,
                        'max' => 99
                    ],
                ],
            ],
        ]);
        
        // Filter "description" field
        $inputFilter->add([
            'name'     => 'description',
            'required' => false,
            'filters'  => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => 'StripNewlines'],
            ],                
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'max' => 199
                    ],
                ],
            ],
        ]);
        
        // Filter "update" field
        $inputFilter->add([
            'name'     => 'update',
            'required' => false,
            'filters'  => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => 'StripNewlines'],
            ],                
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'max' => 5
                    ],
                ],
            ],
        ]);
    }
}