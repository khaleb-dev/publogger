<?php
/**
 * @link        https://publogger.khaleb.dev
 * @copyright   Copyright (c) 2021 Publogger
 * @license     MIT License    
 */

declare(strict_types=1);

namespace Application\Service;

use Application\CustomObject\Utility;
use Application\Entity\Images;
use Application\Entity\Post;
use Application\Entity\PostGroup;
use Application\Entity\PostImages;
use Application\Entity\PostTags;
use Application\Entity\Tags;
use Application\Entity\User;

class BackendApiManager
{
    private $entityManager;
    
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
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

    public function updateTag($tag, $data)
    {
        $tag->setName($data['name']);
        $tag->setDescription($data['description']);
        $this->entityManager->flush();
        return $tag;
    }

    public function deleteTag($tag)
    {
        $this->entityManager->remove($tag);
        $this->entityManager->flush();
        return true;
    }

    public function createGroup($data)
    {
        $group = new PostGroup();
        $now = new \DateTime;

        $group->setName($data['name']);
        $group->setDescription($data['description']);
        $group->setCreatedAt($now);
        $this->entityManager->persist($group);
        $this->entityManager->flush();
        return $group;
    }

    public function updateGroup($group, $data)
    {
        $group->setName($data['name']);
        $group->setDescription($data['description']);
        $this->entityManager->flush();
        return $group;
    }

    public function deleteGroup($group)
    {
        $this->entityManager->remove($group);
        $this->entityManager->flush();
        return true;
    }

}