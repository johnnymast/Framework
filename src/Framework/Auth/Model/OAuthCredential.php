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

    #[Column(type: 'text',  unique: true, nullable: false)]
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

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    public function setService(string $service): void
    {
        $this->service = $service;
    }

    public function setOauthToken(string $oauth_token): void
    {
        $this->oauth_token = $oauth_token;
    }

    public function setExpires(\DateTime $expires): void
    {
        $this->expires = $expires;
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function getService(): string
    {
        return $this->service;
    }

    public function getOauthToken(): string
    {
        return $this->oauth_token;
    }

    public function getExpires(): \DateTime
    {
        return $this->expires;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }
}