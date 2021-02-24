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

class BackendApiManager
{
    public function __construct($entityManager, $utility)
    {
        $this->entityManager = $entityManager;
        $this->utility = $utility;
    }

    public function createTag($data)
    {
        $tag = new Tags();
        $now = new \DateTime;

        $tag->setName($data['name']);
        $tag->setDescription($data['description']);
        $tag->setCreatedAt($now);
        $this->entityManager->persist($tag);
        $this->entityManager->flush();
        return $tag;
    }

    public function updateTag(Tags $tag, $data)
    {
        $tag->setName($data['name']);
        $tag->setDescription($data['description']);
        $this->entityManager->flush();
        return $tag;
    }

    public function deleteTag(Tags $tag)
    {
        $this->entityManager->remove($tag);
        $this->entityManager->flush();
        return true;
    }

    /**
     * This method will check if there are multiple default groups on the database,
     * if yes, it will reset all 'default' column to false.
     */
    private function hotResetFakeGroupDefaults()
    {

    }

    public function createGroup($data) : PostGroup
    {
        $group = new PostGroup();
        $now = new \DateTime;
        // check if there is a default group in the system
        $defaultGroup = $this->entityManager->getRepository(PostGroup::class)->findOneBy(['isDefault' => true]);
        if (empty($defaultGroup)) { // if none, set this new group as default
            $group->setIsDefault(true);
        }
        else { // check if the user wants to set this new group as default
            if ($data['default'] == "true") {
                $defaultGroup->setIsDefault(false); // switch default from old group
                $group->setIsDefault(true);         // to the new group
            }
            else {
                $group->setIsDefault(false);
            }
        }

        $group->setName($data['name']);
        $group->setDescription($data['description']);
        $group->setCreatedAt($now);
        $this->entityManager->persist($group);
        $this->entityManager->flush();
        return $group;
    }

    public function updateGroup(PostGroup $group, $data) : PostGroup
    {
        // check if there is a default group in the system
        $defaultGroup = $this->entityManager->getRepository(PostGroup::class)->findOneBy(['isDefault' => true]);
        // With these block below, the system will be able to force-set a default group even if you unset
        // the default from database.
        if (empty($defaultGroup)) { // if none, set this new group as default
            $group->setIsDefault(true);
        }
        else { // check if the user wants to set this new group as default
            if ($data['default'] == "true") {
                $defaultGroup->setIsDefault(false); // switch default from old group
                $group->setIsDefault(true);         // to the new group
            }
            else {
                $group->setIsDefault(false);
            }
        }
        $group->setName($data['name']);
        $group->setDescription($data['description']);
        $this->entityManager->flush();
        return $group;
    }

    public function deleteGroup(PostGroup $group) : bool
    {
        $this->entityManager->remove($group);
        $this->entityManager->flush();
        return true;
    }

    public function saveImage(string $filename) : Images
    {
        $image = new Images();
        $now = new \DateTime;

        $image->setName($filename);
        $image->setCreatedAt($now);
        $this->entityManager->persist($image);
        $this->entityManager->flush();
        return $image;
    }

    public function deleteImage(Images $image) : bool
    {
        $this->entityManager->remove($image);
        $this->entityManager->flush();
        return true;
    }

    public function createPost($data)
    {
        $post = new Post();
        $data['isUpdate'] = false;
        return $this->handlePost($data, $post);
    }

    public function updatePost($post, $data)
    {
        $data['isUpdate'] = true;
        return $this->handlePost($data, $post);
    }

    private function handlePost($data, $post)
    {
        // group setup
        if(!is_null($data['group']) && $data['group'] != "") { // get the group from database
            $group = $this->entityManager->getRepository(PostGroup::class)->find(intval($data['group']));
        } else { // use the system default
            $group = $this->entityManager->getRepository(PostGroup::class)->findOneBy(['isDefault' => true]);
        }
        // if we still can't find the group, return an error
        if (empty($group)) {
            return false;
        }

        // if theres no title, extract and use first eight words from content as title
        if (!isset($data['title']) || is_null($data['title']) || $data['title'] == "") {
            $data['title'] = $this->utility->wordCount(html_entity_decode($this->utility->sanitize($data['content'])), 8);
        }

        // if theres no slug, generate slug from title
        if (!isset($data['slug']) || is_null($data['slug']) || $data['slug'] == "") {
            $data['slug'] = $this->utility->convertStringToSlug($data['title']);
        }
        else { // format the slug
            $data['slug'] = $this->utility->convertStringToSlug($data['slug']);
        }

        // we want to extract all our image files from content so as to store them in a database table.
        // Also, we will use the first image as thumbnail for our post if a thumbnail was not sent with
        // the request payload.
        // We will use "simple html dom" class for these purpose (thanks to S.C. Chen & co).
        // First create a DOM object
        $html = new simple_html_dom();
        // Load HTML from content string
        $loadedHtmlContent = $html->load($data['content']);
        // Iterate to get element from object and arrange as associative array
        $imgArr = [];
        foreach ($loadedHtmlContent->find('img') as $i) {
            $imgArr[] = ['imgSrc' => $i->src];
        }

        // set thumbnail
        if (!isset($data['thumbnail']) || is_null($data['thumbnail']) || $data['thumbnail'] == "") {
            if (!empty($imgArr)) { // thumbnail will only be set if there's an image in the post content
                $fI = $imgArr[0]['imgSrc'];
                $sT = explode("/", $fI);
                
                $data['thumbnail'] = end($sT);
            }
            else {
                $data['thumbnail'] = null;
            }
        }

        // set publish status
        if ($data['publish'] == 'true') {
            $data['publish'] = true;
        }
        else {
            $data['publish'] = false;
        }

        // time to save the data to db
        $data['content'] = htmlentities(trim($data['content']), ENT_QUOTES, 'UTF-8');
        $now = new \DateTime;

        $post->setGroup($group);
        $user = $this->entityManager->getRepository(User::class)->find(1); // this will not be used in the future when we implement user auth.
        $post->setUser($user);
        $post->setSlug($data['slug']);
        $post->setPostTitle($data['title']);
        $post->setThumbnailUrl($data['thumbnail']);
        $post->setPostBody($data['content']);
        $post->setIsPublished($data['publish']);
        $post->setIsDeleted(false);

        if ($data['isUpdate'] == false) {
            $post->setTotalViews(0);
            $post->setLastViewedOn($now);
            $post->setPublishedOn($now);
            $post->setUpdatedOn($now);
        }
        else {
            $post->setUpdatedOn($now);
        }

        $this->entityManager->persist($post);

        if ($data['isUpdate'] == true) {
            // unlink old tags
            $this->unlinkTagsFromPost($post);
            // unlink old images
            $this->unlinkImagesFromPost($post);
        }

        // lets save the images too
        // we are going to keep record of images related to each post, hence we iterate the 
        // imgArr array if it is not empty.
        if (!empty($imgArr)) {
            $this->linkImagesToPost($imgArr, $post);
        }

        // lets not forget to add tags if avialable
        if (isset($data['tags']) && !is_null($data['tags']) && $data['tags'] != "") {
            $this->addTagsToPost($data['tags'], $post);
        }

        $this->entityManager->flush();

        return $post;
    }

    private function linkImagesToPost(array $images, $post) : bool
    {
        $now = new \DateTime('now');
        foreach ($images as $img) {
            $sT = explode("/", $img['imgSrc']);
            // confirm existence of such imageURL on image table
            $image = $this->entityManager->getRepository(Image::class)->findOneBy(['name' => end($sT)]);
            // if it exists, proceed to PostImages table
            if (!empty($image) && !is_null($image)) {
                // To prevent multiple entry on PostImages table, be sure that it does not exist here
                $imagePost = $this->entityManager->getRepository(PostImages::class)->findOneBy(['image' => $image, 'post' => $post]);
                // if it does not exist, record it.
                if (empty($imagePost) || is_null($imagePost)) {
                    // create new record
                    $newImgPost = new PostImages();
                    
                    $newImgPost->setImage($image);
                    $newImgPost->setPost($post);
                    $newImgPost->setCreatedAt($now);

                    $this->entityManager->persist($newImgPost);
                }
            }
            else {
                continue;
            }
        }
        return true;
    }

    private function addTagsToPost(String $tags, $post) : bool
    {
        $now = new \DateTime('now');
        $breakTag = explode(',', $tags);
        foreach ($breakTag as $tag) {
            if ($tag != '') {
                $tag = $this->entityManager->getRepository(Tags::class)->find($tag);

                if (!empty($tag) && !is_null($tag)) {
                    $postTag = new PostTags();

                    $postTag->setPost($post);
                    $postTag->setTag($tag);
                    $postTag->setCreatedAt($now);

                    $this->entityManager->persist($postTag);
                }
                else {
                    continue;
                }
            }
        }
        return true;
    }

    private function unlinkImagesFromPost(Post $post) : bool
    {
        $images = $this->entityManager->getRepository(PostImages::class)->findBy(['post' => $post]);
        foreach ($images as $img) {
            $this->entityManager->remove($img);
            // flush only the images
            $this->entityManager->flush($img);
        }
        return true;
    }

    private function unlinkTagsFromPost(Post $post) : bool
    {
        $tags = $this->entityManager->getRepository(PostTags::class)->findBy(['post' => $post]);
        foreach ($tags as $tag) {
            $this->entityManager->remove($tag);
            // flush only the tag
            $this->entityManager->flush($tag);
        }
        return true;
    }

    public function switchStatus(Post $post) : Post
    {
        if ($post->getIsPublished() == true) {
            $post->setIsPublished(false);
        }
        else {
            $post->setIsPublished(true);
        }
        $now = new \DateTime;
        $post->setUpdatedOn($now);

        $this->entityManager->flush();

        return $post;
    }

}