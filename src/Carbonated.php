<?php

namespace ThisVessel;

use Carbon\Carbon;
use Request;

trait Carbonated
{
    /**
     * Store carbon instances for reuse.
     *
     * @var object
     */
    protected $carbonInstances;

    /**
     * Indicate whether accessors should be overridden to return carbon instances.
     *
     * @var bool
     */
    protected $returnCarbon = false;

    /**
     * Get the attributes that should be handled as carbonated timestamps.
     *
     * @return array
     */
    public function carbonatedTimestamps()
    {
        // Add default fields for schema builder's timestamps() and softDeletes().
        $defaults = [static::CREATED_AT, static::UPDATED_AT, 'deleted_at'];

        return $this->ensureProperty($this, 'carbonatedTimestamps') ? array_unique(array_merge($this->carbonatedTimestamps, $defaults)) : $defaults;
    }

    /**
     * Get the attributes that should be handled as carbonated dates.
     *
     * @return array
     */
    public function carbonatedDates()
    {
        return $this->ensureProperty($this, 'carbonatedDates') ? (array) $this->carbonatedDates : [];
    }

    /**
     * Get the attributes that should be handled as carbonated times.
     *
     * @return array
     */
    public function carbonatedTimes()
    {
        return $this->ensureProperty($this, 'carbonatedTimes') ? (array) $this->carbonatedTimes : [];
    }

    /**
     * Get all attributes that should be handled by carbonated.
     *
     * @return array
     */
    public function carbonatedAttributes()
    {
        return array_merge($this->carbonatedTimestamps(), $this->carbonatedDates(), $this->carbonatedTimes());
    }

    /**
     * Get carbonated attribute type.
     *
     * @param string $key
     *
     * @return string
     */
    public function carbonatedAttributeType($key)
    {
        if (in_array($key, $this->carbonatedTimestamps())) {
            return 'timestamp';
        } elseif (in_array($key, $this->carbonatedDates())) {
            return 'date';
        } elseif (in_array($key, $this->carbonatedTimes())) {
            return 'time';
        }

        return false;
    }

    /**
     * Get the intended timestamp format for view output.
     *
     * @return string
     */
    public function carbonatedTimestampFormat()
    {
        return $this->ensureProperty($this, 'carbonatedTimestampFormat') ? (string) $this->carbonatedTimestampFormat : 'M d, Y g:ia';
    }

    /**
     * Get the intended date format for view output.
     *
     * @return string
     */
    public function carbonatedDateFormat()
    {
        return $this->ensureProperty($this, 'carbonatedDateFormat') ? (string) $this->carbonatedDateFormat : 'M d, Y';
    }

    /**
     * Get the intended date format for view output.
     *
     * @return string
     */
    public function carbonatedTimeFormat()
    {
        return $this->ensureProperty($this, 'carbonatedTimeFormat') ? (string) $this->carbonatedTimeFormat : 'g:ia';
    }

    /**
     * Get the intended timezone for view output.
     *
     * @return string
     */
    public function carbonatedTimezone()
    {
        // Check for $carbonatedTimezone property in model.
        if ($this->ensureProperty($this, 'carbonatedTimezone')) {
            return (string) $this->carbonatedTimezone;
        } // If not, check for an authenticated user with a $timezone property.
        elseif (class_exists(\Auth::class) && \Auth::check() && \Auth::user()->timezone) {
            return (string) \Auth::user()->timezone;
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
        return $this->ensureProperty($this, 'jsonTimestampFormat') ? (string) $this->jsonTimestampFormat : $this->databaseTimestampFormat();
    }

    /**
     * Get the intended date for json output.
     *
     * @return string
     */
    public function jsonDateFormat()
    {
        return $this->ensureProperty($this, 'jsonDateFormat') ? (string) $this->jsonDateFormat : $this->databaseDateFormat();
    }

    /**
     * Get the intended time for json output.
     *
     * @return string
     */
    public function jsonTimeFormat()
    {
        return $this->ensureProperty($this, 'jsonTimeFormat') ? (string) $this->jsonTimeFormat : $this->databaseTimeFormat();
    }

    /**
     * Get the intended timezone for json output.
     *
     * @return string
     */
    public function jsonTimezone()
    {
        // Check for $jsonTimezone property in model.
        if ($this->ensureProperty($this, 'jsonTimezone')) {
            return (string) $this->jsonTimezone;
        }

        // Otherwise use same timezone as database.
        return $this->databaseTimezone();
    }

    /**
     * Get the intended database format for timestamp storage.
     *
     * @return string
     */
    public function databaseTimestampFormat()
    {
        return $this->ensureProperty($this, 'databaseTimestampFormat') ? (string) $this->databaseTimestampFormat : 'Y-m-d H:i:s';
    }

    /**
     * Get the intended database format for date storage.
     *
     * @return string
     */
    public function databaseDateFormat()
    {
        return $this->ensureProperty($this, 'databaseDateFormat') ? (string) $this->databaseDateFormat : 'Y-m-d';
    }

    /**
     * Get the intended database format for time storage.
     *
     * @return string
     */
    public function databaseTimeFormat()
    {
        return $this->ensureProperty($this, 'databaseTimeFormat') ? (string) $this->databaseTimeFormat : 'H:i:s';
    }

    /**
     * Get the intended timezone for database storage.
     *
     * @return string
     */
    public function databaseTimezone()
    {
        // Check for $databaseTimezone property in model.
        if ($this->ensureProperty($this, 'databaseTimezone')) {
            return (string) $this->databaseTimezone;
        }

        // Otherwise use app's timezone configuration.
        return (string) function_exists('config') ? config('app.timezone') : 'UTC';
    }

    /**
     * Store and return carbon instances for reuse.
     *
     * @return object
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

        $fieldFormats = [];

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

        // Create Carbon instances.
        $carbonInstances = [];
        foreach ($fieldFormats as $field => $format) {
            $value = isset($this->attributes[$field]) ? $this->attributes[$field] : null;
            $carbonInstance = $value ? Carbon::createFromFormat($format, $value, $databaseTimezone) : null;
            $carbonInstances[$field] = $carbonInstance ? $carbonInstance->timezone($carbonatedTimezone) : null;
        }

        // Store Carbon instances for future use.
        $this->carbonInstances = isset($carbonInstances) ? (object) $carbonInstances : null;

        // Return Carbon instances.
        return $this->carbonInstances;
    }

    /**
     * Return a clone of $this object and modify it's accessors.
     *
     * @return $this
     */
    public function getWithCarbonAttribute()
    {
        // Clone $this to preserve it's state.
        $clone = clone $this;

        // Modify clone's accessors.
        $clone->returnCarbon = true;

        // Return clone.
        return $clone;
    }

    /**
     * Access and format for front end.
     *
     * @param string $key
     * @param bool   $json
     *
     * @return string
     */
    public function carbonatedAccessor($key, $json = false)
    {
        // Initial accesor setup.
        $accessorType = $json ? 'json' : 'carbonated';
        $fieldType = $this->carbonatedAttributeType($key);

        // Get output format and timezone for conversion.
        $outputFormat = $this->{$accessorType.ucfirst($fieldType).'Format'}();
        $outputTimezone = $this->{$accessorType.'Timezone'}();

        // Get Carbon instance.
        /** @var Carbon $carbonInstance */
        $carbonInstance = $this->carbonInstances()->$key;

        // Return formatted value.
        $timezonedInstance = $carbonInstance->timezone($outputTimezone);

        if ($this->useLocalizedFormats()) {
            return $carbonInstance ? $timezonedInstance->formatLocalized($outputFormat) : null;
        }

        return $carbonInstance ? $timezonedInstance->format($outputFormat) : null;
    }

    /**
     * Mutate to a storable value for database.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return string
     */
    public function carbonatedMutator($key, $value)
    {
        // Get type.
        $fieldType = $this->carbonatedAttributeType($key);

        // Get database format and timezone.
        $databaseFormat = $this->{'database'.ucfirst($fieldType).'Format'}();
        $databaseTimezone = $this->databaseTimezone();

        // If value is DateTime instance, convert to Carbon instance.
        if ($value instanceof \DateTime) {
            $value = Carbon::instance($value);
        }

        // It value is Carbon intance, return storable value.
        if ($value instanceof Carbon) {
            return $value->timezone($databaseTimezone)->format($databaseFormat);
        }

        // Otherwise, get input format and timezone for conversion.
        if (static::requestIsJson()) {
            $inputFormat = $this->{'json'.ucfirst($fieldType).'Format'}();
            $inputTimezone = $this->jsonTimezone();
        } else {
            $inputFormat = $this->{'carbonated'.ucfirst($fieldType).'Format'}();
            $inputTimezone = $this->carbonatedTimezone();
        }

        // Convert to Carbon instance.
        $carbonInstance = $value ? Carbon::createFromFormat($inputFormat, $value, $inputTimezone) : null;

        // Return storable value.
        return $carbonInstance ? $carbonInstance->timezone($databaseTimezone)->format($databaseFormat) : null;
    }

    /**
     * Check if request is some type of JSON (purposefully more lenient than current Request::isJson() helper).
     *
     * @return bool
     */
    public static function requestIsJson()
    {
        return request()->isJson();
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
        $databaseTimezone = $this->databaseTimezone();

        return Carbon::now($databaseTimezone);
    }

    /**
     * Override default toArray() to include our own accessors.
     *
     * @param bool $useJsonAccessors
     *
     * @return array
     */
    public function toArray($useJsonAccessors = false)
    {
        $attributes = $this->attributesToArray();

        // If returning JSON output, reference our own accessors for relevant date/time fields.
        if ($useJsonAccessors) {
            foreach ($attributes as $key => $value) {
                if (!$this->hasGetMutator($key) && in_array($key, $this->carbonatedAttributes())) {
                    $attributes[$key] = $this->carbonatedAccessor($key, true);
                }
            }
        }

        return array_merge($attributes, $this->relationsToArray());
    }

    /**
     * Override default jsonSerialize() to set $useJsonAccessors parameter.
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
     * @param string $key
     *
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
        if (in_array($key, $this->carbonatedAttributes())) {
            $value = $this->returnCarbon ? $this->carbonInstances()->$key : $this->carbonatedAccessor($key);
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
     * @param string $key
     * @param mixed  $value
     *
     * @return void
     */
    public function setAttribute($key, $value)
    {
        // Check for the presence of a mutator in our model.
        if ($this->hasSetMutator($key)) {
            $method = 'set'.studly_case($key).'Attribute';

            return $this->{$method}($value);
        } // If no mutator found, reference our own mutators for relevant date/time fields.
        elseif (in_array($key, $this->carbonatedAttributes())) {
            $value = $this->carbonatedMutator($key, $value);
        } // Otherwise, revert to default Eloquent behavour.
        elseif (in_array($key, $this->getDates()) && $value) {
            $value = $this->fromDateTime($value);
        }

        if ($this->isJsonCastable($key)) {
            $value = json_encode($value);
        }

        $this->attributes[$key] = $value;
    }

    /**
     * @param object $carbonInstances
     */
    public function setCarbonInstances($carbonInstances)
    {
        if (!is_object($carbonInstances)) {
            throw new \InvalidArgumentException('carbonInstances must be an object.');
        }
        $this->carbonInstances = $carbonInstances;
    }

    private function ensureProperty($instance, $propertyName)
    {
        if (!property_exists($instance, $propertyName)) {
            return false;
        }

        // Check property value for null and false values
        if (empty($instance->{$propertyName})) {
            return false;
        }

        return true;
    }

    private function useLocalizedFormats()
    {
        $localize = config('carbonated.localization', false);

        return $localize;
    }
}
