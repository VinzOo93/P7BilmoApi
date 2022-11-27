<?php

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Product;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use App\Tests\Authentication\AuthenticationTest;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 *
 */
class ProductTest extends ApiTestCase
{
    use RefreshDatabaseTrait;


    /**
     * @return void
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function testGetCollection(): void
    {
        // The client implements Symfony HttpClient's `HttpClientInterface`, and the response `ResponseInterface`
        static::createClient()->request('GET', 'api/products');

        $this->assertResponseStatusCodeSame('401');

        $data = $this->initAuth()->prepareUser()->toArray();
        $response = static::createClient()->request(
            'GET',
            'api/products',
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
            '@context' => '/api/contexts/Product',
            '@id' => '/api/products',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 16,
        ]);
        // Because test fixtures are automatically loaded between each test, you can assert on them
        $this->assertCount(16, $response->toArray()['hydra:member']);

        // Asserts that the returned JSON is validated by the JSON Schema generated for this resource by API Platform
        // This generated JSON Schema is also used in the OpenAPI spec!
        $this->assertMatchesResourceCollectionJsonSchema(Product::class);
    }

    public function testGetProduct(): void
    {
        // The client implements Symfony HttpClient's `HttpClientInterface`, and the response `ResponseInterface`
        static::createClient()->request('GET', 'api/products/4');

        $this->assertResponseStatusCodeSame('401');
        $data = $this->initAuth()->prepareUser()->toArray();
        static::createClient()->request(
            'GET',
            'api/products/4',
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
        $this->assertMatchesResourceItemJsonSchema(Product::class);
    }

    /**
     * @return AuthenticationTest
     */
    private function initAuth(): AuthenticationTest
    {
        return new AuthenticationTest();
    }

}
