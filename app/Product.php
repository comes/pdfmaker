<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'options' => 'object',
        'buyable' => 'boolean'
    ];

    public static function getDescription()
    {
        return [
            'name' => [
                'default' => '',
                'type' => 'text',
                'label' => trans('product.name'),
                'description' => '',
                'validation' => 'required|min:3',
                'position' => 1
            ],
            'description' => [
                'label' => trans('product.description'),
                'default' => '',
                'type' => 'longtext',
                'description' => '',
                'validation' => '',
                'position' => 2
            ],
            'stock' => [
                'default' => 1,
                'type' => 'integer',
                'validation' => 'integer',
                'position' => 3
            ],
            'buyable' => [
                'default' => false,
                'type' => 'radio',
                'validation' => 'boolean',
                'position' => 4,
                'options' => [
                    [
                        'name' => 'ja',
                        'value' => true
                    ],
                    [
                        'name' => 'nein',
                        'value' => false
                    ]

                ],
            ],

            'options' => [
                'step' => ['default' => 1, 'type' => 'integer', 'validation' => 'integer'],
                'minamount' => ['default' => 1, 'type' => 'integer', 'validation' => 'integer'],
                'customizable' => ['default' => false, 'type' => 'radio', 'options' => [
                    [
                        'name' => 'ja',
                        'value' => true
                    ],
                    [
                        'name' => 'nein',
                        'value' => false
                    ]

                ], 'validation' => 'boolean'],
                'layout' => ['default' => null, 'type' => 'text', 'description' => 'path/to/pdf/file', 'validation' => 'sometimes|mimetypes:application/pdf'],
            ]
        ];
    }

    public static function getValidationRules()
    {
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
