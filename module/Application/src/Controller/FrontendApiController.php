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

    public function indexAction() : JsonModel
    {
        return new JsonModel([]);
    }
    
    /**
     * set response
     */
    private function response(int $code = 405, string $status = 'METHOD NOT ALLOWED') : array
    {
        $response = [
            'code' => $code,
            'status' => $status,
        ];

        return $response;
    }

    public function tagAction() : JsonModel
    {
        $response = $this->response();
        
        if ($this->getRequest()->isGet())
        {
            $id = intval($this->params()->fromRoute('id', null));
            if(is_null($id) || $id <= 0){ 
                $response = $this->response(404, 'NOT FOUND');
            }
            // search tag by id
            $tag = $this->entityManager->getRepository(Tags::class)->find($id);
            if (empty($tag)) {
                $response = $this->response(204, 'NO CONTENT');
            }
            else {
                $response = $this->response(200, 'OK');
                $tagData = [];
                $tagData['id'] = $tag->getId();
                $tagData['name'] = $tag->getName();
                $tagData['description'] = $tag->getDescription();
                $tagData['created_at'] = $tag->getCreatedAt()->format('Y-m-d H:i:s');

                $response['tag'] = $tagData;
            }
        }

        return new JsonModel($response);
    }

    public function tagsAction() : JsonModel
    {
        $response = $this->response();
        
        if ($this->getRequest()->isGet())
        {
            $tags = $this->entityManager->getRepository(Tags::class)->findAll();
            if (empty($tags)) {
                $response = $this->response(204, 'NO CONTENT');
            }
            else {
                $response = $this->response(200, 'OK');
                $tagsData = array();
                foreach ($tags as $tag) {
                    $tagData = [];
                    $tagData['id'] = $tag->getId();
                    $tagData['name'] = $tag->getName();
                    $tagData['description'] = $tag->getDescription();
                    $tagData['created_at'] = $tag->getCreatedAt()->format('Y-m-d H:i:s');

                    array_push($tagsData, $tagData);
                }

                $response['tags'] = $tagsData;
            }
        }

        return new JsonModel($response);
    }

    public function groupAction() : JsonModel
    {
        $response = $this->response();
        
        if ($this->getRequest()->isGet())
        {
            $id = intval($this->params()->fromRoute('id', null));
            if(is_null($id) || $id <= 0){ 
                $response = $this->response(404, 'NOT FOUND');
            }
            // search group by id
            $group = $this->entityManager->getRepository(PostGroup::class)->find($id);
            if (empty($group)) {
                $response = $this->response(204, 'NO CONTENT');
            }
            else {
                $response = $this->response(200, 'OK');
                $groupData = [];
                $groupData['id'] = $group->getId();
                $groupData['name'] = $group->getName();
                $groupData['description'] = $group->getDescription();
                $groupData['default'] = $group->getIsDefault();
                $groupData['created_at'] = $group->getCreatedAt()->format('Y-m-d H:i:s');

                $response['group'] = $groupData;
            }
        }

        return new JsonModel($response);
    }

    public function groupsAction() : JsonModel
    {
        $response = $this->response();
        
        if ($this->getRequest()->isGet())
        {
            $groups = $this->entityManager->getRepository(PostGroup::class)->findAll();
            if (empty($groups)) {
                $response = $this->response(204, 'NO CONTENT');
            }
            else {
                $response = $this->response(200, 'OK');
                $groupsArr = array();
                foreach ($groups as $group) {
                    $groupData = [];
                    $groupData['id'] = $group->getId();
                    $groupData['name'] = $group->getName();
                    $groupData['description'] = $group->getDescription();
                    $groupData['default'] = $group->getIsDefault();
                    $groupData['created_at'] = $group->getCreatedAt()->format('Y-m-d H:i:s');

                    array_push($groupsArr, $groupData);
                }

                $response['groups'] = $groupsArr;
            }
        }

        return new JsonModel($response);
    }

    /**
     * build post data once
     */
    private function buildPostData($post) : array
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
        $postData['last_viewed_on'] = $post->getLastViewedOn()->format('Y-m-d H:i:s');
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

    public function blogPostAction() : JsonModel
    {
        $response = $this->response();

        if ($this->getRequest()->isGet())
        {
            $id = intval($this->params()->fromRoute('id', null));
            if(is_null($id) || $id <= 0){ 
                $response = $this->response(404, 'NOT FOUND');
            }
            // search post by id
            $post = $this->entityManager->getRepository(Post::class)->findOneBy(["id" => $id, "isDeleted" => false]);
            if (empty($post)) {
                $response = $this->response(204, 'NO CONTENT');
            }
            else {
                $response = $this->response(200, 'OK');
                // increment the number of views
                $post = $this->frontendApiManager->incrementPostView($post);
                // add post data to json response
                $response['postData'] = $this->buildPostData($post);
            }
        }

        return new JsonModel($response);
    }

    public function blogPostsAction() : JsonModel
    {
        $response = $this->response();

        if ($this->getRequest()->isGet())
        {
            // return all post that have not been deleted
            $posts = $this->entityManager->getRepository(Post::class)->findBy(["isDeleted" => false]);
            if (empty($posts)) {
                $response = $this->response(204, 'NO CONTENT');
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

        return new JsonModel($response);
    }

    public function blogPostsGroupAction() : JsonModel
    {
        $response = $this->response();

        if ($this->getRequest()->isGet())
        {
            $id = intval($this->params()->fromRoute('id', null));
            if(is_null($id) || $id <= 0){ 
                $response = $this->response(404, 'NOT FOUND');
            }
            // find the group
            $group = $this->entityManager->getRepository(PostGroup::class)->findBy(["id" => $id]);
            if (empty($group)) {
                $response = $this->response(404, 'NOT FOUND');
            }
            // return all post under this group that have not been deleted
            $posts = $this->entityManager->getRepository(Post::class)->findBy(["group" => $group, "isDeleted" => false]);
            if (empty($posts)) {
                $response = $this->response(204, 'NO CONTENT');
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

        return new JsonModel($response);
    }

    public function blogPostsTagAction() : JsonModel
    {
        $response = $this->response();

        if ($this->getRequest()->isGet())
        {
            $id = intval($this->params()->fromRoute('id', null));
            if(is_null($id) || $id <= 0){ 
                $response = $this->response(404, 'NOT FOUND');
            }
            // find the tag
            $tag = $this->entityManager->getRepository(Tags::class)->findBy(["id" => $id]);
            if (empty($tag)) {
                $response = $this->response(404, 'NOT FOUND');
            }
            // fetch all posts by this tag
            $tagedPosts = $this->entityManager->getRepository(PostTags::class)->findBy(["tag" => $tag]);
            if (empty($tagedPosts)) {
                $response = $this->response(204, 'NO CONTENT');
            }
            else {
                $response = $this->response(200, 'OK');
                $postsArr = array();
                foreach ($tagedPosts as $tagedPost) {
                    // do not return deleted posts
                    if($tagedPost->getIsDeleted() == true) {
                        continue;
                    }
                    array_push($postsArr, $this->buildPostData($tagedPost->getPost()));
                }
                // add post array to json response
                $response['postData'] = $postsArr;
            }
        }

        return new JsonModel($response);
    }


    
}