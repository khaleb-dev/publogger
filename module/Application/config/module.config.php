<?php
/**
 * @link        https://publogger.khaleb.dev
 * @copyright   Copyright (c) 2021 Publogger
 * @license     MIT License    
 */

declare(strict_types=1);

namespace Application;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Laminas\Router\Http\Hostname;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'frontend-api' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/:action[/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller'    => Controller\FrontendApiController::class,
                        'action'        => 'index',
                    ],
                ],
            ],
            'backend-api' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/base/:action[/:id]',
                    'constraints' => [
                        'api-key' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller'    => Controller\BackendApiController::class,
                        'action'        => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            Controller\BackendApiController::class => Controller\Factory\BackendApiControllerFactory::class,
            Controller\FrontendApiController::class => Controller\Factory\FrontendApiControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            Service\BackendApiManager::class => Service\Factory\BackendApiManagerFactory::class,
            Service\FrontendApiManager::class => Service\Factory\FrontendApiManagerFactory::class,
        ],
    ],
    // We register module-provided controller plugins under this key.
    'controller_plugins' => [
        'factories' => [
            Auth\AuthPlugin::class => Auth\Factory\AuthPluginFactory::class,
        ],
        'aliases' => [
            'auth' => Auth\AuthPlugin::class,
        ],
    ],
    // The following registers our custom view 
    // helper classes in view plugin manager.
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'Application/index/index' => __DIR__ . '/../view/Application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'strategies'  => [
            'ViewJsonStrategy',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ],
            ],
        ],
    ],
];
