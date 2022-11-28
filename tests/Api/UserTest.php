<?php

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use App\Tests\Authentication\AuthenticationTest;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class UserTest extends ApiTestCase
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

    /**
     * @return void
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
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
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testCreateUser(): void
    {
         static::createClient()->request(
             'POST', 'api/users', ['json' => [
            'email' => 'pancho@gmail.com',
            'name' => 'De la vega',
            'firstname' => 'Pancho',
        ]]);

        $this->assertResponseStatusCodeSame('401');

        $data = $this->initAuth()->prepareUser()->toArray();

        $response = static::createClient()->request(
            'POST', 'api/users', [

            'headers' =>[
                'Authorization'=> 'Bearer ' .$data['token']
            ]
                ,'json' => [
            'email' => 'pancho@gmail.com',
            'name' => 'De la vega',
            'firstname' => 'Pancho',
        ]
            ]);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertResponseStatusCodeSame(201);

        $this->assertJsonContains([
            '@context' => '/api/contexts/User',
            '@type' => 'User',
            'email' => 'pancho@gmail.com',
            'name' => 'De la vega',
            'firstname' => 'Pancho',
        ]);
        $this->assertMatchesRegularExpression('~^/api/users/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(User::class);

    }

    /**
     * @return void
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function testDeleteUser()
    {
        $client = static::createClient();
        $iri = $this->findIriBy(User::class,  ['id' => 4]);

        $client->request('DELETE', $iri);

        $this->assertResponseStatusCodeSame('401');

        $data = $this->initAuth()->prepareUser()->toArray();

        $client->request('DELETE', $iri, [
            'headers' => [
                'Authorization'=> 'Bearer ' .$data['token']
            ]
         ]);

        $this->assertNotNull(
            static::getContainer()->get('doctrine')->getRepository(User::class)->findOneBy(['id' => 4])
        );

        $this->assertResponseStatusCodeSame('200');

        $client->request(
            'POST', 'api/users', [

            'headers' =>[
                'Authorization'=> 'Bearer ' .$data['token']
            ]
            ,'json' => [
                'email' => 'pancho@gmail.com',
                'name' => 'De la vega',
                'firstname' => 'Pancho',
            ]
        ]);

        $iri = $this->findIriBy(User::class,  ['id' => 14]);

        $client->request('DELETE', $iri, [
            'headers' => [
                'Authorization'=> 'Bearer ' .$data['token']
            ]
        ]);

        $this->assertResponseStatusCodeSame(200);

        $this->assertNull(
            static::getContainer()->get('doctrine')->getRepository(User::class)->findOneBy(['id' => 14])
        );

    }

    /**
     * @return AuthenticationTest
     */
    private function initAuth(): AuthenticationTest
    {
        return new AuthenticationTest();
    }

}
