<?php

namespace App\Framework\Auth\Factory;

use App\Framework\Auth\Mail\EmailVerification;
use App\Framework\Auth\Model\PasskeyCredential;
use App\Framework\Facade\Email;
use App\Model\User;
use Doctrine\ORM\EntityManager;

final class PasskeyCredentialFactory
{
    /**
     * Save a passkey relation for a user.
     *
     * @param string          $storage_id The storage id.
     * @param string          $credential The credentials.
     * @param \App\Model\User $user       The user.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return \App\Framework\Auth\Model\PasskeyCredential
     */
    public static function create(string $storage_id, string $credential, User $user): PasskeyCredential
    {
        $em = app()->resolve(EntityManager::class);


        $cred = new PasskeyCredential();
        $cred->setUser($user);
        $cred->setStorageId($storage_id);
        $cred->setCredential($credential);

        $em->persist($cred);
        $em->flush();

        return $cred;
    }
}
