<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 23.11.18
 * Time: 11:16
 */

namespace App\Tests\Feature\Api;

use App\Domain\Basket\Basket;
use App\Domain\Basket\BasketId;
use Symfony\Component\HttpFoundation\Response;

class BasketControllerTest extends ApiTestCase
{
    private $endpoint;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        $this->endpoint = getenv('BASE_URL') . '/baskets';

        parent::__construct($name, $data, $dataName);
    }

    public function testGetBaskets()
    {
        $url = $this->endpoint;
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();

        $this->assertSuccessResponse($response);
        $this->assertJsonResponse($response);

        $basketsRaw = $response->getContent();
        $baskets = json_decode($basketsRaw, true);

        $this->assertArrayHasKey('data', $baskets);
        $basket = current($baskets['data']);


        $this->assertArrayHasKey('id', $basket);
        $this->assertArrayHasKey('name', $basket);
        $this->assertArrayHasKey('maxCapacity', $basket);
        $this->assertArrayHasKey('contents', $basket);
        return $basket['id'];
    }

    /**
     * @depends testGetBaskets
     */
    public function testGetBasket($basketId)
    {
        $url = $this->endpoint . "/$basketId";
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertSuccessResponse($response);
        $this->assertJsonResponse($response);
        $basketRaw = $response->getContent();
        $basket = json_decode($basketRaw, true);

        $this->assertArrayHasKey('data', $basket);

        $basketData = $basket['data'];
        $this->assertArrayHasKey('id', $basketData);
        $this->assertArrayHasKey('name', $basketData);
        $this->assertArrayHasKey('maxCapacity', $basketData);
        $this->assertArrayHasKey('contents', $basketData);

        /** @var $dbBasket Basket */
        $dbBasket = $this->entityManager->find(
            Basket::class,
            BasketId::fromString($basketId)
        );

        $this->assertEquals($dbBasket->id()->id(), $basketId);
        $this->assertEquals($dbBasket->name()->name(), $basketData['name']);
        $this->assertEquals($dbBasket->maxCapacity()->weight(), $basketData['maxCapacity']);
    }

    /**
     * @dataProvider incorrectNamesProvider
     * @depends      testGetBaskets
     * Name can't be less @see BasketName::BASKET_NAME_MIN_LENGTH
     * and bigger than @see BasketName::BASKET_NAME_MAX_LENGTH symbols .
     */
    public function testRenameBasketIncorrectName($newName, $basketId)
    {
        $url = $this->endpoint . "/$basketId";
        $this->client->request(
            'PUT',
            $url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => $newName])
        );
        $response = $this->client->getResponse();

        $this->assertSuccessResponse($response, 400);
        $this->assertJsonResponse($response);

        /** @var $dbBasket Basket */
        $dbBasket = $this->entityManager->find(
            Basket::class,
            BasketId::fromString($basketId)
        );

        $this->assertNotEquals($dbBasket->name()->name(), $newName);
    }


    /**
     * @dataProvider correctNamesProvider
     * @depends      testGetBaskets
     * Name can't be less @see BasketName::BASKET_NAME_MIN_LENGTH
     * and bigger than @see BasketName::BASKET_NAME_MAX_LENGTH symbols .
     */
    public function testRenameCannotChangeCapacity($newName, $basketId)
    {
        $url = $this->endpoint . "/$basketId";
        $this->client->request(
            'PUT',
            $url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => $newName, 'maxCapacity' => 50])
        );
        $response = $this->client->getResponse();

        $this->assertSuccessResponse($response, 400);
        $this->assertJsonResponse($response);

        /** @var $dbBasket Basket */
        $dbBasket = $this->entityManager->find(
            Basket::class,
            BasketId::fromString($basketId)
        );

        $this->assertNotEquals($dbBasket->name()->name(), $newName);
    }

    /**
     * @dataProvider correctNamesProvider
     * @depends      testGetBaskets
     */
    public function testRenameBasket($newName, $basketId)
    {
        $url = $this->endpoint . "/$basketId";
        $this->client->request(
            'PUT',
            $url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => $newName])
        );
        $response = $this->client->getResponse();

        $this->assertSuccessResponse($response);
        $this->assertJsonResponse($response);

        $basketRaw = $response->getContent();
        $basket = json_decode($basketRaw, true);

        $this->assertEquals($basket['data']['id'], $basketId);
        $this->assertEquals($basket['data']['name'], $newName);
        /** @var $dbBasket Basket */
        $dbBasket = $this->entityManager->find(
            Basket::class,
            BasketId::fromString($basketId)
        );
        $this->assertEquals($dbBasket->id()->id(), $basketId);
        $this->assertEquals($dbBasket->name()->name(), $newName);
    }

    /**
     * @depends testGetBaskets
     */
    public function testRemoveBasket($basketId)
    {
        $url = $this->endpoint . "/$basketId";
        $this->client->request(
            'DELETE',
            $url
        );
        $response = $this->client->getResponse();

        $this->assertSuccessResponse($response, Response::HTTP_NO_CONTENT);

        /** @var $dbBasket Basket */
        $dbBasket = $this->entityManager->find(
            Basket::class,
            BasketId::fromString($basketId)
        );
        $this->assertEquals($dbBasket, null);

        return $basketId;
    }

    /**
     * @depends testRemoveBasket
     */
    public function testGetBasketNotFound($basketId)
    {
        $url = $this->endpoint . "/$basketId";
        $this->client->request(
            'GET',
            $url
        );
        $response = $this->client->getResponse();

        $this->assertSuccessResponse($response, Response::HTTP_NOT_FOUND);
        $this->assertJsonResponse($response);
    }

    /**
     * @dataProvider basketProvider
     */
    public function testAddBasket($name, $capacity)
    {
        $url = $this->endpoint;
        $this->client->request(
            'POST',
            $url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'name' => $name,
                'maxCapacity' => $capacity,
            ])
        );
        $response = $this->client->getResponse();

        $this->assertSuccessResponse($response, Response::HTTP_CREATED);
        $this->assertJsonResponse($response);

        $basketRaw = $response->getContent();
        $basket = json_decode($basketRaw, true);

        $basketId = $basket['data']['id'];
        $this->assertEquals($basket['data']['name'], $name);
        $this->assertEquals($basket['data']['maxCapacity'], $capacity);

        /** @var $dbBasket Basket */
        $dbBasket = $this->entityManager->find(
            Basket::class,
            BasketId::fromString($basketId)
        );
        $this->assertEquals($dbBasket->name()->name(), $name);
        $this->assertEquals($dbBasket->maxCapacity()->weight(), $capacity);
    }

    public function basketProvider(): array
    {
        return [
            ['testtest', 100],
            ['New test name', 150],
            ['тест', 230.56],
            ['12345', 110]
        ];
    }

    public function correctNamesProvider(): array
    {
        return [
            ['testtest'],
            ['New test name'],
            ['тест'],
            ['12345']
        ];
    }

    public function incorrectNamesProvider(): array
    {
        return [
            [''],
            ['aa'],
            ['aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'],
        ];
    }
}