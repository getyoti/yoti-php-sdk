<?php

declare(strict_types=1);

namespace Yoti\Test\Util;

use Yoti\Test\TestCase;
use Yoti\Util\Validation;

/**
 * @coversDefaultClass \Yoti\Util\Validation
 */
class ValidationTest extends TestCase
{
    private const SOME_NAME = 'some_name';
    private const SOME_STRING = 'some string';

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
     *
     * @dataProvider nonStringDataProvider
     */
    public function testIsStringInvalid($nonStringValue)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('some_name must be a string');

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
     */
    public function testIsBooleanInvalid()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('some_name must be a boolean');

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
     * @covers ::notNull
     */
    public function testNotNull()
    {
        $this->assertNull(Validation::notNull(self::SOME_STRING, self::SOME_NAME));
    }

    /**
     * @covers ::notNull
     */
    public function testNotNullInvalid()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('some_name cannot be null');

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
     */
    public function testNotGreaterThanInvalid()
    {
        $this->expectException(\RangeException::class);
        $this->expectExceptionMessage('\'some_name\' value \'2\' is greater than \'1\'');

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
     */
    public function testNotLessThanInvalid()
    {
        $this->expectException(\RangeException::class);
        $this->expectExceptionMessage('\'some_name\' value \'1\' is less than \'2\'');

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
     */
    public function testWithinRangeGreaterThan()
    {
        $this->expectException(\RangeException::class);
        $this->expectExceptionMessage('\'some_name\' value \'4\' is greater than \'3\'');

        Validation::withinRange(4, 1, 3, self::SOME_NAME);
    }

    /**
     * @covers ::withinRange
     */
    public function testWithinRangeLessThan()
    {
        $this->expectException(\RangeException::class);
        $this->expectExceptionMessage('\'some_name\' value \'1\' is less than \'2\'');

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
     */
    public function testIsArrayOfIntegersInvalid()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('some_name must be array of integers');

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
     */
    public function testIsArrayOfStringsInvalid()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('some_name must be array of strings');

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
     */
    public function testIsArrayOfTypeInvalid()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('some_name must be array of ArrayObject, DateTime');

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
     */
    public function testNotEmptyStringWithEmptyValue()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('some_name cannot be empty');

        Validation::notEmptyString('', self::SOME_NAME);
    }

    /**
     * @covers ::notEmptyString
     *
     * @dataProvider nonStringDataProvider
     */
    public function testNotEmptyStringWithNonStringValue($nonStringValue)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('some_name must be a string');

        Validation::notEmptyString($nonStringValue, self::SOME_NAME);
    }

    /**
     * @covers ::notEmptyArray
     */
    public function testNotEmptyArray()
    {
        $this->assertNull(Validation::notEmptyArray([self::SOME_STRING], self::SOME_NAME));
    }

    /**
     * @covers ::notEmptyArray
     */
    public function testNotEmptyArrayWithEmptyValue()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('some_name cannot be empty');

        Validation::notEmptyArray([], self::SOME_NAME);
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

    /**
     * @covers ::matchesPattern
     */
    public function testMatchesPatternValid()
    {
        $this->assertNull(Validation::matchesPattern(
            'some-6',
            '/\s*-\s*/',
            'match'
        ));
    }

    /**
     * @covers ::matchesPattern
     */
    public function testMatchesPatternInvalid()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("'match' value 'some-value' does not match format '/\d+/'");

        Validation::matchesPattern('some-value', '/\d+/', 'match');
    }
}
