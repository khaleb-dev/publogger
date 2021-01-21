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
use Application\Form\TagForm;
use Application\Form\GroupForm;
use Application\Form\PostForm;

class BackendApiController extends AbstractActionController
{
    private $backendApiManager = null;

    public function __construct($entityManager, $backendApiManager)
    {
        $this->entityManager = $entityManager;
        $this->backendApiManager = $backendApiManager;
    }

    public function indexAction()
    {
        return new JsonModel([]);
    }

    private function response(int $code = 406, string $status = 'NOT ACCEPTABLE')
    {
        $response = [
            'code' => $code,
            'status' => $status,
        ];

        return $response;
    }

    /**
     * Action to handle tag
     */
    public function tagAction()
    {
        $response = $this->response();

        if ($this->getRequest()->isPost())
        {
            // $auth = $this->params()->fromHeader('authorization');
            // var_dump($auth);
            // exit();
            $data = $this->params()->fromPost();
            if (empty($data)) {
                $response = $this->response();
            }
            else {
                $form = new TagForm();
                $form->setData($data);
                if ($form->isValid()) {
                    $data = $form->getData();
                    if (isset($data['update']) && $data['update'] === 'true') {
                        $id = intval($this->params()->fromRoute('id', null));
                        $tag = $this->entityManager->getRepository(Tags::class)->find($id);
                        if (empty($tag)) {
                            return new JsonModel($this->response(404, 'NOT FOUND'));
                        }
                        $tag = $this->backendApiManager->updateTag($tag, $data);
                        $response = $this->response(200, 'OK');
                    }
                    else {
                        $tag = $this->backendApiManager->createTag($data);
                        $response = $this->response(201, 'CREATED');
                    }
                    $tagData = [];
                    $tagData['id'] = $tag->getId();
                    $tagData['name'] = $tag->getName();
                    $tagData['description'] = $tag->getDescription();
                    $tagData['created_at'] = $tag->getCreatedAt()->format('Y-m-d H:i:s');

                    $response['tag'] = $tagData;
                }
                else {
                    $response = $this->response(400, 'BAD REQUEST');
                }
            }
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
                $tagData = [];
                $tagData['id'] = $tag->getId();
                $tagData['name'] = $tag->getName();
                $tagData['description'] = $tag->getDescription();
                $tagData['created_at'] = $tag->getCreatedAt()->format('Y-m-d H:i:s');

                $response['tag'] = $tagData;
            }
        }
        
        if ($this->getRequest()->isDelete())
        {
            $id = intval($this->params()->fromRoute('id', null));
            $tag = $this->entityManager->getRepository(Tags::class)->find($id);
            if (empty($tag)) {
                $response = $this->response(404, 'NOT FOUND');
            }
            else {
                $this->backendApiManager->deleteTag($tag);
                $response = $this->response(200, 'OK');
            }
        }

        return new JsonModel($response);
    }

    /**
     * Action to handle tags
     */
    public function tagsAction()
    {
        $response = $this->response();
        
        if ($this->getRequest()->isGet())
        {
            $id = intval($this->params()->fromRoute('id', null));
            $tags = $this->entityManager->getRepository(Tags::class)->findAll();
            if (empty($tags)) {
                $response = $this->response(404, 'NOT FOUND');
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

    /**
     * Action to handle group
     */
    public function groupAction()
    {
        $response = $this->response();

        if ($this->getRequest()->isPost())
        {
            $data = $this->params()->fromPost();
            if (empty($data)) {
                $response = $this->response();
            }
            else {
                $form = new GroupForm();
                $form->setData($data);
                if ($form->isValid()) {
                    $data = $form->getData();
                    if (isset($data['update']) && $data['update'] === 'true') {
                        $id = intval($this->params()->fromRoute('id', null));
                        $group = $this->entityManager->getRepository(PostGroup::class)->find($id);
                        if (empty($group)) {
                            return new JsonModel($this->response(404, 'NOT FOUND'));
                        }
                        $group = $this->backendApiManager->updateGroup($group, $data);
                        $response = $this->response(200, 'OK');
                    }
                    else {
                        $group = $this->backendApiManager->createGroup($data);
                        $response = $this->response(201, 'CREATED');
                    }
                    $groupData = [];
                    $groupData['id'] = $group->getId();
                    $groupData['name'] = $group->getName();
                    $groupData['description'] = $group->getDescription();
                    $groupData['created_at'] = $group->getCreatedAt()->format('Y-m-d H:i:s');

                    $response['group'] = $groupData;
                }
                else {
                    $response = $this->response(400, 'BAD REQUEST');
                }
            }
        }
        
        if ($this->getRequest()->isGet())
        {
            $id = intval($this->params()->fromRoute('id', null));
            $group = $this->entityManager->getRepository(PostGroup::class)->find($id);
            if (empty($group)) {
                $response = $this->response(404, 'NOT FOUND');
            }
            else {
                $response = $this->response(200, 'OK');
                $groupData = [];
                $groupData['id'] = $group->getId();
                $groupData['name'] = $group->getName();
                $groupData['description'] = $group->getDescription();
                $groupData['created_at'] = $group->getCreatedAt()->format('Y-m-d H:i:s');

                $response['group'] = $groupData;
            }
        }
        
        if ($this->getRequest()->isDelete())
        {
            $id = intval($this->params()->fromRoute('id', null));
            $group = $this->entityManager->getRepository(PostGroup::class)->find($id);
            if (empty($group)) {
                $response = $this->response(404, 'NOT FOUND');
            }
            else {
                $this->backendApiManager->deleteGroup($group);
                $response = $this->response(200, 'OK');
            }
        }

        return new JsonModel($response);
    }

    /**
     * Action to handle groups
     */
    public function groupsAction()
    {
        $response = $this->response();
        
        if ($this->getRequest()->isGet())
        {
            $id = intval($this->params()->fromRoute('id', null));
            $groups = $this->entityManager->getRepository(PostGroup::class)->findAll();
            if (empty($groups)) {
                $response = $this->response(404, 'NOT FOUND');
            }
            else {
                $response = $this->response(200, 'OK');
                $groupsArr = array();
                foreach ($groups as $group) {
                    $groupData = [];
                    $groupData['id'] = $group->getId();
                    $groupData['name'] = $group->getName();
                    $groupData['description'] = $group->getDescription();
                    $groupData['created_at'] = $group->getCreatedAt()->format('Y-m-d H:i:s');

                    array_push($groupsArr, $groupData);
                }

                $response['groups'] = $groupsArr;
            }
        }

        return new JsonModel($response);
    }

    /**
     * Action to handle image upload
     * Make sure you create the 'i1m2a3g4e5s' folder inside public dir to store uploaded images
     */
    public function uploadImageAction()
    {
        $response = $this->response();
        
        if ($this->getRequest()->isPost())
        {
            $request = $this->getRequest();
            $data = array_merge_recursive(
                $request->getPost()->toArray(), 
                $request->getFiles()->toArray()
            );
            $acceptedParams = ['path' => './public/i1m2a3g4e5s/', 'exts' => ['jpeg', 'jpg', 'png', 'gif'], 'type' => '_imgFl'];

            $upload = CustomFileUpload::upload($data, $acceptedParams);
            if ($upload['code'] == 200) {
                $saved = $this->backendApiManager->saveImage($upload['fileName']);
                if ($saved) {
                    $response = $this->response(201, 'CREATED');
                }
                else {
                    $response = $this->response(409, 'CONFLICT');
                    $response['message'] = "File uploaded BUT failed to save resource!";
                }
                // create an object to hold image response data
                $imageData = [];
                $imageData['id'] = $saved->getId();
                $imageData['fileUrl'] = $upload['fileUrl'];
                $imageData['fileName'] = $upload['fileName'];
                $imageData['created_at'] = $saved->getCreatedAt()->format('Y-m-d H:i:s');
                // add image data to json response
                $response['imageData'] = $imageData;
            }
            else {
                $response = $this->response(500, 'INTERNAL SERVER ERROR');
                $response['message'] = $upload['message'];
            }
        }

        if ($this->getRequest()->isGet())
        {
            $id = intval($this->params()->fromRoute('id', null));
            if(is_null($id) || $id <= 0){ // return all images
                $images = $this->entityManager->getRepository(Images::class)->findAll();
                if (empty($images)) {
                    $response = $this->response(404, 'NOT FOUND');
                }
                else {
                    $response = $this->response(200, 'OK');
                    $imagesArr = array();
                    foreach ($images as $image) {
                        $imageData = [];
                        $imageData['id'] = $image->getId();
                        // $imageData['fileUrl'] = $upload['fileUrl'];
                        $imageData['fileName'] = $image->getName();
                        $imageData['created_at'] = $image->getCreatedAt()->format('Y-m-d H:i:s');

                        array_push($imagesArr, $imageData);
                    }
                    // add image array to json response
                    $response['imageData'] = $imagesArr;
                }
            }
            else { // search image by id
                $image = $this->entityManager->getRepository(Images::class)->find($id);
                if (empty($image)) {
                    $response = $this->response(404, 'NOT FOUND');
                }
                else {
                    $response = $this->response(200, 'OK');
                    $imageData = [];
                    $imageData['id'] = $image->getId();
                    // $imageData['fileUrl'] = $upload['fileUrl'];
                    $imageData['fileName'] = $image->getName();
                    $imageData['created_at'] = $image->getCreatedAt()->format('Y-m-d H:i:s');
                    // add image data to json response
                    $response['imageData'] = $imageData;
                }
            }
        }

        if ($this->getRequest()->isDelete())
        {
            $id = intval($this->params()->fromRoute('id', null));
            $image = $this->entityManager->getRepository(Images::class)->find($id);
            if (empty($image)) {
                $response = $this->response(404, 'NOT FOUND');
            }
            else {
                CustomFileUpload::delete('./public/i1m2a3g4e5s/', $image->getName());
                $this->backendApiManager->deleteImage($image);
                $response = $this->response(200, 'OK');
            }
        }

        return new JsonModel($response);
    }

    /**
     * Action to handle create post
     * This will create and publish a new post and return the post data
     * It will also update an existing post if id was passed
     * It will set the post status as "draft" or "publish" based on the status passed in the request payload.
     * By default, the post status is "draft"
     */
    public function postAction()
    {
        $response = $this->response();

        if ($this->getRequest()->isPost())
        {
            $data = $this->params()->fromPost();
            if (empty($data)) {
                $response = $this->response();
            }
            else {
                $form = new PostForm();
                $form->setData($data);
                if ($form->isValid()) {
                    $data = $form->getData();
                    var_dump($data);
                    exit();
                }
                else {
                    $response = $this->response(400, 'BAD REQUEST');
                }
            }
        }

        return new JsonModel($response);
    }

    /**
     * Action to handle draft posts
     * For POST request: This will change the status of a post to "draft" and also update the post content if any was passed
     * For GET request: This will return all draft posts or single draft post as specified by id
     */
    public function draftAction()
    {
        $response = $this->response();

        if ($this->getRequest()->isPost())
        {
            $data = $this->params()->fromPost();

        }

        if ($this->getRequest()->isGet())
        {
            $id = intval($this->params()->fromRoute('id', null));
            if(is_null($id) || $id <= 0){ // return all unpublished post
                $posts = $this->entityManager->getRepository(Post::class)->findBy(["isPublished" => false, "isDeleted" => false]);
                if (empty($posts)) {
                    $response = $this->response(404, 'NOT FOUND');
                }
                else {
                    $response = $this->response(200, 'OK');
                    $postsArr = array();
                    foreach ($posts as $post) {
                        $postData = [];
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
                        $postData['published_on'] = $post->getPublishedOn()->format('Y-m-d H:i:s');
                        $postData['updated_on'] = $post->getUpdatedOn()->format('Y-m-d H:i:s');

                        array_push($postsArr, $postData);
                    }
                    // add post array to json response
                    $response['postData'] = $postsArr;
                }
            }
            else { // search post by id
                $post = $this->entityManager->getRepository(Post::class)->findOneBy(["id" => $id, "isPublished" => false, "isDeleted" => false]);
                if (empty($post)) {
                    $response = $this->response(404, 'NOT FOUND');
                }
                else {
                    $response = $this->response(200, 'OK');
                    $postData = [];
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
                    $postData['published_on'] = $post->getPublishedOn()->format('Y-m-d H:i:s');
                    $postData['updated_on'] = $post->getUpdatedOn()->format('Y-m-d H:i:s');
                    // add post data to json response
                    $response['postData'] = $postData;
                }
            }
        }

        return new JsonModel($response);
    }


    
}