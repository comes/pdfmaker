<?php

namespace App;

class Product extends MetaModel
{
    protected $casts = [
        'options' => 'object',
        'buyable' => 'boolean'
    ];

    protected $fillable = [
        'name', 'description', 'stock', 'buyable','options'
    ];

    protected $with = [
        'catalog'
    ];

    public static function getDescription() {
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

    public function catalog() {
        return $this->belongsToMany(Catalog::class, 'catalog_products');
    }
}
