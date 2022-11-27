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
        dd($this->prepareUser());
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

        // retrieve a token
        return $client->request('POST', '/authentication_token', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => 'xrodriguez@yahoo.com',
                'password' => 'hello123',
            ],
        ]);
    }
}
