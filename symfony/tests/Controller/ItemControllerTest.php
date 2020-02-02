<?php

namespace App\Tests\Controller;

use App\Controller\ItemController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ItemControllerTest extends WebTestCase
{

    /**
     * @dataProvider getInvalidItemId
     * @param $itemId
     * @param $code
     */
    public function testInvalidGetItem($itemId, $code)
    {
        $client = static::createClient();
        $client->request('GET', '/item/'.$itemId);
        $this->assertEquals($code, $client->getResponse()->getStatusCode());
        if (500 === $client->getResponse()->getStatusCode()){
            echo "RESPONSE: \n".$client->getResponse()->getContent()."\n";
        }
    }

    public function getInvalidItemId()
    {
        return [
            [0, 400],
            [-1, 404],
            [9999999999999, 404],
        ];
    }
}
