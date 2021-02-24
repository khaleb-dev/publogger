<?php
/**
 * @link        https://publogger.khaleb.dev
 * @copyright   Copyright (c) 2021 Publogger
 * @license     MIT License    
 */

declare(strict_types=1);

namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Application\Controller\BackendApiController;
use Application\Service\BackendApiManager;
use Application\CustomObject\Utility;

/**
 * This is the factory class for BackendApiController. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class BackendApiControllerFactory
{
    /**
     * This method creates the BackendApiController and returns its instance. 
     */
    public function __invoke(ContainerInterface $container) : BackendApiController
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $backendApiManager = $container->get(BackendApiManager::class);
        $utility = new Utility();
        
        return new BackendApiController($entityManager, $backendApiManager, $utility);
    }
}
