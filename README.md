# Carbonated

An Eloquent model trait that offers flexible timestamp/date/time handling.

Eloquent provides DateTime handling through [Date Mutators](http://laravel.com/docs/5.1/eloquent-mutators#date-mutators).  However, it can be cumbersome having to set [Accessors](http://laravel.com/docs/5.1/eloquent-mutators#accessors-and-mutators) for custom DateTime formatting in your front end, and [Mutators](http://laravel.com/docs/5.1/eloquent-mutators#accessors-and-mutators) to correct custom DateTime formatting coming into your database.  Also, time field handling, nullable fields and timezone conversion are non-existent.  Carbonated aims to help you with these things.

- [Feature Overview](#feature-overview)
- [Requirements](#requirements)
- [Installation](#installation)
- [Basic Usage](#basic-usage)
- [Customization](#customization)
- [Timezone Conversion](#timezone-conversion)

# Feature Overview

- Automatic accessors and mutators for timestamp, date, and time fields.
- Set output formatting once in your model.
- No need to `format()` every attribute for output.
- Carbon instances are still available when you need to `format()` output.
- Timezone support with automatic conversion between database and front end.
- Plays friendly with [form generators](https://github.com/adamwathan/form) that use [model binding](https://github.com/adamwathan/form#model-binding).

# Requirements

- [Laravel 5.0+](http://laravel.com) or [illuminate/database 5.0+](https://github.com/illuminate/database/tree/master)

# Installation

Via [Composer](https://getcomposer.org):
```
composer require 'jerseymilker/carbonated:dev-master'
```

# Basic Usage

Use Carbonated trait in your Eloquent model.
```php
<?php namespace App;

use JerseyMilker\Carbonated;

class ServiceOrder extends Model {

    use Carbonated;

}
```

Add timestamp, date, and time fields to their respective carbonated model properties.
```php
public $carbonatedTimestamps = ['created_at', 'updated_at'];
public $carbonatedDates = ['required_by', 'completed_on', 'invoiced_on'];
public $carbonatedTimes = ['pickup_time'];
```

That's it!  Accessors and mutators are automatically applied with sensible formatting for front end.
```php
{{ $serviceOrder->created_at }}  // Outputs 'Jun 09, 2015 4:10pm'.
{{ $serviceOrder->required_by }} // Outputs 'Jul 30, 2015'.
{{ $serviceOrder->pickup_time }} // Outputs '10:30am'.
```

If you need access to raw carbon instances, the `withCarbon` attribute returns a clone of your object with carbon instances instead of formatted strings.
```php
{{ $serviceOrder->withCarbon->required_by->format('M Y') }}
```

# Customization

Customize view output format by adding these properties to your model.
```php
public $carbonatedTimestampFormat = 'M d, Y g:ia';
public $carbonatedDateFormat = 'M d, Y';
public $carbonatedTimeFormat = 'g:ia';
```

Customize JSON output format by adding these properties to your model.
```php
public $jsonTimestampFormat = 'Y-m-d H:i:s';
public $jsonDateFormat = 'Y-m-d';
public $jsonTimeFormat = 'H:i:s';
```

Customize database storage format by adding these properties to your model.
```php
public $databaseTimestampFormat = 'Y-m-d H:i:s';
public $databaseDateFormat = 'Y-m-d';
public $databaseTimeFormat = 'H:i:s';
```

You can also override all automatic accessors and mutators by providing your own [Accessor and Mutator](http://laravel.com/docs/5.1/eloquent-mutators#accessors-and-mutators) methods in your model.
```php
public function getRequiredByAttribute($value)
{
    return $value; // Returns raw value from database.
}
```

# Timezone Conversion

Carbonated supports automatic timezone conversion between your database and front end.  For example, maybe you are storing as `UTC` in your database, but want to output as `America/Toronto`.

You can set explicitly set timezones by adding these properties to your model.
```php
public $carbonatedTimezone = 'America/Toronto';
public $jsonTimezone = 'UTC';
public $databaseTimezone = 'UTC';
```

If `$carbonatedTimezone` is not defined in your model, Carbonated will search for an authenticated user with a `$timezone` property.  This allows the user model be responsible for user specific timezones.
```php
public $timezone = 'America/Toronto;'
```

The above properties can be set dynamically using [Accessors](http://laravel.com/docs/5.1/eloquent-mutators#accessors-and-mutators) instead of explicit properties.
```php
public function getTimezoneAttribute()
{
    return 'America/Toronto';
}
```

If either `$carbonatedTimezone` or `$jsonTimezone` are undefined, `$databaseTimezone` will be used as a fallback.

If `$databaseTimezone` is undefined, the app's timezone (found in `/config/app.php`) will be used as a fallback.

If you are using Carbonated outside of Laravel, `$databaseTimezone` will default to `UTC`.
