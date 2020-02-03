<?php

declare(strict_types=1);

namespace Yoti\Test\Profile\Attribute;

use Yoti\Media\Image;
use Yoti\Media\Image\Jpeg;
use Yoti\Media\Image\Png;
use Yoti\Profile\Attribute\MultiValue;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Profile\Attribute\MultiValue
 */
class MultiValueTest extends TestCase
{
    /**
     * @var \Yoti\Profile\Attribute\MultiValue
     */
    private $multiValue;

    public function setup(): void
    {
        $this->multiValue = new MultiValue([
            'string 1',
            'string 2',
            new Jpeg('image 1'),
            new Png('image 2'),
            [ 'array 1'],
            (object)[ 'object 1'],
            new MultiValue([
                'string 3',
                'string 4',
                new Jpeg('image 1'),
            ]),
            'longer string 5',
        ]);
    }

    /**
     * @covers ::__construct
     * @covers ::allowInstance
     * @covers ::applyFilters
     */
    public function testMultiValueFilterArrayAccess()
    {
        // Allow images
        $this->multiValue->allowInstance(Image::class);

        $this->assertCount(2, $this->multiValue);
        $this->assertInstanceOf(Image::class, $this->multiValue[0]);
        $this->assertEquals('image/jpeg', $this->multiValue[0]->getMimeType());
        $this->assertInstanceOf(Image::class, $this->multiValue[1]);
        $this->assertEquals('image/png', $this->multiValue[1]->getMimeType());

        // Allow MultiValue.
        $this->multiValue->allowInstance(MultiValue::class);

        $this->assertCount(3, $this->multiValue);
        $this->assertInstanceOf(MultiValue::class, $this->multiValue[2]);

        // Check nested image.
        $this->assertCount(1, $this->multiValue[2]);
        $this->assertInstanceOf(Image::class, $this->multiValue[2][0]);
    }

    /**
     * @covers ::allowInstance
     * @covers ::applyFilters
     */
    public function testMultiValueFilterIterator()
    {
        // Allow images
        $this->multiValue->allowInstance(Image::class);

        foreach ($this->multiValue as $item) {
            $this->assertInstanceOf(Image::class, $item);
        }
    }

    /**
     * @covers ::allowInstance
     * @covers ::allowType
     * @covers ::applyFilters
     */
    public function testMultiValueFilterMultipleTypes()
    {
        $this->multiValue
            ->allowInstance(Image::class)
            ->allowType('string');

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
     * @covers ::applyFilters
     */
    public function testMultiValueCustomFilters()
    {
        // Custom filter to allow images.
        $this->multiValue->filter(function ($item) {
            return $item instanceof Image;
        });

        // Custom filter to allow strings.
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
    }

    /**
     * @covers ::immutable
     * @covers ::filter
     * @covers ::applyFilters
     * @covers ::assertMutable
     */
    public function testImmutableFilter()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Attempting to filter immutable array');

        $this->multiValue->immutable()->filter(function ($item) {
            return $item instanceof MultiValue;
        });
    }

    /**
     * @covers ::immutable
     * @covers ::allowType
     * @covers ::applyFilters
     * @covers ::assertMutable
     */
    public function testImmutableAllowType()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Attempting to filter immutable array');

        $this->multiValue->immutable()->allowType('string');
    }

    /**
     * @covers ::immutable
     * @covers ::allowInstance
     * @covers ::applyFilters
     * @covers ::assertMutable
     */
    public function testImmutableAllowInstance()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Attempting to filter immutable array');

        $this->multiValue->immutable()->allowInstance(Image::class);
    }

    /**
     * @covers ::append
     * @covers ::immutable
     * @covers ::applyFilters
     * @covers ::assertMutable
     */
    public function testImmutableAppend()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Attempting to append to immutable array');

        $this->multiValue->append('allowed');
        $this->assertEquals('allowed', $this->multiValue[8]);

        $this->multiValue->immutable()->append('not allowed');
    }

    /**
     * @covers ::exchangeArray
     * @covers ::immutable
     * @covers ::assertMutable
     */
    public function testImmutableExchangeArray()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Attempting to change immutable array');

        $this->multiValue->exchangeArray([]);
        $this->assertEquals([], $this->multiValue->getArrayCopy());

        $this->multiValue->immutable()->exchangeArray([]);
    }
    /**
     * @covers ::offsetSet
     * @covers ::immutable
     * @covers ::assertMutable
     */
    public function testImmutableOffsetSet()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Attempting to add to immutable array');

        $this->multiValue[0] = 'allowed';
        $this->assertEquals('allowed', $this->multiValue[0]);

        $this->multiValue->immutable()[0] = 'not allowed';
    }

    /**
     * @covers ::offsetUnset
     * @covers ::immutable
     * @covers ::assertMutable
     */
    public function testImmutableOffsetUnset()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Attempting to remove from immutable array');

        unset($this->multiValue[0]);
        $this->assertFalse(isset($this->multiValue[0]));

        unset($this->multiValue->immutable()[0]);
    }

    /**
     * @covers ::offsetUnset
     * @covers ::immutable
     * @covers ::assertMutable
     */
    public function testImmutableOffsetUnsetNested()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Attempting to remove from immutable array');

        unset($this->multiValue->immutable()[6][0]);
    }
}
