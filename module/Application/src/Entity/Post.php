<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Post
 *
 * @ORM\Table(name="post", indexes={@ORM\Index(name="group_id", columns={"group_id"}), @ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity(repositoryClass="\Application\Entity\Repository\PostRepository")
 */
class Post
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_deleted", type="boolean", precision=0, scale=0, nullable=false, unique=false)
     */
    private $isDeleted;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_published", type="boolean", precision=0, scale=0, nullable=false, unique=false)
     */
    private $isPublished;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=300, precision=0, scale=0, nullable=false, unique=false)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="post_title", type="text", length=65535, precision=0, scale=0, nullable=false, unique=false)
     */
    private $postTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="post_body", type="text", length=16777215, precision=0, scale=0, nullable=false, unique=false)
     */
    private $postBody;

    /**
     * @var string|null
     *
     * @ORM\Column(name="thumbnail_url", type="string", length=200, precision=0, scale=0, nullable=true, unique=false)
     */
    private $thumbnailUrl;

    /**
     * @var int
     *
     * @ORM\Column(name="total_views", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $totalViews;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_viewed_on", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     */
    private $lastViewedOn;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="published_on", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     */
    private $publishedOn;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_on", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     */
    private $updatedOn;

    /**
     * @var \Application\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $user;

    /**
     * @var \Application\Entity\PostGroup
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\PostGroup")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="group_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $group;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set isDeleted.
     *
     * @param bool $isDeleted
     *
     * @return Post
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * Get isDeleted.
     *
     * @return bool
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * Set isPublished.
     *
     * @param bool $isPublished
     *
     * @return Post
     */
    public function setIsPublished($isPublished)
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    /**
     * Get isPublished.
     *
     * @return bool
     */
    public function getIsPublished()
    {
        return $this->isPublished;
    }

    /**
     * Set slug.
     *
     * @param string $slug
     *
     * @return Post
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set postTitle.
     *
     * @param string $postTitle
     *
     * @return Post
     */
    public function setPostTitle($postTitle)
    {
        $this->postTitle = $postTitle;

        return $this;
    }

    /**
     * Get postTitle.
     *
     * @return string
     */
    public function getPostTitle()
    {
        return $this->postTitle;
    }

    /**
     * Set postBody.
     *
     * @param string $postBody
     *
     * @return Post
     */
    public function setPostBody($postBody)
    {
        $this->postBody = $postBody;

        return $this;
    }

    /**
     * Get postBody.
     *
     * @return string
     */
    public function getPostBody()
    {
        return $this->postBody;
    }

    /**
     * Set thumbnailUrl.
     *
     * @param string|null $thumbnailUrl
     *
     * @return Post
     */
    public function setThumbnailUrl($thumbnailUrl = null)
    {
        $this->thumbnailUrl = $thumbnailUrl;

        return $this;
    }

    /**
     * Get thumbnailUrl.
     *
     * @return string|null
     */
    public function getThumbnailUrl()
    {
        return $this->thumbnailUrl;
    }

    /**
     * Set totalViews.
     *
     * @param int $totalViews
     *
     * @return Post
     */
    public function setTotalViews($totalViews)
    {
        $this->totalViews = $totalViews;

        return $this;
    }

    /**
     * Get totalViews.
     *
     * @return int
     */
    public function getTotalViews()
    {
        return $this->totalViews;
    }

    /**
     * Set lastViewedOn.
     *
     * @param \DateTime $lastViewedOn
     *
     * @return Post
     */
    public function setLastViewedOn($lastViewedOn)
    {
        $this->lastViewedOn = $lastViewedOn;

        return $this;
    }

    /**
     * Get lastViewedOn.
     *
     * @return \DateTime
     */
    public function getLastViewedOn()
    {
        return $this->lastViewedOn;
    }

    /**
     * Set publishedOn.
     *
     * @param \DateTime $publishedOn
     *
     * @return Post
     */
    public function setPublishedOn($publishedOn)
    {
        $this->publishedOn = $publishedOn;

        return $this;
    }

    /**
     * Get publishedOn.
     *
     * @return \DateTime
     */
    public function getPublishedOn()
    {
        return $this->publishedOn;
    }

    /**
     * Set updatedOn.
     *
     * @param \DateTime $updatedOn
     *
     * @return Post
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;

        return $this;
    }

    /**
     * Get updatedOn.
     *
     * @return \DateTime
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    /**
     * Set user.
     *
     * @param \Application\Entity\User|null $user
     *
     * @return Post
     */
    public function setUser(\Application\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return \Application\Entity\User|null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set group.
     *
     * @param \Application\Entity\PostGroup|null $group
     *
     * @return Post
     */
    public function setGroup(\Application\Entity\PostGroup $group = null)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group.
     *
     * @return \Application\Entity\PostGroup|null
     */
    public function getGroup()
    {
        return $this->group;
    }
}
