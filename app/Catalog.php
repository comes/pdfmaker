<?php

namespace App;

class Catalog extends MetaModel
{
    protected $fillable = [
        'name', 'description', 'visible', 'parent'
    ];

    protected $casts = [
        'visible' => 'boolean'
    ];

    /**
     * get all products of given category object
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products() {
        return $this->belongsToMany(Product::class, 'catalog_products');
    }

    /**
     * get parent category
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent() {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * get all child categories
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public static function getDescription() {
        return [
            'name' => [
                'default' => '',
                'type' => 'text',
                'label' => trans('catalog.name'),
                'description' => '',
                'validation' => 'required|min:3',
                'position' => 1
            ],
            'description' => [
                'label' => trans('catalog.description'),
                'default' => '',
                'type' => 'longtext',
                'description' => '',
                'validation' => '',
                'position' => 2
            ],
            'visible' => [
                'default' => true,
                'type' => 'radio',
                'validation' => 'boolean',
                'position' => 3,
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
            ]
        ];
    }
}
