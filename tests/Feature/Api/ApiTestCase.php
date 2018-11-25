<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 23.11.18
 * Time: 11:16
 */

namespace App\Tests\Feature\Api;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;

class ApiTestCase extends WebTestCase
{
    /** @var Client */
    protected static $client;

    /** @var EntityManager */
    protected static $entityManager;

    public static function setUpBeforeClass()
    {
        self::$client = static::createClient([
            'environment' => 'test'
        ]);
        self::$entityManager = self::$client->getContainer()->get('doctrine.orm.entity_manager');
    }

    protected function assertJsonResponse(Response $response)
    {
        $contentType = $response->headers->get("Content-Type");
        return ($contentType === "application/json");
    }

    protected function assertSuccessResponse(Response $response, int $code = 200)
    {
        $this->assertEquals($code, $response->getStatusCode());
    }
}