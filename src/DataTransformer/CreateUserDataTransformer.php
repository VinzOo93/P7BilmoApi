<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;

/**
 *
 */
class CreateUserDataTransformer implements DataTransformerInterface
{

    /**
     * @var Security
     */
    private Security $security;

    /**
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @param $object
     * @param string $to
     * @param array $context
     * @return User
     */
    public function transform($object, string $to, array $context = []): User
    {
        $user = new User();
        $user->setEmail($object->email);
        $user->setName($object->name);
        $user->setFirstname($object->firstname);
        $user->setCustomer($this->security->getUser());

        return $user;
    }

    /**
     * @param $data
     * @param string $to
     * @param array $context
     * @return bool
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof User) {
            return false;
        }
        return  User::class === $to && null !== ($context['input']['class'] ?? null);
    }
}