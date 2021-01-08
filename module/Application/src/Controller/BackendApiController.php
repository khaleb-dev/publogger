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
use Laminas\Http\Headers;
use Laminas\Http\Response;
use Application\CustomObject\CustomFileUpload;
use Application\CustomObject\Utility;
use Application\CustomObject\simple_html_dom;
use Application\Entity\Images;
use Application\Entity\Post;
use Application\Entity\PostGroup;
use Application\Entity\PostImages;
use Application\Entity\PostTags;
use Application\Entity\Tags;
use Application\Entity\User;

class BackendApiController extends AbstractActionController
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

    private function response(int $code = 404, string $status = 'NOT FOUND')
    {
        $response = [
            'code' => $code,
            'status' => $status,
        ];

        return $response;
    }

    /**
     * Action to handle a single tag
     */
    public function tagAction()
    {
        $response = $this->response();

        if ($this->getRequest()->isPost())
        {
            $response = $this->response(201, 'CREATED');
        }
        
        if ($this->getRequest()->isGet())
        {
            $id = intval($this->params()->fromRoute('id', null));
            $tag = $this->entityManager->getRepository(Tags::class)->find($id);
            if (empty($tag)) {
                $response = $this->response(404, 'NOT FOUND');
            }
            else {
                $response = $this->response(200, 'OK');
                $response['tag'] = $tag;
            }
        }
        
        if ($this->getRequest()->isPatch())
        {
            $response = $this->response(200, 'OK');
            $response['message'] = 'patch';
        }
        
        if ($this->getRequest()->isPut())
        {
            $response = $this->response(200, 'OK');
        }
        
        if ($this->getRequest()->isDelete())
        {
            $response = $this->response(501, 'NOT IMPLEMENTED');
        }

        return new JsonModel($response);
    }

    /**
     * Action to handle a single tag
     */
    public function tagsAction()
    {
        $response = ['code' => 404, 'status' => 'NOT FOUND'];

        if ($this->getRequest()->isPost())
        {
            $response = ['code' => 201, 'status' => 'CREATED', 'message' => 'post'];
        }
        
        if ($this->getRequest()->isGet())
        {
            $response = ['code' => 200, 'status' => 'OK', 'message' => 'get'];
        }
        
        if ($this->getRequest()->isPatch())
        {
            $response = ['code' => 200, 'status' => 'OK', 'message' => 'patch'];
        }
        
        if ($this->getRequest()->isPut())
        {
            $response = ['code' => 200, 'status' => 'OK', 'message' => 'put'];
        }
        
        if ($this->getRequest()->isDelete())
        {
            $response = ['code' => 200, 'status' => 'OK', 'message' => 'delete'];
        }

        return new JsonModel($response);
    }

}