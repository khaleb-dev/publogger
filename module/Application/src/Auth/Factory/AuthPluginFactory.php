<?php
/**
 * @link        https://publogger.khaleb.dev
 * @copyright   Copyright (c) 2021 Publogger
 * @license     MIT License    
 */

declare(strict_types=1);

namespace Application\Auth\Factory;

use Interop\Container\ContainerInterface;
use Application\Auth\AuthPlugin;
use Application\Auth\AuthHeader;

class AuthPluginFactory
{
    public function __invoke(ContainerInterface $container)
    {        
        $header = new AuthHeader();
        
        return new AuthPlugin($header);
    }
}