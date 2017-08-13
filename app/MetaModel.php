<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

abstract class MetaModel extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public static function getValidationRules() {
        $rules = collect(self::getDescription())->map(function ($item, $key) {
            if (count($item) !== count($item, COUNT_RECURSIVE)) {

                $data = collect($item)->map(function ($subItem, $subKey) {
                    return array_get($subItem, 'validation', '');
                });

                return $data;
            } else {
                return array_get($item, 'validation', '');
            }
        });

        return array_dot($rules->toArray());
    }
}
