<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="\Application\Entity\Repository\UserRepository")
 */
class User
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
     * @var string
     *
     * @ORM\Column(name="full_name", type="string", length=200, precision=0, scale=0, nullable=false, unique=false)
     */
    private $fullName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="phone_no", type="string", length=15, precision=0, scale=0, nullable=true, unique=false)
     */
    private $phoneNo;

    /**
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=100, precision=0, scale=0, nullable=true, unique=false)
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(name="profile_photo", type="string", length=200, precision=0, scale=0, nullable=true, unique=false)
     */
    private $profilePhoto;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=20, precision=0, scale=0, nullable=false, unique=false)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=225, precision=0, scale=0, nullable=false, unique=false)
     */
    private $password;

    /**
     * @var string|null
     *
     * @ORM\Column(name="pwd_reset_token", type="string", length=256, precision=0, scale=0, nullable=true, unique=false)
     */
    private $pwdResetToken;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="pwd_reset_token_created_at", type="datetime", precision=0, scale=0, nullable=true, unique=false)
     */
    private $pwdResetTokenCreatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="api_key", type="string", length=50, precision=0, scale=0, nullable=false, unique=false)
     */
    private $apiKey;

    /**
     * @var string
     *
     * @ORM\Column(name="api_secret", type="string", length=50, precision=0, scale=0, nullable=false, unique=false)
     */
    private $apiSecret;

    /**
     * @var bool
     *
     * @ORM\Column(name="logged_in", type="boolean", precision=0, scale=0, nullable=false, unique=false)
     */
    private $loggedIn;

    /**
     * @var string
     *
     * @ORM\Column(name="auth_token", type="string", length=50, precision=0, scale=0, nullable=false, unique=false)
     */
    private $authToken;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="auth_token_created_at", type="datetime", precision=0, scale=0, nullable=true, unique=false)
     */
    private $authTokenCreatedAt;

    /**
     * @var bool
     *
     * @ORM\Column(name="user_active", type="boolean", precision=0, scale=0, nullable=false, options={"default"="1"}, unique=false)
     */
    private $userActive = true;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     */
    private $updatedAt;


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
     * Set fullName.
     *
     * @param string $fullName
     *
     * @return User
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Get fullName.
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Set phoneNo.
     *
     * @param string|null $phoneNo
     *
     * @return User
     */
    public function setPhoneNo($phoneNo = null)
    {
        $this->phoneNo = $phoneNo;

        return $this;
    }

    /**
     * Get phoneNo.
     *
     * @return string|null
     */
    public function getPhoneNo()
    {
        return $this->phoneNo;
    }

    /**
     * Set email.
     *
     * @param string|null $email
     *
     * @return User
     */
    public function setEmail($email = null)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set profilePhoto.
     *
     * @param string|null $profilePhoto
     *
     * @return User
     */
    public function setProfilePhoto($profilePhoto = null)
    {
        $this->profilePhoto = $profilePhoto;

        return $this;
    }

    /**
     * Get profilePhoto.
     *
     * @return string|null
     */
    public function getProfilePhoto()
    {
        return $this->profilePhoto;
    }

    /**
     * Set username.
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password.
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set pwdResetToken.
     *
     * @param string|null $pwdResetToken
     *
     * @return User
     */
    public function setPwdResetToken($pwdResetToken = null)
    {
        $this->pwdResetToken = $pwdResetToken;

        return $this;
    }

    /**
     * Get pwdResetToken.
     *
     * @return string|null
     */
    public function getPwdResetToken()
    {
        return $this->pwdResetToken;
    }

    /**
     * Set pwdResetTokenCreatedAt.
     *
     * @param \DateTime|null $pwdResetTokenCreatedAt
     *
     * @return User
     */
    public function setPwdResetTokenCreatedAt($pwdResetTokenCreatedAt = null)
    {
        $this->pwdResetTokenCreatedAt = $pwdResetTokenCreatedAt;

        return $this;
    }

    /**
     * Get pwdResetTokenCreatedAt.
     *
     * @return \DateTime|null
     */
    public function getPwdResetTokenCreatedAt()
    {
        return $this->pwdResetTokenCreatedAt;
    }

    /**
     * Set apiKey.
     *
     * @param string $apiKey
     *
     * @return User
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Get apiKey.
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Set apiSecret.
     *
     * @param string $apiSecret
     *
     * @return User
     */
    public function setApiSecret($apiSecret)
    {
        $this->apiSecret = $apiSecret;

        return $this;
    }

    /**
     * Get apiSecret.
     *
     * @return string
     */
    public function getApiSecret()
    {
        return $this->apiSecret;
    }

    /**
     * Set loggedIn.
     *
     * @param bool $loggedIn
     *
     * @return User
     */
    public function setLoggedIn($loggedIn)
    {
        $this->loggedIn = $loggedIn;

        return $this;
    }

    /**
     * Get loggedIn.
     *
     * @return bool
     */
    public function getLoggedIn()
    {
        return $this->loggedIn;
    }

    /**
     * Set authToken.
     *
     * @param string $authToken
     *
     * @return User
     */
    public function setAuthToken($authToken)
    {
        $this->authToken = $authToken;

        return $this;
    }

    /**
     * Get authToken.
     *
     * @return string
     */
    public function getAuthToken()
    {
        return $this->authToken;
    }

    /**
     * Set authTokenCreatedAt.
     *
     * @param \DateTime|null $authTokenCreatedAt
     *
     * @return User
     */
    public function setAuthTokenCreatedAt($authTokenCreatedAt = null)
    {
        $this->authTokenCreatedAt = $authTokenCreatedAt;

        return $this;
    }

    /**
     * Get authTokenCreatedAt.
     *
     * @return \DateTime|null
     */
    public function getAuthTokenCreatedAt()
    {
        return $this->authTokenCreatedAt;
    }

    /**
     * Set userActive.
     *
     * @param bool $userActive
     *
     * @return User
     */
    public function setUserActive($userActive)
    {
        $this->userActive = $userActive;

        return $this;
    }

    /**
     * Get userActive.
     *
     * @return bool
     */
    public function getUserActive()
    {
        return $this->userActive;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return User
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
     * Set updatedAt.
     *
     * @param \DateTime $updatedAt
     *
     * @return User
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
