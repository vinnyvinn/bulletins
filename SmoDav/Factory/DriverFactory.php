<?php

namespace SmoDav\Factory;

use App\Driver;

class DriverFactory
{
    public static function create($attributes)
    {
        $driver = new Driver();
        $driver->fill($attributes);
        return $driver->save();
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
        return $driver->fill($attributes)->save();
    }
}
