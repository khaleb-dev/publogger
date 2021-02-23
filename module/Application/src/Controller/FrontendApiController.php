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
    
    /**
     * set response
     */
    private function response(int $code = 405, string $status = 'METHOD NOT ALLOWED')
    {
        $response = [
            'code' => $code,
            'status' => $status,
        ];

        return $response;
    }

    /**
     * build post data once
     */
    private function buildPostData($post)
    {
        $postData = [];
        // find all tags for this post
        $postsTags = $this->entityManager->getRepository(PostTags::class)->findBy(["post" => $post]);
        $postData['id'] = $post->getId();
        $postData['slug'] = $post->getSlug();
        $postData['title'] = $post->getPostTitle();
        $postData['content'] = $post->getPostBody();
        $postData['thumbnail'] = $post->getThumbnailUrl();
        $postData['published'] = $post->getIsPublished();
        $postData['total_views'] = $post->getTotalViews();
        $postData['published'] = $post->getIsPublished();
        $postData['group'] = [];
        $postData['group']['id'] = $post->getGroup()->getId();
        $postData['group']['name'] = $post->getGroup()->getName();
        $postData['publisher'] = [];
        $postData['publisher']['id'] = $post->getUser()->getId();
        $postData['publisher']['full_name'] = $post->getUser()->getFullName();
        $postData['publisher']['username'] = $post->getUser()->getUsername();
        $postData['tags'] = [];
        if (!empty($postsTags) && !is_null($postsTags)) {
            foreach ($postsTags as $tag) {
                $tagData = [];
                $tagData['id'] = $tag->getTag()->getId();
                $tagData['name'] = $tag->getTag()->getName();
                $tagData['description'] = $tag->getTag()->getDescription();
                array_push($postData['tags'], $tagData);
            }
        }
        $postData['published_on'] = $post->getPublishedOn()->format('Y-m-d H:i:s');
        $postData['updated_on'] = $post->getUpdatedOn()->format('Y-m-d H:i:s');

        return $postData;
    }

    public function blogPostsAction()
    {
        $response = $this->response();

        if ($this->getRequest()->isGet())
        {
            $id = intval($this->params()->fromRoute('id', null));
            if(is_null($id) || $id <= 0){ // return all post that have not been deleted
                $posts = $this->entityManager->getRepository(Post::class)->findBy(["isDeleted" => false]);
                if (empty($posts)) {
                    $response = $this->response(404, 'NOT FOUND');
                }
                else {
                    $response = $this->response(200, 'OK');
                    $postsArr = array();
                    foreach ($posts as $post) {
                        array_push($postsArr, $this->buildPostData($post));
                    }
                    // add post array to json response
                    $response['postData'] = $postsArr;
                }
            }
            else { // search post by id
                $post = $this->entityManager->getRepository(Post::class)->findOneBy(["id" => $id, "isDeleted" => false]);
                if (empty($post)) {
                    $response = $this->response(404, 'NOT FOUND');
                }
                else {
                    $response = $this->response(200, 'OK');
                    // add post data to json response
                    $response['postData'] = $this->buildPostData($post);
                }
            }
        }

        return new JsonModel($response);
    }
    
}