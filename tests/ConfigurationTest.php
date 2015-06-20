<?php

class ConfigurationTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->model = new ExampleModel;
    }

    public function invokeProtected($method, ...$args)
    {
        $method = new ReflectionMethod('ExampleModel', $method);
        $method->setAccessible(TRUE);

        return $method->invoke($this->model, $args);
    }

    // public function testCarbonatedTimestampsDefault()
    // {
    //     $expected = ['created_at', 'updated_at', 'deleted_at'];
    //     $actual = $this->model->carbonatedTimestamps();
    //     $this->assertEquals($expected, $actual);

    //     $expected = ['some_field', 'created_at', 'updated_at', 'deleted_at'];
    //     $this->model->carbonatedTimestamps = ['some_field'];
    //     $actual = $this->model->carbonatedTimestamps(); // Error?
    //     $this->assertEquals($expected, $actual);
    // }

    public function testCarbonatedTimestampFormat()
    {
        // Default value.
        $expected = 'M d, Y g:ia';
        $actual = $this->model->carbonatedTimestampFormat();
        $this->assertEquals($expected, $actual);

        // Set value.
        $expected = 'M M d d Y Y g g i i a a';
        $this->model->carbonatedTimestampFormat = $expected;
        $actual = $this->model->carbonatedTimestampFormat();
        $this->assertEquals($expected, $actual);
    }

    public function testCarbonatedDateFormat()
    {
        // Default value.
        $expected = 'M d, Y';
        $actual = $this->model->carbonatedDateFormat();
        $this->assertEquals($expected, $actual);

        // Set value.
        $expected = 'M M d d Y Y';
        $this->model->carbonatedDateFormat = $expected;
        $actual = $this->model->carbonatedDateFormat();
        $this->assertEquals($expected, $actual);
    }

    public function testCarbonatedTimeFormat()
    {
        // Default value.
        $expected = 'g:ia';
        $actual = $this->model->carbonatedTimeFormat();
        $this->assertEquals($expected, $actual);

        // Set value.
        $expected = 'g g i i a a';
        $this->model->carbonatedTimeFormat = $expected;
        $actual = $this->model->carbonatedTimeFormat();
        $this->assertEquals($expected, $actual);
    }

    public function testCarbonatedTimezone()
    {
        // Default value.
        $expected = $this->model->databaseTimezone();
        $actual = $this->model->carbonatedTimezone();
        $this->assertEquals($expected, $actual);

        // !TODO: Test Auth::user() $timezone attribute?

        // Set value.
        $expected = 'Murica/South';
        $this->model->carbonatedTimezone = $expected;
        $actual = $this->model->carbonatedTimezone();
        $this->assertEquals($expected, $actual);
    }

    public function testJsonTimestampFormat()
    {
        // Default value.
        $expected = $this->model->databaseTimestampFormat();
        $actual = $this->model->jsonTimestampFormat();
        $this->assertEquals($expected, $actual);

        // Set value.
        $expected = 'M M d d Y Y g g i i a a';
        $this->model->jsonTimestampFormat = $expected;
        $actual = $this->model->jsonTimestampFormat();
        $this->assertEquals($expected, $actual);
    }

    public function testJsonDateFormat()
    {
        // Default value.
        $expected = $this->model->databaseDateFormat();
        $actual = $this->model->jsonDateFormat();
        $this->assertEquals($expected, $actual);

        // Set value.
        $expected = 'M M d d Y Y';
        $this->model->jsonDateFormat = $expected;
        $actual = $this->model->jsonDateFormat();
        $this->assertEquals($expected, $actual);
    }

    public function testJsonTimeFormat()
    {
        // Default value.
        $expected = $this->model->databaseTimeFormat();
        $actual = $this->model->jsonTimeFormat();
        $this->assertEquals($expected, $actual);

        // Set value.
        $expected = 'g g i i a a';
        $this->model->jsonTimeFormat = $expected;
        $actual = $this->model->jsonTimeFormat();
        $this->assertEquals($expected, $actual);
    }

    public function testJsonTimezone()
    {
        // Default value.
        $expected = $this->model->databaseTimezone();
        $actual = $this->model->jsonTimezone();
        $this->assertEquals($expected, $actual);

        // Set value.
        $expected = 'Murica/South';
        $this->model->jsonTimezone = $expected;
        $actual = $this->model->jsonTimezone();
        $this->assertEquals($expected, $actual);
    }


    public function testDatabaseTimestampFormat()
    {
        // Default value.
        $expected = 'Y-m-d H:i:s';
        $actual = $this->model->databaseTimestampFormat();
        $this->assertEquals($expected, $actual);

        // Set value.
        $expected = 'M M d d Y Y g g i i a a';
        $this->model->databaseTimestampFormat = $expected;
        $actual = $this->model->databaseTimestampFormat();
        $this->assertEquals($expected, $actual);
    }

    public function testDatabaseDateFormat()
    {
        // Default value.
        $expected = 'Y-m-d';
        $actual = $this->model->databaseDateFormat();
        $this->assertEquals($expected, $actual);

        // Set value.
        $expected = 'M M d d Y Y';
        $this->model->databaseDateFormat = $expected;
        $actual = $this->model->databaseDateFormat();
        $this->assertEquals($expected, $actual);
    }

    public function testDatabaseTimeFormat()
    {
        // Default value.
        $expected = 'H:i:s';
        $actual = $this->model->databaseTimeFormat();
        $this->assertEquals($expected, $actual);

        // Set value.
        $expected = 'g g i i a a';
        $this->model->databaseTimeFormat = $expected;
        $actual = $this->model->databaseTimeFormat();
        $this->assertEquals($expected, $actual);
    }

    public function testDatabaseTimezone()
    {
        // Default value.
        $expected = 'UTC';
        $actual = $this->model->databaseTimezone();
        $this->assertEquals($expected, $actual);

        // !TODO: Test Laravel app config() helper?

        // Set value.
        $expected = 'Murica/South';
        $this->model->databaseTimezone = $expected;
        $actual = $this->model->databaseTimezone();
        $this->assertEquals($expected, $actual);
    }

}
