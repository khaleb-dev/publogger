<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PostImages
 *
 * @ORM\Table(name="post_images", indexes={@ORM\Index(name="image_id", columns={"image_id"}), @ORM\Index(name="post_id", columns={"post_id"})})
 * @ORM\Entity(repositoryClass="\Application\Entity\Repository\PostImagesRepository")
 */
class PostImages
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", precision=0, scale=0, nullable=false, unique=false)
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
     * @var \Application\Entity\Post
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Post")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="post_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $post;

    /**
     * @var \Application\Entity\Images
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Images")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="image_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $image;


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
     * @return PostImages
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
     * Set post.
     *
     * @param \Application\Entity\Post|null $post
     *
     * @return PostImages
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

    /**
     * Set image.
     *
     * @param \Application\Entity\Images|null $image
     *
     * @return PostImages
     */
    public function setImage(\Application\Entity\Images $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image.
     *
     * @return \Application\Entity\Images|null
     */
    public function getImage()
    {
        return $this->image;
    }
}
