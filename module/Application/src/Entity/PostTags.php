<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PostTags
 *
 * @ORM\Table(name="post_tags", indexes={@ORM\Index(name="post_id", columns={"post_id"}), @ORM\Index(name="tag_id", columns={"tag_id"})})
 * @ORM\Entity(repositoryClass="\Application\Entity\Repository\PostTagsRepository")
 */
class PostTags
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
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     */
    private $createdAt;

    /**
     * @var \Application\Entity\Tags
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Tags")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tag_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $tag;

    /**
     * @var \Application\Entity\Post
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Post")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="post_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $post;


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
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return PostTags
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set tag.
     *
     * @param \Application\Entity\Tags|null $tag
     *
     * @return PostTags
     */
    public function setTag(\Application\Entity\Tags $tag = null)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag.
     *
     * @return \Application\Entity\Tags|null
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Set post.
     *
     * @param \Application\Entity\Post|null $post
     *
     * @return PostTags
     */
    public function setPost(\Application\Entity\Post $post = null)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post.
     *
     * @return \Application\Entity\Post|null
     */
    public function getPost()
    {
        return $this->post;
    }
}
