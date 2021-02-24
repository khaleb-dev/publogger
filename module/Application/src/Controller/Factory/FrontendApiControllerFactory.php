<?php
/**
 * @link        https://publogger.khaleb.dev
 * @copyright   Copyright (c) 2021 Publogger
 * @license     MIT License    
 */

declare(strict_types=1);

namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Application\Controller\FrontendApiController;
use Application\Service\FrontendApiManager;
use Application\CustomObject\Utility;

/**
 * This is the factory class for FrontendApiController. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class FrontendApiControllerFactory
{
    /**
     * This method creates the FrontendApiController and returns its instance. 
     */
    public function __invoke(ContainerInterface $container) : FrontendApiController
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $frontendApiManager = $container->get(FrontendApiManager::class);
        $utility = new Utility();
        
        return new FrontendApiController($entityManager, $frontendApiManager, $utility);
    }
}
