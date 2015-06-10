# Carbonated

An Eloquent model trait that offers more flexible timestamp/date/time handling.

Eloquent provides DateTime handling through [Date Mutators](http://laravel.com/docs/5.1/eloquent-mutators#date-mutators).  However, it can be cumbersome having to set [Accessors](http://laravel.com/docs/5.1/eloquent-mutators#accessors-and-mutators) for custom DateTime formatting in your views, and [Mutators](http://laravel.com/docs/5.1/eloquent-mutators#accessors-and-mutators) to correct custom DateTime formatting coming into your database.  Also, time field handling and timezone conversion are non-existent.  Carbonated aims to help you with these things.

# Requirements

- [Laravel 5.0+](http://laravel.com) or [illuminate/database 5.0+](https://github.com/illuminate/database/tree/master)

# Feature Overview

- Automatic accessors and mutators for timestamp, date, and time fields.
- Set output formatting once in your model.
- No need to `format()` output in your views.
- Carbon instances are still available when you need to `format()` output.
- Timezone support with automatic conversion between database and view output.
- Plays friendly with [form generators](https://github.com/adamwathan/form) that use [model binding](https://github.com/adamwathan/form#model-binding).

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

By default, all properties now have accessors and mutators applied with sensible view formatting.
```php
{{ $serviceOrder->created_at }} // outputs 'Jun 09, 2015 4:10pm'
{{ $serviceOrder->required_by }} // outputs 'Jul 30, 2015'
{{ $serviceOrder->pickup_time }} // outputs '10:30am'
```

Customize output format by adding these properties.
```php
public $carbonatedTimestampFormat = 'M d, Y g:ia';
public $carbonatedDateFormat = 'M d, Y';
public $carbonatedTimeFormat = 'g:ia';
```

If you want to reformat output in your view, the `carbon` attribute holds raw carbon instances necessary for this.
```php
{{ $serviceOrder->carbon->required_by->format('M Y') }}
```

You can also override the automatic accessors and mutators by providing your own [Accessor and Mutator](http://laravel.com/docs/5.1/eloquent-mutators#accessors-and-mutators) methods in your model.
```php
public function getRequiredByAttribute()
{
    return $this->carbon->required_by->format('M Y');
}
```

# Timezone Support

Carbonated supports automatic timezone conversion between database and view output.  If your app timezone (in `/config/app.php`) is set to `UTC`, but you want to display `America/Toronto` to your end users, two options are available:

You can set timezone using the following property in your model.
```php
public $carbonatedTimezone = 'America/Toronto';
```

If the above property is not available in your model, Carbonated will search the currently authenticated User object for a `getTimezone()` method.  This allows the User model be responsible for User specific timezones.
```php
public function getTimezone()
{
    return $this->timezone;
}
```

# To-Do:

- Everything crashes and burns if `\Auth::user()` is not available for checking timezone :/  Will fix soon.
