<?php

use SKAgarwal\Reflection\ReflectableTrait;

class ConfigurationTest extends \PHPUnit\Framework\TestCase
{
    private $model;

    public function setUp()
    {
        // Setup ExampleModel.
        $this->model = new ExampleModel();
    }

    public function testCarbonatedTimestamps()
    {
        // Default.
        $expected = ['created_at', 'updated_at', 'deleted_at'];
        $actual = $this->model->carbonatedTimestamps;
        $this->assertEquals($expected, $actual);

        // Set by user.
        $expected = ['completed_at', 'created_at', 'updated_at', 'deleted_at'];
        $this->model->setCarbonatedTimestamps = ['completed_at'];
        $actual = $this->model->carbonatedTimestamps;
        $this->assertEquals($expected, $actual);
    }

    public function testCarbonatedDates()
    {
        // Default.
        $expected = [];
        $actual = $this->model->carbonatedDates;
        $this->assertEquals($expected, $actual);

        // Set by user.
        $expected = ['required_by'];
        $this->model->setCarbonatedDates = ['required_by'];
        $actual = $this->model->carbonatedDates;
        $this->assertEquals($expected, $actual);
    }

    public function testCarbonatedTimes()
    {
        // Default.
        $expected = [];
        $actual = $this->model->carbonatedTimes;
        $this->assertEquals($expected, $actual);

        // Set by user.
        $expected = ['pickup_time'];
        $this->model->setCarbonatedTimes = ['pickup_time'];
        $actual = $this->model->carbonatedTimes;
        $this->assertEquals($expected, $actual);
    }

    public function testCarbonatedTimestampFormat()
    {
        // Default.
        $expected = 'M d, Y g:ia';
        $actual = $this->model->carbonatedTimestampFormat;
        $this->assertEquals($expected, $actual);

        // Set by user.
        $expected = 'M M d d Y Y g g i i a a';
        $this->model->setCarbonatedTimestampFormat = $expected;
        $actual = $this->model->carbonatedTimestampFormat;
        $this->assertEquals($expected, $actual);
    }

    public function testCarbonatedDateFormat()
    {
        // Default.
        $expected = 'M d, Y';
        $actual = $this->model->carbonatedDateFormat;
        $this->assertEquals($expected, $actual);

        // Set by user.
        $expected = 'M M d d Y Y';
        $this->model->setCarbonatedDateFormat = $expected;
        $actual = $this->model->carbonatedDateFormat;
        $this->assertEquals($expected, $actual);
    }

    public function testCarbonatedTimeFormat()
    {
        // Default.
        $expected = 'g:ia';
        $actual = $this->model->carbonatedTimeFormat;
        $this->assertEquals($expected, $actual);

        // Set by user.
        $expected = 'g g i i a a';
        $this->model->setCarbonatedTimeFormat = $expected;
        $actual = $this->model->carbonatedTimeFormat;
        $this->assertEquals($expected, $actual);
    }

    public function testCarbonatedTimezone()
    {
        // Default.
        $expected = $this->model->databaseTimezone;
        $actual = $this->model->carbonatedTimezone;
        $this->assertEquals($expected, $actual);

        // !TODO: Test Auth::user() $timezone attribute with functional test.

        // Set by user.
        $expected = 'Murica/South';
        $this->model->setCarbonatedTimezone = $expected;
        $actual = $this->model->carbonatedTimezone;
        $this->assertEquals($expected, $actual);
    }

    public function testJsonTimestampFormat()
    {
        // Default.
        $expected = $this->model->databaseTimestampFormat;
        $actual = $this->model->jsonTimestampFormat;
        $this->assertEquals($expected, $actual);

        // Set by user.
        $expected = 'M M d d Y Y g g i i a a';
        $this->model->setJsonTimestampFormat = $expected;
        $actual = $this->model->jsonTimestampFormat;
        $this->assertEquals($expected, $actual);
    }

    public function testJsonDateFormat()
    {
        // Default.
        $expected = $this->model->databaseDateFormat;
        $actual = $this->model->jsonDateFormat;
        $this->assertEquals($expected, $actual);

        // Set by user.
        $expected = 'M M d d Y Y';
        $this->model->setJsonDateFormat = $expected;
        $actual = $this->model->jsonDateFormat;
        $this->assertEquals($expected, $actual);
    }

    public function testJsonTimeFormat()
    {
        // Default.
        $expected = $this->model->databaseTimeFormat;
        $actual = $this->model->jsonTimeFormat;
        $this->assertEquals($expected, $actual);

        // Set by user.
        $expected = 'g g i i a a';
        $this->model->setJsonTimeFormat = $expected;
        $actual = $this->model->jsonTimeFormat;
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
        $this->model->setJsonTimezone = $expected;
        $actual = $this->JsonTimezone();
        $this->assertEquals($expected, $actual);
    }

    public function testDatabaseTimestampFormat()
    {
        // Default.
        $expected = 'Y-m-d H:i:s';
        $actual = $this->model->databaseTimestampFormat;
        $this->assertEquals($expected, $actual);

        // Set by user.
        $expected = 'M M d d Y Y g g i i a a';
        $this->model->setDatabaseTimestampFormat = $expected;
        $actual = $this->model->databaseTimestampFormat;
        $this->assertEquals($expected, $actual);
    }

    public function testDatabaseDateFormat()
    {
        // Default.
        $expected = 'Y-m-d';
        $actual = $this->model->databaseDateFormat;
        $this->assertEquals($expected, $actual);

        // Set by user.
        $expected = 'M M d d Y Y';
        $this->model->setDatabaseDateFormat = $expected;
        $actual = $this->model->databaseDateFormat;
        $this->assertEquals($expected, $actual);
    }

    public function testDatabaseTimeFormat()
    {
        // Default.
        $expected = 'H:i:s';
        $actual = $this->model->databaseTimeFormat;
        $this->assertEquals($expected, $actual);

        // Set by user.
        $expected = 'g g i i a a';
        $this->model->setDatabaseTimeFormat = $expected;
        $actual = $this->model->databaseTimeFormat;
        $this->assertEquals($expected, $actual);
    }

    public function testDatabaseTimezone()
    {
        // Default.
        $expected = 'UTC';
        $actual = $this->model->databaseTimezone;
        $this->assertEquals($expected, $actual);

        // !TODO: Test Laravel app config() helper with functional test.

        // Set by user.
        $expected = 'Murica/South';
        $this->model->setDatabaseTimezone = $expected;
        $actual = $this->model->databaseTimezone;
        $this->assertEquals($expected, $actual);
    }
}
