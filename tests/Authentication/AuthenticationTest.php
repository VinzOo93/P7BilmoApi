<?php

namespace App\Tests\Authentication;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Customer;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class AuthenticationTest extends ApiTestCase
{
    use ReloadDatabaseTrait;

    public function testLogin(): void
    {
        $client = self::createClient();
        $container = self::getContainer();

        $customer = new Customer();
        $customer->setEmail('test@example.com');
        $customer->setUsername('test');
        $customer->setCompany('test');
        $customer->setAddress('test');
        $customer->setCity('test');
        $customer->setPhoneNumber('0606060666');
        $customer->setCountry('France');
        $customer->setPostalCode('67000');
        $customer->setPassword(
            $container->get('security.user_password_hasher')->hashPassword($customer, '$3CR3T')
        );

        $manager = $container->get('doctrine')->getManager();
        $manager->persist($customer);
        $manager->flush();

        // retrieve a token
        $response = $client->request('POST', '/authentication_token', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => 'test@example.com',
                'password' => '$3CR3T',
            ],
        ]);

        $json = $response->toArray();
        $this->assertResponseIsSuccessful();
        $this->assertArrayHasKey('token', $json);

    }
}
