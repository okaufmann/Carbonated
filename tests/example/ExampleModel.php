<?php

use Illuminate\Database\Eloquent\Model;
use ThisVessel\Carbonated;

class ExampleModel extends Illuminate\Database\Eloquent\Model
{
    use Carbonated;

    public $carbonatedTimestamps;
    public $carbonatedDates;
    public $carbonatedTimes;

    public $carbonatedTimestampFormat;
    public $carbonatedDateFormat;
    public $carbonatedTimeFormat;
    public $carbonatedTimezone;

    public $jsonTimestampFormat;
    public $jsonDateFormat;
    public $jsonTimeFormat;
    public $jsonTimezone;

    public $databaseTimestampFormat;
    public $databaseDateFormat;
    public $databaseTimeFormat;
    public $databaseTimezone;


}
