<?php

namespace App\Tests\Service\EntityServices;

use App\Service\EntityServices\EntityItemService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EntityItemServiceTest extends WebTestCase
{

    /**
     * @dataProvider getOrderCriteriaData
     * @param $initData
     * @param $procData
     */
    public function testGetOrderCriteria($initData, $procData)
    {
        $orderlyFields = ['name', 'price', 'id', 'created'];

        self::bootKernel();

        $container = self::$container->get(EntityItemService::class);

        $this->assertEquals($container->getOrderCriteria($initData, $orderlyFields, '-created'), $procData);
    }

    public function getOrderCriteriaData()
    {
        return [
            [
                'asc_name, desc_price, ASC(id), ASC_created',
                [
                    'name' => 'asc',
                    'price' => 'desc',
                    'id' => 'asc',
                    'created' => 'asc',
                ],
            ],
            [
                'asc_name,desc_price,DESC(id),asc_created',
                [
                    'name' => 'asc',
                    'price' => 'desc',
                    'id' => 'desc',
                    'created' => 'asc',
                ],
            ],
            [
                'asc_name, desc_prrrice, ASC(id), asc_created',
                [
                    'name' => 'asc',
                    'id' => 'asc',
                    'created' => 'asc',
                ],
            ],
            [
                'asc_namedesc_priceASC(id)asc_created',
                [
                    'name' => 'asc',
                    'price' => 'desc',
                    'id' => 'asc',
                    'created' => 'asc',
                ],
            ],
            [
                'asc_name,asc(price),asc_id asc_created',
                [
                    'name' => 'asc',
                    'price' => 'asc',
                    'id' => 'asc',
                    'created' => 'asc',
                ],
            ],
            [
                'DESC(name)DESC(price)ASC(id), asc_created',
                [
                    'name' => 'desc',
                    'price' => 'desc',
                    'id' => 'asc',
                    'created' => 'asc',
                ],
            ],
            [
                'asc_nafme, desc_price, ASC(id), asc__created',
                [
                    'price' => 'desc',
                    'id' => 'asc',
                ],
            ],
            [
                'dfgdfDdsasc_nameghdesc_fggfdesc_price, ASC(id), asc_created',
                [
                    'name' => 'asc',
                    'price' => 'desc',
                    'id' => 'asc',
                    'created' => 'asc',
                ],
            ],
            [
                'asc_id, asc_price, asc_created',
                [
                    'id' => 'asc',
                    'price' => 'asc',
                    'created' => 'asc',
                ],
            ],
            [
                'asc_fid, asc_ddprice, asc_jkcreated',
                [
                    'created' => 'desc',
                ],
            ],
            [
                '',
                [
                    'created' => 'desc',
                ],
            ],
            [
                'asc_id, asc_price, asc_created, asc_imgLinks, desc_imgLinksArr',
                [
                    'id' => 'asc',
                    'price' => 'asc',
                    'created' => 'asc',
                ],
            ],
        ];
    }
}
