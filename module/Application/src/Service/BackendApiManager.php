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

    public function createGroup($data)
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

    public function updateGroup(PostGroup $group, $data)
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

    public function deleteGroup(PostGroup $group)
    {
        $this->entityManager->remove($group);
        $this->entityManager->flush();
        return true;
    }

    public function saveImage(string $filename)
    {
        $image = new Images();
        $now = new \DateTime;

        $image->setName($filename);
        $image->setCreatedAt($now);
        $this->entityManager->persist($image);
        $this->entityManager->flush();
        return $image;
    }

    public function deleteImage(Images $image)
    {
        $this->entityManager->remove($image);
        $this->entityManager->flush();
        return true;
    }

    public function createPost($data)
    {
        

    }
    private function saveDraft($data, $type)
    {
        // if group field was not sent or is an empty string, we will use the system default.
        // The system must have a default group
        if(isset($data['group']) && !is_null($data['group']) && $data['group'] != "")
            $group = $this->entityManager->getRepository(PostGroup::class)->find(intval($data['group']));
        else
            $group = $this->entityManager->getRepository(PostGroup::class)->findOneBy(['isDefault' => true]);

        if ($data['txtTitle'] == "") {
            $data['txtTitle'] = $this->utility->wordCount(html_entity_decode($this->utility->sanitize($data['txtCompose'])),6);
        }

        if (empty($data['txtCustomUrl'])) {
            $data['txtCustomUrl'] = $this->utility->convertStringToSlug($data['txtTitle']);
        }
        else {
            $data['txtCustomUrl'] = $this->utility->convertStringToSlug($data['txtCustomUrl']);
        }

        // Create a DOM object
        $html = new simple_html_dom();
        // Load HTML from a string
        $dhd = $html->load($data['txtCompose']);
        // Iterate to get element from object and arrange as associative array
        $imgArr = [];
        foreach ($dhd->find('img') as $i) {
            $imgArr[] = ['imgSrc' => $i->src];
        }
        // article thumbnail  
        if (empty($imgArr)) {
            $data['thumbnail'] = null;
        } else {
            $fI = $imgArr[0]['imgSrc'];
            $sT = explode("/", $fI);
            $dT = explode("_", end($sT));
            if ($dT[0] == 'coc') {
                $data['thumbnail'] = end($sT);
            } else {
                $data['thumbnail'] = null;
            }
        }

        // check if hdnArticleId has value, if yes, use the value as id to update the article instead of creating new.
        if (!empty($data['hdnArticleId'])) {
            // be sure that this article exists
            $article = $this->entityManager->getRepository(Article::class)->find($data['hdnArticleId']);
            if (!empty($article) && !is_null($article)) {
                // proceed to update the matched article. 
                // NOTE: that the article will be saved as draft!
                $data['txtCompose'] = htmlentities(trim($data['txtCompose']), ENT_QUOTES, 'UTF-8');
                $updated = $this->editorManager->updateArticle($article, $data, $category, $status, $imgArr);
                if ($updated) {
                    $article = $this->entityManager->getRepository(Article::class)->find($data['hdnArticleId']);
                    return new JsonModel([
                                            'code' => 200,
                                            'status' => 'updated',
                                            'message' => 'I\'ve saved your article as Draft by updating the previous copy I found.',
                                            'identity' => $article->getId(),
                                            'slug' => $article->getSlug(),
                                        ]);
                }
                else {
                    return new JsonModel([
                                            'code' => 500,
                                            'status' => 'failed',
                                            'message' => 'I was unable to save your article as Draft.'
                                        ]);
                }
            }
            else {
                return new JsonModel([
                                        'code' => 404,
                                        'status' => 'warning',
                                        'message' => 'I was instructed never to update phantom Articles. They are treats to my existence.'
                                    ]);
            }
        }
        else {
            // this will create an absolutely new article as "Draft"!
            $data['txtCompose'] = htmlentities(trim($data['txtCompose']), ENT_QUOTES, 'UTF-8');
            $created = $this->editorManager->createArticle($data, $category, $type, $this->currentUser(), $status, $imgArr);

            if (!empty($created) && !is_null($created)) {
                return new JsonModel([
                                        'code' => 200,
                                        'status' => 'success',
                                        'message' => 'Your new Article has been saved as a Draft.',
                                        'identity' => $created->getId(),
                                        'slug' => $created->getSlug(),
                                    ]);
            } 
            else {
                return new JsonModel([
                                        'code' => 500,
                                        'status' => 'error',
                                        'message' => 'I am unable to perform this task at the moment. Please, try again later.',
                                    ]);
            }
        }
    }
    /*private function createNewPost($data, $group)
    {

    }*/

}