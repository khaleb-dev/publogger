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
use Application\CustomObject\CustomFileUpload;
use Application\CustomObject\Utility;
use Application\CustomObject\simple_html_dom;

class ApiController extends AbstractActionController
{
    private $appManager = null;

    public function __construct($entityManager, $appManager)
    {
        $this->entityManager = $entityManager;
        $this->appManager = $appManager;
    }

    public function indexAction()
    {
        return new JsonModel([]);
    }
}