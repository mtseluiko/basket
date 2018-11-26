<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 23.11.18
 * Time: 11:16
 */

namespace App\Tests\Feature\Api\Basket;

use App\Domain\Basket\Basket;
use App\Domain\Basket\BasketId;
use App\Tests\Feature\Api\ApiTestCase;

class BasketControllerTest extends ApiTestCase
{
    private $endpoint;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        $this->endpoint = getenv('BASE_URL').'/baskets';

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
        $url = $this->endpoint."/$basketId";
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
     * @depends testGetBaskets
     * Name can't be less @see BasketName::BASKET_NAME_MIN_LENGTH
     * and bigger than @see BasketName::BASKET_NAME_MAX_LENGTH symbols .
     */
    public function testRenameBasketIncorrectName($newName, $basketId)
    {
        $url = $this->endpoint."/$basketId";
        $this->client->request(
            'PUT',
            $url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name'=>$newName])
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
     * @depends testGetBaskets
     * Name can't be less @see BasketName::BASKET_NAME_MIN_LENGTH
     * and bigger than @see BasketName::BASKET_NAME_MAX_LENGTH symbols .
     */
    public function testRenameCannotChangeCapacity($newName, $basketId)
    {
        $url = $this->endpoint."/$basketId";
        $this->client->request(
            'PUT',
            $url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name'=>$newName, 'maxCapacity'=>50])
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
     * @depends testGetBaskets
     */
    public function testRenameBasket($newName, $basketId)
    {
        $url = $this->endpoint."/$basketId";
        $this->client->request(
            'PUT',
            $url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name'=>$newName])
        );
        $response = $this->client->getResponse();

        $this->assertSuccessResponse($response);
        $this->assertJsonResponse($response);


        $basketRaw = $response->getContent();
        $basket = json_decode($basketRaw, true);

        $this->assertTrue(isset($basket['data']));

        $basketData = $basket['data'];

        $this->assertEquals($basketData['id'], $basketId);
        $this->assertEquals($basketData['name'], $newName);
        /** @var $dbBasket Basket */
        $dbBasket = $this->entityManager->find(
            Basket::class,
            BasketId::fromString($basketId)
        );
        $this->assertEquals($dbBasket->id()->id(), $basketId);
        $this->assertEquals($dbBasket->name()->name(), $newName);
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