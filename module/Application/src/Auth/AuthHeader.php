<?php
/**
 * @link        https://publogger.khaleb.dev
 * @copyright   Copyright (c) 2021 Publogger
 * @license     MIT License    
 */

declare(strict_types=1);

namespace Application\Auth;

use Laminas\Http\Header\Authorization;

class AuthHeader extends Authorization
{
    public $token;

    public function getAuthToken(Authorization $authHeader)
    {
        $this->token = $authHeader->value;

        return $this;
    }
}