# Carbonated

An Eloquent model trait that offers flexible timestamp/date/time handling.

Eloquent provides DateTime handling through [Date Mutators](http://laravel.com/docs/5.1/eloquent-mutators#date-mutators).  However, it can be cumbersome having to set [Accessors](http://laravel.com/docs/5.1/eloquent-mutators#accessors-and-mutators) for custom DateTime formatting in your views, and [Mutators](http://laravel.com/docs/5.1/eloquent-mutators#accessors-and-mutators) to correct custom DateTime formatting coming into your database.  Also, time field handling, nullable fields and timezone conversion are non-existent.  Carbonated aims to help you with these things.

- [Feature Overview](#feature-overview)
- [Requirements](#requirements)
- [Installation](#installation)
- [Basic Usage](#basic-usage)
- [Customization](#customization)
- [Timezone Conversion](#timezone-conversion)

# Feature Overview

- Automatic accessors and mutators for timestamp, date, and time fields.
- Set output formatting once in your model.
- No need to `format()` output in your views.
- Carbon instances are still available when you need to `format()` output.
- Timezone support with automatic conversion between database and view output.
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

If you need access to raw carbon instances, you can access these through the `carbon` attribute.
```php
{{ $serviceOrder->carbon->required_by->format('M Y') }}
```

# Customization

Customize output format by adding these properties to your model.
```php
public $carbonatedTimestampFormat = 'M d, Y g:ia';
public $carbonatedDateFormat = 'M d, Y';
public $carbonatedTimeFormat = 'g:ia';
```

Customize storage format by adding these properties to your model.
```php
public $databaseTimestampFormat = 'Y-m-d H:i:s';
public $databaseDateFormat = 'Y-m-d';
public $databaseTimeFormat = 'H:i:s';
```

Disable the automatic accessors for JSON output by adding this property to your model.
```php
public $carbonatedJson = false;
```

You can also override the automatic accessors and mutators by providing your own [Accessor and Mutator](http://laravel.com/docs/5.1/eloquent-mutators#accessors-and-mutators) methods in your model.
```php
public function getRequiredByAttribute()
{
    return $this->carbon->required_by->format('M Y');
}
```

If you are passing a model instance to a 3rd party helper, it won't know about the mentioned `carbon` attribute for accessing raw carbon instances.  For these situations, you can use the `withCarbon()` object modifier.  This will set a property on your object to override all accessors and return raw carbon instances by default.
```php
$order = $orders->find($id)->withCarbon() // Use withCarbon() object modifier.
$order->required_by                       // Outputs carbon instance.
```

# Timezone Conversion

Carbonated supports automatic timezone conversion between your database and front end.  For example, maybe you are storing as `UTC` in your database, but want to display as `America/Toronto` in your front end.

You can set explicitly set your timezones by adding these properties to your model.
```php
public $carbonatedTimezone = 'America/Toronto';
public $databaseTimezone = 'UTC';
```

If the `$carbonatedTimezone` property is not explicitly set, Carbonated will search the currently authenticated user object for a `getTimezone()` method.  This allows the user model be responsible for user specific timezones.
```php
public function getTimezone()
{
    return $this->timezone;
}
```

If the `$databaseTimezone` property is not explicitly set, the app's timezone (found in `/config/app.php`) will be used instead.
