<?php

namespace App\Tests\Authentication;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Customer;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use \Symfony\Contracts\HttpClient\ResponseInterface;

/**
 *
 */
class AuthenticationTest extends ApiTestCase
{
    use ReloadDatabaseTrait;

    /**
     * @return void
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function testLogin()
    {
        $json = $this->prepareUser()->toArray();
        $this->assertResponseIsSuccessful();
        $this->assertArrayHasKey('token', $json);
    }

    /**
     * @return ResponseInterface
     * @throws TransportExceptionInterface
     */
    public function prepareUser(): ResponseInterface
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
        return $client->request('POST', '/authentication_token', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => 'test@example.com',
                'password' => '$3CR3T',
            ],
        ]);
    }
}
