<?php

namespace App\Framework\Auth\Factory;

use App\Framework\Auth\Mail\EmailVerification;
use App\Framework\Facade\Email;
use App\Model\Ascii;
use App\Model\User;
use Doctrine\ORM\EntityManager;

final class UserFactory
{
    /**
     * Create a new user.
     *
     * @param string $name            The Name of the user.
     * @param string $email           The Email for the user.
     * @param string $password        The password for the user.
     * @param bool   $no_confirmation Extra parameter to disable confirmation (used with oauth).
     *
     * @throws \PHPMailer\PHPMailer\Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return \App\Model\User
     */
    public static function create(
        string $name = '',
        string $email = '',
        string $password = '',
        bool $no_confirmation = false
    ): User {
        $settings = config('auth.user');

        $em = app()->resolve(EntityManager::class);

        /**
         * @var \App\Model\User $user
         */
        $user = new $settings['entity'];
        $user->setName($name);
        $user->setEmail($email);
        $user->setPassword($password);

        if ($settings['require_confirmation'] && $no_confirmation === false) {
            $user->setActivated(false);
            $user->setVerificationToken(self::createVerificationToken());

            $cornfirmationRequest = new EmailVerification($user);

            Email::to($user->getEmail())
                ->send($cornfirmationRequest);
        } else {
            $user->setActivated(true);
        }
        $em->persist($user);
        $em->flush();

        return $user;
    }

    /**
     * Destroy a user and all related entities.
     *
     * @param User $user The user to destroy.
     *
     * @throws \Psr\Container\ContainerExceptionInterface If there is an error resolving the entity manager from the container.
     * @throws \Psr\Container\NotFoundExceptionInterface If the entity manager is not found in the container.
     */
    public static function destroy(User $user): void
    {

        $em = app()->resolve(EntityManager::class);
        $repository = $em->getRepository(Ascii::class);

        $categories = $user->getCategories();
        $tags = $user->getTags();
        $art = $user->getArt();

        foreach($categories as $cat) {
            $repository->deleteLinkedCategoriesWithId($cat->getId());
            $em->remove($cat);
        }

        foreach($tags as $tag) {
            $repository->deleteLinkedTagsWithId($tag->getId());
            $em->remove($tag);
        }

        foreach($art as $ascii) {
            $em->remove($ascii);
        }

        $em->remove($user);
        $em->flush();
    }

    /**
     * Create an activation token.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public static function createVerificationToken(): string
    {
        $settings = config('auth.user');
        $repository = app()->resolve(EntityManager::class)->getRepository($settings['entity']);

        $tokenGeneric = $settings['secret_key'];

        $data = time();
        $token = hash('sha256', $tokenGeneric . $data);

        if ($repository->findOneBy(['verification_token' => $token])) {
            return self::createVerificationToken();
        }

        return $token;
    }

    /**
     * Create a password reset token.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * @return string The generated password reset token.
     */
    public static function createPasswordToken(): string
    {
        $settings = config('auth.user');
        $repository = app()->resolve(EntityManager::class)->getRepository($settings['entity']);

        $tokenGeneric = $settings['secret_key'];

        $data = time();
        $token = hash('sha256', $tokenGeneric . $data);

        if ($repository->findOneBy(['password_token' => $token])) {
            return self::createPasswordToken();
        }

        return $token;
    }
}
