<?php

namespace SmoDav\Models;

use App\Http\Helpers\Helpers;
use App\Option;
use Carbon\Carbon;
use DB;

class Make extends SmoDavModel
{
    protected $fillable = ['name'];

    const UDF = 'make_udf';

    public function models()
    {
        return $this->hasMany(Model::class);
    }

    public static function updateFromSAGE()
    {
        $makeColumn = collect(DB::select('SELECT cFieldName FROM _rtblUserDict WHERE idUserDict = ' .
            "(SELECT option_value FROM options WHERE option_key = '" . self::UDF . "')"))
            ->first();

        if (! $makeColumn) {
            return false;
        }

        $current = self::all(['name'])->map(function ($make) {
            return $make->name;
        })->toArray();

        $makes = collect(DB::table('StkItem')
            ->where('ItemGroup', Helpers::get_option(Option::OPTION_ITEM_GROUP))
            ->select([$makeColumn->cFieldName])
            ->distinct()
            ->get())
            ->reject(function ($make) use ($current, $makeColumn) {
                return \in_array($make->{$makeColumn->cFieldName}, $current);
            })
            ->map(function ($make) use ($makeColumn) {
                return [
                    'name' => $make->{$makeColumn->cFieldName},
                    'created_at' => Carbon::now()
                    ];
            })
            ->toArray();

        self::insert($makes);

        return true;
    }
}
