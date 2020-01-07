<?php

namespace App\Tests\Form;

use App\Entity\Item;
use App\Form\ItemType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

class ItemTypeTest extends TypeTestCase
{
    protected function getExtensions()
    {
        $validator = Validation::createValidator();

        // or if you also need to read constraints from annotations
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();

        return [
            new ValidatorExtension($validator),
        ];
    }

    /**
     * @dataProvider getValidData
     * @param $name
     * @param $description
     * @param $imgLinks
     * @param $price
     */
    public function testSubmitValidData($name, $description, $imgLinks, $price)
    {
        $formData = [
            'name'          => $name,
            'description'   => $description,
            'imgLinks'      => $imgLinks,
            'price'         => $price
        ];

        $objectToCompare = new Item();
        // $objectToCompare will retrieve data from the form submission; pass it as the second argument
        $form = $this->factory->create(ItemType::class, $objectToCompare);

        $object = new Item();
        $object->setName($formData['name']);
        $object->setDescription($formData['description']);
        $object->setImgLinksArr(explode(',', $formData['imgLinks']));
        $object->setPrice($formData['price']);
        $object->setCreated($objectToCompare->getCreated());

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        // check that $objectToCompare was modified as expected when the form was submitted
        $this->assertEquals($object, $objectToCompare);

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

    public function getValidData(){
        return [
            [   'name'          => 'testName',
                'description'   => 'testDescription',
                'imgLinks'      => 'http://example.com/123.png,https://example.com/123.png,http://example.com/123.tif',
                'price'         => 10000000 ],
            [   'name'          => '321testName123312',
                'description'   => 'testDescriptionErndfgdgdg',
                'imgLinks'      => 'http://example.com/123.png',
                'price'         => 10000000 ],
            [   'name'          => 'testName12312123123123',
                'description'   => str_repeat('testDescription12345',25),
                'imgLinks'      => 'http://example.com/123.png,https://example.com/123.png,http://example.com/123.tif,http://example.com/123.tif,http://example.com/123.tif',
                'price'         => 10000000 ],
            [   'name'          => 'testName',
                'description'   => 'testDescription',
                'imgLinks'      => 'http://example.com/123.png,https://example.com/123.png,http://example.com/123.tif',
                'price'         => 10000000000 ],
        ];
    }
}
