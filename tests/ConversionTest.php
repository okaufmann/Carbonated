<?php

use SKAgarwal\Reflection\ReflectableTrait;
use Carbon\Carbon;
use Illuminate\Database\Capsule\Manager as Capsule;

class ConversionTest extends PHPUnit_Framework_TestCase
{
    use ReflectableTrait;

    public $model;
    public $modelReflection;
    public $carbon;
    public $dateTime;

    public function setUp()
    {
        // Setup ExampleModel.
        $this->model = new ExampleModel;

        // Setup ExampleModel reflection.
        $this->reflect($this->model);
        $this->modelReflection = $this->on($this->model);

        // Setup Carbon instance.
        $this->dateTime = new DateTime;
        $this->carbon = Carbon::instance($this->dateTime);
    }

    public function testTimestampAccessor()
    {
        // Configure conversion.
        $this->modelReflection->setCarbonatedTimestampFormat = 'M d, Y g:ia';
        $this->modelReflection->setJsonTimestampFormat = 'Y-m-d\TH:i:sP';
        $this->modelReflection->setCarbonatedTimezone = 'America/Toronto';
        $this->modelReflection->setJsonTimezone = 'America/Vancouver';

        // Configure field.
        $this->modelReflection->setCarbonatedTimestamps = ['completed_at'];
        $this->modelReflection->setCarbonInstances = (object) ['completed_at' => $this->carbon];

        // Assert conversion for view output.
        $expected = $this->carbon->timezone('America/Toronto')->format('M d, Y g:ia');
        $actual = $this->modelReflection->callCarbonatedAccessor('completed_at');
        $this->assertEquals($expected, $actual);

        // Assert conversion for JSON output.
        $expected = $this->carbon->timezone('America/Vancouver')->format('Y-m-d\TH:i:sP');
        $actual = $this->modelReflection->callCarbonatedAccessor('completed_at', true);
        $this->assertEquals($expected, $actual);
    }

    public function testDateAccessor()
    {
        // Configure conversion.
        $this->modelReflection->setCarbonatedDateFormat = 'M d, Y';
        $this->modelReflection->setJsonDateFormat = 'Y-m-dP';
        $this->modelReflection->setCarbonatedTimezone = 'America/Toronto';
        $this->modelReflection->setJsonTimezone = 'America/Vancouver';

        // Configure field.
        $this->modelReflection->setCarbonatedDates = ['required_by'];
        $this->modelReflection->setCarbonInstances = (object) ['required_by' => $this->carbon];

        // Assert conversion for view output.
        $expected = $this->carbon->timezone('America/Toronto')->format('M d, Y');
        $actual = $this->modelReflection->callCarbonatedAccessor('required_by');
        $this->assertEquals($expected, $actual);

        // Assert conversion for JSON output.
        $expected = $this->carbon->timezone('America/Vancouver')->format('Y-m-dP');
        $actual = $this->modelReflection->callCarbonatedAccessor('required_by', true);
        $this->assertEquals($expected, $actual);
    }

    public function testTimeAccessor()
    {
        // Configure conversion.
        $this->modelReflection->setCarbonatedTimeFormat = 'M d, Y';
        $this->modelReflection->setJsonTimeFormat = '\TH:i:sP';
        $this->modelReflection->setCarbonatedTimezone = 'America/Toronto';
        $this->modelReflection->setJsonTimezone = 'America/Vancouver';

        // Configure field.
        $this->modelReflection->setCarbonatedTimes = ['pickup_time'];
        $this->modelReflection->setCarbonInstances = (object) ['pickup_time' => $this->carbon];

        // Assert conversion for view output.
        $expected = $this->carbon->timezone('America/Toronto')->format('M d, Y');
        $actual = $this->modelReflection->callCarbonatedAccessor('pickup_time');
        $this->assertEquals($expected, $actual);

        // Assert conversion for JSON output.
        $expected = $this->carbon->timezone('America/Vancouver')->format('\TH:i:sP');
        $actual = $this->modelReflection->callCarbonatedAccessor('pickup_time', true);
        $this->assertEquals($expected, $actual);
    }

    public function testTimestampMutator()
    {
        // Configure conversion.
        $this->modelReflection->setCarbonatedTimestampFormat = 'M d, Y g:ia:s';
        $this->modelReflection->setJsonTimestampFormat = 'Y-m-d\TH:i:sP';
        $this->modelReflection->setDatabaseTimestampFormat = 'Y-m-d H:i:s';
        $this->modelReflection->setCarbonatedTimezone = 'America/Toronto';
        $this->modelReflection->setJsonTimezone = 'America/Vancouver';
        $this->modelReflection->setDatabaseTimezone = 'UTC';

        // Configure field.
        $this->modelReflection->setCarbonatedTimestamps = ['completed_at'];

        // Set expectation.
        $expected = $this->carbon->timezone('UTC')->format('Y-m-d H:i:s');

        // Assert conversion from datetime instance.
        $actual = $this->modelReflection->callCarbonatedMutator('completed_at', $this->dateTime);
        $this->assertEquals($expected, $actual);

        // Assert conversion from carbon instance.
        $actual = $this->modelReflection->callCarbonatedMutator('completed_at', $this->carbon);
        $this->assertEquals($expected, $actual);

        // Assert conversion from view input.
        $input = $this->carbon->timezone('America/Toronto')->format('M d, Y g:ia:s');
        $actual = $this->modelReflection->callCarbonatedMutator('completed_at', $input);
        $this->assertEquals($expected, $actual);

        // Assert conversion from JSON input.
        // $input = $this->carbon->timezone('America/Vancouver')->format('Y-m-d\TH:i:sP');
        // $actual = $this->modelReflection->callCarbonatedMutator('completed_at', $input);
        // $this->assertEquals($expected, $actual);
    }

}
