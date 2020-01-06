<?php

namespace App\Tests\Controller;

use App\Controller\ItemController;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ItemControllerTest extends WebTestCase
{
    public function testGetItem()
    {
        $client = static::createClient();
        $client->request('GET', '/item/6');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
