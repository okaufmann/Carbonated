<?php

use Orchestra\Testbench\TestCase;

class ConfigurationTest extends TestCase
{
    /**
     * @var ExampleModel
     */
    private $model;

    public function setUp()
    {
        parent::setUp();

        // Setup ExampleModel.
        $this->model = new ExampleModel();
    }

    public function testCarbonatedTimestamps()
    {
        // Default.
        $expected = ['created_at', 'updated_at', 'deleted_at'];
        $actual = $this->model->carbonatedTimestamps();
        $this->assertEquals($expected, $actual);

        // Set by user.
        $expected = ['completed_at', 'created_at', 'updated_at', 'deleted_at'];
        $this->model->carbonatedTimestamps = ['completed_at'];
        $actual = $this->model->carbonatedTimestamps();
        $this->assertEquals($expected, $actual);
    }

    public function testCarbonatedDates()
    {
        // Default.
        $expected = [];
        $actual = $this->model->carbonatedDates();
        $this->assertEquals($expected, $actual);

        // Set by user.
        $expected = ['required_by'];
        $this->model->carbonatedDates = ['required_by'];
        $actual = $this->model->carbonatedDates;
        $this->assertEquals($expected, $actual);
    }

    public function testCarbonatedTimes()
    {
        // Default.
        $expected = [];
        $actual = $this->model->carbonatedTimes();
        $this->assertEquals($expected, $actual);

        // Set by user.
        $expected = ['pickup_time'];
        $this->model->carbonatedTimes = ['pickup_time'];
        $actual = $this->model->carbonatedTimes;
        $this->assertEquals($expected, $actual);
    }

    public function testCarbonatedTimestampFormat()
    {
        // Default.
        $expected = 'M d, Y g:ia';
        $actual = $this->model->carbonatedTimestampFormat();
        $this->assertEquals($expected, $actual);

        // Set by user.
        $expected = 'M M d d Y Y g g i i a a';
        $this->model->carbonatedTimestampFormat = $expected;
        $actual = $this->model->carbonatedTimestampFormat;
        $this->assertEquals($expected, $actual);
    }

    public function testCarbonatedDateFormat()
    {
        // Default.
        $expected = 'M d, Y';
        $actual = $this->model->carbonatedDateFormat();
        $this->assertEquals($expected, $actual);

        // Set by user.
        $expected = 'M M d d Y Y';
        $this->model->carbonatedDateFormat = $expected;
        $actual = $this->model->carbonatedDateFormat();
        $this->assertEquals($expected, $actual);
    }

    public function testCarbonatedTimeFormat()
    {
        // Default.
        $expected = 'g:ia';
        $actual = $this->model->carbonatedTimeFormat();
        $this->assertEquals($expected, $actual);

        // Set by user.
        $expected = 'g g i i a a';
        $this->model->carbonatedTimeFormat = $expected;
        $actual = $this->model->carbonatedTimeFormat();
        $this->assertEquals($expected, $actual);
    }

    public function testCarbonatedTimezone()
    {
        // Default.
        $expected = $this->model->databaseTimezone();
        $actual = $this->model->carbonatedTimezone();
        $this->assertEquals($expected, $actual);

        // !TODO: Test Auth::user() $timezone attribute with functional test.

        // Set by user.
        $expected = 'Murica/South';
        $this->model->carbonatedTimezone = $expected;
        $actual = $this->model->carbonatedTimezone();
        $this->assertEquals($expected, $actual);
    }

    public function testJsonTimestampFormat()
    {
        // Default.
        $expected = $this->model->databaseTimestampFormat();
        $actual = $this->model->jsonTimestampFormat();
        $this->assertEquals($expected, $actual);

        // Set by user.
        $expected = 'M M d d Y Y g g i i a a';
        $this->model->jsonTimestampFormat = $expected;
        $actual = $this->model->jsonTimestampFormat();
        $this->assertEquals($expected, $actual);
    }

    public function testJsonDateFormat()
    {
        // Default.
        $expected = $this->model->databaseDateFormat();
        $actual = $this->model->jsonDateFormat();
        $this->assertEquals($expected, $actual);

        // Set by user.
        $expected = 'M M d d Y Y';
        $this->model->jsonDateFormat = $expected;
        $actual = $this->model->jsonDateFormat();
        $this->assertEquals($expected, $actual);
    }

    public function testJsonTimeFormat()
    {
        // Default.
        $expected = $this->model->databaseTimeFormat();
        $actual = $this->model->jsonTimeFormat();
        $this->assertEquals($expected, $actual);

        // Set by user.
        $expected = 'g g i i a a';
        $this->model->jsonTimeFormat = $expected;
        $actual = $this->model->jsonTimeFormat();
        $this->assertEquals($expected, $actual);
    }

    public function testJsonTimezone()
    {
        // Default.
        $expected = $this->model->databaseTimezone;
        $actual = $this->model->jsonTimezone;
        $this->assertEquals($expected, $actual);

        // Set by user.
        $expected = 'Murica/South';
        $this->model->jsonTimezone = $expected;
        $actual = $this->model->jsonTimezone();
        $this->assertEquals($expected, $actual);
    }

    public function testDatabaseTimestampFormat()
    {
        // Default.
        $expected = 'Y-m-d H:i:s';
        $actual = $this->model->databaseTimestampFormat();
        $this->assertEquals($expected, $actual);

        // Set by user.
        $expected = 'M M d d Y Y g g i i a a';
        $this->model->databaseTimestampFormat = $expected;
        $actual = $this->model->databaseTimestampFormat();
        $this->assertEquals($expected, $actual);
    }

    public function testDatabaseDateFormat()
    {
        // Default.
        $expected = 'Y-m-d';
        $actual = $this->model->databaseDateFormat();
        $this->assertEquals($expected, $actual);

        // Set by user.
        $expected = 'M M d d Y Y';
        $this->model->databaseDateFormat = $expected;
        $actual = $this->model->databaseDateFormat();
        $this->assertEquals($expected, $actual);
    }

    public function testDatabaseTimeFormat()
    {
        // Default.
        $expected = 'H:i:s';
        $actual = $this->model->databaseTimeFormat();
        $this->assertEquals($expected, $actual);

        // Set by user.
        $expected = 'g g i i a a';
        $this->model->databaseTimeFormat = $expected;
        $actual = $this->model->databaseTimeFormat();
        $this->assertEquals($expected, $actual);
    }

    public function testDatabaseTimezone()
    {
        // Default.
        $expected = 'UTC';
        $actual = $this->model->databaseTimezone();
        $this->assertEquals($expected, $actual);

        // !TODO: Test Laravel app config() helper with functional test.

        // Set by user.
        $expected = 'Murica/South';
        $this->model->databaseTimezone = $expected;
        $actual = $this->model->databaseTimezone();
        $this->assertEquals($expected, $actual);
    }
}
