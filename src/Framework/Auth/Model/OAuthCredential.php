<?php

namespace App\Framework\Auth\Model;

use App\Framework\Auth\Repository\OAuthCredentialRepository;
use App\Model\User;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\Table;
use Doctrine\Persistence\Event\LifecycleEventArgs;

#[Table(name: 'oauth_credentials'), Entity(repositoryClass: OAuthCredentialRepository::class)]
#[HasLifecycleCallbacks]
class OAuthCredential
{
    #[Id, Column(options: ['unsigned' => true]), GeneratedValue]
    private int $id;

    #[ManyToOne(targetEntity: User::class)]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private User|null $user = null;

    #[Column(type: 'string',  length: 128, unique: false, nullable: false)]
    private string $service;

    #[Column(type: 'text', length: 65535,  unique: false, nullable: false)]
    private string $oauth_token;

    #[Column(name: 'expires', nullable: true)]
    private \DateTime $expires;

    #[Column(name: 'created_at', nullable: false)]
    private \DateTime $createdAt;

    #[Column(name: 'updated_at', nullable: false)]
    private \DateTime $updatedAt;

    /**
     * Pre update/save functions.
     *
     * @param \Doctrine\Persistence\Event\LifecycleEventArgs $args
     *
     * @return void
     */
    #[PrePersist, PreUpdate]
    public function updateTimeStamps(LifecycleEventArgs $args): void
    {
        if (!isset($this->createdAt)) {
            $this->setCreatedAt(new \DateTime());
        }

        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * Sets the ID of the object.
     *
     * @param int $id The ID value to be set.
     *
     * @return void
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Set the user.
     *
     * @param User|null $user The user object. Pass null to remove the user.
     *
     * @return void
     */
    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    /**
     * Sets the service for the object.
     *
     * @param string $service The service to be set.
     *
     * @return void
     */
    public function setService(string $service): void
    {
        $this->service = $service;
    }

    /**
     * Sets the OAuth token.
     *
     * @param string $oauth_token The OAuth token.
     *
     * @return void
     */
    public function setOauthToken(string $oauth_token): void
    {
        $this->oauth_token = $oauth_token;
    }

    /**
     * Sets the "expires" property of the object.
     *
     * @param \DateTime $expires The expiration date and time
     *
     * @return void
     */
    public function setExpires(\DateTime $expires): void
    {
        $this->expires = $expires;
    }

    /**
     * Set the createdAt value.
     *
     * @param \DateTime $createdAt The value to set the createdAt property.
     *
     * @return void
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Sets the updated timestamp for the object.
     *
     * @param \DateTime $updatedAt The timestamp to set as the updated time
     *
     * @return void
     */
    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Gets the ID of the object.
     *
     * @return int The ID of the object.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the user associated with this object.
     *
     * @return User|null The user object if associated, null otherwise.
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Gets the service value.
     *
     * @return string The value of the service.
     */
    public function getService(): string
    {
        return $this->service;
    }

    /**
     * Retrieves the OAuth token.
     *
     * @return string The OAuth token.
     */
    public function getOauthToken(): string
    {
        return $this->oauth_token;
    }

    /**
     * Get the expiration date and time of an object.
     *
     * @return \DateTime The expiration date and time of an object.
     */
    public function getExpires(): \DateTime
    {
        return $this->expires;
    }

    /**
     * Get the creation date and time of the object.
     *
     * @return \DateTime The creation date and time.
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * Retrieves the last updated date and time of the object.
     *
     * @return \DateTime The last updated date and time.
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }
}