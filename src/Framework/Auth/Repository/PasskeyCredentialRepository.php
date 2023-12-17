<?php

namespace App\Framework\Auth\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * @method findOneByEmail(string $email)
 */
final class PasskeyCredentialRepository extends EntityRepository
{
    // Silence is golden
}
