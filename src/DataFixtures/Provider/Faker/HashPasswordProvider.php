<?php

namespace App\DataFixtures\Provider\Faker;

use App\Entity\Customer;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 *
 */
class HashPasswordProvider
{
    /**
     * @var UserPasswordHasherInterface
     */
    private UserPasswordHasherInterface $hasher;

    /**
     * @param UserPasswordHasherInterface $hasher
     */
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    /**
     * @param string $plainPassword
     * @return string
     */
    public function hashPassword(string $plainPassword): string
    {
        return $this->hasher->hashPassword(new Customer(), $plainPassword);
    }


}