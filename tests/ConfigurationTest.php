<?php

use SKAgarwal\Reflection\ReflectableTrait;

class ConfigurationTest extends PHPUnit_Framework_TestCase
{
    use ReflectableTrait;

    public function setUp()
    {
        // Setup ExampleModel.
        $this->reflect(new ExampleModel);
    }

    public function testCarbonatedTimestamps()
    {
        // Default.
        $expected = ['created_at', 'updated_at', 'deleted_at'];
        $actual = $this->callCarbonatedTimestamps();
        $this->assertEquals($expected, $actual);

        // Set by user.
        $expected = ['completed_at', 'created_at', 'updated_at', 'deleted_at'];
        $this->setCarbonatedTimestamps = ['completed_at'];
        $actual = $this->callCarbonatedTimestamps();
        $this->assertEquals($expected, $actual);
    }

    public function testCarbonatedDates()
    {
        // Default.
        $expected = [];
        $actual = $this->callCarbonatedDates();
        $this->assertEquals($expected, $actual);

        // Set by user.
        $expected = ['required_by'];
        $this->setCarbonatedDates = ['required_by'];
        $actual = $this->callCarbonatedDates();
        $this->assertEquals($expected, $actual);
    }

    public function testCarbonatedTimes()
    {
        // Default.
        $expected = [];
        $actual = $this->callCarbonatedTimes();
        $this->assertEquals($expected, $actual);

        // Set by user.
        $expected = ['pickup_time'];
        $this->setCarbonatedTimes = ['pickup_time'];
        $actual = $this->callCarbonatedTimes();
        $this->assertEquals($expected, $actual);
    }

    public function testCarbonatedTimestampFormat()
    {
        // Default.
        $expected = 'M d, Y g:ia';
        $actual = $this->callCarbonatedTimestampFormat();
        $this->assertEquals($expected, $actual);

        // Set by user.
        $expected = 'M M d d Y Y g g i i a a';
        $this->setCarbonatedTimestampFormat = $expected;
        $actual = $this->callCarbonatedTimestampFormat();
        $this->assertEquals($expected, $actual);
    }

    public function testCarbonatedDateFormat()
    {
        // Default.
        $expected = 'M d, Y';
        $actual = $this->callCarbonatedDateFormat();
        $this->assertEquals($expected, $actual);

        // Set by user.
        $expected = 'M M d d Y Y';
        $this->setCarbonatedDateFormat = $expected;
        $actual = $this->callCarbonatedDateFormat();
        $this->assertEquals($expected, $actual);
    }

    public function testCarbonatedTimeFormat()
    {
        // Default.
        $expected = 'g:ia';
        $actual = $this->callCarbonatedTimeFormat();
        $this->assertEquals($expected, $actual);

        // Set by user.
        $expected = 'g g i i a a';
        $this->setCarbonatedTimeFormat = $expected;
        $actual = $this->callCarbonatedTimeFormat();
        $this->assertEquals($expected, $actual);
    }

    public function testCarbonatedTimezone()
    {
        // Default.
        $expected = $this->callDatabaseTimezone();
        $actual = $this->callCarbonatedTimezone();
        $this->assertEquals($expected, $actual);

        // !TODO: Test Auth::user() $timezone attribute with functional test.

        // Set by user.
        $expected = 'Murica/South';
        $this->setCarbonatedTimezone = $expected;
        $actual = $this->callCarbonatedTimezone();
        $this->assertEquals($expected, $actual);
    }

    public function testJsonTimestampFormat()
    {
        // Default.
        $expected = $this->callDatabaseTimestampFormat();
        $actual = $this->callJsonTimestampFormat();
        $this->assertEquals($expected, $actual);

        // Set by user.
        $expected = 'M M d d Y Y g g i i a a';
        $this->setJsonTimestampFormat = $expected;
        $actual = $this->callJsonTimestampFormat();
        $this->assertEquals($expected, $actual);
    }

    public function testJsonDateFormat()
    {
        // Default.
        $expected = $this->callDatabaseDateFormat();
        $actual = $this->callJsonDateFormat();
        $this->assertEquals($expected, $actual);

        // Set by user.
        $expected = 'M M d d Y Y';
        $this->setJsonDateFormat = $expected;
        $actual = $this->callJsonDateFormat();
        $this->assertEquals($expected, $actual);
    }

    public function testJsonTimeFormat()
    {
        // Default.
        $expected = $this->callDatabaseTimeFormat();
        $actual = $this->callJsonTimeFormat();
        $this->assertEquals($expected, $actual);

        // Set by user.
        $expected = 'g g i i a a';
        $this->setJsonTimeFormat = $expected;
        $actual = $this->callJsonTimeFormat();
        $this->assertEquals($expected, $actual);
    }

    public function testJsonTimezone()
    {
        // Default.
        $expected = $this->callDatabaseTimezone();
        $actual = $this->callJsonTimezone();
        $this->assertEquals($expected, $actual);

        // Set by user.
        $expected = 'Murica/South';
        $this->setJsonTimezone = $expected;
        $actual = $this->callJsonTimezone();
        $this->assertEquals($expected, $actual);
    }

    public function testDatabaseTimestampFormat()
    {
        // Default.
        $expected = 'Y-m-d H:i:s';
        $actual = $this->callDatabaseTimestampFormat();
        $this->assertEquals($expected, $actual);

        // Set by user.
        $expected = 'M M d d Y Y g g i i a a';
        $this->setDatabaseTimestampFormat = $expected;
        $actual = $this->callDatabaseTimestampFormat();
        $this->assertEquals($expected, $actual);
    }

    public function testDatabaseDateFormat()
    {
        // Default.
        $expected = 'Y-m-d';
        $actual = $this->callDatabaseDateFormat();
        $this->assertEquals($expected, $actual);

        // Set by user.
        $expected = 'M M d d Y Y';
        $this->setDatabaseDateFormat = $expected;
        $actual = $this->callDatabaseDateFormat();
        $this->assertEquals($expected, $actual);
    }

    public function testDatabaseTimeFormat()
    {
        // Default.
        $expected = 'H:i:s';
        $actual = $this->callDatabaseTimeFormat();
        $this->assertEquals($expected, $actual);

        // Set by user.
        $expected = 'g g i i a a';
        $this->setDatabaseTimeFormat = $expected;
        $actual = $this->callDatabaseTimeFormat();
        $this->assertEquals($expected, $actual);
    }

    public function testDatabaseTimezone()
    {
        // Default.
        $expected = 'UTC';
        $actual = $this->callDatabaseTimezone();
        $this->assertEquals($expected, $actual);

        // !TODO: Test Laravel app config() helper with functional test.

        // Set by user.
        $expected = 'Murica/South';
        $this->setDatabaseTimezone = $expected;
        $actual = $this->callDatabaseTimezone();
        $this->assertEquals($expected, $actual);
    }
}
