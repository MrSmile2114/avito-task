<?php

namespace App\Tests\Controller;

use App\Controller\ItemsController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ItemsControllerTest extends WebTestCase
{
    /**
     * @dataProvider getTestData
     * @param $page
     * @param $resultsOnPage
     */
    public function testShowItems($page, $resultsOnPage)
    {
        $client = static::createClient();
        $client->request('GET', '/items?page='.$page.'&resultsOnPage='.$resultsOnPage);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function getTestData()
    {
        return [
            ['-1', '-1'],
            ['-100000000', '-10000000'],
            ['100', '100'],
            ['10000000000000000', '1000000000000000'],
            ['0', '0'],
            ['-0', '-0'],
            ['-dfd', 'dfgdfg'],
        ];
    }
}
