<?php

namespace App\Framework\Auth\Factory;

use App\Framework\Auth\Model\OAuthCredential;
use App\Model\User;
use Doctrine\ORM\EntityManager;
use Carbon\Carbon;

final class OAuthCredentialFactory
{
    /**
     * Save a passkey relation for a user.
     *
     * @param string          $token   The token string.
     * @param ?int|null       $expires Timestamp for when the token expires.
     * @param \App\Model\User $user    The user. The user to connect it to.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public static function create(string $token, string $service, int|null $expires, User $user): OAuthCredential
    {
        $em = app()->resolve(EntityManager::class);

        $cred = new OAuthCredential();
        $cred->setUser($user);
        $cred->setService($service);
        $cred->setOauthToken($token);

        if ($expires) {
            $cred->setExpires(Carbon::createFromTimestamp($expires)->toDateTime());
        }
        
        $em->persist($cred);
        $em->flush();

        return $cred;
    }
}
