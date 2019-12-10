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
     *
     * @doesNotPerformAssertions
     */
    public function testIsString()
    {
        Validation::isString(self::SOME_STRING, self::SOME_NAME);
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
     * @doesNotPerformAssertions
     *
     * @dataProvider booleanDataProvider
     */
    public function testIsBoolean($boolean)
    {
        Validation::isBoolean($boolean, self::SOME_NAME);
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
     * @doesNotPerformAssertions
     *
     * @dataProvider integerDataProvider
     */
    public function testIsInteger($integer)
    {
        Validation::isInteger($integer, self::SOME_NAME);
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
     * @doesNotPerformAssertions
     *
     * @dataProvider floatDataProvider
     */
    public function testIsFloat($float)
    {
        Validation::isFloat($float, self::SOME_NAME);
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
     * @doesNotPerformAssertions
     *
     * @dataProvider floatDataProvider
     * @dataProvider integerDataProvider
     */
    public function testIsNumeric($number)
    {
        Validation::isNumeric($number, self::SOME_NAME);
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
     *
     * @doesNotPerformAssertions
     */
    public function testNotNull()
    {
        Validation::notNull(self::SOME_STRING, self::SOME_NAME);
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
     *
     * @doesNotPerformAssertions
     */
    public function testNotGreaterThan()
    {
        Validation::notGreaterThan(1, 2, self::SOME_NAME);
        Validation::notGreaterThan(2, 2, self::SOME_NAME);
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
     *
     * @doesNotPerformAssertions
     */
    public function testNotLessThan()
    {
        Validation::notLessThan(2, 1, self::SOME_NAME);
        Validation::notLessThan(2, 2, self::SOME_NAME);
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
     *
     * @doesNotPerformAssertions
     */
    public function testWithinRangeThan()
    {
        Validation::withinRange(2, 1, 3, self::SOME_NAME);
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
     *
     * @doesNotPerformAssertions
     */
    public function testIsArrayOfIntegers()
    {
        Validation::isArrayOfIntegers([-1, 0, 1, 3, 100], self::SOME_NAME);
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
     *
     * @doesNotPerformAssertions
     */
    public function testIsArrayOfStrings()
    {
        Validation::isArrayOfStrings(['', self::SOME_STRING], self::SOME_NAME);
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
     *
     * @doesNotPerformAssertions
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
        Validation::isArrayOfType($arrayOfTypes, $allowedTypes, self::SOME_NAME);
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
     *
     * @doesNotPerformAssertions
     */
    public function testNotEmptyString()
    {
        Validation::notEmptyString(self::SOME_STRING, self::SOME_NAME);
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
