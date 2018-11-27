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
use App\Domain\Basket\BasketName;
use App\Domain\Basket\Weight;

class BasketItemsControllerTest extends ApiTestCase
{
    private $url;

    /** @var $basket Basket */
    private $basket;

    public function setUp()
    {
        parent::setUp();

        /** @var $basket Basket */
        $basket = new Basket(
            BasketId::generate(),
            new BasketName('test items'),
            new Weight(100000)
        );

        $basket->addItem('apple', 500);
        $basket->addItem('orange', 500);
        $basket->addItem('watermelon', 500);
        $this->entityManager->persist($basket);
        $this->entityManager->flush($basket);

        $this->basket = $basket;
        $this->url = getenv('BASE_URL') . '/baskets/' . $this->basket->id()->id() . '/items';
    }

    /**
     * @dataProvider itemsProvider
     */
    public function testAddItemsToBasket($items)
    {
        $this->client->request(
            'POST',
            $this->url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['items' => $items])
        );
        $response = $this->client->getResponse();
        var_dump($response->getContent());
        $this->assertSuccessResponse($response);
        $this->assertJsonResponse($response);

        $itemsRaw = $response->getContent();
        $item = json_decode($itemsRaw, true);
        $this->assertArrayHasKey('data', $item);
        $itemData = $item['data'];


        $this->assertArrayHasKey('id', $itemData);
        $this->assertArrayHasKey('name', $itemData);
        $this->assertArrayHasKey('maxCapacity', $itemData);
        $this->assertArrayHasKey('contents', $itemData);
    }

    public function testAddItemsToBasketTooMuchItems()
    {
        $items = [
            [
                'type' => 'apple',
                'weight' => 1000000000
            ],
            [
                'type' => 'orange',
                'weight' => 1000000000
            ],
        ];

        $this->client->request(
            'POST',
            $this->url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['items' => $items])
        );
        $response = $this->client->getResponse();
        $this->assertSuccessResponse($response, 400);
        $this->assertJsonResponse($response);
    }

    public function testAddItemsToBasketIncorrectType()
    {
        $items = [
            [
                'type' => 'test',
                'weight' => 1
            ]
        ];

        $this->client->request(
            'POST',
            $this->url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['items' => $items])
        );
        $response = $this->client->getResponse();
        $this->assertSuccessResponse($response, 400);
        $this->assertJsonResponse($response);
    }

    public function testAddItemsToBasketNegativeWeight()
    {
        $items = [
            [
                'type' => 'apple',
                'weight' => -1
            ]
        ];

        $this->client->request(
            'POST',
            $this->url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['items' => $items])
        );
        $response = $this->client->getResponse();
        $this->assertSuccessResponse($response, 400);
        $this->assertJsonResponse($response);
    }

    /**
     * @dataProvider itemsProvider
     */
    public function testRemoveItemsFromBasket($items)
    {
        $this->client->request(
            'DELETE',
            $this->url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['items' => $items])
        );
        $response = $this->client->getResponse();
        var_dump($response->getContent());
        $this->assertSuccessResponse($response);
        $this->assertJsonResponse($response);

        $itemsRaw = $response->getContent();
        $item = json_decode($itemsRaw, true);
        $this->assertArrayHasKey('data', $item);
        $itemData = $item['data'];


        $this->assertArrayHasKey('id', $itemData);
        $this->assertArrayHasKey('name', $itemData);
        $this->assertArrayHasKey('maxCapacity', $itemData);
        $this->assertArrayHasKey('contents', $itemData);
    }

    public function testRemoveItemsFromBasketTooMuch()
    {

        $items = [
            [
                'type' => 'apple',
                'weight' => 1000000000
            ],
            [
                'type' => 'orange',
                'weight' => 1000000000
            ],
        ];

        $this->client->request(
            'DELETE',
            $this->url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['items' => $items])
        );
        $response = $this->client->getResponse();
        $this->assertSuccessResponse($response, 400);
        $this->assertJsonResponse($response);
    }

    public function itemsProvider(): array
    {
        return [
            [[
                [
                    'type' => 'apple',
                    'weight' => 5.5
                ]
            ]],
            [[
                [
                    'type' => 'apple',
                    'weight' => 10
                ],
                [
                    'type' => 'orange',
                    'weight' => 5
                ],
                [
                    'type' => 'watermelon',
                    'weight' => 4
                ]
            ]],
            [[
                [
                    'type' => 'watermelon',
                    'weight' => 5
                ],
                [
                    'type' => 'watermelon',
                    'weight' => 10.3
                ]
            ]]
        ];
    }
}