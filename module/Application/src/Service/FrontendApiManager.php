<?php
/**
 * @link        https://publogger.khaleb.dev
 * @copyright   Copyright (c) 2021 Publogger
 * @license     MIT License    
 */

declare(strict_types=1);

namespace Application\Service;

use Application\Entity\Images;
use Application\Entity\Post;
use Application\Entity\PostGroup;
use Application\Entity\PostImages;
use Application\Entity\PostTags;
use Application\Entity\Tags;
use Application\Entity\User;
use Application\CustomObject\simple_html_dom;

class FrontendApiManager
{
    public function __construct($entityManager, $utility)
    {
        $this->entityManager = $entityManager;
        $this->utility = $utility;
    }

}