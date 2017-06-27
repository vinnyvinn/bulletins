<?php

namespace App;

use App\Http\Helpers\Helpers;
use App\Http\Requests\ModelRequest;
use Illuminate\Database\Eloquent\Model;

class SAGEUDF extends Model
{
    protected $fillable = ['cLookupOptions'];

    protected $table = '_rtblUserDict';

    protected $primaryKey = 'idUserDict';

    public $timestamps = false;

    const MODEL_UDF = 'model_udf';
    const BRAND_UDF = 'brand_udf';

    public static function updateUDFs()
    {
        $brand = Helpers::get_option(SAGEUDF::BRAND_UDF);
        $model = Helpers::get_option(SAGEUDF::MODEL_UDF);

        $udfs = self::whereIn('idUserDict', [$brand, $model])->get(['idUserDict', 'cLookupOptions']);

        foreach ($udfs as $udf) {
            $options = explode(';', $udf->cLookupOptions);

            foreach ($options as $option) {
                switch ($udf->idUserDict) {
                    case $brand:
                        Brand::firstOrCreate(['brand_name' => $option]);
                        continue;
                        break;
                    case $model:
                        ModelRequest::firstOrCreate(['brand_name' => $option]);
                        continue;
                        break;
                }
            }
        }
    }

    public static function addMakeUDF($value)
    {
        $brand = Helpers::get_option(SAGEUDF::BRAND_UDF);

        return self::addLookupUDF($brand, $value);
    }

    public static function addModelUDF($value)
    {
        $modelUdf = Helpers::get_option(SAGEUDF::MODEL_UDF);

        return self::addLookupUDF($modelUdf, $value);
    }

    private static function addLookupUDF($itemUdf, $value)
    {
        $udf = self::where('idUserDict', $itemUdf)->first();

        $lookup = explode(';', $udf->cLookupOptions);
        if (! in_array($value, $lookup)) {
            $lookup[] = $value;
        }

        return $udf->update([
            'cLookupOptions' => implode(';', $lookup)
        ]);
    }
}
