<?php

/*
 * Carbonated
 *
 * This File belongs to to Project Carbonated
 *
 * @author Oliver Kaufmann <okaufmann91@gmail.com>
 * @version 1.0
 */

use Carbon\Carbon;

class LocalizationConversionTest extends TestCase
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

        $this->carbon = Carbon::create(2017, 01, 01);

        $this->app['config']->set('carbonated.localization', true);
    }

    public function testLocalizedGermanDay()
    {
        // Arrange
        setlocale(LC_TIME, 'de', 'de_CH.UTF8', 'de_CH');

        $this->model->carbonatedTimestampFormat = "%A";
        $this->model->carbonatedTimestamps = ['completed_at'];
        $this->model->setCarbonInstances((object)['completed_at' => $this->carbon]);

        // Act
        $actual = $this->model->carbonatedAccessor('completed_at');

        // Assert
        $expected = "Sonntag";
        $this->assertEquals($expected, $actual);
    }

    public function testLocalizedGermanDate()
    {
        // Arrange
        setlocale(LC_TIME, 'de', 'de_CH.UTF8', 'de_CH');

        $this->model->carbonatedTimestampFormat = "%A, %d %B %Y";
        $this->model->carbonatedTimestamps = ['completed_at'];
        $this->model->setCarbonInstances((object)['completed_at' => $this->carbon]);

        // Act
        $actual = $this->model->carbonatedAccessor('completed_at');

        // Assert
        $expected = "Sonntag, 01 Januar 2017";
        $this->assertEquals($expected, $actual);
    }

    public function testLocalizedFrenchDay()
    {
        if (!LocaleHelper::localeExists("fr")) {
            $this->markTestSkipped(
                'French is not installed on this system. You can install further locales like: https://askubuntu.com/a/76106'
            );
        }

        // Arrange
        setlocale(LC_TIME, 'fr', 'fr_FR.UTF8', 'fr_FR');

        $this->model->carbonatedTimestampFormat = "%A";
        $this->model->carbonatedTimestamps = ['completed_at'];
        $this->model->setCarbonInstances((object)['completed_at' => $this->carbon]);

        // Act
        $actual = $this->model->carbonatedAccessor('completed_at');

        // Assert
        $expected = "dimanche";
        $this->assertEquals($expected, $actual);
    }

    public function testLocalizedFrenchDate()
    {
        if (!LocaleHelper::localeExists("fr")) {
            $this->markTestSkipped(
                'French is not installed on this system. You can install further locales like: https://askubuntu.com/a/76106'
            );
        }

        // Arrange
        setlocale(LC_TIME, 'fr', 'fr_FR.UTF8', 'fr_FR');

        $this->model->carbonatedTimestampFormat = "%A, %d %B %Y";
        $this->model->carbonatedTimestamps = ['completed_at'];
        $this->model->setCarbonInstances((object)['completed_at' => $this->carbon]);

        // Act
        $actual = $this->model->carbonatedAccessor('completed_at');

        // Assert
        $expected = "dimanche, 01 janvier 2017";
        $this->assertEquals($expected, $actual);
    }

}