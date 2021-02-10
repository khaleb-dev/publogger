<?php
/**
 * @link        https://publogger.khaleb.dev
 * @copyright   Copyright (c) 2021 Publogger
 * @license     MIT License    
 */

declare(strict_types=1);

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
// use Laminas\Http\Headers;
// use Laminas\Http\Response;
use Application\CustomObject\simple_html_dom;
use Application\Entity\Images;
use Application\Entity\Post;
use Application\Entity\PostGroup;
use Application\Entity\PostImages;
use Application\Entity\PostTags;
use Application\Entity\Tags;
use Application\Entity\User;
use Application\Form\TagForm;
use Application\Form\GroupForm;
use Application\Form\PostForm;

class FrontendApiController extends AbstractActionController
{
    public function __construct($entityManager, $frontendApiManager, $utility)
    {
        $this->entityManager = $entityManager;
        $this->frontendApiManager = $frontendApiManager;
        $this->utility = $utility;
    }

    public function indexAction()
    {
        return new JsonModel([]);
    }
    
}