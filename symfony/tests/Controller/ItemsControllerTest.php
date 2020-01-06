<?php

namespace App\Tests\Controller;

use App\Controller\ItemsController;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ItemsControllerTest extends WebTestCase
{
    public function testGetItems()
    {
        $client = static::createClient();
        $client->request('GET', '/items');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }


}
