<?php

use Orchestra\Testbench\TestCase;
use Carbon\Carbon;
use Illuminate\Database\Capsule\Manager as Capsule;

class ConversionTest extends TestCase
{
    /**
     * @var Carbon
     */
    public $carbon;
    public $dateTime;

    /**
     * @var ExampleModel
     */
    public $model;

    public function setUp()
    {
        parent::setUp();

        // Setup ExampleModel.
        $this->model = new ExampleModel();

        // Setup Carbon instance.
        $this->dateTime = new DateTime;
        $this->carbon = Carbon::instance($this->dateTime);
    }

    public function testTimestampAccessor()
    {
        // Configure conversion.
        $this->model->carbonatedTimestampFormat = 'M d, Y g:ia';
        $this->model->jsonTimestampFormat = 'Y-m-d\TH:i:sP';
        $this->model->carbonatedTimezone = 'America/Toronto';
        $this->model->jsonTimezone = 'America/Vancouver';

        // Configure field.
        $this->model->carbonatedTimestamps = ['completed_at'];
        $this->model->carbonInstances = (object)['completed_at' => $this->carbon];

        // Assert conversion for view output.
        $expected = $this->carbon->timezone('America/Toronto')->format('M d, Y g:ia');
        $actual = $this->model->carbonatedAccessor('completed_at');
        $this->assertEquals($expected, $actual);

        // Assert conversion for JSON output.
        $expected = $this->carbon->timezone('America/Vancouver')->format('Y-m-d\TH:i:sP');
        $actual = $this->model->carbonatedAccessor('completed_at', true);
        $this->assertEquals($expected, $actual);
    }

    public function testDateAccessor()
    {
        // Configure conversion.
        $this->model->carbonatedDateFormat = 'M d, Y';
        $this->model->jsonDateFormat = 'Y-m-dP';
        $this->model->carbonatedTimezone = 'America/Toronto';
        $this->model->jsonTimezone = 'America/Vancouver';

        // Configure field.
        $this->model->carbonatedDates = ['required_by'];
        $this->model->carbonInstances = (object)['required_by' => $this->carbon];

        // Assert conversion for view output.
        $expected = $this->carbon->timezone('America/Toronto')->format('M d, Y');
        $actual = $this->model->carbonatedAccessor('required_by');
        $this->assertEquals($expected, $actual);

        // Assert conversion for JSON output.
        $expected = $this->carbon->timezone('America/Vancouver')->format('Y-m-dP');
        $actual = $this->model->carbonatedAccessor('required_by', true);
        $this->assertEquals($expected, $actual);
    }

    public function testTimeAccessor()
    {
        // Configure conversion.
        $this->model->carbonatedTimeFormat = 'M d, Y';
        $this->model->jsonTimeFormat = '\TH:i:sP';
        $this->model->carbonatedTimezone = 'America/Toronto';
        $this->model->jsonTimezone = 'America/Vancouver';

        // Configure field.
        $this->model->carbonatedTimes = ['pickup_time'];
        $this->model->carbonInstances = (object)['pickup_time' => $this->carbon];

        // Assert conversion for view output.
        $expected = $this->carbon->timezone('America/Toronto')->format('M d, Y');
        $actual = $this->model->carbonatedAccessor('pickup_time');
        $this->assertEquals($expected, $actual);

        // Assert conversion for JSON output.
        $expected = $this->carbon->timezone('America/Vancouver')->format('\TH:i:sP');
        $actual = $this->model->carbonatedAccessor('pickup_time', true);
        $this->assertEquals($expected, $actual);
    }

    public function testTimestampMutator()
    {
        // Configure conversion.
        $this->model->carbonatedTimestampFormat = 'M d, Y g:i:s a';
        $this->model->jsonTimestampFormat = 'Y-m-d\TH:i:sP';
        $this->model->DatabaseTimestampFormat = 'Y-m-d H:i:s';
        $this->model->carbonatedTimezone = 'America/Toronto';
        $this->model->jsonTimezone = 'America/Vancouver';
        $this->model->DatabaseTimezone = 'UTC';

        // Configure field.
        $this->model->carbonatedTimestamps = ['completed_at'];

        // Set expectation.
        $expected = $this->carbon->timezone('UTC')->format('Y-m-d H:i:s');

        // Assert conversion from datetime instance.
        $actual = $this->model->carbonatedMutator('completed_at', $this->dateTime);
        $this->assertEquals($expected, $actual);

        // Assert conversion from carbon instance.
        $actual = $this->model->carbonatedMutator('completed_at', $this->carbon);
        $this->assertEquals($expected, $actual);

        // Assert conversion from view input.
        $input = $this->carbon->timezone('America/Toronto')->format('M d, Y g:i:s a');
        $actual = $this->model->carbonatedMutator('completed_at', $input);
        $this->assertEquals($expected, $actual);

        // Assert conversion from JSON input.
        // $input = $this->carbon->timezone('America/Vancouver')->format('Y-m-d\TH:i:sP');
        // $actual = $this->model->carbonatedMutator('completed_at', $input);
        // $this->assertEquals($expected, $actual);
    }

}
