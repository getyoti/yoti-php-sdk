<?php

namespace YotiTest\Util;

use Yoti\Util\Validation;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \Yoti\Util\Validation
 */
class ValidationTest extends TestCase
{
    const SOME_NAME = 'some_name';
    const SOME_STRING = 'some string';

    /**
     * @covers ::isString
     */
    public function testIsString()
    {
        $this->assertNull(Validation::isString(self::SOME_STRING, self::SOME_NAME));
    }

    /**
     * @covers ::isString
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage some_name must be a string
     *
     * @dataProvider nonStringDataProvider
     */
    public function testIsStringInvalid($nonStringValue)
    {
        Validation::isString($nonStringValue, self::SOME_NAME);
    }

    /**
     * @covers ::isBoolean
     *
     * @dataProvider booleanDataProvider
     */
    public function testIsBoolean($boolean)
    {
        $this->assertNull(Validation::isBoolean($boolean, self::SOME_NAME));
    }

    /**
     * @covers ::isBoolean
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage some_name must be a boolean
     */
    public function testIsBooleanInvalid()
    {
        Validation::isBoolean(self::SOME_STRING, self::SOME_NAME);
    }

    /**
     * Provides valid booleans.
     *
     * @return array
     */
    public function booleanDataProvider()
    {
        return [
            [ true ],
            [ false ],
        ];
    }

    /**
     * @covers ::isInteger
     *
     * @dataProvider integerDataProvider
     */
    public function testIsInteger($integer)
    {
        $this->assertNull(Validation::isInteger($integer, self::SOME_NAME));
    }

    /**
     * @covers ::isInteger
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage some_name must be an integer
     */
    public function testIsIntegerInvalid()
    {
        Validation::isInteger(self::SOME_STRING, self::SOME_NAME);
    }

    /**
     * Provides valid integers.
     *
     * @return array
     */
    public function integerDataProvider()
    {
        return [
            [ -20 ],
            [ 1 ],
            [ 50 ],
        ];
    }

    /**
     * @covers ::isFloat
     *
     * @dataProvider floatDataProvider
     */
    public function testIsFloat($float)
    {
        $this->assertNull(Validation::isFloat($float, self::SOME_NAME));
    }

    /**
     * @covers ::isFloat
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage some_name must be a float
     */
    public function testIsFloatInvalid()
    {
        Validation::isFloat(self::SOME_STRING, self::SOME_NAME);
    }

    /**
     * Provides valid floats.
     *
     * @return array
     */
    public function floatDataProvider()
    {
        return [
            [ -20.000 ],
            [ 1.6 ],
            [ 5000.1 ],
        ];
    }

    /**
     * @covers ::isNumeric
     *
     * @dataProvider floatDataProvider
     * @dataProvider integerDataProvider
     */
    public function testIsNumeric($number)
    {
        $this->assertNull(Validation::isNumeric($number, self::SOME_NAME));
    }

    /**
     * @covers ::isNumeric
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage some_name must be numeric
     */
    public function testIsNumericInvalid()
    {
        Validation::isNumeric(self::SOME_STRING, self::SOME_NAME);
    }

    /**
     * @covers ::notNull
     */
    public function testNotNull()
    {
        $this->assertNull(Validation::notNull(self::SOME_STRING, self::SOME_NAME));
    }

    /**
     * @covers ::notNull
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage some_name cannot be null
     */
    public function testNotNullInvalid()
    {
        Validation::notNull(null, self::SOME_NAME);
    }

    /**
     * @covers ::notGreaterThan
     */
    public function testNotGreaterThan()
    {
        $this->assertNull(Validation::notGreaterThan(1, 2, self::SOME_NAME));
        $this->assertNull(Validation::notGreaterThan(2, 2, self::SOME_NAME));
    }

    /**
     * @covers ::notGreaterThan
     *
     * @expectedException \RangeException
     * @expectedExceptionMessage 'some_name' value '2' is greater than '1'
     */
    public function testNotGreaterThanInvalid()
    {
        Validation::notGreaterThan(2, 1, self::SOME_NAME);
    }

    /**
     * @covers ::notLessThan
     */
    public function testNotLessThan()
    {
        $this->assertNull(Validation::notLessThan(2, 1, self::SOME_NAME));
        $this->assertNull(Validation::notLessThan(2, 2, self::SOME_NAME));
    }

    /**
     * @covers ::notLessThan
     *
     * @expectedException \RangeException
     * @expectedExceptionMessage 'some_name' value '1' is less than '2'
     */
    public function testNotLessThanInvalid()
    {
        Validation::notLessThan(1, 2, self::SOME_NAME);
    }

    /**
     * @covers ::withinRange
     */
    public function testWithinRangeThan()
    {
        $this->assertNull(Validation::withinRange(2, 1, 3, self::SOME_NAME));
    }

    /**
     * @covers ::withinRange
     *
     * @expectedException \RangeException
     * @expectedExceptionMessage 'some_name' value '4' is greater than '3'
     */
    public function testWithinRangeGreaterThan()
    {
        Validation::withinRange(4, 1, 3, self::SOME_NAME);
    }

    /**
     * @covers ::withinRange
     *
     * @expectedException \RangeException
     * @expectedExceptionMessage 'some_name' value '1' is less than '2'
     */
    public function testWithinRangeLessThan()
    {
        Validation::withinRange(1, 2, 4, self::SOME_NAME);
    }

    /**
     * @covers ::isArrayOfIntegers
     */
    public function testIsArrayOfIntegers()
    {
        $this->assertNull(Validation::isArrayOfIntegers([-1, 0, 1, 3, 100], self::SOME_NAME));
    }

    /**
     * @covers ::isArrayOfIntegers
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage some_name must be array of integers
     */
    public function testIsArrayOfIntegersInvalid()
    {
        Validation::isArrayOfIntegers([1, self::SOME_STRING], self::SOME_NAME);
    }

    /**
     * @covers ::isArrayOfStrings
     */
    public function testIsArrayOfStrings()
    {
        $this->assertNull(Validation::isArrayOfStrings(['', self::SOME_STRING], self::SOME_NAME));
    }

    /**
     * @covers ::isArrayOfStrings
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage some_name must be array of strings
     */
    public function testIsArrayOfStringsInvalid()
    {
        Validation::isArrayOfStrings([1, [], self::SOME_STRING], self::SOME_NAME);
    }

    /**
     * @covers ::isArrayOfType
     * @covers ::isOneOfType
     */
    public function testIsArrayOfType()
    {
        $arrayOfTypes = [
            new \stdClass(),
            new \DateTime(),
        ];
        $allowedTypes = [
            \stdClass::class,
            \DateTime::class,
        ];
        $this->assertNull(Validation::isArrayOfType($arrayOfTypes, $allowedTypes, self::SOME_NAME));
    }

    /**
     * @covers ::isArrayOfType
     * @covers ::isOneOfType
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage some_name must be array of ArrayObject, DateTime
     */
    public function testIsArrayOfTypeInvalid()
    {
        $arrayOfTypes = [
            new \stdClass(),
            new \ArrayObject(),
        ];
        $allowedTypes = [
            \ArrayObject::class,
            \DateTime::class,
        ];
        Validation::isArrayOfType($arrayOfTypes, $allowedTypes, self::SOME_NAME);
    }

    /**
     * @covers ::notEmptyString
     */
    public function testNotEmptyString()
    {
        $this->assertNull(Validation::notEmptyString(self::SOME_STRING, self::SOME_NAME));
    }

    /**
     * @covers ::notEmptyString
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage some_name cannot be empty
     */
    public function testNotEmptyStringWithEmptyValue()
    {
        Validation::notEmptyString('', self::SOME_NAME);
    }

    /**
     * @covers ::notEmptyString
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage some_name must be a string
     *
     * @dataProvider nonStringDataProvider
     */
    public function testNotEmptyStringWithNonStringValue($nonStringValue)
    {
        Validation::notEmptyString($nonStringValue, self::SOME_NAME);
    }

    /**
     * Provides non-string values.
     */
    public function nonStringDataProvider()
    {
        return [
            [ [] ],
            [ false ],
            [ true ],
            [ 1 ],
            [ 0 ],
            [ (object)[] ],
        ];
    }
}
