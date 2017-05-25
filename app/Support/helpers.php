<?php
use App\UDF;
use Illuminate\Database\Schema\Blueprint;

function convertString($string){
    $string =  preg_replace('/[^\da-z ]/i', '', $string);
    return strtolower(str_replace(' ','_',$string));
}

function flash($message, $status = 'success'){
    session()->flash('flash_message', $message);
    session()->flash('flash_status', $status);
}

function addColumn($tablename,$column,$columntype){
    if (Schema::hasColumn($tablename,$column)) {
        return false;
    }
    else{
        Schema::table($tablename,function ($table) use($column,$columntype){
            if ($columntype==App\UDF::TEXTAREA){
                $table->text($column)->nullable();
            }
            else{
                $table->string($column)->nullable();
            }

        });
        return true;
    }
}

function renamecolumn($tablename, $oldcolumn, $newcolumn){
    if (Schema::hasColumn($tablename, $oldcolumn)){
        $newcolumn = convertString($newcolumn);
        Schema::table($tablename,function ($table) use($oldcolumn, $newcolumn){
            $table->renameColumn($oldcolumn, $newcolumn);
        });
        return true;
    }
    else{
        return false;
    }
}

function deletecolumn($tablename, $column){
//    $column = convertString($column);
    if (Schema::hasColumn($tablename,$column)){
        Schema::table($tablename, function (Blueprint $table) use($column) {
            $table->dropColumn($column);
        });
        return true;
    }
    else{
        return false;
    }
}

function toJson($request){
    if ($request['input_type']== 'options'){

         return json_encode($array);
    }
    die();
}
