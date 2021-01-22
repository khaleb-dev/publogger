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
 * This form is used to validate post data.
 */
class PostForm extends Form
{
    public function __construct()
    {
        parent::__construct('post-form');
        $this->setAttribute('method', 'POST');
           
        $this->addElements();
        $this->addInputFilter(); 
    }

    protected function addElements()
    {
        // Add "slug" field
        $this->add([
            'type'  => 'text',
            'name' => 'slug',
            'options' => [
                'label' => 'Slug',
            ],
        ]);

        // Add "title" field
        $this->add([
            'type'  => 'text',
            'name' => 'title',
            'options' => [
                'label' => 'Post Title',
            ],
        ]);

        // Add "thumbnail" field
        $this->add([
            'type'  => 'text',
            'name' => 'thumbnail',
            'options' => [
                'label' => 'Thumbnail URL',
            ],
        ]);

        // Add "content" field
        $this->add([
            'type'  => 'text',
            'name' => 'content',
            'options' => [
                'label' => 'Post Content',
            ],
        ]);

        // Add "tags" field
        $this->add([
            'type'  => 'text',
            'name' => 'tags',
            'options' => [
                'label' => 'Tags',
            ],
        ]);

        // Add "group" field
        $this->add([
            'type'  => 'text',
            'name' => 'group',
            'options' => [
                'label' => 'Group',
            ],
        ]);

        // Add "publish" field
        $this->add([
            'type'  => 'text',
            'name' => 'publish',
            'options' => [
                'label' => 'Publish Status',
            ],
        ]);
    }

    private function addInputFilter() 
    {
        $inputFilter = new InputFilter();        
        $this->setInputFilter($inputFilter);
        
        // Filter "slug" field
        $inputFilter->add([
            'name'     => 'slug',
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
                        'min' => 2,
                        'max' => 300
                    ],
                ],
            ],
        ]);
        
        // Filter "title" field
        $inputFilter->add([
            'name'     => 'title',
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
                        'max' => 2000
                    ],
                ],
            ],
        ]);
        
        // Filter "thumbnail" field
        $inputFilter->add([
            'name'     => 'thumbnail',
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
                        'min' => 2,
                        'max' => 200
                    ],
                ],
            ],
        ]);
        
        // Filter "content" field
        $inputFilter->add([
            'name'     => 'content',
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
                    ],
                ],
            ],
        ]);
        
        // Filter "tags" field
        $inputFilter->add([
            'name'     => 'tags',
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
                        'min' => 1,
                    ],
                ],
            ],
        ]);
        
        // Filter "group" field
        $inputFilter->add([
            'name'     => 'group',
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
                        'min' => 1,
                        'max' => 4
                    ],
                ],
            ],
        ]);

        // Filter "publish" field
        $inputFilter->add([
            'name'     => 'publish',
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
                        'min' => 1,
                        'max' => 6
                    ],
                ],
            ],
        ]);
    }
}