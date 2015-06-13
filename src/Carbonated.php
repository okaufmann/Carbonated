<?php

namespace JerseyMilker;

use Carbon\Carbon;

trait Carbonated
{
    /**
     * Store carbon instances for use by carbon accessor.
     *
     * @var \Carbon\Carbon
     */
    protected $carbonInstances;

    /**
     * Get the intended timestamp format for displaying to end user.
     *
     * @return string
     */
    public function carbonatedTimestampFormat()
    {
        return isset($this->carbonatedTimestampFormat) ? $this->carbonatedTimestampFormat : 'M d, Y g:ia';
    }

    /**
     * Get the intended date format for displaying to end user.
     *
     * @return string
     */
    public function carbonatedDateFormat()
    {
        return isset($this->carbonatedDateFormat) ? $this->carbonatedDateFormat : 'M d, Y';
    }

    /**
     * Get the intended date format for displaying to end user.
     *
     * @return string
     */
    public function carbonatedTimeFormat()
    {
        return isset($this->carbonatedTimeFormat) ? $this->carbonatedTimeFormat : 'g:ia';
    }

    /**
     * Get the intended timezone for displaying to end user.
     *
     * @return array
     */
    public function carbonatedTimezone()
    {
        // Check for $carbonatedTimezone property in model.
        if (isset($this->carbonatedTimezone)) {
            return $this->carbonatedTimezone;
        }

        // If not, check for Auth::user() with a getTimezone() method.
        elseif (class_exists(\Auth::class) && \Auth::check() && method_exists(config('auth.model'), 'getTimezone')) {
            if (\Auth::user()->getTimezone()) {
                return \Auth::user()->getTimezone();
            }
        }

        // Otherwise use same timezone as database.
        return $this->databaseTimezone();
    }

    /**
     * Get the intended database format for timestamp storage.
     *
     * @return string
     */
    protected function databaseTimestampFormat()
    {
        return isset($this->databaseTimestampFormat) ? $this->databaseTimestampFormat : 'Y-m-d H:i:s';
    }

    /**
     * Get the intended database format for date storage.
     *
     * @return string
     */
    protected function databaseDateFormat()
    {
        return isset($this->databaseDateFormat) ? $this->databaseDateFormat : 'Y-m-d';
    }

    /**
     * Get the intended database format for time storage.
     *
     * @return string
     */
    protected function databaseTimeFormat()
    {
        return isset($this->databaseTimeFormat) ? $this->databaseTimeFormat : 'H:i:s';
    }

    /**
     * Get the intended timezone for database storage.
     *
     * @return string
     */
    protected function databaseTimezone()
    {
        return isset($this->databaseTimezone) ? $this->databaseTimezone : config('app.timezone');
    }

    /**
     * Return an object containing carbon instances of all specified date/time fields.
     *
     * @return \Carbon\Carbon
     */
    public function getCarbonAttribute()
    {
        // Check if date/time fields have already been carbonated.
        if ($this->carbonatedInstances) {
            return $this->carbonatedInstances;
        }

        // If not, get timezones.
        $databaseTimezone = $this->databaseTimezone();
        $carbonatedTimezone = $this->carbonatedTimezone();

        // Get database field formats.
        foreach ($this->getCarbonatedTimestamps() as $field) {
            $fieldFormats[$field] = $this->databaseTimestampFormat();
        }
        foreach ($this->getCarbonatedDates() as $field) {
            $fieldFormats[$field] = $this->databaseDateFormat();
        }
        foreach ($this->getCarbonatedTimes() as $field) {
            $fieldFormats[$field] = $this->databaseTimeFormat();
        }

        // Create carbon instances.
        foreach ($fieldFormats as $field => $format) {
            $value = $this->getOriginal($field);
            $carbonInstances[$field] = $value ? Carbon::createFromFormat($format, $value, $databaseTimezone)->timezone($carbonatedTimezone) : null;
        }

        // And store carbon instances for future use.
        $this->carbonatedInstances = isset($carbonInstances) ? (object) $carbonInstances : null;

        return $this->carbonatedInstances;
    }

    /**
     * Get the attributes that should be handled as carbonated timestamps.
     *
     * @return array
     */
    protected function getCarbonatedTimestamps()
    {
        // Add default timestamp fields created by migrations schema builder.
        $defaults = [static::CREATED_AT, static::UPDATED_AT, 'deleted_at'];

        return isset($this->carbonatedTimestamps) ? array_unique(array_merge($this->carbonatedTimestamps, $defaults)) : $defaults;
    }

    /**
     * Get the attributes that should be handled as carbonated dates.
     *
     * @return array
     */
    protected function getCarbonatedDates()
    {
        return isset($this->carbonatedDates) ? (array) $this->carbonatedDates : [];
    }

    /**
     * Get the attributes that should be handled as carbonated times.
     *
     * @return array
     */
    protected function getCarbonatedTimes()
    {
        return isset($this->carbonatedTimes) ? (array) $this->carbonatedTimes : [];
    }

    /**
     * Get final timestamp string for displaying to end user.
     *
     * @param  string  $key
     * @return string
     */
    protected function viewableTimestamp($key)
    {
        return $this->carbon->$key ? $this->carbon->$key->format($this->carbonatedTimestampFormat()) : null;
    }

    /**
     * Get final date string for displaying to end user.
     *
     * @param  string  $key
     * @return string
     */
    protected function viewableDate($key)
    {
        return $this->carbon->$key ? $this->carbon->$key->format($this->carbonatedDateFormat()) : null;
    }

    /**
     * Get final time string for displaying to end user.
     *
     * @param  string  $key
     * @return string
     */
    protected function viewableTime($key)
    {
        return $this->carbon->$key ? $this->carbon->$key->format($this->carbonatedTimeFormat()) : null;
    }

    /**
     * Mutate incoming timestamp for database storage.
     *
     * @param  mixed  $value
     * @return string
     */
    protected function storableTimestamp($value)
    {
        // If Eloquent returns value via freshTimestamp(), it will already be a carbon object.
        if (is_object($value)) {
            $databaseFormat = $this->databaseTimestampFormat();

            // All we need to do is convert to storable value.
            return $value->format($databaseFormat);
        }

        // Otherwise get necessary data for conversion.
        $carbonatedFormat = $this->carbonatedTimestampFormat();
        $databaseFormat = $this->databaseTimestampFormat();
        $carbonatedTimezone = $this->carbonatedTimezone();
        $databaseTimezone = $this->databaseTimezone();

        // Return storable value.
        return $value ? Carbon::createFromFormat($carbonatedFormat, $value, $carbonatedTimezone)->timezone($databaseTimezone)->format($databaseFormat) : null;
    }

    /**
     * Mutate incoming date for database storage.
     *
     * @param  string  $value
     * @return string
     */
    protected function storableDate($value)
    {
        // Get necessary data for conversion.
        $carbonatedFormat = $this->carbonatedDateFormat();
        $databaseFormat = $this->databaseDateFormat();
        $carbonatedTimezone = $this->carbonatedTimezone();
        $databaseTimezone = $this->databaseTimezone();

        // Return storable value.
        return $value ? Carbon::createFromFormat($carbonatedFormat, $value, $carbonatedTimezone)->timezone($databaseTimezone)->format($databaseFormat) : null;
    }

    /**
     * Mutate incoming time for database storage.
     *
     * @param  string  $value
     * @return string
     */
    protected function storableTime($value)
    {
        // Get necessary data for conversion.
        $carbonatedFormat = $this->carbonatedTimeFormat();
        $databaseFormat = $this->databaseTimeFormat();
        $carbonatedTimezone = $this->carbonatedTimezone();
        $databaseTimezone = $this->databaseTimezone();

        // Return storable value.
        return $value ? Carbon::createFromFormat($carbonatedFormat, $value, $carbonatedTimezone)->timezone($databaseTimezone)->format($databaseFormat) : null;
    }

    /**
     * Override default getDates() to allow created_at and updated_at handling by carbonated.
     *
     * @return array
     */
    public function getDates()
    {
        return (array) $this->dates;
    }

    /**
     * Override default freshTimestamp() to be more explicit in setting timezone for storage.
     *
     * @return \Carbon\Carbon
     */
    public function freshTimestamp()
    {
        return Carbon::now($this->databaseTimezone());
    }

    /**
     * Override default getAttributeValue() to include our own accessors.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttributeValue($key)
    {
        $value = $this->getAttributeFromArray($key);

        // First we will check for the presence of a mutator in our model.
        if ($this->hasGetMutator($key)) {
            return $this->mutateAttribute($key, $value);
        }

        // If no accessor found, reference our own accessors for relevant date/time fields.
        if (in_array($key, $this->getCarbonatedTimestamps())) {
            $value = $this->viewableTimestamp($key);
        } elseif (in_array($key, $this->getCarbonatedDates())) {
            $value = $this->viewableDate($key);
        } elseif (in_array($key, $this->getCarbonatedTimes())) {
            $value = $this->viewableTime($key);
        }

        // Otherwise, revert to default Eloquent behavour.
        if ($this->hasCast($key)) {
            $value = $this->castAttribute($key, $value);
        } elseif (in_array($key, $this->getDates())) {
            if (!is_null($value)) {
                return $this->asDateTime($value);
            }
        }

        return $value;
    }

    /**
     * Override default setAttribute() to include our own mutators.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public function setAttribute($key, $value)
    {
        // First we will check for the presence of a mutator in our model.
        if ($this->hasSetMutator($key)) {
            $method = 'set'.studly_case($key).'Attribute';

            return $this->{$method}($value);
        }

        // If no mutator found, reference our own mutators for relevant date/time fields.
        elseif (in_array($key, $this->getCarbonatedTimestamps())) {
            $value = $this->storableTimestamp($value);
        } elseif (in_array($key, $this->getCarbonatedDates())) {
            $value = $this->storableDate($value);
        } elseif (in_array($key, $this->getCarbonatedTimes())) {
            $value = $this->storableTime($value);
        }

        // Otherwise, revert to default Eloquent behavour.
        elseif (in_array($key, $this->getDates()) && $value) {
            $value = $this->fromDateTime($value);
        }

        if ($this->isJsonCastable($key)) {
            $value = json_encode($value);
        }

        $this->attributes[$key] = $value;
    }
}
