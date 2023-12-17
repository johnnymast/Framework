<?php

namespace App\Framework\Auth\Model;

use App\Model\User;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Table(name: 'passkey_credentials'), Entity(repositoryClass: "App\Framework\Auth\Repository\PasskeyCredentialRepository")]
class PasskeyCredential
{
    #[Id, Column(options: ['unsigned' => true]), GeneratedValue]
    private int $id;

    #[ManyToOne(targetEntity: User::class)]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private User|null $user = null;

    #[Column(type: 'text', unique: true, nullable: false)]
    private string $storage_id;

    #[Column(type: 'text', unique: false, nullable: false)]
    private string $credential;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    public function getStorageId(): string
    {
        return $this->storage_id;
    }

    public function setStorageId(string $storage_id): void
    {
        $this->storage_id = $storage_id;
    }

    public function getCredential(): string
    {
        return $this->credential;
    }

    public function setCredential(string $credential): void
    {
        $this->credential = $credential;
    }
}