<?php
/**
 * @link        https://publogger.khaleb.dev
 * @copyright   Copyright (c) 2021 Publogger
 * @license     MIT License    
 */

/**
 * List of enabled modules for this application.
 *
 * This should be an array of module namespaces used in the application.
 */
return [
    'Laminas\ServiceManager\Di',
    'Laminas\Serializer',
    'Laminas\Mvc\Plugin\FilePrg',
    'Laminas\Mvc\Plugin\FlashMessenger',
    'Laminas\Mvc\Plugin\Identity',
    'Laminas\Mvc\Plugin\Prg',
    'Laminas\Session',
    'Laminas\I18n',
    'Laminas\Mvc\Console',
    'Laminas\Log',
    'Laminas\Cache',
    'Laminas\Form',
    'Laminas\InputFilter',
    'Laminas\Filter',
    'Laminas\Paginator',
    'Laminas\Hydrator',
    'Laminas\Router',
    'Laminas\Validator',
    'Chibex\Ozioma',
    'DoctrineModule',
    'DoctrineORMModule',
    'ZfrCors',
    'Application'
];
