<?php

namespace App\Tests\Validator;

use App\Validator\ImgLinksValidator;
use App\Validator\ImgLinks;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class ImgLinksValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator()
    {
        return new ImgLinksValidator();
    }

    public function testNullIsValid()
    {
        $this->validator->validate(null, new ImgLinks());
        $this->assertNoViolation();
    }

    public function testEmptyStringIsValid()
    {
        $this->validator->validate('', new ImgLinks());
        $this->assertNoViolation();
    }

    public function testExpectsStringCompatibleType()
    {
        $this->expectException('Symfony\Component\Validator\Exception\UnexpectedValueException');
        $this->validator->validate(new \stdClass(), new ImgLinks());
    }


    public function testExceptInvalidOptionMin()
    {
        $this->expectException('Symfony\Component\Validator\Exception\InvalidOptionsException');
        $this->validator->validate('', new ImgLinks(['min' => -1]));
    }

    public function testExceptInvalidOptionMax()
    {
        $this->expectException('Symfony\Component\Validator\Exception\InvalidOptionsException');
        $this->validator->validate('', new ImgLinks(['max' => -1]));
    }

    public function testExceptInvalidOptionMaxZero()
    {
        $this->expectException('Symfony\Component\Validator\Exception\InvalidOptionsException');
        $this->validator->validate('', new ImgLinks(['max' => 0]));
    }

    /**
     * @dataProvider getValidImgLinks
     * @param $links
     */
    public function testValidImgLinks($links)
    {
        $this->validator->validate($links, new ImgLinks());
        $this->assertNoViolation();
    }

    public function getValidImgLinks()
    {
        return [
            ['http://example.com/123.png,https://example.com/123.png,http://example.com/123.tif'],
            ['https://example.com/123.png'],
            ['http://example.com/fggff/fggf/123.jpg,http://example.com/123.png'],
            ['https://example.com/fggff/fggf/123.png'],
            ['http://example.com/123.tif,http://example.com/123.png'],
            ['http://example.com/123.png,http://example.com/123.tif'],
            ['http://example.com/123.png']
        ];
    }

    /**
     * @dataProvider getInvalidImgLinks
     * @param $links
     * @param $errors_count
     */
    public function testInvalidImgLinks($links, $errors_count)
    {
        $this->validator->validate($links, new ImgLinks());
        $this->assertEquals($errors_count, count($this->context->getViolations()));
    }

    public function getInvalidImgLinks()
    {
        return [
            ['://example.com/123.png,https://example.com/123.png,http://example.com/123.tif', 1],
            ['example.com/123.png', 1],
            ['http://example.com/fggff/fggf/123.jpg,example.com//123.png', 1],
            ['https:/example.com/fggff/fggf/123.png', 1],
            ['http://example.com/123.tif,http://examp,le.com/123.png', 1],
            ['http://example.com/123.png.http://example.com/123.tif', 1],
            ['http:://example.com/123.png,http://example.com/123.png.http://example.com/123.tif', 1],
            ['http://example.com/123.png,https://example.com/123.png, http://example.com/123.tif', 1],
            ['://example.com/123.png,https://example.com/123.png,http://example.com/123.tif', 1],
            ['example.com/fggff/fggf/123.jpg,example.com//123.png', 2],
            ['https:/example/fggff/fggf/123.png', 1],
            ['http://exam,ple.com/123.tif,http://examp,le.com/123.png', 2],
            ['http://example.com/123.png.http://example.com/123.tif.http://example.com/123.tif', 1],
            ['htp:://example.com/123.png,htp://example.com/123.png,tp://example.com/123.tif', 3],
            ['http://example.com/123.png, https://example.com/123.png, http://example.com/123.tif', 2]
        ];
    }

    /**
     * @dataProvider getImgLinksLen
     * @param $links
     * @param $min
     * @param $max
     * @param $errors_count
     */
    public function testImgLinksLen($links, $min, $max, $errors_count)
    {
        $this->validator->validate($links, new ImgLinks(['min' => $min, 'max' => $max]));
        $this->assertEquals($errors_count, count($this->context->getViolations()));
    }

    public function getImgLinksLen()
    {
        return [
            ['http://example.com/123.png', 1, 3, 0],
            ['http://example.com/123.png,http://example.com/123.png,http://example.com/123.png,http://example.com/123.png', 1, 3, 1],
            ['http://example.com/123.png', 2, 3, 1],
            ['http://example.com/123.png', 0, 3, 0],
        ];
    }
}
