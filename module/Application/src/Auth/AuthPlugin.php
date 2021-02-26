<?php
/**
 * @link        https://publogger.khaleb.dev
 * @copyright   Copyright (c) 2021 Publogger
 * @license     MIT License    
 */

declare(strict_types=1);

namespace Application\Auth;

use Laminas\Mvc\Controller\Plugin\AbstractPlugin;

class AuthPlugin extends AbstractPlugin
{
    private $header;
    
    public function __construct($header)
    {
        $this->header = $header;
    }

    public function getAuthToken($auth)
    {
        if (!is_null($auth)){
            $r = $this->header->getAuthToken($auth);
    
            return $r->token;
        }
        return null;
    }

    public function validateAuthToken($auth)
    {

        return null;
    }

}