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
     * Indicate whether accessor should return carbon.
     *
     * @var boolean
     */
    protected $returnCarbon = false;

    /**
     * Get the attributes that should be handled as carbonated timestamps.
     *
     * @return array
     */
    protected function carbonatedTimestamps()
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
    protected function carbonatedDates()
    {
        return isset($this->carbonatedDates) ? (array) $this->carbonatedDates : [];
    }

    /**
     * Get the attributes that should be handled as carbonated times.
     *
     * @return array
     */
    protected function carbonatedTimes()
    {
        return isset($this->carbonatedTimes) ? (array) $this->carbonatedTimes : [];
    }

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
        // Check for getCarbonatedTimezone() method in model.
        if (method_exists(get_class($this), 'getCarbonatedTimezone')) {
            return $this->getCarbonatedTimezone();
        }

        // Check for $carbonatedTimezone property in model.
        elseif (isset($this->carbonatedTimezone)) {
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
     * Get the intended timestamp for json output.
     *
     * @return string
     */
    public function jsonTimestampFormat()
    {
        return isset($this->jsonTimestampFormat) ? $this->jsonTimestampFormat : $this->databaseTimestampFormat();
    }

    /**
     * Get the intended date for json output.
     *
     * @return string
     */
    public function jsonDateFormat()
    {
        return isset($this->jsonDateFormat) ? $this->jsonDateFormat : $this->databaseDateFormat();
    }

    /**
     * Get the intended time for json output.
     *
     * @return string
     */
    public function jsonTimeFormat()
    {
        return isset($this->jsonTimeFormat) ? $this->jsonTimeFormat : $this->databaseTimeFormat();
    }

    /**
     * Get the intended timezone for json output.
     *
     * @return array
     */
    public function jsonTimezone()
    {
        // Check for getJsonTimezone() method in model.
        if (method_exists(get_class($this), 'getJsonTimezone')) {
            return $this->getJsonTimezone();
        }

        // Check for $jsonTimezone property in model.
        elseif (isset($this->jsonTimezone)) {
            return $this->jsonTimezone;
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
        // Check for getDatabaseTimezone() method in model.
        if (method_exists(get_class($this), 'getDatabaseTimezone')) {
            return $this->getdatabaseTimezone();
        }

        // Check for $databaseTimezone property in model.
        elseif (isset($this->getDatabaseTimezone)) {
            return $this->databaseTimezone;
        }

        // Otherwise use app's timezone configuration.
        return config('app.timezone');
    }

    /**
     * Return an object containing carbon instances of all specified date/time fields.
     *
     * @return \Carbon\Carbon
     */
    protected function carbonInstances()
    {
        // Check if date/time fields have already been carbonated.
        if ($this->carbonInstances) {
            return $this->carbonInstances;
        }

        // If not, get timezones.
        $databaseTimezone = $this->databaseTimezone();
        $carbonatedTimezone = $this->carbonatedTimezone();

        // Get database field formats.
        foreach ($this->carbonatedTimestamps() as $field) {
            $fieldFormats[$field] = $this->databaseTimestampFormat();
        }
        foreach ($this->carbonatedDates() as $field) {
            $fieldFormats[$field] = $this->databaseDateFormat();
        }
        foreach ($this->carbonatedTimes() as $field) {
            $fieldFormats[$field] = $this->databaseTimeFormat();
        }

        // Create carbon instances.
        foreach ($fieldFormats as $field => $format) {
            $value = $this->getOriginal($field);
            $carbonInstances[$field] = $value ? Carbon::createFromFormat($format, $value, $databaseTimezone)->timezone($carbonatedTimezone) : null;
        }

        // And store carbon instances for future use.
        $this->carbonInstances = isset($carbonInstances) ? (object) $carbonInstances : null;

        return $this->carbonInstances;
    }

    /**
     * Return a clone of $this object with flag to modify accessors.
     *
     * @return $this
     */
    public function getWithCarbonAttribute()
    {
        // Clone $this to preserve object state.
        $clone = clone $this;

        // Set flag for accessors to return carbon instances.
        $clone->returnCarbon = true;

        return $clone;
    }

    /**
     * Get final timestamp string for displaying to end user.
     *
     * @param  string  $key
     * @return string
     */
    protected function viewableTimestamp($key)
    {
        return $this->carbonInstances()->$key ? $this->carbonInstances()->$key->format($this->carbonatedTimestampFormat()) : null;
    }

    /**
     * Get final date string for displaying to end user.
     *
     * @param  string  $key
     * @return string
     */
    protected function viewableDate($key)
    {
        return $this->carbonInstances()->$key ? $this->carbonInstances()->$key->format($this->carbonatedDateFormat()) : null;
    }

    /**
     * Get final time string for displaying to end user.
     *
     * @param  string  $key
     * @return string
     */
    protected function viewableTime($key)
    {
        return $this->carbonInstances()->$key ? $this->carbonInstances()->$key->format($this->carbonatedTimeFormat()) : null;
    }

    /**
     * Get final timestamp string for json output.
     *
     * @param  string  $key
     * @return string
     */
    protected function jsonTimestamp($key)
    {
        // Get necessary data for conversion.
        $jsonFormat = $this->jsonTimestampFormat();
        $jsonTimezone = $this->jsonTimezone();

        // Return JSON output.
        return $this->carbonInstances()->$key ? $this->carbonInstances()->$key->timezone($jsonTimezone)->format($jsonFormat) : null;
    }

    /**
     * Get final date string for json output.
     *
     * @param  string  $key
     * @return string
     */
    protected function jsonDate($key)
    {
        // Get necessary data for conversion.
        $jsonFormat = $this->jsonDateFormat();
        $jsonTimezone = $this->jsonTimezone();

        // Return JSON output.
        return $this->carbonInstances()->$key ? $this->carbonInstances()->$key->timezone($jsonTimezone)->format($jsonFormat) : null;
    }

    /**
     * Get final time string for json output.
     *
     * @param  string  $key
     * @return string
     */
    protected function jsonTime($key)
    {
        // Get necessary data for conversion.
        $jsonFormat = $this->jsonTimeFormat();
        $jsonTimezone = $this->jsonTimezone();

        // Return JSON output.
        return $this->carbonInstances()->$key ? $this->carbonInstances()->$key->timezone($jsonTimezone)->format($jsonFormat) : null;
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
     * Override default toArray() to include our own accessors.
     *
     * @return array
     */
    public function toArray($useJsonAccessors = false)
    {
        $attributes = $this->attributesToArray();

        // If returning JSON output, reference our own accessors for relevant date/time fields.
        if ($useJsonAccessors) {
            foreach ($attributes as $key => $value) {
                if (! $this->hasGetMutator($key) && in_array($key, $this->carbonatedTimestamps())) {
                    $attributes[$key] = $this->jsonTimestamp($key);
                } elseif (! $this->hasGetMutator($key) && in_array($key, $this->carbonatedDates())) {
                    $attributes[$key] = $this->jsonDate($key);
                } elseif (! $this->hasGetMutator($key) && in_array($key, $this->carbonatedTimes())) {
                    $attributes[$key] = $this->jsonTime($key);
                }
            }
        }

        return array_merge($attributes, $this->relationsToArray());
    }

    /**
     * Override default jsonSerialize() to set $jsonSerialize property flag for accessors.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray(true);
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

        // Check for the presence of an accessor in our model.
        if ($this->hasGetMutator($key)) {
            return $this->mutateAttribute($key, $value);
        }

        // If no accessor found, reference our own accessors for relevant date/time fields.
        if (in_array($key, $this->carbonatedTimestamps())) {
            $value = $this->returnCarbon ? $this->carbonInstances()->$key : $this->viewableTimestamp($key);
        } elseif (in_array($key, $this->carbonatedDates())) {
            $value = $this->returnCarbon ? $this->carbonInstances()->$key : $this->viewableDate($key);
        } elseif (in_array($key, $this->carbonatedTimes())) {
            $value = $this->returnCarbon ? $this->carbonInstances()->$key : $this->viewableTime($key);
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
        // Check for the presence of a mutator in our model.
        if ($this->hasSetMutator($key)) {
            $method = 'set'.studly_case($key).'Attribute';

            return $this->{$method}($value);
        }

        // If no mutator found, reference our own mutators for relevant date/time fields.
        elseif (in_array($key, $this->carbonatedTimestamps())) {
            $value = $this->storableTimestamp($value);
        } elseif (in_array($key, $this->carbonatedDates())) {
            $value = $this->storableDate($value);
        } elseif (in_array($key, $this->carbonatedTimes())) {
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
