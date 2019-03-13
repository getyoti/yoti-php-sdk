<?php
namespace YotiTest\Entity;

use YotiTest\TestCase;
use Yoti\Entity\MultiValue;
use Yoti\Entity\Image;

/**
 * @coversDefaultClass \Yoti\Entity\MultiValue
 */
class MultiValueTest extends TestCase
{
    /**
     * @var \Yoti\Entity\MultiValue
     */
    private $multiValue;

    public function setup()
    {
        $this->multiValue = new MultiValue([
            'string 1',
            'string 2',
            new Image('image 1', 'jpeg'),
            new Image('image 2', 'png'),
            [ 'array 1'],
            (object)[ 'object 1'],
            new MultiValue([
                'string 3',
                'string 4',
                new Image('image 1', 'jpeg'),
            ]),
            'longer string 5',
        ]);
    }

    /**
     * @covers ::filterInstance
     */
    public function testMultiValueFilterArrayAccess()
    {
        // Allow images
        $this->multiValue->filterInstance(Image::class);

        $this->assertCount(2, $this->multiValue);
        $this->assertInstanceOf(Image::class, $this->multiValue[0]);
        $this->assertEquals('image/jpeg', $this->multiValue[0]->getMimeType());
        $this->assertInstanceOf(Image::class, $this->multiValue[1]);
        $this->assertEquals('image/png', $this->multiValue[1]->getMimeType());

        // Allow MultiValue.
        $this->multiValue->filterInstance(MultiValue::class);

        $this->assertCount(3, $this->multiValue);
        $this->assertInstanceOf(MultiValue::class, $this->multiValue[2]);

        // Check nested image.
        $this->assertCount(1, $this->multiValue[2]);
        $this->assertInstanceOf(Image::class, $this->multiValue[2][0]);
    }

    /**
     * @covers ::filterInstance
     */
    public function testMultiValueFilterIterator()
    {
        // Allow images
        $this->multiValue->filterInstance(Image::class);

        foreach ($this->multiValue as $item) {
            $this->assertInstanceOf(Image::class, $item);
        }
    }

    /**
     * @covers ::filterType
     * @covers ::filterInstance
     */
    public function testMultiValueFilterMultipleTypes()
    {
        // Allow images and strings;
        $this->multiValue
            ->filterInstance(Image::class)
            ->filterType('string');

        $this->assertCount(5, $this->multiValue);
        $this->assertEquals('string 1', $this->multiValue[0]);
        $this->assertEquals('string 2', $this->multiValue[1]);
        $this->assertInstanceOf(Image::class, $this->multiValue[2]);
        $this->assertEquals('image/jpeg', $this->multiValue[2]->getMimeType());
        $this->assertInstanceOf(Image::class, $this->multiValue[3]);
        $this->assertEquals('image/png', $this->multiValue[3]->getMimeType());
    }

    /**
     * @covers ::filter
     */
    public function testMultiValueCustomFilters()
    {
        // Allow images
        $this->multiValue->filter(function ($item) {
            return $item instanceof Image;
        });

        // Allow strings
        $this->multiValue->filter(function ($item) {
            return gettype($item) === 'string';
        });

        $this->assertCount(5, $this->multiValue);
        $this->assertEquals('string 1', $this->multiValue[0]);
        $this->assertEquals('string 2', $this->multiValue[1]);
        $this->assertInstanceOf(Image::class, $this->multiValue[2]);
        $this->assertEquals('image/jpeg', $this->multiValue[2]->getMimeType());
        $this->assertInstanceOf(Image::class, $this->multiValue[3]);
        $this->assertEquals('image/png', $this->multiValue[3]->getMimeType());

        // Filter long strings.
        $this->multiValue->resetFilters();
        $this->multiValue->filter(function ($item) {
            return gettype($item) === 'string' && strlen($item) > 8;
        });
        $this->assertCount(1, $this->multiValue);
        $this->assertEquals('longer string 5', $this->multiValue[0]);
    }

    /**
     * @covers ::resetFilters
     */
    public function testMultiValueResetFilters()
    {
        $this->assertCount(8, $this->multiValue);

        // Allow images
        $this->multiValue->filter(function ($item) {
            return $item instanceof Image;
        });

        // Allow strings
        $this->multiValue->filter(function ($item) {
            return gettype($item) === 'string';
        });

        $this->assertCount(5, $this->multiValue);

        // Reset filters.
        $this->multiValue->resetFilters();
        $this->assertCount(8, $this->multiValue);

        // Allow MultiValue.
        $this->multiValue->filter(function ($item) {
            return $item instanceof MultiValue;
        });

        // Allow images
        $this->multiValue->filter(function ($item) {
            return $item instanceof Image;
        });

        $this->assertInstanceOf(MultiValue::class, $this->multiValue[2]);
        $this->assertCount(1, $this->multiValue[2]);

        // Check nested image.
        $this->assertCount(1, $this->multiValue[2]);
        $this->assertInstanceOf(Image::class, $this->multiValue[2][0]);

        // Reset filters.
        $this->multiValue->resetFilters();

        // Check nested image.
        $this->assertCount(3, $this->multiValue[6]);
        $this->assertInstanceOf(Image::class, $this->multiValue[6][2]);
    }
}
