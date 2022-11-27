<?php

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Customer;
use App\Tests\Authentication\AuthenticationTest;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class CustomerTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    /**
     * @return void
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface|
     * @throws TransportExceptionInterface
     */
    public function testGetCollection(): void
    {
        // The client implements Symfony HttpClient's `HttpClientInterface`, and the response `ResponseInterface`
        static::createClient()->request('GET', 'api/customers');

        $this->assertResponseStatusCodeSame('401');

        $data = $this->initAuth()->prepareUser()->toArray();
        $response = static::createClient()->request(
            'GET',
            'api/customers',
            [
                'headers' => [
                    'Authorization' => 'Bearer ' .$data['token']
                ]
            ]
        );
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        // Asserts that the returned JSON is a superset of this one
        $this->assertJsonContains([
            '@context' => '/api/contexts/Customer',
            '@id' => '/api/customers',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 13,
        ]);
        // Because test fixtures are automatically loaded between each test, you can assert on them
        $this->assertCount(13, $response->toArray()['hydra:member']);

        // Asserts that the returned JSON is validated by the JSON Schema generated for this resource by API Platform
        // This generated JSON Schema is also used in the OpenAPI spec!
        $this->assertMatchesResourceCollectionJsonSchema(Customer::class);
    }

    /**
     * @return AuthenticationTest
     */
    private function initAuth(): AuthenticationTest
    {
        return new AuthenticationTest();
    }
}
