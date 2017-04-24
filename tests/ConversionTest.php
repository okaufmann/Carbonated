<?php

use Carbon\Carbon;
use SKAgarwal\Reflection\ReflectableTrait;

class ConversionTest extends PHPUnit_Framework_TestCase
{
    use ReflectableTrait;

    public $carbon;
    public $dateTime;

    public function setUp()
    {
        // Setup ExampleModel.
        $this->reflect(new ExampleModel());

        // Setup Carbon instance.
        $this->dateTime = new DateTime();
        $this->carbon = Carbon::instance($this->dateTime);
    }

    public function testTimestampAccessor()
    {
        // Configure conversion.
        $this->setCarbonatedTimestampFormat = 'M d, Y g:ia';
        $this->setJsonTimestampFormat = 'Y-m-d\TH:i:sP';
        $this->setCarbonatedTimezone = 'America/Toronto';
        $this->setJsonTimezone = 'America/Vancouver';

        // Configure field.
        $this->setCarbonatedTimestamps = ['completed_at'];
        $this->setCarbonInstances = (object) ['completed_at' => $this->carbon];

        // Assert conversion for view output.
        $expected = $this->carbon->timezone('America/Toronto')->format('M d, Y g:ia');
        $actual = $this->callCarbonatedAccessor('completed_at');
        $this->assertEquals($expected, $actual);

        // Assert conversion for JSON output.
        $expected = $this->carbon->timezone('America/Vancouver')->format('Y-m-d\TH:i:sP');
        $actual = $this->callCarbonatedAccessor('completed_at', true);
        $this->assertEquals($expected, $actual);
    }

    public function testDateAccessor()
    {
        // Configure conversion.
        $this->setCarbonatedDateFormat = 'M d, Y';
        $this->setJsonDateFormat = 'Y-m-dP';
        $this->setCarbonatedTimezone = 'America/Toronto';
        $this->setJsonTimezone = 'America/Vancouver';

        // Configure field.
        $this->setCarbonatedDates = ['required_by'];
        $this->setCarbonInstances = (object) ['required_by' => $this->carbon];

        // Assert conversion for view output.
        $expected = $this->carbon->timezone('America/Toronto')->format('M d, Y');
        $actual = $this->callCarbonatedAccessor('required_by');
        $this->assertEquals($expected, $actual);

        // Assert conversion for JSON output.
        $expected = $this->carbon->timezone('America/Vancouver')->format('Y-m-dP');
        $actual = $this->callCarbonatedAccessor('required_by', true);
        $this->assertEquals($expected, $actual);
    }

    public function testTimeAccessor()
    {
        // Configure conversion.
        $this->setCarbonatedTimeFormat = 'M d, Y';
        $this->setJsonTimeFormat = '\TH:i:sP';
        $this->setCarbonatedTimezone = 'America/Toronto';
        $this->setJsonTimezone = 'America/Vancouver';

        // Configure field.
        $this->setCarbonatedTimes = ['pickup_time'];
        $this->setCarbonInstances = (object) ['pickup_time' => $this->carbon];

        // Assert conversion for view output.
        $expected = $this->carbon->timezone('America/Toronto')->format('M d, Y');
        $actual = $this->callCarbonatedAccessor('pickup_time');
        $this->assertEquals($expected, $actual);

        // Assert conversion for JSON output.
        $expected = $this->carbon->timezone('America/Vancouver')->format('\TH:i:sP');
        $actual = $this->callCarbonatedAccessor('pickup_time', true);
        $this->assertEquals($expected, $actual);
    }

    public function testTimestampMutator()
    {
        // Configure conversion.
        $this->setCarbonatedTimestampFormat = 'M d, Y g:i:s a';
        $this->setJsonTimestampFormat = 'Y-m-d\TH:i:sP';
        $this->setDatabaseTimestampFormat = 'Y-m-d H:i:s';
        $this->setCarbonatedTimezone = 'America/Toronto';
        $this->setJsonTimezone = 'America/Vancouver';
        $this->setDatabaseTimezone = 'UTC';

        // Configure field.
        $this->setCarbonatedTimestamps = ['completed_at'];

        // Set expectation.
        $expected = $this->carbon->timezone('UTC')->format('Y-m-d H:i:s');

        // Assert conversion from datetime instance.
        $actual = $this->callCarbonatedMutator('completed_at', $this->dateTime);
        $this->assertEquals($expected, $actual);

        // Assert conversion from carbon instance.
        $actual = $this->callCarbonatedMutator('completed_at', $this->carbon);
        $this->assertEquals($expected, $actual);

        // Assert conversion from view input.
        $input = $this->carbon->timezone('America/Toronto')->format('M d, Y g:i:s a');
        $actual = $this->callCarbonatedMutator('completed_at', $input);
        $this->assertEquals($expected, $actual);

        // Assert conversion from JSON input.
        // $input = $this->carbon->timezone('America/Vancouver')->format('Y-m-d\TH:i:sP');
        // $actual = $this->callCarbonatedMutator('completed_at', $input);
        // $this->assertEquals($expected, $actual);
    }
}
