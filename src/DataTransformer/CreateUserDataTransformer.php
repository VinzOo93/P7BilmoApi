<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUserDataTransformer implements DataTransformerInterface
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher )
    {
        $this->hasher = $hasher;
    }

    public function transform($object, string $to, array $context = []): User
    {
        $user = new User();
        $user->setEmail($object->email);
        $user->setPassword($this->hasher->hashPassword($user, $object->password));
        $user->setUsername($object->username);

        return $user;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof User) {
            return false;
        }
        return  User::class === $to && null !== ($context['input']['class'] ?? null);
    }
}