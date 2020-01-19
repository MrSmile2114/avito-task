<?php

namespace App\Tests\Controller;

use App\Controller\ItemsController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ItemsControllerTest extends WebTestCase
{
    /**
     * @dataProvider getOrderCriteriaData
     * @param $initData
     * @param $procData
     */
    public function testGetOrderCriteria($initData, $procData)
    {
        $controller = new ItemsController();
        $this->assertEquals($controller->getOrderCriteria($initData), $procData);
    }

    public function getOrderCriteriaData()
    {
        return [
            [
                'asc_name, desc_price, ASC(id), +created',
                [
                    'name' => 'asc',
                    'price' => 'desc',
                    'id' => 'asc',
                    'created' => 'asc',
                ],
            ],
            [
                'asc_name,desc_price,DESC(id),+created',
                [
                    'name' => 'asc',
                    'price' => 'desc',
                    'id' => 'desc',
                    'created' => 'asc',
                ],
            ],
            [
                'asc_name, desc_prrrice, ASC(id), +created',
                [
                    'name' => 'asc',
                    'id' => 'asc',
                    'created' => 'asc',
                ],
            ],
            [
                'asc_namedesc_priceASC(id)+created',
                [
                    'name' => 'asc',
                    'price' => 'desc',
                    'id' => 'asc',
                    'created' => 'asc',
                ],
            ],
            [
                '+name-price+id+created',
                [
                    'name' => 'asc',
                    'price' => 'desc',
                    'id' => 'asc',
                    'created' => 'asc',
                ],
            ],
            [
                'DESC(name)DESC(price)ASC(id), +created',
                [
                    'name' => 'desc',
                    'price' => 'desc',
                    'id' => 'asc',
                    'created' => 'asc',
                ],
            ],
            [
                'asc_nafme, desc_price, ASC(id), +created',
                [
                    'price' => 'desc',
                    'id' => 'asc',
                    'created' => 'asc',
                ],
            ],
            [
                'dfgdfDdsasc_nameghdesc_fggfdesc_price, ASC(id), +created',
                [
                    'name' => 'asc',
                    'price' => 'desc',
                    'id' => 'asc',
                    'created' => 'asc',
                ],
            ],
        ];
    }

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
