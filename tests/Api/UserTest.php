<?php

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use App\Tests\Authentication\AuthenticationTest;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class UserTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    public function testGetCollection(): void
    {
        static::createClient()->request('GET', 'api/users');

        $this->assertResponseStatusCodeSame('401');

        $data = $this->initAuth()->prepareUser()->toArray();

        $response = static::createClient()->request(
            'GET',
            'api/users',
            [
                'headers' => [
                    'Authorization' => 'Bearer ' .$data['token']
                ]
            ]
        );

        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/api/contexts/User',
            '@id' => '/api/users',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 1,
        ]);

        $this->assertCount(1, $response->toArray()['hydra:member']);

        $this->assertMatchesResourceCollectionJsonSchema(User::class);

    }

    public function testGetUser(): void
    {
        // The client implements Symfony HttpClient's `HttpClientInterface`, and the response `ResponseInterface`
        static::createClient()->request('GET', 'api/users/4');

        $this->assertResponseStatusCodeSame('401');
        $data = $this->initAuth()->prepareUser()->toArray();
        static::createClient()->request(
            'GET',
            'api/users/4',
            [
                'headers' => [
                    'Authorization' => 'Bearer ' .$data['token']
                ]
            ]
        );
        $this->assertResponseStatusCodeSame('404');

        static::createClient()->request(
            'GET',
            'api/users/8',
            [
                'headers' => [
                    'Authorization' => 'Bearer ' .$data['token']
                ]
            ]
        );

        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        // Asserts that the returned JSON is validated by the JSON Schema generated for this resource by API Platform
        // This generated JSON Schema is also used in the OpenAPI spec!
        $this->assertMatchesResourceItemJsonSchema(User::class);
    }

    /**
     * @return AuthenticationTest
     */
    private function initAuth(): AuthenticationTest
    {
        return new AuthenticationTest();
    }


}
