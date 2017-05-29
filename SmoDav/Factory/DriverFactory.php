<?php

namespace SmoDav\Factory;

use App\Driver;

class DriverFactory
{
    public static function create($attributes)
    {
        return Driver::create($attributes);

    }

    public static function all($columns = ['*'])
    {
        return Driver::all($columns);
    }

    public static function findOrFail($id)
    {
        return Driver::findOrFail($id);
    }

    public static function update(Driver $driver, $attributes)
    {
        return $driver->update($attributes);
    }
}
