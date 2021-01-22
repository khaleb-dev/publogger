<?php
/**
 * @link        https://publogger.khaleb.dev
 * @copyright   Copyright (c) 2021 Publogger
 * @license     MIT License    
 */

declare(strict_types=1);

namespace Application\Service\Factory;

use Interop\Container\ContainerInterface;
use Application\Service\BackendApiManager;
use Application\CustomObject\Utility;

/**
 * This is the factory class for BackendApiManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class BackendApiManagerFactory
{
    /**
     * This method creates the BackendApiManager service and returns its instance. 
     */

    public function __invoke(ContainerInterface $container)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $utility = new Utility();
        
        return new BackendApiManager($entityManager, $utility);
    }
}
