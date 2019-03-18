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
     * @covers ::allowInstance
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
     * @covers ::filterType
     * @covers ::allowInstance
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
     *
     * @expectedException \LogicException
     * @expectedExceptionMessage Attempting to filter immutable array
     */
    public function testImmutableFilter()
    {
        $this->multiValue->immutable()->filter(function ($item) {
            return $item instanceof MultiValue;
        });
    }

    /**
     * @covers ::immutable
     * @covers ::allowType
     *
     * @expectedException \LogicException
     * @expectedExceptionMessage Attempting to filter immutable array
     */
    public function testImmutableAllowType()
    {
        $this->multiValue->immutable()->allowType('string');
    }

    /**
     * @covers ::immutable
     * @covers ::allowInstance
     *
     * @expectedException \LogicException
     * @expectedExceptionMessage Attempting to filter immutable array
     */
    public function testImmutableAllowInstance()
    {
        $this->multiValue->immutable()->allowInstance(Image::class);
    }

    /**
     * @covers ::append
     * @covers ::immutable
     *
     * @expectedException \LogicException
     * @expectedExceptionMessage Attempting to append to immutable array
     */
    public function testImmutableAppend()
    {
        $this->multiValue->append('allowed');
        $this->assertEquals('allowed', $this->multiValue[8]);

        $this->multiValue->immutable()->append('not allowed');
    }

    /**
     * @covers ::exchangeArray
     * @covers ::immutable
     *
     * @expectedException \LogicException
     * @expectedExceptionMessage Attempting to change immutable array
     */
    public function testImmutableExchangeArray()
    {
        $this->multiValue->exchangeArray([]);
        $this->assertEquals([], $this->multiValue->getArrayCopy());

        $this->multiValue->immutable()->exchangeArray([]);
    }
    /**
     * @covers ::offsetSet
     * @covers ::immutable
     *
     * @expectedException \LogicException
     * @expectedExceptionMessage Attempting to add to immutable array
     */
    public function testImmutableOffsetSet()
    {
        $this->multiValue[0] = 'allowed';
        $this->assertEquals('allowed', $this->multiValue[0]);

        $this->multiValue->immutable()[0] = 'not allowed';
    }

    /**
     * @covers ::offsetUnset
     * @covers ::immutable
     *
     * @expectedException \LogicException
     * @expectedExceptionMessage Attempting to remove from immutable array
     */
    public function testImmutableOffsetUnset()
    {
        unset($this->multiValue[0]);
        $this->assertFalse(isset($this->multiValue[0]));

        unset($this->multiValue->immutable()[0]);
    }

    /**
     * @covers ::offsetUnset
     * @covers ::immutable
     *
     * @expectedException \LogicException
     * @expectedExceptionMessage Attempting to remove from immutable array
     */
    public function testImmutableOffsetUnsetNested()
    {
        unset($this->multiValue->immutable()[6][0]);
    }
}
