<?php

use Illuminate\Database\Eloquent\Model;
use JerseyMilker\Carbonated;

class ExampleModel extends Illuminate\Database\Eloquent\Model
{
    use Carbonated;

    public $carbonatedTimestamps;
    public $carbonatedDates;
    public $carbonatedTimes;
}
