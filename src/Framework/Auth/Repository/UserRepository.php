<?php

namespace App\src\Framework\Auth\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * @method findOneByEmail(string $email)
 */
final class UserRepository extends EntityRepository
{
    // Silence is golden
}
